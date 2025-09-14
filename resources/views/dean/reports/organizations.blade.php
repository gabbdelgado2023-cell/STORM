@extends('layouts.dean')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Organizations Report</h1>
                <p class="text-gray-600">Generated on {{ $reportData['generated_at']->format('F j, Y \a\t h:i A') }}</p>
                <p class="text-sm text-gray-500">By {{ $reportData['generated_by'] }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('dean.reports') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    ← Back to Reports
                </a>
                <button onclick="window.print()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Organizations</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $reportData['total_organizations'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Active Organizations</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $reportData['active_organizations'] }}</p>
                    <p class="text-sm text-green-600">5+ members</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Inactive Organizations</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $reportData['inactive_organizations'] }}</p>
                    <p class="text-sm text-red-600">&lt; 5 members</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Organizations Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Organization Details</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Officer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approval</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reportData['organizations'] as $index => $org)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $org->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($org->description, 50) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $org->category }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $org->officer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $org->officer->email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $org->memberships_count }}</div>
                            @if($org->memberships_count >= 5)
                                <span class="text-xs text-green-600">Active</span>
                            @else
                                <span class="text-xs text-red-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $org->memberships_count >= 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $org->memberships_count >= 5 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ ($org->approval_status ?? 'pending') === 'approved' ? 'bg-green-100 text-green-800' : 
                                   (($org->approval_status ?? 'pending') === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($org->approval_status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $org->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Organizations by Category</h2>
        </div>
        <div class="p-6">
            @php
                $categories = $reportData['organizations']->groupBy('category');
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categories as $category => $orgs)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">{{ $category }}</h3>
                    <div class="text-2xl font-bold text-blue-600 mb-1">{{ $orgs->count() }}</div>
                    <div class="text-sm text-gray-500">
                        {{ $orgs->where('memberships_count', '>=', 5)->count() }} active, 
                        {{ $orgs->where('memberships_count', '<', 5)->count() }} inactive
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { font-size: 12px; }
    .container { max-width: none; }
}
</style>
@endsection