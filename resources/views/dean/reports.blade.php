@extends('layouts.dean')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Reports & Analytics</h1>
        <p class="text-gray-600">Generate comprehensive reports on organizations, members, and events</p>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Organizations Report -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Organizations Report</h3>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Comprehensive report on all registered organizations, their status, and member counts.
            </p>
            <div class="space-y-2">
                <a href="{{ route('dean.reports.organizations') }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg text-sm transition duration-200">
                    Generate Report
                </a>
                <a href="{{ route('dean.reports.organizations', ['format' => 'pdf']) }}" 
                   class="block w-full bg-blue-100 hover:bg-blue-200 text-blue-700 text-center py-2 px-4 rounded-lg text-sm transition duration-200">
                    Download PDF
                </a>
            </div>
        </div>

        <!-- Membership Report -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Membership Report</h3>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Detailed analysis of student memberships across all organizations.
            </p>
            <div class="space-y-2">
                <a href="{{ route('dean.reports.memberships') }}" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg text-sm transition duration-200">
                    Generate Report
                </a>
                <a href="{{ route('dean.reports.memberships', ['format' => 'pdf']) }}" 
                   class="block w-full bg-green-100 hover:bg-green-200 text-green-700 text-center py-2 px-4 rounded-lg text-sm transition duration-200">
                    Download PDF
                </a>
            </div>
        </div>

        <!-- Events Report -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Events Report</h3>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Report on all events by date range, including approval status and details.
            </p>
            <div class="space-y-2">
                <button onclick="showEventsReportModal()" 
                        class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-lg text-sm transition duration-200">
                    Generate Report
                </button>
                <button onclick="showEventsReportModal(true)" 
                        class="block w-full bg-purple-100 hover:bg-purple-200 text-purple-700 text-center py-2 px-4 rounded-lg text-sm transition duration-200">
                    Download PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Organization Statistics -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Organization Statistics</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Total Organizations</span>
                    <span class="font-semibold text-lg">{{ App\Models\Organization::count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <span class="text-gray-700">Approved Organizations</span>
                    <span class="font-semibold text-lg text-green-600">{{ App\Models\Organization::where('approval_status', 'approved')->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <span class="text-gray-700">Pending Approval</span>
                    <span class="font-semibold text-lg text-yellow-600">{{ App\Models\Organization::where('approval_status', 'pending')->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <span class="text-gray-700">Active Organizations (5+ members)</span>
                    <span class="font-semibold text-lg text-blue-600">
                        {{ App\Models\Organization::approved()->withCount(['memberships' => function($query) { $query->where('status', 'approved'); }])->having('memberships_count', '>=', 5)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h3>
            <div class="space-y-3">
                @php
                    $recentActivities = collect([
                        ['type' => 'organization', 'count' => App\Models\Organization::where('created_at', '>=', now()->subWeek())->count(), 'label' => 'New organizations this week'],
                        ['type' => 'membership', 'count' => App\Models\Membership::where('created_at', '>=', now()->subWeek())->count(), 'label' => 'New memberships this week'],
                        ['type' => 'event', 'count' => App\Models\Event::where('created_at', '>=', now()->subWeek())->count(), 'label' => 'New events this week'],
                        ['type' => 'approval', 'count' => App\Models\Event::where('status', 'approved')->where('updated_at', '>=', now()->subWeek())->count(), 'label' => 'Events approved this week']
                    ]);
                @endphp

                @foreach($recentActivities as $activity)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-gray-700 text-sm">{{ $activity['label'] }}</span>
                    </div>
                    <span class="font-semibold text-blue-600">{{ $activity['count'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Export Options</h3>
        <p class="text-gray-600 mb-4">Export data for external analysis and record keeping</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors">
                <div class="text-center">
                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm text-gray-600">Export All Data (CSV)</span>
                </div>
            </button>
            
            <button class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors">
                <div class="text-center">
                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-sm text-gray-600">Analytics Dashboard (Excel)</span>
                </div>
            </button>
            
            <button class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors">
                <div class="text-center">
                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm text-gray-600">Summary Report (PDF)</span>
                </div>
            </button>
        </div>
    </div>
</div>

<!-- Events Report Modal -->
<div id="eventsReportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Generate Events Report</h3>
            
            <form id="eventsReportForm" method="GET" action="{{ route('dean.reports.events') }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" 
                           value="{{ now()->startOfYear()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" 
                           value="{{ now()->endOfYear()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <input type="hidden" name="format" id="reportFormat" value="">
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="hideEventsReportModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showEventsReportModal(isPdf = false) {
    document.getElementById('reportFormat').value = isPdf ? 'pdf' : '';
    document.getElementById('eventsReportModal').classList.remove('hidden');
}

function hideEventsReportModal() {
    document.getElementById('eventsReportModal').classList.add('hidden');
}
</script>
@endsection