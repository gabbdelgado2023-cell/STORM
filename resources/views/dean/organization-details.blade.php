@extends('layouts.dean')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $organization->name }}</h1>
                <p class="text-gray-600">Organization Details & Review</p>
            </div>
            <a href="{{ route('dean.organizations') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                ← Back to Organizations
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Organization Information -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Organization Information</h2>
                    <span class="px-3 py-1 text-sm rounded-full 
                        {{ $organization->approval_status === 'approved' ? 'bg-green-100 text-green-800' : 
                           ($organization->approval_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($organization->approval_status) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organization Name</label>
                        <p class="text-gray-900">{{ $organization->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <p class="text-gray-900">{{ $organization->category }}</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <p class="text-gray-900">{{ $organization->description }}</p>
                </div>
                
                @if($organization->vision)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vision</label>
                    <p class="text-gray-900">{{ $organization->vision }}</p>
                </div>
                @endif
                
                @if($organization->mission)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mission</label>
                    <p class="text-gray-900">{{ $organization->mission }}</p>
                </div>
                @endif

                @if($organization->rejection_reason && $organization->approval_status === 'rejected')
                <div class="mb-4 p-4 bg-red-50 rounded-lg">
                    <label class="block text-sm font-medium text-red-700 mb-1">Rejection Reason</label>
                    <p class="text-red-800">{{ $organization->rejection_reason }}</p>
                </div>
                @endif
            </div>

            <!-- Members List -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Members ({{ $organization->memberships_count }})</h2>
                
                @if($organization->memberships->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($organization->memberships as $membership)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $membership->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $membership->user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $membership->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($membership->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($membership->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $membership->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No members yet.</p>
                @endif
            </div>

            <!-- Events -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Events</h2>
                
                @if($organization->events->count() > 0)
                    <div class="space-y-3">
                        @foreach($organization->events as $event)
                        <div class="p-4 border rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $event->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $event->location }} • {{ $event->date->format('M d, Y h:i A') }}</p>
                                    @if($event->budget)
                                        <p class="text-sm text-gray-500">Budget: ${{ number_format($event->budget, 2) }}</p>
                                    @endif
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $event->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($event->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No events created yet.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Officer Information -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Officer Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="text-gray-900">{{ $organization->officer->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="text-gray-900">{{ $organization->officer->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Registration Date</label>
                        <p class="text-gray-900">{{ $organization->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Members:</span>
                        <span class="font-medium">{{ $organization->memberships_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Active Status:</span>
                        <span class="font-medium {{ $organization->memberships_count >= 5 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $organization->memberships_count >= 5 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Events:</span>
                        <span class="font-medium">{{ $organization->events->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($organization->approval_status === 'pending')
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('dean.organizations.approve', $organization) }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to approve this organization?')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg">
                            Approve Organization
                        </button>
                    </form>
                    
                    <button onclick="showRejectModal({{ $organization->id }}, '{{ $organization->name }}')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg">
                        Reject Organization
                    </button>
                </div>
            </div>
            @endif

            @if($organization->approval_status === 'approved' && $organization->approved_by)
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Approval Info</h3>
                <div class="space-y-2">
                    <p class="text-sm text-gray-600">
                        Approved by: <span class="font-medium">{{ $organization->approver->name }}</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Approved on: <span class="font-medium">{{ $organization->approved_at->format('M d, Y h:i A') }}</span>
                    </p>
                </div>
            </div>
            @endif
        </div>
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