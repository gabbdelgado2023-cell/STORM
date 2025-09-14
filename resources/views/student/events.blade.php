@extends('layouts.student')

@section('student-content')
<div x-data="{ openModal: null, search: '' }" class="space-y-6">
    <h2 class="text-2xl font-bold mb-4">Upcoming Events</h2>

    <!-- Search Bar -->
    <div class="mb-4">
        <input 
            type="text" 
            placeholder="Search events..." 
            x-model="search"
            class="w-full md:w-1/3 px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-purple-500"
        >
    </div>

    <!-- Group Events by Month -->
    @php
        use Carbon\Carbon;
        $groupedEvents = $events->sortBy('date')->groupBy(function($event) {
            return Carbon::parse($event->date)->format('F Y');
        });
    @endphp

    @forelse($groupedEvents as $month => $monthEvents)
        <div>
            <h3 class="text-xl font-semibold mb-3 border-b pb-1">{{ $month }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($monthEvents as $event)
                    <div 
                        class="p-4 bg-white rounded-lg shadow hover:shadow-lg border transition duration-200"
                        x-show="search === '' || '{{ strtolower($event->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($event->organization->name ?? '') }}'.includes(search.toLowerCase())"
                    >
                        @if($event->image)
                            <img src="{{ asset('storage/'.$event->image) }}" alt="{{ $event->name }}" class="rounded w-full h-40 object-cover mb-3">
                        @endif
                        <h3 class="text-lg font-semibold">{{ $event->name }}</h3>
                        <p class="text-sm text-gray-600 mb-1">{{ $event->formatted_date }}</p>
                        <p class="text-sm text-gray-500 mb-2 truncate">{{ $event->description }}</p>
                        
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Upcoming
                        </span>

                        <button 
                            @click="openModal = 'event-{{ $event->id }}'" 
                            class="mt-3 px-3 py-1 bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                            View Details
                        </button>
                    </div>

                    <!-- Modal -->
                    <div 
                        x-show="openModal === 'event-{{ $event->id }}'" 
                        style="display:none"
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4 overflow-auto"
                    >
                        <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                            <div class="flex justify-between items-start mb-4">
                                <h2 class="text-2xl font-bold">{{ $event->name }}</h2>
                                <button @click="openModal = null" class="text-gray-500 hover:text-gray-700">&times;</button>
                            </div>

                            @if($event->image)
                                <img src="{{ asset('storage/'.$event->image) }}" alt="{{ $event->name }}" class="rounded w-full mb-4">
                            @endif

                            <div class="space-y-2 text-gray-700">
                                <p><strong>Date & Time:</strong> {{ $event->formatted_date }}</p>
                                <p><strong>Location:</strong> {{ $event->location }}</p>
                                <p><strong>Hosted By:</strong> {{ $event->organization->name ?? 'N/A' }}</p>
                                <p><strong>Budget:</strong> 
                                    @if($event->budget)
                                        ₱{{ number_format($event->budget, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Description:</strong></p>
                                <p class="text-gray-600">{{ $event->description }}</p>
                                <p><strong>Remaining Seats:</strong> {{ $event->capacity - $event->attendees_count ?? 0 }}</p>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <button 
                                    @click="alert('RSVP functionality coming soon!')"
                                    class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition mr-2">
                                    RSVP
                                </button>
                                <button 
                                    @click="openModal = null" 
                                    class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <p class="mt-6 text-gray-500">No upcoming events available at the moment.</p>
    @endforelse
</div>
@endsection
