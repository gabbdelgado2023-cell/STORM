@extends('layouts.officer')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Officer Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
        <p class="text-sm text-gray-500">Managing: <span class="font-semibold">{{ $organization->name }}</span></p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Members</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $totalMembers }}</p>
                    <p class="text-sm text-gray-500">
                        @if($totalMembers >= 5)
                            <span class="text-green-600">✅ Active Organization</span>
                        @else
                            <span class="text-red-600">⚠️ Need {{ 5 - $totalMembers }} more members</span>
                        @endif
                    </p>
                </div>
                <div class="text-green-500">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Pending Requests</h3>
                    <p class="text-3xl font-bold text-yellow-600">{{ $pendingRequests }}</p>
                    <p class="text-sm text-gray-500">Awaiting approval</p>
                </div>
                <div class="text-yellow-500">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Events</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $eventsCount }}</p>
                    <p class="text-sm text-gray-500">Total created</p>
                </div>
                <div class="text-purple-500">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('officer.events.create') }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center transition duration-200">
                    📅 Create New Event
                </a>
                <a href="{{ route('officer.members') }}" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-center transition duration-200">
                    👥 Manage Members
                </a>
                <a href="{{ route('officer.profile') }}" 
                   class="block w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-center transition duration-200">
                    ⚙️ Edit Organization Profile
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Members</h3>
            @if($recentMembers->count() > 0)
                <div class="space-y-2">
                    @foreach($recentMembers as $member)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <div>
                                <p class="font-medium text-gray-800">{{ $member->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $member->user->email }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $member->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($member->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('officer.members') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View all members →
                    </a>
                </div>
            @else
                <p class="text-gray-500">No members yet. Encourage students to join your organization!</p>
            @endif
        </div>
    </div>

    <!-- Organization Status -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Organization Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-700 mb-2">{{ $organization->name }}</h4>
                <p class="text-gray-600 text-sm mb-2">{{ $organization->description }}</p>
                <p class="text-sm">
                    <span class="font-medium">Category:</span> {{ $organization->category }}
                </p>
                <p class="text-sm">
                    <span class="font-medium">Status:</span> 
                    <span class="px-2 py-1 text-xs rounded-full {{ $totalMembers >= 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $totalMembers >= 5 ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
            <div>
                @if($organization->vision)
                    <div class="mb-3">
                        <h5 class="font-medium text-gray-700">Vision</h5>
                        <p class="text-sm text-gray-600">{{ $organization->vision }}</p>
                    </div>
                @endif
                @if($organization->mission)
                    <div>
                        <h5 class="font-medium text-gray-700">Mission</h5>
                        <p class="text-sm text-gray-600">{{ $organization->mission }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection