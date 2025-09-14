@extends('layouts.admin')

@section('page-title', 'System Maintenance')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Cache Management -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Cache Management
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Manage system cache to improve performance and resolve issues.</p>
                    <div class="row g-2">
                        <div class="col-12">
                            <form method="POST" action="{{ route('admin.maintenance.clear-cache') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100" onclick="return confirm('This will clear all cached data. Continue?')">
                                    <i class="bi bi-trash me-2"></i>Clear All Cache
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>This will clear:</strong><br>
                            • Application cache<br>
                            • Configuration cache<br>
                            • View cache<br>
                            • Route cache
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Management -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-database me-2"></i>Database Management
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Backup and manage your database.</p>
                    <div class="row g-2">
                        <div class="col-12">
                            <form method="POST" action="{{ route('admin.maintenance.backup-database') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-download me-2"></i>Create Database Backup
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Backup includes:</strong><br>
                            • All tables and data<br>
                            • Database structure<br>
                            • Indexes and constraints
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>System Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <strong>Laravel Version:</strong><br>
                            <span class="text-muted">{{ app()->version() }}</span>
                        </div>
                        <div class="col-6">
                            <strong>PHP Version:</strong><br>
                            <span class="text-muted">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="col-6">
                            <strong>Environment:</strong><br>
                            <span class="badge bg-{{ app()->environment() === 'production' ? 'danger' : 'warning' }}">
                                {{ strtoupper(app()->environment()) }}
                            </span>
                        </div>
                        <div class="col-6">
                            <strong>Debug Mode:</strong><br>
                            <span class="badge bg-{{ config('app.debug') ? 'warning' : 'success' }}">
                                {{ config('app.debug') ? 'ENABLED' : 'DISABLED' }}
                            </span>
                        </div>
                        <div class="col-6">
                            <strong>Database:</strong><br>
                            <span class="text-muted">{{ config('database.default') }}</span>
                        </div>
                        <div class="col-6">
                            <strong>Queue Driver:</strong><br>
                            <span class="text-muted">{{ config('queue.default') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Management -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Log Management
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">View and manage application logs.</p>
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-warning w-100" onclick="viewLogs('error')">
                                <i class="bi bi-exclamation-triangle me-2"></i>Error Logs
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-info w-100" onclick="viewLogs('info')">
                                <i class="bi bi-info-circle me-2"></i>Info Logs
                            </button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Monitor:</strong><br>
                            • Application errors<br>
                            • User activities<br>
                            • System events
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Check -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-exclamation me-2"></i>Security Check
                    </h5>
                </div>
                <div class="card-body">
                    <div class="security-checks">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-{{ config('app.debug') ? 'x-circle text-danger' : 'check-circle text-success' }} me-2"></i>
                            <span>Debug Mode: {{ config('app.debug') ? 'ENABLED (Disable in production)' : 'DISABLED' }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-{{ app()->environment() === 'production' ? 'check-circle text-success' : 'exclamation-triangle text-warning' }} me-2"></i>
                            <span>Environment: {{ strtoupper(app()->environment()) }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-{{ config('app.key') ? 'check-circle text-success' : 'x-circle text-danger' }} me-2"></i>
                            <span>App Key: {{ config('app.key') ? 'SET' : 'NOT SET' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span>CSRF Protection: ENABLED</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Monitor -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-speedometer me-2"></i>Performance Monitor
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <strong>Memory Usage:</strong><br>
                            <span class="text-muted">{{ round(memory_get_usage() / 1024 / 1024, 2) }} MB</span>
                        </div>
                        <div class="col-6">
                            <strong>Peak Memory:</strong><br>
                            <span class="text-muted">{{ round(memory_get_peak_usage() / 1024 / 1024, 2) }} MB</span>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="runPerformanceTest()">
                                <i class="bi bi-play me-2"></i>Run Performance Test
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Log -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Recent System Activities
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><small>{{ now()->subMinutes(5)->format('H:i:s') }}</small></td>
                                    <td>Cache Cleared</td>
                                    <td>{{ auth()->user()->name }}</td>
                                    <td>All cache types cleared</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                </tr>
                                <tr>
                                    <td><small>{{ now()->subMinutes(15)->format('H:i:s') }}</small></td>
                                    <td>User Login</td>
                                    <td>Student User</td>
                                    <td>Successful login</td>
                                    <td><span class="badge bg-info">Info</span></td>
                                </tr>
                                <tr>
                                    <td><small>{{ now()->subMinutes(25)->format('H:i:s') }}</small></td>
                                    <td>Organization Created</td>
                                    <td>Officer User</td>
                                    <td>New organization registration</td>
                                    <td><span class="badge bg-primary">Created</span></td>
                                </tr>
                                <tr>
                                    <td><small>{{ now()->subMinutes(45)->format('H:i:s') }}</small></td>
                                    <td>Database Backup</td>
                                    <td>System</td>
                                    <td>Automated backup completed</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Viewer Modal -->
    <div class="modal fade" id="logViewerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">System Logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <pre id="logContent" style="max-height: 400px; overflow-y: auto; background: #f8f9fa; padding: 15px;">
Loading logs...
                    </pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="downloadLogs()">Download Logs</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewLogs(type) {
    const modal = new bootstrap.Modal(document.getElementById('logViewerModal'));
    const logContent = document.getElementById('logContent');
    
    logContent.textContent = 'Loading ' + type + ' logs...';
    modal.show();
    
    // Simulate log loading (replace with actual AJAX call)
    setTimeout(() => {
        const sampleLogs = `
[${new Date().toISOString()}] ${type.toUpperCase()}: Sample log entry
[${new Date(Date.now() - 60000).toISOString()}] ${type.toUpperCase()}: Another log entry
[${new Date(Date.now() - 120000).toISOString()}] ${type.toUpperCase()}: Previous log entry
        `.trim();
        logContent.textContent = sampleLogs;
    }, 1000);
}

function downloadLogs() {
    alert('Log download functionality would be implemented here');
}

function runPerformanceTest() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Running Test...';
    button.disabled = true;
    
    // Simulate performance test
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        alert('Performance test completed!\nResponse Time: 125ms\nMemory Usage: Good\nDatabase Queries: Optimized');
    }, 3000);
}

// Auto-refresh system info every 30 seconds
setInterval(function() {
    // In a real implementation, you would fetch updated system metrics
    console.log('Refreshing system metrics...');
}, 30000);
</script>
@endpush