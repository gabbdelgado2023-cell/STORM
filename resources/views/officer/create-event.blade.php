@extends('layouts.officer')

@section('content')
<div class="container mx-auto max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 mb-2">
            <a href="{{ route('officer.events') }}" class="text-blue-600 hover:text-blue-800">← Back to Events</a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Create New Event</h1>
        <p class="text-gray-600">Plan an event for your organization members</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('officer.events.store') }}">
            @csrf
            
            <!-- Event Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Event Name *
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       placeholder="e.g., Annual General Meeting, Leadership Summit, Community Service"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Event Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Event Description *
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Describe the event purpose, activities, and what participants can expect..."
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date and Location Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Event Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Date & Time *
                    </label>
                    <input type="datetime-local" 
                           id="date" 
                           name="date" 
                           value="{{ old('date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror"
                           min="{{ date('Y-m-d\TH:i') }}"
                           required>
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Location *
                    </label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror"
                           placeholder="e.g., Main Auditorium, Room 301, Online via Zoom"
                           required>
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Budget -->
            <div class="mb-6">
                <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">
                    Event Budget (Optional)
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">₱</span>
                    <input type="number" 
                           id="budget" 
                           name="budget" 
                           value="{{ old('budget') }}"
                           step="0.01"
                           min="0"
                           class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('budget') border-red-500 @enderror"
                           placeholder="0.00">
                </div>
                @error('budget')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">
                    Enter the estimated budget for this event (materials, venue, refreshments, etc.)
                </p>
            </div>

            <!-- Information Box -->
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-800">Important Information</h4>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Your event will be submitted for approval by the Dean/OSAD office</li>
                                <li>Only approved events will be visible to students</li>
                                <li>You can edit pending events, but approved events cannot be modified</li>
                                <li>Make sure all details are accurate before submitting</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <a href="{{ route('officer.events') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    Cancel
                </a>
                
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    📅 Create Event & Submit for Approval
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date');
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        dateInput.min = now.toISOString().slice(0, 16);
    });
</script>
@endsection