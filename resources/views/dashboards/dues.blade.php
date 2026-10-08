@extends('layouts.header')

@section('pageTitle', 'Dues Collector Dashboard')

@push('css')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

@endpush

@section('section')
    <div class="d-flex justify-content-end mb-3">
        @include('partials.daterange_picker')
    </div>


<!-- Hero Section -->
<div class="dues-hero">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="hero-date-chip">
                <span class="live-indicator"></span> 
                Live Dashboard &bull; {{ now()->format('l, jS F Y') }}
            </div>
            <h1 class="dues-hero-title">Dues Collection Overview</h1>
            <p class="dues-hero-subtitle">Monitor pending payments, track overdue accounts, and analyze collection performance.</p>
        </div>
        <div class="col-md-4 text-end d-none d-md-block">
            <img src="{{ asset('assets/images/payment-illustration.svg') }}" alt="" style="max-height: 120px; opacity: 0.9;" onerror="this.style.display='none'">
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <!-- Total Due Students -->
    <div class="col-xl-3 col-md-6">
        <div class="dues-stat-card blue">
            <div class="dues-stat-header">
                <div class="dues-stat-title">Total Due Students

</div>
                <div class="dues-stat-icon blue"><i class="fas fa-users"></i></div>
            </div>
            <div class="dues-stat-value">{{ $stats['total_due_students']['count'] }}</div>
            <div class="dues-stat-footer text-muted">
                <span class="trend-up"><i class="fas fa-arrow-up me-1"></i>{{ $stats['total_due_students']['new'] }} new</span> this month
            </div>
        </div>
    </div>

    <!-- Overdue Students -->
    <div class="col-xl-3 col-md-6">
        <div class="dues-stat-card red">
            <div class="dues-stat-header">
                <div class="dues-stat-title">Overdue Students</div>
                <div class="dues-stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
            <div class="dues-stat-value">{{ $stats['overdue_students']['count'] }}</div>
            <div class="dues-stat-footer text-muted">
                <span class="trend-alert"><i class="fas fa-bell me-1"></i>Urgent</span> action required
            </div>
        </div>
    </div>

    <!-- Total Due Amount -->
    <div class="col-xl-3 col-md-6">
        <div class="dues-stat-card amber">
            <div class="dues-stat-header">
                <div class="dues-stat-title">Total Pending Dues</div>
                <div class="dues-stat-icon amber"><i class="fas fa-rupee-sign"></i></div>
            </div>
            <div class="dues-stat-value">₹{{ $stats['total_due_amount']['amount'] }}</div>
            <div class="dues-stat-footer text-muted">
                <span class="trend-neutral"><i class="fas fa-clock me-1"></i>Pending Collection</span>
            </div>
        </div>
    </div>

    <!-- Total Collected -->
    <div class="col-xl-3 col-md-6">
        <div class="dues-stat-card green">
            <div class="dues-stat-header">
                <div class="dues-stat-title">Total Collected</div>
                <div class="dues-stat-icon green"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="dues-stat-value">₹{{ $stats['total_collected']['amount'] }}</div>
            <div class="dues-stat-footer text-muted">
                <span class="trend-up"><i class="fas fa-chart-line me-1"></i>All time</span> success
            </div>
        </div>
    </div>
</div>

