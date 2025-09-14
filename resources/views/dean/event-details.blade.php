@extends('layouts.dean')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $event->name }}</h1>
                <p class="text-gray-600">Event Details & Review</p>
            </div>
            <a href="{{ route('dean.events') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                ← Back to Events
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Information -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Event Information</h2>
                    <span class="px-3 py-1 text-sm rounded-full 
                        {{ $event->status === 'approved' ? 'bg-green-100 text-green-800' : 
                           ($event->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event Name</label>
                        <p class="text-gray-900">{{ $event->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organization</label>
                        <p class="text-gray-900">{{ $event->organization->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                        <p class="text-gray-900">{{ $event->date->format('l, F j, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <p class="text-gray-900">{{ $event->location }}</p>
                    </div>
                    @if($event->budget)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
                        <p class="text-gray-900">${{ number_format($event->budget, 2) }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Submitted</label>
                        <p class="text-gray-900">{{ $event->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <p class="text-gray-900">{{ $event->description }}</p>
                </div>

                @if($event->status === 'future' && $event->date->isFuture())
                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm text-blue-800">
                            This event is scheduled for {{ $event->date->diffForHumans() }}.
                        </p>
                    </div>
                </div>
                @endif

                @if($event->date->isPast())
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm text-gray-600">
                            This event occurred {{ $event->date->diffForHumans() }}.
                        </p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Organization Details -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Organization Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">{{ $event->organization->name }}</h3>
                        <p class="text-gray-600 text-sm mb-3">{{ $event->organization->description }}</p>
                        <div class="space-y-1 text-sm">
                            <p><span class="font-medium">Category:</span> {{ $event->organization->category }}</p>
                            <p><span class="font-medium">Officer:</span> {{ $event->organization->officer->name }}</p>
                            <p><span class="font-medium">Email:</span> {{ $event->organization->officer->email }}</p>
                        </div>
                    </div>
                    <div>
                        @if($event->organization->vision)
                        <div class="mb-3">
                            <h4 class="font-medium text-gray-700 text-sm">Vision</h4>
                            <p class="text-sm text-gray-600">{{ $event->organization->vision }}</p>
                        </div>
                        @endif
                        @if($event->organization->mission)
                        <div>
                            <h4 class="font-medium text-gray-700 text-sm">Mission</h4>
                            <p class="text-sm text-gray-600">{{ $event->organization->mission }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Event Status -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Status</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $event->status === 'approved' ? 'bg-green-100 text-green-800' : 
                               ($event->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Event Date:</span>
                        <span class="font-medium">{{ $event->date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Time Until Event:</span>
                        <span class="font-medium {{ $event->date->isPast() ? 'text-gray-500' : 'text-blue-600' }}">
                            {{ $event->date->diffForHumans() }}
                        </span>
                    </div>
                    @if($event->budget)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Budget:</span>
                        <span class="font-medium">${{ number_format($event->budget, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Organization Stats -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Organization Info</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Members:</span>
                        <span class="font-medium">{{ $event->organization->memberships->where('status', 'approved')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium {{ $event->organization->isActive() ? 'text-green-600' : 'text-red-600' }}">
                            {{ $event->organization->isActive() ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Events:</span>
                        <span class="font-medium">{{ $event->organization->events->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Approval Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $event->organization->approval_status === 'approved' ? 'bg-green-100 text-green-800' : 
                               ($event->organization->approval_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($event->organization->approval_status ?? 'pending') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($event->status === 'pending')
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('dean.events.approve', $event) }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to approve this event?')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg">
                            Approve Event
                        </button>
                    </form>
                    
                    <button onclick="showRejectEventModal({{ $event->id }}, '{{ $event->name }}')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg">
                        Reject Event
                    </button>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Event Submitted</p>
                            <p class="text-xs text-gray-500">{{ $event->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($event->status !== 'pending')
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 {{ $event->status === 'approved' ? 'bg-green-600' : 'bg-red-600' }} rounded-full mt-2"></div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">
                                Event {{ ucfirst($event->status) }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $event->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 {{ $event->date->isPast() ? 'bg-gray-400' : 'bg-purple-600' }} rounded-full mt-2"></div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">
                                Event {{ $event->date->isPast() ? 'Occurred' : 'Scheduled' }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $event->date->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
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
                    <button type="button" 
                            onclick="hideRejectEventModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Reject Event
                    </button>
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