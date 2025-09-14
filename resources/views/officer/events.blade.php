@extends('layouts.officer')

@section('content')
<div class="container mx-auto">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Event Management</h1>
            <p class="text-gray-600">{{ $organization->name }}</p>
        </div>
        <a href="{{ route('officer.events.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
            📅 Create New Event
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($events->count() > 0)
        <div class="grid gap-6">
            @foreach($events as $event)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6 flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $event->name }}</h3>
                            <p class="text-gray-600 mb-3">{{ $event->description }}</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">📅 Date:</span>
                                    <p class="text-gray-600">{{ $event->formatted_date }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">📍 Location:</span>
                                    <p class="text-gray-600">{{ $event->location }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">💰 Budget:</span>
                                    <p class="text-gray-600">
                                        {{ $event->budget ? '₱'.number_format($event->budget,2) : 'Not specified' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">⏰ Created:</span>
                                    <p class="text-gray-600">{{ $event->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="ml-4 flex flex-col items-end space-y-2">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $event->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($event->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($event->status) }}
                            </span>

                            <div class="flex space-x-2">
                                <button 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm"
                                    onclick="openEditModal({
                                        id: {{ $event->id }},
                                        name: @js($event->name),
                                        description: @js($event->description),
                                        date: '{{ \Carbon\Carbon::parse($event->date)->format('Y-m-d\TH:i') }}',
                                        location: @js($event->location),
                                        budget: '{{ $event->budget }}'
                                    })">
                                    ✏️ Edit
                                </button>

                                <form method="POST" action="{{ route('officer.events.delete', $event->id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this event?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No events created yet.</p>
    @endif

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" id="editEventForm" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Event Name</label>
                            <input type="text" name="name" id="eventName" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="eventDescription" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="datetime-local" name="date" id="eventDate" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" id="eventLocation" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Budget</label>
                            <input type="number" step="0.01" name="budget" id="eventBudget" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
function openEditModal(event) {
    let form = document.getElementById('editEventForm');
    form.action = `/officer/events/${event.id}`; // ✅ fixed route

    document.getElementById('eventName').value = event.name;
    document.getElementById('eventDescription').value = event.description;
    document.getElementById('eventDate').value = event.date;
    document.getElementById('eventLocation').value = event.location;
    document.getElementById('eventBudget').value = event.budget ?? '';

    let modal = new bootstrap.Modal(document.getElementById('editEventModal'));
    modal.show();
}
</script>
@endsection
