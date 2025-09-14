@extends('layouts.student')

@section('content')
{{-- small helper CSS to hide x-cloak content until Alpine initializes --}}
<style>
    [x-cloak] { display: none !important; }
</style>

<h1 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h1>

<!-- Dashboard Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white rounded shadow">
        <h2 class="text-lg font-semibold">Total Organizations</h2>
        <p class="text-3xl font-bold text-blue-600">{{ $organizationsCount }}</p>
    </div>
    <div class="p-4 bg-white rounded shadow">
        <h2 class="text-lg font-semibold">My Memberships</h2>
        <p class="text-3xl font-bold text-green-600">{{ $myMembershipsCount }}</p>
    </div>
    <div class="p-4 bg-white rounded shadow">
        <h2 class="text-lg font-semibold">Approved Events</h2>
        <p class="text-3xl font-bold text-purple-600">{{ $eventsCount }}</p>
    </div>
</div>

<!-- Notifications -->
<div class="bg-white p-6 rounded shadow mb-6">
    <h2 class="text-xl font-bold mb-4">Notifications</h2>
    @if(!empty($notifications) && count($notifications) > 0)
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            @foreach($notifications as $note)
                <li>{{ $note }}</li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500">No new notifications.</p>
    @endif
</div>

{{-- Alpine wrapper: listens for a custom open-modal event from FullCalendar --}}
<div x-data="{ openModal: null }" @open-modal.window="openModal = $event.detail" class="space-y-6">

    <!-- Upcoming Events Section -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Upcoming Events</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($recentEvents as $event)
                <div class="p-4 bg-white rounded shadow border hover:shadow-lg transition relative">
                    <h3 class="text-lg font-semibold">{{ $event->title ?? ($event->name ?? 'Untitled') }}</h3>
                    <p class="text-sm text-gray-600 mb-1">{{ $event->formatted_date ?? ($event->date ?? '') }}</p>
                    <p class="text-sm text-gray-500 mb-1"><strong>Organization:</strong> {{ $event->organization->name ?? '—' }}</p>
                    <span class="inline-block px-2 py-1 text-xs rounded text-white
                        @if($event->status === 'approved') bg-green-600 
                        @elseif($event->status === 'pending') bg-yellow-500 
                        @else bg-red-600 @endif">
                        {{ ucfirst($event->status) }}
                    </span>

                    <div class="mt-2 flex gap-2">
                        <button 
                            @click="openModal = 'event-{{ $event->id }}'" 
                            class="px-3 py-1 bg-purple-600 text-white rounded hover:bg-purple-700">
                            View Details
                        </button>

                        {{-- Quick action: open calendar's event modal as well by dispatching same detail --}}
                        <button
                            @click="$dispatch('open-modal', 'event-{{ $event->id }}')"
                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Highlight in Calendar
                        </button>
                    </div>
                </div>

                <!-- Modal -->
                <div 
                    x-cloak
                    x-show="openModal === 'event-{{ $event->id }}'"
                    @click.self="openModal = null"
                    x-transition.opacity
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
                >
                    <div class="bg-white rounded-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
                        <h2 class="text-2xl font-bold mb-4">{{ $event->title ?? ($event->name ?? 'Untitled') }}</h2>
                        <div class="space-y-2 text-gray-700">
                            <p><strong>Date & Time:</strong> {{ $event->formatted_date ?? ($event->date ?? '') }}</p>
                            <p><strong>Location:</strong> {{ $event->location ?? '—' }}</p>
                            <p><strong>Description:</strong> {{ $event->description ?? 'No description' }}</p>
                            <p><strong>Budget:</strong> ₱{{ number_format($event->budget ?? 0, 2) }}</p>
                            <p><strong>Organization:</strong> {{ $event->organization->name ?? '—' }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($event->status ?? '—') }}</p>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button 
                                @click="openModal = null" 
                                class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No upcoming events available.</p>
            @endforelse
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">📅 Event Calendar</h2>
        <div id="event-calendar"></div>
    </div>
</div>

{{-- Load Alpine first (defer) so x-data is available before other scripts run --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- FullCalendar CSS + JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // calendar element
        var calendarEl = document.getElementById('event-calendar');

        // Build FullCalendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 500,
            events: @json($calendarEvents), // must be an array of { id, title, start }
            eventColor: '#7c3aed',
            eventTextColor: '#fff',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            eventClick: function(info) {
                // Prevent default navigation (if any)
                info.jsEvent.preventDefault();

                // dispatch a window-level event so Alpine can listen and open modal
                // detail: string like 'event-123' to match modal x-show
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: `event-${info.event.id}`
                }));

                // optional: scroll modal into view if needed (Alpine will show it)
                // setTimeout(() => document.querySelector(`[x-show]`)?.scrollIntoView({ behavior: 'smooth', block: 'center' }), 200);
            }
        });

        calendar.render();

        // Optional: if you want to highlight the clicked calendar event element UI-wise
        // you can add a CSS class to info.el inside eventClick and remove after some time.
    });
</script>
@endsection
