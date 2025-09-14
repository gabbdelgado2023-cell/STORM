@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 text-dark">System Settings</h1>
        <p class="text-muted mb-0">Configure system-wide settings and preferences</p>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-secondary" onclick="resetAllSettings()">
            <i class="bi bi-arrow-clockwise me-2"></i>Reset to Defaults
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="exportSettings()">
            <i class="bi bi-download me-2"></i>Export Config
        </button>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" id="settingsForm">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- System Information -->
        <div class="col-lg-6">
            <div class="action-card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-gear text-primary me-2"></i>System Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="system_name" class="form-label">System Name</label>
                        <input type="text" 
                               class="form-control @error('system_name') is-invalid @enderror" 
                               id="system_name" 
                               name="system_name" 
                               value="{{ old('system_name', $settings['system_name']) }}" 
                               required>
                        @error('system_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            This name appears in the application header and emails
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="academic_year" class="form-label">Academic Year</label>
                        <input type="number" 
                               class="form-control @error('academic_year') is-invalid @enderror" 
                               id="academic_year" 
                               name="academic_year" 
                               value="{{ old('academic_year', $settings['academic_year']) }}" 
                               min="2020" 
                               max="2035"
                               required>
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-calendar me-1"></i>
                            Current academic year for organization registration
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Organization Settings -->
        <div class="col-lg-6">
            <div class="action-card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-building text-success me-2"></i>Organization Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="min_members_required" class="form-label">Minimum Members Required</label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('min_members_required') is-invalid @enderror" 
                                   id="min_members_required" 
                                   name="min_members_required" 
                                   value="{{ old('min_members_required', $settings['min_members_required']) }}" 
                                   min="1" 
                                   max="50"
                                   required>
                            <span class="input-group-text">members</span>
                            @error('min_members_required')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">
                            <i class="bi bi-people me-1"></i>
                            Minimum approved members for active organization status
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="registration_open" 
                                   name="registration_open" 
                                   value="1"
                                   {{ old('registration_open', $settings['registration_open']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="registration_open">
                                <strong>Allow New Organization Registration</strong>
                            </label>
                        </div>
                        <small class="text-muted">
                            When disabled, new organizations cannot be created
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Settings -->
        <div class="col-lg-6">
            <div class="action-card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event text-info me-2"></i>Event Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="event_approval_required" 
                                   name="event_approval_required" 
                                   value="1"
                                   {{ old('event_approval_required', $settings['event_approval_required']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="event_approval_required">
                                <strong>Require Dean Approval for Events</strong>
                            </label>
                        </div>
                        <small class="text-muted">
                            When disabled, events are automatically approved
                        </small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="public_event_calendar" 
                                   name="public_event_calendar" 
                                   value="1"
                                   {{ old('public_event_calendar', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="public_event_calendar">
                                <strong>Public Event Calendar</strong>
                            </label>
                        </div>
                        <small class="text-muted">
                            Allow public viewing of approved events
                        </small>
                    </div>

                    <div class="mb-0">
                        <label for="event_lead_time" class="form-label">Minimum Event Lead Time</label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control" 
                                   id="event_lead_time" 
                                   name="event_lead_time" 
                                   value="{{ old('event_lead_time', 7) }}" 
                                   min="1" 
                                   max="30">
                            <span class="input-group-text">days</span>
                        </div>
                        <div class="form-text">
                            <i class="bi bi-clock me-1"></i>
                            Minimum days before event date for submission
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="col-lg-6">
            <div class="action-card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check text-warning me-2"></i>System Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="recent-item" style="border-left-color: #2563eb;">
                                <div class="d-flex align-items-center">
                                    <div class="recent-avatar text-white me-3" style="background: #2563eb;">
                                        <i class="bi bi-code-square"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ app()->version() }}</h6>
                                        <small class="text-muted">Laravel Version</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="recent-item" style="border-left-color: #059669;">
                                <div class="d-flex align-items-center">
                                    <div class="recent-avatar text-white me-3" style="background: #059669;">
                                        <i class="bi bi-server"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ PHP_VERSION }}</h6>
                                        <small class="text-muted">PHP Version</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="recent-item" style="border-left-color: #d97706;">
                                <div class="d-flex align-items-center">
                                    <div class="recent-avatar text-white me-3" style="background: #d97706;">
                                        <i class="bi bi-database"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ config('database.default') }}</h6>
                                        <small class="text-muted">Database</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="recent-item" style="border-left-color: {{ app()->environment() === 'production' ? '#dc2626' : '#0891b2' }};">
                                <div class="d-flex align-items-center">
                                    <div class="recent-avatar text-white me-3" style="background: {{ app()->environment() === 'production' ? '#dc2626' : '#0891b2' }};">
                                        <i class="bi bi-gear"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ strtoupper(app()->environment()) }}</h6>
                                        <small class="text-muted">Environment</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-{{ config('app.debug') ? 'warning' : 'success' }} mb-0">
                        <i class="bi bi-{{ config('app.debug') ? 'exclamation-triangle' : 'shield-check' }} me-2"></i>
                        <strong>Debug Mode:</strong> {{ config('app.debug') ? 'ENABLED' : 'DISABLED' }}
                        @if(config('app.debug') && app()->environment() === 'production')
                            <br><small>Consider disabling debug mode in production for security.</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Email & Notifications -->
        <div class="col-lg-6">
            <div class="action-card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope text-primary me-2"></i>Email & Notifications
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="email_notifications" 
                                   name="email_notifications" 
                                   value="1"
                                   {{ old('email_notifications', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_notifications">
                                <strong>Enable Email Notifications</strong>
                            </label>
                        </div>
                        <small class="text-muted">
                            Send system notifications via email
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Administrator Email</label>
                        <input type="email" 
                               class="form-control" 
                               id="admin_email" 
                               name="admin_email" 
                               value="{{ old('admin_email', 'admin@system.com') }}"
                               placeholder="admin@example.com">
                        <div class="form-text">
                            <i class="bi bi-envelope me-1"></i>
                            Email for system notifications and alerts
                        </div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> Email configuration is managed through environment variables. 
                        Check your .env file for MAIL_* settings.
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="col-lg-6">
            <div class="action-card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-lock text-danger me-2"></i>Security Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="force_password_reset" 
                                   name="force_password_reset" 
                                   value="1"
                                   {{ old('force_password_reset', false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="force_password_reset">
                                <strong>Force Password Reset on First Login</strong>
                            </label>
                        </div>
                        <small class="text-muted">
                            Require new users to change password on first login
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="session_timeout" class="form-label">Session Timeout</label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control" 
                                   id="session_timeout" 
                                   name="session_timeout" 
                                   value="{{ old('session_timeout', 120) }}" 
                                   min="30" 
                                   max="720">
                            <span class="input-group-text">minutes</span>
                        </div>
                        <div class="form-text">
                            <i class="bi bi-clock me-1"></i>
                            Auto-logout inactive users after specified time
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="audit_logging" 
                                   name="audit_logging" 
                                   value="1"
                                   {{ old('audit_logging', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="audit_logging">
                                <strong>Enable Audit Logging</strong>
                            </label>
                        </div>
                        <small class="text-muted">
                            Log important system activities for security
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="action-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset Changes
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-primary me-2" onclick="previewChanges()">
                                <i class="bi bi-eye me-2"></i>Preview Changes
                            </button>
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="bi bi-check-circle me-2"></i>Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('settingsForm');
    const saveBtn = document.getElementById('saveBtn');
    let formChanged = false;

    // Store original form values
    const originalValues = new FormData(form);

    // Track changes
    form.addEventListener('change', function() {
        formChanged = true;
    });

    form.addEventListener('input', function() {
        formChanged = true;
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        saveBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Saving...';
        saveBtn.disabled = true;
    });

    // Warn before leaving if form has changes
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // Reset form function
    window.resetForm = function() {
        if (confirm('Are you sure you want to reset all changes?')) {
            form.reset();
            formChanged = false;
        }
    };

    window.resetAllSettings = function() {
        if (confirm('Are you sure you want to reset ALL settings to default values? This action cannot be undone.')) {
            // Reset to default values
            document.getElementById('system_name').value = 'Student Organization Management System';
            document.getElementById('academic_year').value = new Date().getFullYear();
            document.getElementById('min_members_required').value = 5;
            document.getElementById('event_lead_time').value = 7;
            document.getElementById('session_timeout').value = 120;
            document.getElementById('admin_email').value = 'admin@system.com';
            
            // Reset checkboxes
            document.getElementById('registration_open').checked = true;
            document.getElementById('event_approval_required').checked = true;
            document.getElementById('public_event_calendar').checked = true;
            document.getElementById('email_notifications').checked = true;
            document.getElementById('force_password_reset').checked = false;
            document.getElementById('audit_logging').checked = true;
            
            formChanged = true;
        }
    };

    window.exportSettings = function() {
        const settings = {};
        const formData = new FormData(form);
        
        for (let [key, value] of formData.entries()) {
            settings[key] = value;
        }
        
        const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(settings, null, 2));
        const downloadAnchorNode = document.createElement('a');
        downloadAnchorNode.setAttribute("href", dataStr);
        downloadAnchorNode.setAttribute("download", "system_settings_" + new Date().toISOString().split('T')[0] + ".json");
        document.body.appendChild(downloadAnchorNode);
        downloadAnchorNode.click();
        downloadAnchorNode.remove();
    };

    window.previewChanges = function() {
        const changes = [];
        const currentData = new FormData(form);
        
        // Compare with original values
        for (let [key, value] of currentData.entries()) {
            if (originalValues.get(key) !== value) {
                changes.push(`${key}: ${originalValues.get(key)} → ${value}`);
            }
        }
        
        if (changes.length === 0) {
            alert('No changes detected.');
        } else {
            alert('Changes to be saved:\n\n' + changes.join('\n'));
        }
    };
});
</script>
@endpush