<!-- Lists Row -->
<div class="row g-4 mb-4">
    <!-- Today's Dues -->
    <div class="col-xl-6">
        <div class="dashboard-panel">
            <div class="panel-header">
                <h5 class="panel-title">
                    <div class="stat-icon blue" style="width:36px;height:36px;font-size:1rem;"><i class="fas fa-calendar-day"></i></div>
                    Today's Expected Dues
                </h5>
                <span class="panel-badge badge-primary">{{ count($todayDues) }} Scheduled</span>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayDues as $due)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-initials" style="background: {{ $due['color'] }}20; color: {{ $due['color'] }}; border: 1px solid {{ $due['color'] }}40;">
                                        {{ $due['initials'] }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $due['name'] }}</div>
                                        <div class="text-muted small"><i class="fas fa-phone-alt me-1" style="font-size:10px;"></i>{{ $due['phone'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold" style="color:var(--dues-dark)">₹{{ $due['amount'] }}</td>
                            <td><span class="status-pill status-pending">{{ $due['status'] }}</span></td>
                            <td>
                                <a href="{{ route('leads.show', encrypt($due['lead_id'] ?? 0)) }}" class="btn btn-sm btn-light border shadow-sm rounded-pill px-3 fw-semibold text-primary">
                                    View <i class="fas fa-chevron-right ms-1" style="font-size:10px;"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted mb-2"><i class="fas fa-check-circle fa-2x" style="color:#10b981;opacity:0.5;"></i></div>
                                <div class="fw-medium">No dues scheduled for today.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Overdue / Reminders -->
    <div class="col-xl-6">
        <div class="dashboard-panel">
            <div class="panel-header">
                <h5 class="panel-title">
                    <div class="stat-icon red" style="width:36px;height:36px;font-size:1rem;"><i class="fas fa-exclamation-circle"></i></div>
                    Overdue Reminders
                </h5>
                <span class="panel-badge badge-danger">{{ count($todayReminders) }} Action Needed</span>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayReminders as $reminder)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-initials" style="background: {{ $reminder['color'] }}20; color: {{ $reminder['color'] }}; border: 1px solid {{ $reminder['color'] }}40;">
                                        {{ $reminder['initials'] }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $reminder['name'] }}</div>
                                        <div class="text-muted small"><i class="fas fa-phone-alt me-1" style="font-size:10px;"></i>{{ $reminder['phone'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold text-danger">₹{{ $reminder['amount'] }}</td>
                            <td>
                                <div class="fw-semibold {{ $reminder['status'] == 'Overdue' ? 'text-danger' : 'text-dark' }}">{{ $reminder['date'] }}</div>
                                <div class="status-pill {{ $reminder['status'] == 'Overdue' ? 'status-overdue' : 'status-pending' }} mt-1" style="padding:0.2rem 0.5rem;font-size:0.65rem;">{{ $reminder['status'] }}</div>
                            <td>
                                <a href="{{ route('leads.show', encrypt($reminder['lead_id'] ?? 0)) }}" class="btn btn-sm btn-light border shadow-sm rounded-pill px-3 fw-semibold text-danger">
                                    Chase <i class="fas fa-chevron-right ms-1" style="font-size:10px;"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted mb-2"><i class="fas fa-glass-cheers fa-2x" style="color:#f59e0b;opacity:0.5;"></i></div>
                                <div class="fw-medium">No overdue reminders! Great job.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart Row -->
<div class="row mb-4">
    <div class="col-12">
        <div class="dashboard-panel">
            <div class="panel-header">
                <h5 class="panel-title">
                    <div class="stat-icon indigo" style="width:36px;height:36px;font-size:1rem;background:#e0e7ff;color:#4f46e5;"><i class="fas fa-chart-bar"></i></div>
                    Collection Performance (Last 7 Days)
                </h5>
            </div>
            <div class="chart-wrapper">
                <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                    <canvas id="collectionChart"></canvas>
                </div>
                <div class="chart-stats">
                    <div class="chart-stat-box">
                        <div class="chart-stat-label">Total 7-Day Collection</div>
                        <div class="chart-stat-value highlight">₹{{ $chartData['total_week'] }}</div>
                    </div>
                    <div class="chart-stat-box text-end">
                        <div class="chart-stat-label">Daily Average</div>
                        <div class="chart-stat-value">₹{{ $chartData['avg_day'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set global font family for Chart.js
        Chart.defaults.font.family = "'Inter', sans-serif";
        
        const ctx = document.getElementById('collectionChart').getContext('2d');
        
        // Gradient for bars
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, '#4f46e5'); // Indigo top
        gradient.addColorStop(1, '#3b82f6'); // Blue bottom

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Amount Collected (₹)',
                    data: @json($chartData['values']),
                    backgroundColor: gradient,
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 40,
                    hoverBackgroundColor: '#312e81'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        titleFont: { weight: 'bold', size: 14 },
                        bodyFont: { size: 14 },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-IN').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 10000,
                        grid: { color: '#e2e8f0', drawBorder: false, borderDash: [5, 5] },
                        ticks: { 
                            color: '#64748b', 
                            font: { size: 12, weight: '500' },
                            callback: function(value) { 
                                if (value === 0) return '₹0';
                                if (value >= 1000) return '₹' + (value / 1000) + 'k';
                                return '₹' + value;
                            }
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#64748b', font: { size: 13, weight: '600' } },
                        border: { display: false }
                    }
                },
                animation: { duration: 1200, easing: 'easeOutQuart' }
            }
        });
    });
</script>
@endpush

