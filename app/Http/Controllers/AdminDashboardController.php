<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use App\Models\Event;
use App\Models\Membership;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use PDF; 
use App\Models\Setting;


class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with system overview
     */
    public function index()
    {
        // === System Overview ===
        $stats = [
            'total_users'          => User::count(),
            'total_students'       => User::where('role', 'student')->count(),
            'total_officers'       => User::where('role', 'officer')->count(),
            'total_deans'          => User::where('role', 'dean')->count(),
            'total_admins'         => User::where('role', 'admin')->count(),
            'total_organizations'  => Organization::count(),
            'active_organizations' => Organization::where('approval_status', 'approved')->count(),
            'pending_organizations'=> Organization::where('approval_status', 'pending')->count(),
            'total_events'         => Event::count(),
            'approved_events'      => Event::where('status', 'approved')->count(),
            'total_memberships'    => Membership::count(),
            'active_memberships'   => Membership::where('status', 'approved')->count(),
        ];

        // === Recent Activity ===
        $recentUsers         = User::latest()->take(5)->get();
        $recentOrganizations = Organization::with('officer')->latest()->take(5)->get();
        $recentEvents        = Event::with('organization')->latest()->take(5)->get();

        // === System Health ===
        $systemHealth = [
            'avg_members_per_org'     => round(Membership::where('status', 'approved')->count() / max(Organization::where('approval_status', 'approved')->count(), 1), 2),
            'org_approval_rate'       => Organization::count() > 0 ? round((Organization::where('approval_status', 'approved')->count() / Organization::count()) * 100, 2) : 0,
            'event_approval_rate'     => Event::count() > 0 ? round((Event::where('status', 'approved')->count() / Event::count()) * 100, 2) : 0,
            'membership_approval_rate'=> Membership::count() > 0 ? round((Membership::where('status', 'approved')->count() / Membership::count()) * 100, 2) : 0,
        ];

        // === Event Chart: Approved Events Per Month ===
        $currentYear = Carbon::now()->year;

        $monthlyEvents = Event::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->where('status', 'approved')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month'); // [1 => 5, 2 => 3, ...]

        $monthsLabels   = [];
        $eventChartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthsLabels[]   = date('M', mktime(0, 0, 0, $i, 1)); // Jan, Feb...
            $eventChartData[] = $monthlyEvents[$i] ?? 0;           // Default 0 if no data
        }

        $eventStats = [
            'months' => $monthsLabels,
            'totals' => $eventChartData,
        ];

        // === Top 5 Active Orgs ===
        $mostActiveOrgs = Organization::withCount(['memberships' => function($q) {
            $q->where('status', 'approved');
        }])->orderBy('memberships_count', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentOrganizations',
            'recentEvents',
            'systemHealth',
            'mostActiveOrgs',
            'eventStats',
            'monthsLabels',
            'eventChartData'
        ));
    }

    


    /**
     * User Management Functions
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10); // paginate instead of get()

        $userStats = [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'officers' => User::where('role', 'officer')->count(),
            'deans' => User::where('role', 'dean')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        return view('admin.users', compact('users', 'userStats'));
    }

    public function auditLogs()
    {
        $logs = \App\Models\AuditLog::with('user')->latest()->paginate(20);
        return view('admin.audit-logs', compact('logs'));
    }



    public function showUser($id)
    {
        $user = User::with(['memberships.organization', 'managedOrganizations', 'approvedOrganizations'])->findOrFail($id);
        return view('admin.user-details', compact('user'));
    }

    public function createUser()
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,officer,dean,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Auto-verify admin created accounts
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:student,officer,dean,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deletion of the current admin user
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Cannot delete your own account!');
        }

        // Handle organization transfers if user is an officer
        if ($user->role === 'officer') {
            $organizations = Organization::where('officer_id', $user->id)->get();
            foreach ($organizations as $org) {
                $org->update(['officer_id' => null, 'approval_status' => 'pending']);
            }
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    /**
     * System Configuration Functions
     */
   

    public function settings()
    {
        $settings = [
            'system_name' => Setting::getValue('system_name', config('app.name')),
            'academic_year' => Setting::getValue('academic_year', now()->year),
            'min_members_required' => Setting::getValue('min_members_required', 5),
            'registration_open' => (bool) Setting::getValue('registration_open', true),
            'event_approval_required' => (bool) Setting::getValue('event_approval_required', true),
            'public_event_calendar' => (bool) Setting::getValue('public_event_calendar', true),
            'event_lead_time' => Setting::getValue('event_lead_time', 7),
            'email_notifications' => (bool) Setting::getValue('email_notifications', true),
            'admin_email' => Setting::getValue('admin_email', 'admin@system.com'),
            'force_password_reset' => (bool) Setting::getValue('force_password_reset', false),
            'session_timeout' => Setting::getValue('session_timeout', 120),
            'audit_logging' => (bool) Setting::getValue('audit_logging', true),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'system_name' => 'required|string|max:255',
            'academic_year' => 'required|integer|min:2020|max:2035',
            'min_members_required' => 'required|integer|min:1|max:50',
            'registration_open' => 'nullable|boolean',
            'event_approval_required' => 'nullable|boolean',
            'public_event_calendar' => 'nullable|boolean',
            'event_lead_time' => 'required|integer|min:1|max:30',
            'email_notifications' => 'nullable|boolean',
            'admin_email' => 'nullable|email',
            'force_password_reset' => 'nullable|boolean',
            'session_timeout' => 'required|integer|min:30|max:720',
            'audit_logging' => 'nullable|boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, is_bool($value) ? (int) $value : $value);
        }

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }


    /**
     * Organization Categories Management
     */
     public function categories()
    {
        $categories = \App\Models\Category::all();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        \App\Models\Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categories')
            ->with('success', 'Category added successfully!');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories')
            ->with('success', 'Category deleted successfully!');
    }



    /**
     * System Reports and Analytics
     */
    public function systemReports()
    {
        return view('admin.reports');
    }

   

    public function generateSystemReport(Request $request)
    {
        $reportType = $request->get('type', 'overview');
        
        $reportData = [
            'generated_at' => now(),
            'generated_by' => Auth::user()->name,
            'report_type' => $reportType,
        ];

        switch ($reportType) {
            case 'users':
                $reportData['data'] = User::with('memberships', 'managedOrganizations')->get();
                $reportData['summary'] = [
                    'total_users' => User::count(),
                    'by_role' => User::selectRaw('role, COUNT(*) as count')->groupBy('role')->get(),
                ];
                break;

            case 'organizations':
                $reportData['data'] = Organization::with(['officer', 'memberships', 'events'])->get();
                $reportData['summary'] = [
                    'total_orgs' => Organization::count(),
                    'by_status' => Organization::selectRaw('approval_status, COUNT(*) as count')->groupBy('approval_status')->get(),
                    'by_category' => Organization::selectRaw('category, COUNT(*) as count')->groupBy('category')->get(),
                ];
                break;

            case 'activities':
                $reportData['data'] = [
                    'events' => Event::with('organization')->get(),
                    'memberships' => Membership::with(['user', 'organization'])->get(),
                ];
                $reportData['summary'] = [
                    'total_events' => Event::count(),
                    'events_by_status' => Event::selectRaw('status, COUNT(*) as count')->groupBy('status')->get(),
                    'total_memberships' => Membership::count(),
                    'memberships_by_status' => Membership::selectRaw('status, COUNT(*) as count')->groupBy('status')->get(),
                ];
                break;

            default:
                $reportData['data'] = [
                    'users_count' => User::count(),
                    'organizations_count' => Organization::count(),
                    'events_count' => Event::count(),
                    'memberships_count' => Membership::count(),
                ];
                break;
        }

        if ($request->get('format') === 'pdf') {
            $pdf = PDF::loadView('admin.reports.system-pdf', compact('reportData'))
                    ->setPaper('a4', 'landscape');
            $filename = $reportType . '_report_' . now()->format('Ymd_His') . '.pdf';
            return $pdf->download($filename);
        }

        return view('admin.reports.system', compact('reportData'));
    }


    /**
     * System Maintenance Functions
     */
    public function maintenance()
    {
        return view('admin.maintenance');
    }

    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');

            return back()->with('success', 'System cache cleared successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    public function backupDatabase()
    {
        try {
            // Implement database backup logic here
            // This is a placeholder - you'd want to use a proper backup package
            
            return back()->with('success', 'Database backup initiated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to backup database: ' . $e->getMessage());
        }
    }

    /**
     * Bulk Operations
     */
    public function bulkUserActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,change_role',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'new_role' => 'required_if:action,change_role|in:student,officer,dean,admin',
        ]);

        $userIds = $request->user_ids;
        
        // Prevent bulk actions on current user
        if (in_array(Auth::id(), $userIds)) {
            return back()->with('error', 'Cannot perform bulk actions on your own account!');
        }

        switch ($request->action) {
            case 'delete':
                User::whereIn('id', $userIds)->delete();
                $message = 'Selected users deleted successfully!';
                break;
                
            case 'change_role':
                User::whereIn('id', $userIds)->update(['role' => $request->new_role]);
                $message = 'User roles updated successfully!';
                break;
                
            default:
                $message = 'Action completed successfully!';
        }

        return back()->with('success', $message);
    }

    /**
     * System Analytics API
     */
    public function getSystemAnalytics()
    {
        $analytics = [
            'user_growth' => User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                ->whereDate('created_at', '>=', now()->subDays(30))
                                ->groupBy('date')
                                ->orderBy('date')
                                ->get(),
                                
            'org_creation' => Organization::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                        ->whereDate('created_at', '>=', now()->subDays(30))
                                        ->groupBy('date')
                                        ->orderBy('date')
                                        ->get(),
                                        
            'event_creation' => Event::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                    ->whereDate('created_at', '>=', now()->subDays(30))
                                    ->groupBy('date')
                                    ->orderBy('date')
                                    ->get(),
                                    
            'role_distribution' => User::selectRaw('role, COUNT(*) as count')
                                     ->groupBy('role')
                                     ->get(),
        ];

        return response()->json($analytics);
    }

    /**
     * Audit Logs (if you want to implement logging)
     */
   public function exportUsers()
{
    // Example: Export all users as CSV
    $users = User::all();

    $filename = 'users_' . now()->format('Ymd_His') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($users) {
        $handle = fopen('php://output', 'w');
        // Add header row
        fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Created At']);

        foreach ($users as $user) {
            fputcsv($handle, [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->created_at,
            ]);
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}


    public function dashboard(Request $request)
    {
        $query = User::query();

        // Date filter
        if ($request->from && $request->to) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalOrganizations' => Organization::count(),
            'totalEvents' => Event::count(),
            'pendingApprovals' => User::where('status', 'pending')->count(),
            'recentUsers' => User::latest()->take(5)->get(),
            'recentEvents' => Event::orderBy('date', 'asc')->take(5)->get(),
            'chartLabels' => ['Jan','Feb','Mar','Apr','May'], // Example
            'chartData' => [5, 12, 8, 20, 15], // Example
        ]);
    }

}