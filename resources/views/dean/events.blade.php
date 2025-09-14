@extends('layouts.dean')

@section('content')
<div class="container mx-auto py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Event Management</h1>
        <p class="text-gray-600">Review and approve events submitted by organizations</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 rounded-lg p-4 shadow flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Pending</p>
                <h2 class="text-xl font-bold text-yellow-700">{{ $events->where('status','pending')->count() }}</h2>
            </div>
            <div class="text-yellow-500">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="bg-green-50 rounded-lg p-4 shadow flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Approved</p>
                <h2 class="text-xl font-bold text-green-700">{{ $events->where('status','approved')->count() }}</h2>
            </div>
            <div class="text-green-500">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="bg-red-50 rounded-lg p-4 shadow flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Rejected</p>
                <h2 class="text-xl font-bold text-red-700">{{ $events->where('status','rejected')->count() }}</h2>
            </div>
            <div class="text-red-500">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>

    <!-- Search & Export -->
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4">
        <!-- Search -->
        <input type="text" placeholder="Search by event or organization..." 
               x-data x-model="searchQuery" 
               class="mb-3 md:mb-0 md:mr-3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-1/3">
        
        <!-- Export Buttons -->
        <div class="flex space-x-3">
            <a href="{{ route('dean.events.export', ['format' => 'csv']) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                Export CSV
            </a>
            <a href="{{ route('dean.events.export', ['format' => 'pdf']) }}" 
               class="bg-gray-800 hover:bg-gray-900 text-white px-3 py-1 rounded text-sm">
                Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div x-data="{ activeTab: 'pending', searchQuery: '' }" class="mb-6">
        <div class="border-b border-gray-200 mb-4">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'pending'" 
                        :class="activeTab === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">Pending Approval</button>
                <button @click="activeTab = 'approved'" 
                        :class="activeTab === 'approved' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">Approved Events</button>
                <button @click="activeTab = 'rejected'" 
                        :class="activeTab === 'rejected' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">Rejected Events</button>
                <button @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">All Events</button>
            </nav>
        </div>

        @php
            $groupedEvents = $events->groupBy('status');
            $pendingEvents = $groupedEvents->get('pending', collect());
            $approvedEvents = $groupedEvents->get('approved', collect());
            $rejectedEvents = $groupedEvents->get('rejected', collect());
        @endphp

        <!-- Events List -->
        <div class="space-y-4">
            @foreach(['pending'=>$pendingEvents, 'approved'=>$approvedEvents, 'rejected'=>$rejectedEvents] as $status => $eventsGroup)
                <div x-show="activeTab === '{{ $status }}' || activeTab === 'all'">
                    @forelse($eventsGroup as $event)
                        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 {{ $status==='pending' ? 'border-yellow-500' : ($status==='approved' ? 'border-green-500' : 'border-red-500') }}">
                            <div class="flex flex-col lg:flex-row lg:justify-between">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $event->name }}</h3>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                              {{ $status==='pending' ? 'bg-yellow-100 text-yellow-800' : ($status==='approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                              {{ ucfirst($status) }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 text-sm text-gray-700">
                                        <div><strong>Organization:</strong> {{ $event->organization->name }}</div>
                                        <div><strong>Date & Time:</strong> {{ $event->date->format('M d, Y h:i A') }}</div>
                                        <div><strong>Location:</strong> {{ $event->location }}</div>
                                        <div><strong>Budget:</strong> {{ $event->budget ? '$'.number_format($event->budget,2) : 'N/A' }}</div>
                                    </div>
                                    <p class="text-sm text-gray-900 mb-3">{{ Str::limit($event->description, 100) }}</p>
                                    <div class="flex justify-between items-center text-sm text-gray-500">
                                        <span>Officer: {{ $event->organization->officer->name }} • Submitted: {{ $event->created_at->diffForHumans() }}</span>
                                        <div class="flex space-x-2 mt-2 lg:mt-0">
                                            <a href="{{ route('dean.events.show', $event) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Details</a>
                                            @if($status==='pending')
                                                <form method="POST" action="{{ route('dean.events.approve', $event) }}">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Approve this event?')"
                                                            class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1 rounded">
                                                        Approve
                                                    </button>
                                                </form>
                                                <button onclick="showRejectEventModal({{ $event->id }}, '{{ $event->name }}')"
                                                        class="bg-red-600 hover:bg-red-700 text-white text-sm px-3 py-1 rounded">
                                                    Reject
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <p>No {{ $status }} events.</p>
                        </div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Event Rejection Modal -->
<div id="rejectEventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Event</h3>
            <p class="text-sm text-gray-500 mb-4">
                Are you sure you want to reject "<span id="eventName"></span>"? Please provide a reason:
            </p>
            <form id="rejectEventForm" method="POST">
                @csrf
                <textarea name="rejection_reason" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          rows="4" 
                          placeholder="Reason for rejection (optional)..."></textarea>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="hideRejectEventModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Reject Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectEventModal(eventId, eventName) {
    document.getElementById('eventName').textContent = eventName;
    document.getElementById('rejectEventForm').action = `/dean/events/${eventId}/reject`;
    document.getElementById('rejectEventModal').classList.remove('hidden');
}
function hideRejectEventModal() {
    document.getElementById('rejectEventModal').classList.add('hidden');
}
</script>
@endsection
