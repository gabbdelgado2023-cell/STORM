@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 text-dark">Create New User</h1>
        <p class="text-muted mb-0">Add a new user to the system with appropriate role and permissions</p>
    </div>
    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Users
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="action-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>User Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
                    @csrf

                    <div class="row g-4">
                        <!-- Personal Information Section -->
                        <div class="col-12">
                            <div class="border-bottom pb-3 mb-4">
                                <h6 class="text-primary mb-0">Personal Information</h6>
                                <small class="text-muted">Basic user details and contact information</small>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autocomplete="name" 
                                   autofocus
                                   placeholder="Enter full name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Enter the user's complete legal name
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email"
                                   placeholder="user@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Must be a valid email address for login and notifications
                            </div>
                        </div>

                        <!-- Account Configuration Section -->
                        <div class="col-12 mt-5">
                            <div class="border-bottom pb-3 mb-4">
                                <h6 class="text-primary mb-0">Account Configuration</h6>
                                <small class="text-muted">Set user role and access permissions</small>
                            </div>
                        </div>

                        <!-- User Role -->
                        <div class="col-12">
                            <label for="role" class="form-label">
                                User Role <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="radio" name="role" id="role_student" value="student" {{ old('role') === 'student' ? 'checked' : '' }}>
                                        <label class="form-check-label card h-100" for="role_student">
                                            <div class="card-body text-center p-3">
                                                <div class="recent-avatar text-white mb-3 mx-auto" style="background: #059669;">
                                                    <i class="bi bi-mortarboard"></i>
                                                </div>
                                                <h6 class="card-title">Student</h6>
                                                <small class="text-muted">Can join organizations and view events</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="radio" name="role" id="role_officer" value="officer" {{ old('role') === 'officer' ? 'checked' : '' }}>
                                        <label class="form-check-label card h-100" for="role_officer">
                                            <div class="card-body text-center p-3">
                                                <div class="recent-avatar text-white mb-3 mx-auto" style="background: #0891b2;">
                                                    <i class="bi bi-person-badge"></i>
                                                </div>
                                                <h6 class="card-title">Officer</h6>
                                                <small class="text-muted">Can manage organizations and create events</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="radio" name="role" id="role_dean" value="dean" {{ old('role') === 'dean' ? 'checked' : '' }}>
                                        <label class="form-check-label card h-100" for="role_dean">
                                            <div class="card-body text-center p-3">
                                                <div class="recent-avatar text-white mb-3 mx-auto" style="background: #d97706;">
                                                    <i class="bi bi-award"></i>
                                                </div>
                                                <h6 class="card-title">Dean/OSAD</h6>
                                                <small class="text-muted">Can approve organizations and events</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="radio" name="role" id="role_admin" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}>
                                        <label class="form-check-label card h-100" for="role_admin">
                                            <div class="card-body text-center p-3">
                                                <div class="recent-avatar text-white mb-3 mx-auto" style="background: #dc2626;">
                                                    <i class="bi bi-shield-check"></i>
                                                </div>
                                                <h6 class="card-title">Administrator</h6>
                                                <small class="text-muted">Full system access and management</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Security Section -->
                        <div class="col-12 mt-5">
                            <div class="border-bottom pb-3 mb-4">
                                <h6 class="text-primary mb-0">Security Settings</h6>
                                <small class="text-muted">Set login credentials for the user</small>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Enter secure password">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password')">
                                    <i class="bi bi-eye" id="password-icon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="bi bi-shield-check me-1"></i>
                                Password must be at least 8 characters long
                            </div>
                            <div class="mt-2">
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar" id="password-strength" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="password-strength-text" class="text-muted">Password strength</small>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">
                                Confirm Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Confirm password">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password_confirmation')">
                                    <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                            <div class="form-text" id="password-match-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Re-enter the password to confirm
                            </div>
                        </div>

                        <!-- Additional Settings -->
                        <div class="col-12 mt-4">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Account Setup:</strong> The user's email will be automatically verified upon creation. 
                                They will be able to log in immediately with the provided credentials. A welcome email will be sent to their address.
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Form
                                </button>
                                <div>
                                    <a href="{{ route('admin.users') }}" class="btn btn-secondary me-2">
                                        <i class="bi bi-x-lg me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bi bi-person-plus me-2"></i>Create User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
.form-check-card .form-check-input {
    display: none;
}

.form-check-card .card {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid #e5e7eb;
}

.form-check-card .card:hover {
    border-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.1);
}

.form-check-card .form-check-input:checked + .card {
    border-color: #2563eb;
    background: #eff6ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
}

.progress-bar {
    transition: width 0.3s ease, background-color 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createUserForm');
    const passwordInput = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');

    // Password strength checker
    passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        validatePasswordMatch();
    });

    passwordConfirmation.addEventListener('input', validatePasswordMatch);

    function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('password-strength');
        const strengthText = document.getElementById('password-strength-text');
        
        let score = 0;
        let feedback = '';
        
        if (password.length >= 8) score += 1;
        if (/[a-z]/.test(password)) score += 1;
        if (/[A-Z]/.test(password)) score += 1;
        if (/[0-9]/.test(password)) score += 1;
        if (/[^A-Za-z0-9]/.test(password)) score += 1;
        
        switch (score) {
            case 0:
            case 1:
                strengthBar.style.width = '20%';
                strengthBar.className = 'progress-bar bg-danger';
                feedback = 'Very Weak';
                break;
            case 2:
                strengthBar.style.width = '40%';
                strengthBar.className = 'progress-bar bg-warning';
                feedback = 'Weak';
                break;
            case 3:
                strengthBar.style.width = '60%';
                strengthBar.className = 'progress-bar bg-info';
                feedback = 'Fair';
                break;
            case 4:
                strengthBar.style.width = '80%';
                strengthBar.className = 'progress-bar bg-success';
                feedback = 'Good';
                break;
            case 5:
                strengthBar.style.width = '100%';
                strengthBar.className = 'progress-bar bg-success';
                feedback = 'Excellent';
                break;
        }
        
        strengthText.textContent = `Password strength: ${feedback}`;
    }

    function validatePasswordMatch() {
        const matchText = document.getElementById('password-match-text');
        
        if (passwordConfirmation.value && passwordInput.value !== passwordConfirmation.value) {
            passwordConfirmation.setCustomValidity('Passwords do not match');
            passwordConfirmation.classList.add('is-invalid');
            matchText.innerHTML = '<i class="bi bi-x-circle text-danger me-1"></i>Passwords do not match';
        } else if (passwordConfirmation.value && passwordInput.value === passwordConfirmation.value) {
            passwordConfirmation.setCustomValidity('');
            passwordConfirmation.classList.remove('is-invalid');
            passwordConfirmation.classList.add('is-valid');
            matchText.innerHTML = '<i class="bi bi-check-circle text-success me-1"></i>Passwords match';
        } else {
            passwordConfirmation.setCustomValidity('');
            passwordConfirmation.classList.remove('is-invalid', 'is-valid');
            matchText.innerHTML = '<i class="bi bi-info-circle me-1"></i>Re-enter the password to confirm';
        }
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creating User...';
        submitBtn.disabled = true;
    });

    window.resetForm = function() {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            form.reset();
            document.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });
            document.getElementById('password-strength').style.width = '0%';
            document.getElementById('password-strength-text').textContent = 'Password strength';
            document.getElementById('password-match-text').innerHTML = '<i class="bi bi-info-circle me-1"></i>Re-enter the password to confirm';
        }
    };
});

function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endpush