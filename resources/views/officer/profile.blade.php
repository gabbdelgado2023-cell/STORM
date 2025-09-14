@extends('layouts.officer')

@section('content')
<div class="container mx-auto max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Organization Profile</h1>
        <p class="text-gray-600">Manage your organization's information and settings</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('officer.profile.update') }}">
            @csrf
            @method('PUT')
            
            <!-- Organization Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Organization Name *
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $organization->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description *
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          required>{{ old('description', $organization->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Category *
                </label>
                <select id="category" 
                        name="category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror"
                        required>
                    <option value="">Select Category</option>
                    <option value="Academic" {{ old('category', $organization->category) == 'Academic' ? 'selected' : '' }}>Academic</option>
                    <option value="Sports" {{ old('category', $organization->category) == 'Sports' ? 'selected' : '' }}>Sports</option>
                    <option value="Cultural" {{ old('category', $organization->category) == 'Cultural' ? 'selected' : '' }}>Cultural</option>
                    <option value="Religious" {{ old('category', $organization->category) == 'Religious' ? 'selected' : '' }}>Religious</option>
                    <option value="Service" {{ old('category', $organization->category) == 'Service' ? 'selected' : '' }}>Community Service</option>
                    <option value="Professional" {{ old('category', $organization->category) == 'Professional' ? 'selected' : '' }}>Professional</option>
                    <option value="Special Interest" {{ old('category', $organization->category) == 'Special Interest' ? 'selected' : '' }}>Special Interest</option>
                </select>
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Vision -->
            <div class="mb-6">
                <label for="vision" class="block text-sm font-medium text-gray-700 mb-2">
                    Vision Statement
                </label>
                <textarea id="vision" 
                          name="vision" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vision') border-red-500 @enderror"
                          placeholder="What is your organization's long-term vision?">{{ old('vision', $organization->vision) }}</textarea>
                @error('vision')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mission -->
            <div class="mb-6">
                <label for="mission" class="block text-sm font-medium text-gray-700 mb-2">
                    Mission Statement
                </label>
                <textarea id="mission" 
                          name="mission" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mission') border-red-500 @enderror"
                          placeholder="What is your organization's mission and purpose?">{{ old('mission', $organization->mission) }}</textarea>
                @error('mission')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Organization Information Display -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Current Organization Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Officer:</span>
                        <p class="text-gray-600">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Created:</span>
                        <p class="text-gray-600">{{ $organization->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Total Members:</span>
                        <p class="text-gray-600">{{ $organization->approvedMembers()->count() }} approved</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $organization->isActive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $organization->status }}
                        </span>
                    </div>
                </div>
                
                @if(!$organization->isActive())
                    <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <p class="text-sm text-yellow-700">
                            <strong>Note:</strong> Your organization needs at least 5 approved members to be considered active. 
                            Currently you have {{ $organization->approvedMembers()->count() }} member(s).
                        </p>
                    </div>
                @endif
            </div>

            <!-- Tips Section -->
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
                <h4 class="text-sm font-medium text-blue-800 mb-2">💡 Profile Tips</h4>
                <div class="text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Keep your description clear and engaging to attract new members</li>
                        <li>A well-defined vision and mission helps students understand your purpose</li>
                        <li>Update your profile regularly to reflect current activities and goals</li>
                        <li>Your profile information is visible to all students browsing organizations</li>
                    </ul>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <a href="{{ route('officer.dashboard') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    Back to Dashboard
                </a>
                
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    💾 Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
        <h3 class="text-lg font-medium text-red-800 mb-2">⚠️ Danger Zone</h3>
        <p class="text-sm text-red-700 mb-4">
            These actions are irreversible. Please be certain before proceeding.
        </p>
        <div class="space-y-3">
            <div class="flex justify-between items-center p-3 bg-red-50 rounded border">
                <div>
                    <h4 class="font-medium text-red-800">Transfer Organization Ownership</h4>
                    <p class="text-sm text-red-600">Transfer this organization to another officer</p>
                </div>
                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition duration-200"
                        onclick="alert('Feature coming soon!')">
                    Transfer
                </button>
            </div>
            
            <div class="flex justify-between items-center p-3 bg-red-50 rounded border">
                <div>
                    <h4 class="font-medium text-red-800">Delete Organization</h4>
                    <p class="text-sm text-red-600">Permanently delete this organization and all its data</p>
                </div>
                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition duration-200"
                        onclick="alert('Feature coming soon!')">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection