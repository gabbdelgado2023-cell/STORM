@extends('layouts.dean')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Events Report</h1>
                <p class="text-gray-600">Generated on {{ $reportData['generated_at']->format('F j, Y \a\t h:i A') }}</p>
                <p class="text-sm text-gray-500">By {{ $reportData['generated_by'] }} • Period: {{ $reportData['period']['start'] }} - {{ $reportData['period']['end'] }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('dean.reports') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    ← Back to Reports
                </a>
                <button onclick="window.print()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Events</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $reportData['total_events'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Approved Events</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $reportData['approved_events'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Pending Events</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $reportData['pending_events'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm4.707-5.293a1 1 0 00-1.414-1.414L11 13.586l-2.293-2.293a1 1 0 00-1.414 1.414L9.586 15l-2.293 2.293a1 1 0 101.414 1.414L11 16.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15l2.293-2.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Rejected Events</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $reportData['rejected_events'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Event Details</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reportData['events'] as $index => $event)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $event->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($event->description, 50) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $event->organization->name }}</div>
                                <div class="text-sm text-gray-500">{{ $event->organization->officer->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $event->date->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $event->date->format('h:i A') }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $event->location }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $event->budget ? 'px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class=" . number_format($event->budget, 2) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $event->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($event->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Events by Organization -->
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Events by Organization</h2>
        </div>
        <div class="p-6">
            @php
                $eventsByOrg = $reportData['events']->groupBy('organization.name');
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($eventsByOrg as $orgName => $events)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">{{ $orgName }}</h3>
                    <div class="text-2xl font-bold text-blue-600 mb-1">{{ $events->count() }}</div>
                    <div class="space-y-1 text-sm text-gray-500">
                        <div>{{ $events->where('status', 'approved')->count() }} approved</div>
                        <div>{{ $events->where('status', 'pending')->count() }} pending</div>
                        <div>{{ $events->where('status', 'rejected')->count() }} rejected</div>
                        @if($events->sum('budget') > 0)
                        <div class="text-xs text-gray-400 mt-2">
                            Total Budget: ${{ number_format($events->sum('budget'), 2) }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Monthly Event Distribution</h2>
        </div>
        <div class="p-6">
            @php
                $eventsByMonth = $reportData['events']->groupBy(function($event) {
                    return $event->date->format('F Y');
                });
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($eventsByMonth as $month => $events)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">{{ $month }}</h3>
                    <div class="text-2xl font-bold text-purple-600 mb-1">{{ $events->count() }}</div>
                    <div class="text-sm text-gray-500">
                        {{ $events->where('status', 'approved')->count() }} approved, 
                        {{ $events->where('status', 'pending')->count() }} pending
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Event Statistics Summary</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-3xl font-bold text-blue-600 mb-2">
                        {{ $reportData['total_events'] > 0 ? number_format(($reportData['approved_events'] / $reportData['total_events']) * 100, 1) : 0 }}%
                    </div>
                    <div class="text-sm text-gray-600">Approval Rate</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-3xl font-bold text-green-600 mb-2">
                        ${{ number_format($reportData['events']->sum('budget'), 2) }}
                    </div>
                    <div class="text-sm text-gray-600">Total Budget</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <div class="text-3xl font-bold text-yellow-600 mb-2">
                        {{ $eventsByOrg->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Organizations with Events</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-3xl font-bold text-purple-600 mb-2">
                        {{ $eventsByOrg->count() > 0 ? number_format($reportData['total_events'] / $eventsByOrg->count(), 1) : 0 }}
                    </div>
                    <div class="text-sm text-gray-600">Avg Events per Organization</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { font-size: 12px; }
    .container { max-width: none; }
}
</style>
@endsectionpx-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="