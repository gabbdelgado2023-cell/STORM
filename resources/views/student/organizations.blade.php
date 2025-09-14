@extends('layouts.student')

@section('student-content')
<div x-data="{ openModal: null }">
    <h2 class="text-xl font-bold mb-4">Available Organizations</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($organizations as $org)
            @php
                $membership = $org->memberships()->where('user_id', auth()->id())->first();
            @endphp

            <div class="p-4 bg-white rounded shadow border hover:shadow-lg transition relative">
                <h3 class="text-lg font-semibold">{{ $org->name }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($org->description, 100) }}</p>
                <p class="text-sm text-gray-500 mb-1"><strong>Vision:</strong> {{ Str::limit($org->vision, 80) }}</p>
                <p class="text-sm text-gray-500 mb-1"><strong>Mission:</strong> {{ Str::limit($org->mission, 80) }}</p>
                <p class="text-sm text-gray-500 mb-2"><strong>Total Members:</strong> {{ $org->memberships()->count() }}</p>
                <p class="text-sm text-gray-500 mb-2"><strong>Officer:</strong> {{ $org->officer ? $org->officer->name : 'Not Assigned' }}</p>

                {{-- Membership Status --}}
                @if($membership)
                    @if($membership->status === 'pending')
                        <span class="absolute top-2 right-2 px-2 py-1 text-xs bg-yellow-400 text-white rounded">Pending</span>
                        <button disabled class="mt-2 px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed">Apply</button>
                    @elseif($membership->status === 'approved')
                        <span class="absolute top-2 right-2 px-2 py-1 text-xs bg-green-600 text-white rounded">Joined</span>
                        <button @click="openModal = 'withdraw-{{ $org->id }}'" class="mt-2 px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Withdraw</button>
                    @elseif($membership->status === 'rejected')
                        <span class="absolute top-2 right-2 px-2 py-1 text-xs bg-red-500 text-white rounded">Rejected</span>
                        <button disabled class="mt-2 px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed">Apply</button>
                    @endif
                @else
                    <button @click="openModal = 'apply-{{ $org->id }}'" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Apply</button>
                @endif
            </div>

            {{-- Apply Modal --}}
            <div x-show="openModal === 'apply-{{ $org->id }}'" style="display:none" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
                <div class="bg-white rounded-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
                    <h2 class="text-2xl font-bold mb-4">{{ $org->name }}</h2>

                    <div class="space-y-2 text-gray-700">
                        <p><strong>Description:</strong></p>
                        <p class="text-gray-600">{{ $org->description }}</p>

                        <p><strong>Vision:</strong></p>
                        <p class="text-gray-600">{{ $org->vision }}</p>

                        <p><strong>Mission:</strong></p>
                        <p class="text-gray-600">{{ $org->mission }}</p>

                        <p><strong>Officer:</strong></p>
                        <p class="text-gray-600">{{ $org->officer ? $org->officer->name : 'Not Assigned' }}</p>

                        <p><strong>Total Members:</strong> {{ $org->memberships()->count() }}</p>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        @if(!$membership)
                        <form method="POST" action="{{ route('student.apply', $org->id) }}">
                            @csrf
                            <button class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Yes, Apply</button>
                        </form>
                        @endif
                        <button @click="openModal = null" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                    </div>
                </div>
            </div>

            {{-- Withdraw Modal --}}
            @if($membership && $membership->status === 'approved')
            <div x-show="openModal === 'withdraw-{{ $org->id }}'" style="display:none" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
                <div class="bg-white rounded-lg p-6 w-full max-w-md">
                    <h2 class="text-2xl font-bold mb-4">Withdraw from {{ $org->name }}?</h2>
                    <p class="text-gray-700 mb-4">Are you sure you want to withdraw your membership? You will need to reapply if you wish to join again.</p>
                    <div class="flex justify-end gap-2">
                        <form method="POST" action="{{ route('student.withdraw', $org->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Yes, Withdraw</button>
                        </form>
                        <button @click="openModal = null" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>

    @if($organizations->isEmpty())
        <p class="mt-6 text-gray-500">No organizations available at the moment.</p>
    @endif
</div>
@endsection
