@extends('layouts.dean')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Dean Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
        <p class="text-sm text-gray-500">Office of Student Affairs and Development</p>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Organization Status Pie Chart -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Organization Status</h3>
            <canvas id="orgStatusChart" height="250"></canvas>
        </div>

        <!-- Events Over Time Line Chart -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Events Over Time</h3>
            <canvas id="eventsLineChart" height="250"></canvas>
        </div>

        <!-- Memberships per Organization Bar Chart -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Memberships per Organization</h3>
            <canvas id="membershipsBarChart" height="250"></canvas>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('dean.organizations') }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center transition duration-200">
                    Review Organizations
                </a>
                <a href="{{ route('dean.events') }}" 
                   class="block w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-center transition duration-200">
                    Approve Events
                </a>
                <a href="{{ route('dean.reports') }}" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-center transition duration-200">
                    Generate Reports
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Pending Events</h3>
            @if($recentEvents->count() > 0)
                <div class="space-y-2">
                    @foreach($recentEvents as $event)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <div>
                                <p class="font-medium text-gray-800">{{ $event->name }}</p>
                                <p class="text-sm text-gray-500">{{ $event->organization->name }}</p>
                                <p class="text-xs text-gray-400">{{ $event->date->format('M d, Y') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('dean.events') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View all pending events →
                    </a>
                </div>
            @else
                <p class="text-gray-500">No pending events to review.</p>
            @endif
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --------------------------
    // Pie Chart - Organization Status
    // --------------------------
    const ctxOrg = document.getElementById('orgStatusChart').getContext('2d');
    new Chart(ctxOrg, {
        type: 'pie',
        data: {
            labels: {!! json_encode($orgStatusData['labels']) !!},
            datasets: [{
                data: {!! json_encode($orgStatusData['counts']) !!},
                backgroundColor: ['#FBBF24', '#10B981', '#EF4444']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // --------------------------
    // Line Chart - Events Over Time
    // --------------------------
    const ctxEvents = document.getElementById('eventsLineChart').getContext('2d');
    new Chart(ctxEvents, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Events per Month',
                data: {!! json_encode(array_values($eventsByMonth)) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, precision: 0 } }
        }
    });

    // --------------------------
    // Bar Chart - Memberships per Organization
    // --------------------------
    const ctxMembers = document.getElementById('membershipsBarChart').getContext('2d');
    new Chart(ctxMembers, {
        type: 'bar',
        data: {
            labels: {!! json_encode($orgNames) !!},
            datasets: [{
                label: 'Members',
                data: {!! json_encode($membersCounts) !!},
                backgroundColor: '#3B82F6'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, precision: 0 } }
        }
    });
</script>
@endsection
