@extends('layouts.admin')

@section('page-title', 'Manage Categories')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Categories</h1>
    </div>

    {{-- Add Category Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">Add New Category</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-8">
                    <label for="categoryName" class="form-label fw-semibold">Category Name</label>
                    <input type="text" name="name" id="categoryName" 
                           class="form-control @error('name') is-invalid @enderror" 
                           placeholder="Enter category name" 
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0">All Categories</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $category->name }}</td>
                                <td class="text-center">
                                    {{-- Edit Button --}}
                                    <button class="btn btn-sm btn-warning" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editCategoryModal" 
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>

                                    {{-- Delete Form --}}
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this category?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" id="editCategoryForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="category_id" id="editCategoryId" value="{{ old('category_id') }}">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Edit Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="form-label">Category Name</label>
          <input type="text" name="name" id="editCategoryName" 
                 class="form-control @error('name') is-invalid @enderror" 
                 value="{{ old('name') }}" required>
          @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
          @enderror
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning"><i class="bi bi-check-circle"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editCategoryModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');

        const form = document.getElementById('editCategoryForm');
        const inputName = document.getElementById('editCategoryName');
        const inputId = document.getElementById('editCategoryId');

        inputName.value = name;
        inputId.value = id;
        form.action = `/admin/categories/${id}`;
    });

    // Auto-open modal if validation fails on edit
    @if($errors->any() && session('edit_category_id'))
        const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        modal.show();
        document.getElementById('editCategoryId').value = "{{ session('edit_category_id') }}";
        document.getElementById('editCategoryName').value = "{{ old('name') }}";
    @endif
});
</script>
@endpush
