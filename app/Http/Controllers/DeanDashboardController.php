<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Event;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeanDashboardController extends Controller
{
    /**
     * Display the dean dashboard with overview statistics
     */
    public function index()
    {
        // Overview statistics
        $stats = [
            'total_organizations' => Organization::count(),
            'pending_organizations' => Organization::where('approval_status', 'pending')->count(),
            'active_organizations' => Organization::where('approval_status', 'approved')->count(),
            'pending_events' => Event::where('status', 'pending')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_memberships' => Membership::where('status', 'approved')->count(),
        ];

        // Recent activities
        $recentEvents = Event::where('status', 'pending')
                            ->with('organization')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        $recentOrganizations = Organization::where('approval_status', 'pending')
                                        ->with('officer')
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();

        // -----------------------
        // Chart Data Preparation
        // -----------------------

        // 1. Organization Status Pie Chart
        $orgStatusData = [
            'labels' => ['Pending', 'Active', 'Rejected'],
            'counts' => [
                $stats['pending_organizations'],
                $stats['active_organizations'],
                $stats['total_organizations'] - $stats['pending_organizations'] - $stats['active_organizations']
            ]
        ];

        // 2. Events Over Time Line Chart (events per month)
        $eventsByMonth = Event::selectRaw('MONTH(date) as month, COUNT(*) as total')
                            ->whereYear('date', now()->year)
                            ->groupBy('month')
                            ->orderBy('month')
                            ->pluck('total', 'month')
                            ->toArray();

        $months = array_map(fn($m) => date('M', mktime(0,0,0,$m,1)), array_keys($eventsByMonth));

        // 3. Memberships per Organization Bar Chart
        $orgNames = Organization::pluck('name');
        $membersCounts = Organization::withCount('memberships')->pluck('memberships_count');

        return view('dean.dashboard', compact(
            'stats', 'recentEvents', 'recentOrganizations',
            'orgStatusData', 'eventsByMonth', 'months',
            'orgNames', 'membersCounts'
        ));
    }


    /**
     * 1. View and validate all organization registrations
     */
    public function organizations()
    {
        $organizations = Organization::with(['officer', 'memberships.user'])
                                   ->withCount(['memberships' => function($query) {
                                       $query->where('status', 'approved');
                                   }])
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        return view('dean.organizations', compact('organizations'));
    }

    /**
     * Show organization details for approval
     */
    public function showOrganization($id)
    {
        $organization = Organization::with(['officer', 'memberships.user', 'events'])
                                  ->withCount(['memberships' => function($query) {
                                      $query->where('status', 'approved');
                                  }])
                                  ->findOrFail($id);

        return view('dean.organization-details', compact('organization'));
    }

    /**
     * Approve organization registration
     */
    public function approveOrganization(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        
        $organization->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Log the approval
        DB::table('organization_approvals')->insert([
            'organization_id' => $organization->id,
            'dean_id' => Auth::id(),
            'action' => 'approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Organization approved successfully!');
    }

    /**
     * Reject organization registration
     */
    public function rejectOrganization(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $organization = Organization::findOrFail($id);
        
        $organization->update([
            'approval_status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Log the rejection
        DB::table('organization_approvals')->insert([
            'organization_id' => $organization->id,
            'dean_id' => Auth::id(),
            'action' => 'rejected',
            'reason' => $request->rejection_reason,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Organization rejected successfully!');
    }

    /**
     * 2. Approve or reject events submitted by clubs
     */
    public function events()
    {
        $events = Event::with(['organization.officer'])
                      ->orderBy('status', 'asc') // pending first
                      ->orderBy('date', 'asc')
                      ->get();

        return view('dean.events', compact('events'));
    }

    /**
     * Show event details for approval
     */
    public function showEvent($id)
    {
        $event = Event::with(['organization.officer'])
                     ->findOrFail($id);

        return view('dean.event-details', compact('event'));
    }

    /**
     * Approve event
     */
    public function approveEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $event->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Event approved successfully!');
    }

    /**
     * Reject event
     */
    public function rejectEvent(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $event = Event::findOrFail($id);
        
        $event->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Event rejected successfully!');
    }

    /**
     * 3. Monitor student membership across organizations
     */
    public function memberships()
    {
        $memberships = Membership::with(['user', 'organization'])
                                ->orderBy('created_at', 'desc')
                                ->get();

        // Get membership statistics
        $membershipStats = [
            'total_memberships' => Membership::count(),
            'approved_memberships' => Membership::where('status', 'approved')->count(),
            'pending_memberships' => Membership::where('status', 'pending')->count(),
            'rejected_memberships' => Membership::where('status', 'rejected')->count(),
        ];

        // Get students with multiple memberships
        $multiMemberships = User::where('role', 'student')
                               ->withCount('memberships')
                               ->having('memberships_count', '>', 1)
                               ->with('memberships.organization')
                               ->get();

        return view('dean.memberships', compact('memberships', 'membershipStats', 'multiMemberships'));
    }

    /**
     * 4. Generate reports on clubs, members, and events
     */
    public function reports()
    {
        return view('dean.reports');
    }

    /**
     * Generate organization report
     */
    public function generateOrganizationReport(Request $request)
    {
        $organizations = Organization::with(['officer', 'memberships.user', 'events'])
                                   ->withCount(['memberships' => function($query) {
                                       $query->where('status', 'approved');
                                   }])
                                   ->get();

        $reportData = [
            'total_organizations' => $organizations->count(),
            'active_organizations' => $organizations->where('memberships_count', '>=', 5)->count(),
            'inactive_organizations' => $organizations->where('memberships_count', '<', 5)->count(),
            'organizations' => $organizations,
            'generated_at' => now(),
            'generated_by' => Auth::user()->name,
        ];

        if ($request->format === 'pdf') {
            // You can implement PDF generation here
            return response()->json(['message' => 'PDF generation not implemented yet']);
        }

        return view('dean.reports.organizations', compact('reportData'));
    }

    /**
     * Generate membership report
     */
    public function generateMembershipReport(Request $request)
    {
        $membershipsByOrg = Organization::withCount(['memberships' => function($query) {
                                         $query->where('status', 'approved');
                                     }])
                                     ->with(['memberships' => function($query) {
                                         $query->where('status', 'approved')->with('user');
                                     }])
                                     ->get();

        $reportData = [
            'total_students' => User::where('role', 'student')->count(),
            'total_memberships' => Membership::where('status', 'approved')->count(),
            'organizations' => $membershipsByOrg,
            'generated_at' => now(),
            'generated_by' => Auth::user()->name,
        ];

        if ($request->format === 'pdf') {
            // You can implement PDF generation here
            return response()->json(['message' => 'PDF generation not implemented yet']);
        }

        return view('dean.reports.memberships', compact('reportData'));
    }

    /**
     * Generate events report
     */
    public function generateEventsReport(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfYear();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfYear();

        $events = Event::with(['organization.officer'])
                      ->whereBetween('date', [$startDate, $endDate])
                      ->orderBy('date', 'desc')
                      ->get();

        $reportData = [
            'total_events' => $events->count(),
            'approved_events' => $events->where('status', 'approved')->count(),
            'pending_events' => $events->where('status', 'pending')->count(),
            'rejected_events' => $events->where('status', 'rejected')->count(),
            'events' => $events,
            'period' => [
                'start' => $startDate->format('M d, Y'),
                'end' => $endDate->format('M d, Y')
            ],
            'generated_at' => now(),
            'generated_by' => Auth::user()->name,
        ];

        if ($request->format === 'pdf') {
            // You can implement PDF generation here
            return response()->json(['message' => 'PDF generation not implemented yet']);
        }

        return view('dean.reports.events', compact('reportData'));
    }

    /**
     * Get analytics data for dashboard charts
     */
    public function getAnalytics()
    {
        // Monthly membership growth
        $membershipGrowth = Membership::where('status', 'approved')
                                    ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
                                    ->whereYear('created_at', date('Y'))
                                    ->groupBy('year', 'month')
                                    ->orderBy('year', 'month')
                                    ->get();

        // Organization categories distribution
        $orgCategories = Organization::where('approval_status', 'approved')
                                   ->selectRaw('category, COUNT(*) as count')
                                   ->groupBy('category')
                                   ->get();

        // Event status distribution
        $eventStatus = Event::selectRaw('status, COUNT(*) as count')
                           ->groupBy('status')
                           ->get();

        return response()->json([
            'membership_growth' => $membershipGrowth,
            'org_categories' => $orgCategories,
            'event_status' => $eventStatus
        ]);
    }

    public function export($format)
    {
        $events = Event::with('organization')->orderBy('status')->orderBy('date')->get();

        if ($format === 'csv') {
            $filename = 'events_' . now()->format('Ymd_His') . '.csv';
            $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];
            
            $callback = function() use ($events) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Event Name', 'Organization', 'Date', 'Location', 'Status', 'Budget']);
                foreach ($events as $event) {
                    fputcsv($file, [
                        $event->name,
                        $event->organization->name,
                        $event->date,
                        $event->location,
                        ucfirst($event->status),
                        $event->budget,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dean.exports.events', compact('events'));
            return $pdf->download('events_' . now()->format('Ymd_His') . '.pdf');
        }

        return redirect()->back()->with('error', 'Invalid export format.');
    }

}