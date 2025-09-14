@extends('layouts.dean')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Organization Management</h1>
        <p class="text-gray-600">Review and approve organization registrations</p>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6" x-data="{ activeTab: 'all' }">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    All Organizations
                </button>
                <button @click="activeTab = 'pending'" 
                        :class="activeTab === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Pending Approval
                </button>
                <button @click="activeTab = 'approved'" 
                        :class="activeTab === 'approved' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Approved
                </button>
                <button @click="activeTab = 'rejected'" 
                        :class="activeTab === 'rejected' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Rejected
                </button>
            </nav>
        </div>

        <!-- Organization Cards -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($organizations as $org)
                <div x-show="activeTab === 'all' || activeTab === '{{ $org->approval_status }}'" x-cloak
                     class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Header -->
                    <div class="p-4 bg-gray-50 border-b">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $org->name ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-600">{{ $org->category ?? 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $org->approval_status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($org->approval_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($org->approval_status ?? 'N/A') }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <p class="text-gray-700 text-sm mb-3">{{ Str::limit($org->description ?? 'No description provided.', 100) }}</p>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                                Officer: {{ $org->officer->name ?? 'N/A' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Members: {{ $org->memberships_count ?? 0 }}
                                @if(($org->memberships_count ?? 0) >= 5)
                                    <span class="ml-1 text-green-600">(Active)</span>
                                @else
                                    <span class="ml-1 text-red-600">(Inactive)</span>
                                @endif
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Applied: {{ $org->created_at?->format('M d, Y') ?? 'N/A' }}
                            </div>
                        </div>

                        @if($org->rejection_reason && $org->approval_status === 'rejected')
                            <div class="mb-4 p-3 bg-red-50 rounded-lg">
                                <p class="text-sm text-red-800">
                                    <strong>Rejection Reason:</strong> {{ $org->rejection_reason }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="px-4 py-3 bg-gray-50 border-t">
                        <div class="flex justify-between items-center">
                            <a href="{{ route('dean.organizations.show', $org) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Details
                            </a>
                            
                            @if($org->approval_status === 'pending')
                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('dean.organizations.approve', $org) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to approve this organization?')"
                                                class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded">
                                            Approve
                                        </button>
                                    </form>
                                    <button onclick="showRejectModal({{ $org->id }}, '{{ $org->name }}')"
                                            class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded">
                                        Reject
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($organizations->count() === 0)
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No organizations</h3>
                <p class="mt-1 text-sm text-gray-500">No organizations have been registered yet.</p>
            </div>
        @endif
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Organization</h3>
            <p class="text-sm text-gray-500 mb-4">
                Are you sure you want to reject "<span id="orgName"></span>"? Please provide a reason:
            </p>
            
            <form id="rejectForm" method="POST">
                @csrf
                <textarea name="rejection_reason" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          rows="4" 
                          placeholder="Reason for rejection..."
                          required></textarea>
                
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" 
                            onclick="hideRejectModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Reject Organization
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(orgId, orgName) {
    document.getElementById('orgName').textContent = orgName;
    document.getElementById('rejectForm').action = `/dean/organizations/${orgId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
