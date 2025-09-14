@extends('layouts.admin')

@section('page-title', 'User Management')

@section('content')
<div class="container-fluid py-4">
    <!-- User Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $userStats['total'] }}</h4>
                            <p class="card-text mb-0">Total Users</p>
                        </div>
                        <i class="bi bi-people fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $userStats['students'] }}</h4>
                            <p class="card-text mb-0">Students</p>
                        </div>
                        <i class="bi bi-mortarboard fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $userStats['officers'] }}</h4>
                            <p class="card-text mb-0">Officers</p>
                        </div>
                        <i class="bi bi-person-badge fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $userStats['admins'] + $userStats['deans'] }}</h4>
                            <p class="card-text mb-0">Administrators</p>
                        </div>
                        <i class="bi bi-shield-check fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Users</h5>
            <div>
                <button class="btn btn-outline-secondary btn-sm me-2" onclick="toggleBulkActions()">
                    <i class="bi bi-check-square"></i> Bulk Actions
                </button>
                <button class="btn btn-primary btn-sm" onclick="openUserModal()">
                    <i class="bi bi-person-plus"></i> Add New User
                </button>
            </div>
        </div>

        <!-- Bulk Actions Panel -->
        <div id="bulkActionsPanel" class="card-body border-bottom bg-light" style="display: none;">
            <form id="bulkActionForm" method="POST" action="{{ route('admin.users.bulk-actions') }}">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <select name="action" class="form-select form-select-sm" required onchange="toggleRoleSelect(this.value)">
                            <option value="">Select Action</option>
                            <option value="delete">Delete Selected</option>
                            <option value="change_role">Change Role</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="new_role" class="form-select form-select-sm" id="roleSelect" style="display: none;">
                            <option value="">Select New Role</option>
                            <option value="student">Student</option>
                            <option value="officer">Officer</option>
                            <option value="dean">Dean</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-warning btn-sm me-2" onclick="return confirm('Are you sure you want to execute this action?')">Execute</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleBulkActions()">Cancel</button>
                    </div>
                    <div class="col-md-2 text-end">
                        <span id="selectedCount" class="text-sm text-muted">0 users selected</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Search & Filter -->
        <div class="card-body border-bottom">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="userSearch" class="form-control" placeholder="Search users by name or email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="roleFilter" class="form-select form-select-sm">
                        <option value="">All Roles</option>
                        <option value="student">Students</option>
                        <option value="officer">Officers</option>
                        <option value="dean">Deans</option>
                        <option value="admin">Admins</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th width="50"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr data-role="{{ $user->role }}" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                            <td>
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-check-input user-checkbox" 
                                       @if($user->id === auth()->id()) disabled title="Cannot select your own account" @endif>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'dean' ? 'warning' : ($user->role === 'officer' ? 'info' : 'success')) }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'secondary' }}">
                                    {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                </span>
                            </td>
                            <td><small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Actions</button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0);" onclick='openUserModal(@json($user))'>
                                            <i class="bi bi-pencil me-2"></i>Edit User</a></li>
                                        @if($user->id !== auth()->id())
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick='openDeleteModal(@json($user))'>
                                            <i class="bi bi-trash me-2"></i>Delete User</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- Add/Edit User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="userForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Add/Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="userName" class="form-label">Name</label>
                            <input type="text" id="userName" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" id="userEmail" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Password</label>
                            <input type="password" id="userPassword" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPasswordConfirmation" class="form-label">Confirm Password</label>
                            <input type="password" id="userPasswordConfirmation" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="userRole" class="form-label">Role</label>
                            <select id="userRole" name="role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="student">Student</option>
                                <option value="officer">Officer</option>
                                <option value="dean">Dean</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<!-- Delete User Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="deleteUserName"></strong>? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');
    const table = document.getElementById('usersTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    const selectAll = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll('.user-checkbox:not([disabled])');
    const selectedCount = document.getElementById('selectedCount');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleFilterValue = roleFilter.value;

        Array.from(rows).forEach(row => {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const role = row.getAttribute('data-role');
            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesRole = !roleFilterValue || role === roleFilterValue;
            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        });
    }

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        selectedCount.textContent = `${checkedBoxes.length} users selected`;
    }

    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);

    selectAll.addEventListener('change', function() {
        const visibleCheckboxes = Array.from(userCheckboxes).filter(cb => cb.closest('tr').style.display !== 'none');
        visibleCheckboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });

    userCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            updateSelectedCount();
            const visibleBoxes = Array.from(userCheckboxes).filter(cb => cb.closest('tr').style.display !== 'none');
            const checkedBoxes = Array.from(userCheckboxes).filter(cb => cb.closest('tr').style.display !== 'none' && cb.checked);
            selectAll.checked = checkedBoxes.length === visibleBoxes.length && visibleBoxes.length > 0;
            selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < visibleBoxes.length;
        });
    });
});

function toggleBulkActions() {
    const panel = document.getElementById('bulkActionsPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    if(panel.style.display === 'none') {
        document.getElementById('selectAll').checked = false;
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('selectedCount').textContent = '0 users selected';
    }
}

function toggleRoleSelect(action) {
    const roleSelect = document.getElementById('roleSelect');
    roleSelect.style.display = action === 'change_role' ? 'block' : 'none';
    if (action !== 'change_role') roleSelect.value = '';
}

    // Add/Edit Modal
    function openUserModal(user = null) {
        const modalLabel = document.getElementById('userModalLabel');
        const form = document.getElementById('userForm');
        const methodInput = document.getElementById('formMethod');
        const passwordInput = document.getElementById('userPassword');
        const passwordConfirmInput = document.getElementById('userPasswordConfirmation');

        if (user) {
            // Edit user
            modalLabel.textContent = 'Edit User';
            form.action = `/admin/users/${user.id}`;
            methodInput.value = 'PUT';
            document.getElementById('userName').value = user.name;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userRole').value = user.role;
            // Password optional for edit
            passwordInput.required = false;
            passwordInput.value = '';
            passwordConfirmInput.required = false;
            passwordConfirmInput.value = '';
        } else {
            // Add new user
            modalLabel.textContent = 'Add New User';
            form.action = '/admin/users';
            methodInput.value = 'POST';
            form.reset();
            passwordInput.required = true;
            passwordConfirmInput.required = true;
        }

        new bootstrap.Modal(document.getElementById('userModal')).show();
    }


// Delete Modal
function openDeleteModal(user) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/admin/users/${user.id}`;
    document.getElementById('deleteUserName').textContent = user.name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
