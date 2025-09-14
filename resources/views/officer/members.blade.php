@extends('layouts.officer')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Member Management</h1>
            <p class="text-gray-600">{{ $organization->name }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">Organization Status</p>
            <span class="px-3 py-1 rounded-full text-sm font-medium
                {{ $members->get('approved', collect())->count() >= 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $members->get('approved', collect())->count() >= 5 ? 'Active' : 'Inactive' }}
            </span>
        </div>
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

    <!-- Pending Requests -->
    @if($members->has('pending') && $members->get('pending')->count() > 0)
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm mr-2">
                        {{ $members->get('pending')->count() }}
                    </span>
                    Pending Membership Requests
                </h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($members->get('pending') as $membership)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-gray-900">{{ $membership->user->name }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-gray-600">{{ $membership->user->email }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-gray-600">{{ $membership->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex space-x-2">
                                            <form method="POST" action="{{ route('officer.approve-membership', $membership->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition duration-200"
                                                        onclick="return confirm('Are you sure you want to approve this membership?')">
                                                    ✅ Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('officer.reject-membership', $membership->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition duration-200"
                                                        onclick="return confirm('Are you sure you want to reject this membership?')">
                                                    ❌ Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Approved Members -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm mr-2">
                    {{ $members->get('approved', collect())->count() }}
                </span>
                Approved Members
            </h2>
        </div>
        <div class="p-6">
            @if($members->has('approved') && $members->get('approved')->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($members->get('approved') as $membership)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-gray-900">{{ $membership->user->name }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-gray-600">{{ $membership->user->email }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-gray-600">{{ $membership->updated_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            Active Member
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No approved members yet</h3>
                    <p class="text-gray-500">Start by approving pending membership requests.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Rejected Members (if any) -->
    @if($members->has('rejected') && $members->get('rejected')->count() > 0)
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm mr-2">
                        {{ $members->get('rejected')->count() }}
                    </span>
                    Rejected Applications
                </h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rejected Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($members->get('rejected') as $membership)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-gray-900">{{ $membership->user->name }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-gray-600">{{ $membership->user->email }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-gray-600">{{ $membership->updated_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <form method="POST" action="{{ route('officer.approve-membership', $membership->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition duration-200"
                                                    onclick="return confirm('Are you sure you want to approve this membership?')">
                                                ✅ Approve
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Minimum Members Warning -->
    @if($members->get('approved', collect())->count() < 5)
        <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Warning:</strong> Your organization needs at least 5 approved members to be considered active. 
                        You currently have {{ $members->get('approved', collect())->count() }} approved member(s). 
                        Need {{ 5 - $members->get('approved', collect())->count() }} more.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection