@extends('layouts.admin')

@section('page-title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">📊 System Overview</h2>
            <p class="text-muted mb-0">Overview of users, organizations, events, and system activity</p>
        </div>
        <div>
            <input type="date" class="form-control form-control-sm" style="min-width: 180px;">
        </div>
    </div>

    {{-- Statistics Section --}}
    <div class="row g-4">
        @php
            $cards = [
                ['label' => 'Total Users', 'value' => $stats['total_users'], 'icon' => 'bi-people', 'color' => 'primary'],
                ['label' => 'Students', 'value' => $stats['total_students'], 'icon' => 'bi-mortarboard', 'color' => 'success'],
                ['label' => 'Officers', 'value' => $stats['total_officers'], 'icon' => 'bi-person-badge', 'color' => 'warning'],
                ['label' => 'Deans', 'value' => $stats['total_deans'], 'icon' => 'bi-person-workspace', 'color' => 'danger'],
                ['label' => 'Admins', 'value' => $stats['total_admins'], 'icon' => 'bi-shield-lock', 'color' => 'secondary'],
                ['label' => 'Organizations', 'value' => $stats['total_organizations'], 'icon' => 'bi-building', 'color' => 'info'],
                ['label' => 'Active Orgs', 'value' => $stats['active_organizations'], 'icon' => 'bi-check-circle', 'color' => 'success'],
                ['label' => 'Pending Orgs', 'value' => $stats['pending_organizations'], 'icon' => 'bi-hourglass-split', 'color' => 'warning'],
                ['label' => 'Events', 'value' => $stats['total_events'], 'icon' => 'bi-calendar-event', 'color' => 'primary'],
                ['label' => 'Approved Events', 'value' => $stats['approved_events'], 'icon' => 'bi-check2-square', 'color' => 'success'],
                ['label' => 'Memberships', 'value' => $stats['total_memberships'], 'icon' => 'bi-people-fill', 'color' => 'secondary'],
                ['label' => 'Active Memberships', 'value' => $stats['active_memberships'], 'icon' => 'bi-person-check', 'color' => 'info'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-light rounded-circle p-3 me-3">
                            <i class="bi {{ $card['icon'] }} text-{{ $card['color'] }} fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">{{ $card['label'] }}</h6>
                            <h4 class="fw-bold text-dark mb-0">{{ $card['value'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Charts & Insights --}}
    <div class="row g-4 mt-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0 fw-bold">📈 Events per Month</div>
                <div class="card-body">
                    <canvas id="eventsChart" height="140"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0 fw-bold">🏆 Most Active Organizations</div>
                <ul class="list-group list-group-flush">
                    @foreach($mostActiveOrgs as $org)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $org->name }}</span>
                            <span class="badge bg-primary rounded-pill">{{ $org->memberships_count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row g-4 mt-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0 fw-bold">🧑 New Users</div>
                <ul class="list-group list-group-flush">
                    @foreach($recentUsers as $user)
                        <li class="list-group-item small d-flex justify-content-between">
                            <span>{{ $user->name }}</span>
                            <span class="text-muted">{{ $user->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0 fw-bold">🏛️ New Organizations</div>
                <ul class="list-group list-group-flush">
                    @foreach($recentOrganizations as $org)
                        <li class="list-group-item small d-flex justify-content-between">
                            <span>{{ $org->name }}</span>
                            <span class="text-muted">{{ $org->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0 fw-bold">📅 New Events</div>
                <ul class="list-group list-group-flush">
                    @foreach($recentEvents as $event)
                        <li class="list-group-item small d-flex justify-content-between">
                            <span>{{ $event->name }}</span>
                            <span class="text-muted">{{ $event->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('eventsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($monthsLabels),
            datasets: [{
                label: 'Approved Events',
                data: @json($eventChartData),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
