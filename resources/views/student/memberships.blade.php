@extends('layouts.student')

@section('student-content')
<div x-data="membershipsComponent()" class="space-y-6">

    <h2 class="text-2xl font-bold text-gray-800 mb-4">My Memberships</h2>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
        <div class="flex gap-2 items-center">
            <label for="status" class="font-medium">Filter by Status:</label>
            <select id="status" x-model="filterStatus" class="border rounded px-2 py-1">
                <option value="">All</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div class="flex gap-2 items-center">
            <input type="text" placeholder="Search organization..." x-model="searchQuery"
                class="border rounded px-2 py-1 w-full md:w-64">
        </div>
    </div>

    <!-- Success/Error Messages -->
    <template x-if="successMessage">
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded" x-text="successMessage"></div>
    </template>
    <template x-if="errorMessage">
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded" x-text="errorMessage"></div>
    </template>

    <!-- Memberships Table -->
    <table class="min-w-full bg-white border rounded shadow">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border text-left cursor-pointer" @click="memberships.sort((a,b) => a.organization.name.localeCompare(b.organization.name))">
                    Organization
                </th>
                <th class="px-4 py-2 border text-left">Status</th>
                <th class="px-4 py-2 border text-left">Date Joined</th>
                <th class="px-4 py-2 border text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="membership in memberships" :key="membership.id">
                <tr x-show="(
                            membership.status.toLowerCase().includes(filterStatus.toLowerCase()) || !filterStatus
                        ) && (
                            membership.organization.name.toLowerCase().includes(searchQuery.toLowerCase())
                        ) && membership.status !== 'withdrawn'"
                    class="hover:bg-gray-50 border-b">
                    <td class="px-4 py-2 border font-medium" x-text="membership.organization.name"></td>
                    <td class="px-4 py-2 border">
                        <span class="px-2 py-1 rounded text-white text-sm"
                            :class="{
                                'bg-green-600': membership.status === 'approved',
                                'bg-yellow-500': membership.status === 'pending',
                                'bg-red-600': membership.status === 'rejected'
                            }"
                            x-text="membership.status.charAt(0).toUpperCase() + membership.status.slice(1)"></span>
                    </td>
                    <td class="px-4 py-2 border" x-text="new Date(membership.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })"></td>
                    <td class="px-4 py-2 border text-center">
                        <button @click="openModal = membership.id"
                            class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            View
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>

    @if($memberships->isEmpty())
    <p class="mt-6 text-gray-500">You have not joined any organizations yet.</p>
    @endif

    <!-- Modals -->
    <template x-for="membership in memberships" :key="'modal-' + membership.id">
        <div x-show="openModal === membership.id" style="display:none"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto relative">
                <h2 class="text-2xl font-bold mb-4" x-text="membership.organization.name"></h2>

                <div class="space-y-3 text-gray-700">
                    <p><strong>Status:</strong>
                        <span class="px-2 py-1 rounded text-white"
                            :class="{
                                'bg-green-600': membership.status === 'approved',
                                'bg-yellow-500': membership.status === 'pending',
                                'bg-red-600': membership.status === 'rejected'
                            }"
                            x-text="membership.status.charAt(0).toUpperCase() + membership.status.slice(1)"></span>
                    </p>

                    <p><strong>Date Joined:</strong> <span x-text="new Date(membership.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })"></span></p>

                    <p><strong>Description:</strong></p>
                    <p class="text-gray-600" x-text="membership.organization.description ?? 'N/A'"></p>

                    <p><strong>Vision:</strong></p>
                    <p class="text-gray-600" x-text="membership.organization.vision ?? 'N/A'"></p>

                    <p><strong>Mission:</strong></p>
                    <p class="text-gray-600" x-text="membership.organization.mission ?? 'N/A'"></p>

                    <p><strong>Total Members:</strong> <span x-text="membership.organization.members_count"></span></p>

                    <div class="mt-4">
                        <button @click="withdraw(membership.id)"
                            class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 w-full">
                            Withdraw Membership
                        </button>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button @click="openModal = null"
                        class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Close</button>
                </div>
            </div>
        </div>
    </template>
</div>
<script>
function membershipsComponent() {
    return {
        memberships: @json($memberships),
        openModal: null,
        filterStatus: '',
        searchQuery: '',
        successMessage: '',
        errorMessage: '',

        withdraw(id) {
            if (!confirm('Are you sure you want to withdraw from this organization?')) return;

            fetch(`/student/memberships/${id}/withdraw`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.memberships = this.memberships.filter(m => m.id !== id);
                    this.successMessage = data.message;
                    setTimeout(() => this.successMessage = '', 3000);
                    this.openModal = null;
                } else {
                    this.errorMessage = data.message || 'Something went wrong';
                    setTimeout(() => this.errorMessage = '', 3000);
                }
            })
            .catch(err => {
                this.errorMessage = 'Error withdrawing membership';
                setTimeout(() => this.errorMessage = '', 3000);
                console.error(err);
            });
        }
    }
}
</script>
@endsection
