@extends('layouts.officer')

@section('content')
<div class="container mx-auto max-w-4xl">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Welcome, Officer!</h1>
        <p class="text-xl text-gray-600 mb-4">Let's set up your organization</p>
        <div class="w-24 h-1 bg-blue-500 mx-auto rounded"></div>
    </div>

    <!-- Setup Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-center space-x-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                <span class="ml-2 text-sm font-medium text-gray-700">Basic Info</span>
            </div>
            <div class="w-16 h-1 bg-gray-200"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                <span class="ml-2 text-sm font-medium text-gray-500">Vision & Mission</span>
            </div>
            <div class="w-16 h-1 bg-gray-200"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                <span class="ml-2 text-sm font-medium text-gray-500">Complete</span>
            </div>
        </div>
    </div>

    <!-- Setup Form -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('officer.setup.store') }}">
            @csrf
            
            <!-- Organization Name -->
            <div class="mb-6">
                <label for="name" class="block text-lg font-medium text-gray-700 mb-2">
                    What's your organization name? *
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg @error('name') border-red-500 @enderror"
                       placeholder="e.g., Computer Science Society, Drama Club, Environmental Club"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-lg font-medium text-gray-700 mb-2">
                    Tell students about your organization *
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Describe what your organization does, its goals, and why students should join..."
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category" class="block text-lg font-medium text-gray-700 mb-2">
                    Choose your organization category *
                </label>
                <select id="category" 
                        name="category"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg @error('category') border-red-500 @enderror"
                        required>
                    <option value="">Select a category</option>
                    <option value="Academic" {{ old('category') == 'Academic' ? 'selected' : '' }}>📚 Academic</option>
                    <option value="Sports" {{ old('category') == 'Sports' ? 'selected' : '' }}>⚽ Sports</option>
                    <option value="Cultural" {{ old('category') == 'Cultural' ? 'selected' : '' }}>🎭 Cultural</option>
                    <option value="Religious" {{ old('category') == 'Religious' ? 'selected' : '' }}>⛪ Religious</option>
                    <option value="Service" {{ old('category') == 'Service' ? 'selected' : '' }}>🤝 Community Service</option>
                    <option value="Professional" {{ old('category') == 'Professional' ? 'selected' : '' }}>💼 Professional</option>
                    <option value="Special Interest" {{ old('category') == 'Special Interest' ? 'selected' : '' }}>🎯 Special Interest</option>
                </select>
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Vision -->
            <div class="mb-6">
                <label for="vision" class="block text-lg font-medium text-gray-700 mb-2">
                    Organization Vision (Optional)
                </label>
                <textarea id="vision" 
                          name="vision" 
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vision') border-red-500 @enderror"
                          placeholder="What is your organization's long-term vision and aspirations?">{{ old('vision') }}</textarea>
                @error('vision')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mission -->
            <div class="mb-8">
                <label for="mission" class="block text-lg font-medium text-gray-700 mb-2">
                    Organization Mission (Optional)
                </label>
                <textarea id="mission" 
                          name="mission" 
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mission') border-red-500 @enderror"
                          placeholder="What is your organization's mission and main purpose?">{{ old('mission') }}</textarea>
                @error('mission')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Important Information -->
            <div class="mb-8 p-6 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-3">📋 Important Information</h3>
                <div class="text-blue-700 space-y-2">
                    <p class="flex items-start">
                        <span class="text-blue-500 mr-2">•</span>
                        You need at least <strong>5 approved members</strong> for your organization to be considered active
                    </p>
                    <p class="flex items-start">
                        <span class="text-blue-500 mr-2">•</span>
                        Students can browse and apply to join your organization once it's created
                    </p>
                    <p class="flex items-start">
                        <span class="text-blue-500 mr-2">•</span>
                        You can create events that need approval from the Dean/OSAD office
                    </p>
                    <p class="flex items-start">
                        <span class="text-blue-500 mr-2">•</span>
                        You can update this information anytime from your dashboard
                    </p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-center">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition duration-200 shadow-md hover:shadow-lg">
                    🚀 Create My Organization
                </button>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="mt-8 text-center">
        <p class="text-gray-500 text-sm">
            Need help setting up your organization? 
            <a href="#" class="text-blue-600 hover:text-blue-800 underline">Contact Support</a>
        </p>
    </div>
</div>

<script>
    // Add some interactive feedback
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '⏳ Creating Organization...';
            submitBtn.disabled = true;
        });
    });
</script>
@endsection