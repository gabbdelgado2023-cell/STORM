@extends('layouts.admin')

@section('page-title', 'Reports & Analytics')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Report Generation Cards -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>User Reports
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Generate comprehensive reports on system users, roles, and account statistics.</p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check text-success me-2"></i>User registration trends</li>
                        <li><i class="bi bi-check text-success me-2"></i>Role distribution analysis</li>
                        <li><i class="bi bi-check text-success me-2"></i>Account verification status</li>
                        <li><i class="bi bi-check text-success me-2"></i>User activity metrics</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <form method="GET" action="{{ route('admin.reports.generate') }}">
                        <input type="hidden" name="type" value="users">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-file-earmark-text me-2"></i>Generate Report
                            </button>
                            <button type="submit" name="format" value="pdf" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>Organization Reports
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Detailed analysis of organizations, their status, and membership data.</p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check text-success me-2"></i>Organization approval rates</li>
                        <li><i class="bi bi-check text-success me-2"></i>Category distribution</li>
                        <li><i class="bi bi-check text-success me-2"></i>Membership statistics</li>
                        <li><i class="bi bi-check text-success me-2"></i>Activity status analysis</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <form method="GET" action="{{ route('admin.reports.generate') }}">
                        <input type="hidden" name="type" value="organizations">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-file-earmark-text me-2"></i>Generate Report
                            </button>
                            <button type="submit" name="format" value="pdf" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i>Activity Reports
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Comprehensive overview of events, memberships, and system activities.</p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check text-success me-2"></i>Event creation trends</li>
                        <li><i class="bi bi-check text-success me-2"></i>Event approval statistics</li>
                        <li><i class="bi bi-check text-success me-2"></i>Membership applications</li>
                        <li><i class="bi bi-check text-success me-2"></i>Activity timeline</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <form method="GET" action="{{ route('admin.reports.generate') }}">
                        <input type="hidden" name="type" value="activities">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-file-earmark-text me-2"></i>Generate Report
                            </button>
                            <button type="submit" name="format" value="pdf" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Report Builder -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-tools me-2"></i>Custom Report Builder
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.generate') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="report_type" class="form-label">Report Type</label>
                                <select class="form-select" id="report_type" name="type">
                                    <option value="overview">System Overview</option>
                                    <option value="users">User Analysis</option>
                                    <option value="organizations">Organization Analysis</option>
                                    <option value="activities">Activity Analysis</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_range" class="form-label">Date Range</label>
                                <select class="form-select" id="date_range" name="date_range">
                                    <option value="all">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="quarter">This Quarter</option>
                                    <option value="year">This Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="custom_date_range" style="display: none;">
                                <label for="start_date" class="form-label">Custom Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div class="col-md-3" id="custom_end_date" style="display: none;">
                                <label for="end_date" class="form-label">Custom End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="format" class="form-label">Output Format</label>
                                <select class="form-select" id="format" name="format">
                                    <option value="web">View in Browser</option>
                                    <option value="pdf">Download PDF</option>
                                    <option value="excel">Download Excel</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Include Sections</label>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_summary" name="sections[]" value="summary" checked>
                                            <label class="form-check-label" for="include_summary">Summary Statistics</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_charts" name="sections[]" value="charts" checked>
                                            <label class="form-check-label" for="include_charts">Charts & Graphs</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_details" name="sections[]" value="details" checked>
                                            <label class="form-check-label" for="include_details">Detailed Data</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_trends" name="sections[]" value="trends">
                                            <label class="form-check-label" for="include_trends">Trend Analysis</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-play-fill me-2"></i>Generate Custom Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Dashboard -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Quick Analytics Dashboard
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <canvas id="userRoleChart" width="100" height="100"></canvas>
                                <h6 class="mt-2">User Distribution</h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <canvas id="orgStatusChart" width="100" height="100"></canvas>
                                <h6 class="mt-2">Organization Status</h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <canvas id="eventStatusChart" width="100" height="100"></canvas>
                                <h6 class="mt-2">Event Status</h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <canvas id="membershipTrendChart" width="100" height="100"></canvas>
                                <h6 class="mt-2">Membership Trend</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report History -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Report History
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Report Type</th>
                                    <th>Generated By</th>
                                    <th>Date Generated</th>
                                    <th>Format</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>System Overview</td>
                                    <td>{{ auth()->user()->name }}</td>
                                    <td>{{ now()->subHours(2)->format('M d, Y - h:i A') }}</td>
                                    <td><span class="badge bg-info">PDF</span></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i> Download
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>User Analysis</td>
                                    <td>{{ auth()->user()->name }}</td>
                                    <td>{{ now()->subDays(1)->format('M d, Y - h:i A') }}</td>
                                    <td><span class="badge bg-secondary">Web</span></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Organization Analysis</td>
                                    <td>{{ auth()->user()->name }}</td>
                                    <td>{{ now()->subDays(3)->format('M d, Y - h:i A') }}</td>
                                    <td><span class="badge bg-success">Excel</span></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i> Download
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Custom date range toggle
    const dateRangeSelect = document.getElementById('date_range');
    const customDateRange = document.getElementById('custom_date_range');
    const customEndDate = document.getElementById('custom_end_date');

    dateRangeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDateRange.style.display = 'block';
            customEndDate.style.display = 'block';
        } else {
            customDateRange.style.display = 'none';
            customEndDate.style.display = 'none';
        }
    });

    // Load analytics data and create charts
    loadAnalyticsCharts();
});

function loadAnalyticsCharts() {
    // Fetch analytics data
    fetch('/admin/analytics')
        .then(response => response.json())
        .then(data => {
            createUserRoleChart(data.role_distribution);
            createOrgStatusChart();
            createEventStatusChart();
            createMembershipTrendChart(data.user_growth);
        })
        .catch(error => {
            console.error('Error loading analytics:', error);
        });
}

function createUserRoleChart(roleData) {
    const ctx = document.getElementById('userRoleChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: roleData.map(item => item.role.charAt(0).toUpperCase() + item.role.slice(1)),
            datasets: [{
                data: roleData.map(item => item.count),
                backgroundColor: ['#28a745', '#007bff', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function createOrgStatusChart() {
    const ctx = document.getElementById('orgStatusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{
                data: [65, 25, 10], // Sample data
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function createEventStatusChart() {
    const ctx = document.getElementById('eventStatusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{
                data: [70, 20, 10], // Sample data
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function createMembershipTrendChart(userData) {
    const ctx = document.getElementById('membershipTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: userData.map(item => new Date(item.date).toLocaleDateString()),
            datasets: [{
                label: 'New Users',
                data: userData.map(item => item.count),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false
                }
            }
        }
    });
}
</script>
@endpush