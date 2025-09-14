@extends('layouts.dean')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Membership Monitoring</h1>
        <p class="text-gray-600 mt-2">Track student membership across all organizations</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        @php
            $cards = [
                ['title' => 'Total Memberships', 'count' => $membershipStats['total_memberships'], 'color' => 'blue', 'icon' => 'M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z'],
                ['title' => 'Approved', 'count' => $membershipStats['approved_memberships'], 'color' => 'green', 'icon' => 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'],
                ['title' => 'Pending', 'count' => $membershipStats['pending_memberships'], 'color' => 'yellow', 'icon' => 'M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z'],
                ['title' => 'Rejected', 'count' => $membershipStats['rejected_memberships'], 'color' => 'red', 'icon' => 'M10 18a8 8 0 100-16 8 8 0 000 16zm4.707-5.293a1 1 0 00-1.414-1.414L11 13.586l-2.293-2.293a1 1 0 00-1.414 1.414L9.586 15l-2.293 2.293a1 1 0 101.414 1.414L11 16.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15l2.293-2.293z']
            ];
        @endphp

        @foreach($cards as $card)
        <div class="bg-white shadow-md rounded-lg border-l-4 border-{{ $card['color'] }}-500 p-6 flex justify-between items-center hover:shadow-xl transition">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">{{ $card['title'] }}</h3>
                <p class="text-3xl font-bold text-{{ $card['color'] }}-600 mt-2">{{ $card['count'] }}</p>
            </div>
            <div class="text-{{ $card['color'] }}-500 w-12 h-12">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="{{ $card['icon'] }}"></path></svg>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Filter Tabs -->
    <div x-data="{ activeTab: 'all' }" class="mb-8">
        <nav class="flex space-x-6 border-b border-gray-200 mb-6">
            <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 text-sm font-medium border-b-2">All Memberships</button>
            <button @click="activeTab = 'multi'" :class="activeTab === 'multi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 text-sm font-medium border-b-2">Multiple Memberships</button>
            <button @click="activeTab = 'pending'" :class="activeTab === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 text-sm font-medium border-b-2">Pending Approvals</button>
        </nav>

        <!-- All Memberships Table -->
        <div x-show="activeTab === 'all'" class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">All Memberships</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Officer</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($memberships as $membership)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $membership->user->name }}</div>
                                    <div class="text-gray-500">{{ $membership->user->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $membership->organization->name }}</div>
                                    <div class="text-gray-500">{{ $membership->organization->category }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $membership->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($membership->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($membership->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $membership->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $membership->organization->officer->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Multiple Memberships -->
        <div x-show="activeTab === 'multi'" class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Students with Multiple Memberships</h3>
                <p class="text-sm text-gray-500 mt-1">Students who are members of multiple organizations</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($multiMemberships as $student)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $student->name }}</h4>
                            <p class="text-gray-500 text-sm">{{ $student->email }}</p>
                        </div>
                        <span class="px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                            {{ $student->memberships_count }} memberships
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($student->memberships as $membership)
                        <div class="p-3 bg-gray-50 rounded-lg flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $membership->organization->name }}</p>
                                <p class="text-xs text-gray-500">{{ $membership->organization->category }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $membership->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($membership->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($membership->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <p>No students have multiple memberships.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pending Memberships -->
        <div x-show="activeTab === 'pending'" class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Pending Membership Approvals</h3>
                <p class="text-sm text-gray-500 mt-1">Memberships awaiting officer approval</p>
            </div>
            @php $pendingMemberships = $memberships->where('status', 'pending'); @endphp
            @if($pendingMemberships->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Officer</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Days Pending</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingMemberships as $membership)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $membership->user->name }}</div>
                                    <div class="text-gray-500 text-sm">{{ $membership->user->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $membership->organization->name }}</div>
                                    <div class="text-gray-500 text-sm">{{ $membership->organization->category }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $membership->organization->officer->name }}</div>
                                    <div class="text-gray-500 text-sm">{{ $membership->organization->officer->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $membership->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="{{ $membership->created_at->diffInDays() > 7 ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                    {{ $membership->created_at->diffInDays() }} days
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-12 text-center text-gray-500">
                <p>No pending memberships.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
