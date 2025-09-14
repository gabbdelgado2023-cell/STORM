<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DeanDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OfficerDashboardController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    // Redirect to appropriate dashboard based on role
    return match($user->role) {
        'student' => redirect()->route('student.dashboard'),
        'officer' => redirect()->route('officer.dashboard'),
        'dean' => redirect()->route('dean.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        default => redirect()->route('login')
    };
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =======================
// STUDENT ROUTES - Protected with role middleware
// =======================
Route::middleware(['auth', 'verified', 'role:student'])->group(function () {  
    Route::get('/student/organizations', [StudentDashboardController::class, 'organizations'])->name('student.organizations');
    Route::post('/student/organizations/{org}/apply', [StudentDashboardController::class, 'apply'])->name('student.apply');
    Route::delete('/student/organizations/{org}/withdraw', [StudentDashboardController::class, 'withdraw'])->name('student.withdraw');
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student/dashboard/profile', [StudentDashboardController::class, 'profile'])->name('student.dashboard.profile');
    Route::get('/student/dashboard/organizations', [StudentDashboardController::class, 'organizations'])->name('student.organizations');
    Route::post('/student/organizations/{orgId}/apply', [StudentDashboardController::class, 'apply'])->name('student.apply');
    Route::get('/student/dashboard/memberships', [StudentDashboardController::class, 'memberships'])->name('student.memberships');
    Route::get('/student/dashboard/events', [StudentDashboardController::class, 'events'])->name('student.events');
    Route::post('/student/memberships/{membership}/withdraw', [App\Http\Controllers\StudentDashboardController::class, 'withdraw'])
    ->name('student.memberships.withdraw');
});

// =======================
// OFFICER ROUTES - Protected with role middleware
// =======================
Route::middleware(['auth', 'verified', 'role:officer'])->group(function () {
    // Main dashboard
    Route::get('/officer/dashboard', [OfficerDashboardController::class, 'index'])
        ->name('officer.dashboard');
    
    // Organization setup (for new officers)
    Route::get('/officer/setup', [OfficerDashboardController::class, 'setup'])
        ->name('officer.setup');
    Route::post('/officer/setup', [OfficerDashboardController::class, 'storeSetup'])
        ->name('officer.setup.store');
    
    // Organization profile management
    Route::get('/officer/profile', [OfficerDashboardController::class, 'editProfile'])
        ->name('officer.profile');
    Route::put('/officer/profile', [OfficerDashboardController::class, 'updateProfile'])
        ->name('officer.profile.update');
    
    // Member management
    Route::get('/officer/members', [OfficerDashboardController::class, 'members'])
        ->name('officer.members');
    Route::post('/officer/members/{membership}/approve', [OfficerDashboardController::class, 'approveMembership'])
        ->name('officer.approve-membership');
    Route::post('/officer/members/{membership}/reject', [OfficerDashboardController::class, 'rejectMembership'])
        ->name('officer.reject-membership');
    
    // Event management
    Route::get('/officer/events', [OfficerDashboardController::class, 'events'])
        ->name('officer.events');
    Route::get('/officer/events/create', [OfficerDashboardController::class, 'createEvent'])
        ->name('officer.events.create');
    Route::post('/officer/events', [OfficerDashboardController::class, 'storeEvent'])
        ->name('officer.events.store');
    Route::get('/officer/events/{event}/edit', [OfficerDashboardController::class, 'editEvent'])
        ->name('officer.events.edit');
    Route::put('/officer/events/{event}', [OfficerDashboardController::class, 'updateEvent'])
        ->name('officer.events.update');
    Route::delete('/officer/events/{event}', [OfficerDashboardController::class, 'deleteEvent'])
        ->name('officer.events.delete');

        
    
});

// =======================
// DEAN ROUTES - Protected with role middleware
// =======================
// =======================
// DEAN ROUTES - Enhanced with all functions
// =======================
Route::middleware(['auth', 'verified', 'role:dean'])->group(function () {
    // Main dashboard
    Route::get('/dean/dashboard', [DeanDashboardController::class, 'index'])->name('dean.dashboard');
    
    // 1. Organization management and validation
    Route::get('/dean/organizations', [DeanDashboardController::class, 'organizations'])->name('dean.organizations');
    Route::get('/dean/organizations/{organization}', [DeanDashboardController::class, 'showOrganization'])->name('dean.organizations.show');
    Route::post('/dean/organizations/{organization}/approve', [DeanDashboardController::class, 'approveOrganization'])->name('dean.organizations.approve');
    Route::post('/dean/organizations/{organization}/reject', [DeanDashboardController::class, 'rejectOrganization'])->name('dean.organizations.reject');
    
    // 2. Event approval management
    Route::get('/dean/events', [DeanDashboardController::class, 'events'])->name('dean.events');
    Route::get('/dean/events/{event}', [DeanDashboardController::class, 'showEvent'])->name('dean.events.show');
    Route::post('/dean/events/{event}/approve', [DeanDashboardController::class, 'approveEvent'])->name('dean.events.approve');
    Route::post('/dean/events/{event}/reject', [DeanDashboardController::class, 'rejectEvent'])->name('dean.events.reject');
    
    // 3. Student membership monitoring
    Route::get('/dean/memberships', [DeanDashboardController::class, 'memberships'])->name('dean.memberships');
    
    // 4. Reports and analytics
    Route::get('/dean/reports', [DeanDashboardController::class, 'reports'])->name('dean.reports');
    Route::get('/dean/reports/organizations', [DeanDashboardController::class, 'generateOrganizationReport'])->name('dean.reports.organizations');
    Route::get('/dean/reports/memberships', [DeanDashboardController::class, 'generateMembershipReport'])->name('dean.reports.memberships');
    Route::get('/dean/reports/events', [DeanDashboardController::class, 'generateEventsReport'])->name('dean.reports.events');
    
    // Analytics API for dashboard charts
    Route::get('/dean/analytics', [DeanDashboardController::class, 'getAnalytics'])->name('dean.analytics');

    // Dean Events Export
    Route::get('/dean/events/export/{format}', [App\Http\Controllers\DeanDashboardController::class, 'export'])
     ->name('dean.events.export')
     ->middleware(['auth', 'role:dean']);

});
    

// =======================
// ADMIN ROUTES - Enhanced with all admin functions
// =======================
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Main dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Export users
     Route::get('/admin/users/export', [AdminDashboardController::class, 'exportUsers'])
    ->name('admin.users.export');

   Route::get('/admin/reports/generate', [App\Http\Controllers\Admin\ReportController::class, 'generate'])
   ->name('admin.reports.generate');


    
    // User Management
    Route::get('/admin/users', [AdminDashboardController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [AdminDashboardController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users', [AdminDashboardController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{user}', [AdminDashboardController::class, 'showUser'])->name('admin.users.show');
    Route::get('/admin/users/{user}/edit', [AdminDashboardController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminDashboardController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/admin/users/bulk-actions', [AdminDashboardController::class, 'bulkUserActions'])->name('admin.users.bulk-actions');

    
        // Categories CRUD
        

        Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
            // Resource controller for all CRUD
            Route::resource('categories', CategoryController::class);
        });



    
    // System Configuration
    Route::get('/admin/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
    Route::put('/admin/settings', [AdminDashboardController::class, 'updateSettings'])->name('admin.settings.update');
    
    // Organization Categories Management
    Route::get('/admin/categories', [AdminDashboardController::class, 'categories'])->name('admin.categories');
    
    // System Reports
    Route::get('/admin/reports', [AdminDashboardController::class, 'systemReports'])->name('admin.reports');
    Route::get('/admin/reports/generate', [AdminDashboardController::class, 'generateSystemReport'])->name('admin.reports.generate');
    
    // System Maintenance
    Route::get('/admin/maintenance', [AdminDashboardController::class, 'maintenance'])->name('admin.maintenance');
    Route::post('/admin/maintenance/clear-cache', [AdminDashboardController::class, 'clearCache'])->name('admin.maintenance.clear-cache');
    Route::post('/admin/maintenance/backup-database', [AdminDashboardController::class, 'backupDatabase'])->name('admin.maintenance.backup-database');
    
    // Analytics API
    Route::get('/admin/analytics', [AdminDashboardController::class, 'getSystemAnalytics'])->name('admin.analytics');
    
    // Audit Logs
    Route::get('/admin/audit-logs', [AdminDashboardController::class, 'auditLogs'])->name('admin.audit-logs');
});

// =======================
// Logout
// =======================
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

require __DIR__.'/auth.php';