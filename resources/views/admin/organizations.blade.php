@extends('layouts.admin')

@section('page-title', 'Organizations Management')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Organizations</h3>
        <a href="#" class="btn btn-primary btn-sm">+ Add Organization</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizations as $org)
                        <tr>
                            <td>{{ $org->name }}</td>
                            <td>{{ $org->description }}</td>
                            <td>{{ $org->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                <a href="#" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No organizations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $organizations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
