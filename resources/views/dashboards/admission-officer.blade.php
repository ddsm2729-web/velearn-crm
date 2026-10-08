@extends('layouts.master')

@section('pageTitle', 'Admission Officer Dashboard')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        @include('partials.daterange_picker')
    </div>

    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dashboards-admission_officer.css') }}">
    @endpush

    <div class="dashboard-container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <h3 class="fw-bold mb-0">Admission Officer Dashboard</h3>
                <p class="text-muted small">Welcome back! Here's what's happening today.</p>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div>
                        <div class="icon-box bg-blue-soft">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h2>{{ $totalLeads }}</h2>
                        <div class="label">Total Leads</div>
                    </div>
                    <div class="badge-row">
                        <span class="badge-soft bg-blue-soft"><i class="fas fa-arrow-up me-1"></i> {{ $leadsThisWeek }} this
                            week</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div>
                        <div class="icon-box bg-orange-soft">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h2>{{ $followupsToday }}</h2>
                        <div class="label">Follow-ups Today</div>
                    </div>
                    <div class="badge-row">
                        <span class="badge-soft bg-orange-soft">{{ $overdueFollowups }} overdue</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div>
                        <div class="icon-box bg-green-soft">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h2>₹{{ number_format($revenueCollected) }}</h2>
                        <div class="label">Revenue Collected</div>
                    </div>
                    <div class="badge-row">
                        <span class="badge-soft bg-green-soft">₹{{ number_format($pendingRevenue) }} pending</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div>
                        <div class="icon-box bg-purple-soft">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h2>{{ $registeredCount }}</h2>
                        <div class="label">Registered Students</div>
                    </div>
                    <div class="badge-row">
                        <span class="badge-soft bg-purple-soft"><i class="fas fa-arrow-up me-1"></i>
                            {{ $registeredThisWeek }} this week</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Recent Leads -->
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Recent Leads</h5>
                        <a href="{{ route('leads.index') }}" class="text-primary small text-decoration-none fw-bold">View
                            All &rarr;</a>


                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLeads as $lead)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-sm" style="background: #e0f2fe; color: #0369a1;">
                                                    {{ strtoupper(substr($lead->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark small">{{ $lead->name }}</div>
                                                    <div class="text-muted smaller">{{ $lead->courses->pluck('title')->join(', ') ?: 'No Course' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted small">{{ $lead->phone }}
                                        </td>
                                        <td class="text-muted small">{{ $lead->created_at->format('d M') }}</td>
                                        <td>
                                            @php
                                                $status = $lead->status->name ?? 'New';
                                                $statusClass = 'bg-light';
                                                switch (strtolower($status)) {
                                                    case 'converted':
                                                        $statusClass = 'bg-orange-soft';
                                                        break;
                                                    case 'confirmed':
                                                        $statusClass = 'bg-green-soft';
                                                        break;
                                                    case 'registered':
                                                        $statusClass = 'bg-blue-soft';
                                                        break;
                                                }
                                            @endphp
                                            <span class="badge-soft {{ $statusClass }}">{{ $status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No recent leads found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pipeline -->
            <div class="col-lg-4">
                <div class="content-card">
                    <h5>Pipeline</h5>

                    @php
                        $counts = array_column($pipeline, 'count');
                        $max = !empty($counts) ? max($counts) : 1;
                        $max = $max > 0 ? $max : 1;
                    @endphp

                    @foreach($pipeline as $stage)
                        @php
                            $width = ($stage['count'] / $max) * 100;
                        @endphp

                        <div class="pipeline-item">
                            <div class="pipeline-info">
                                <div class="pipeline-dot" style="background: {{ $stage['color'] }}"></div>
                                <span class="pipeline-label">{{ $stage['label'] }}</span>
                            </div>

                            <div class="pipeline-progress">
                                <div class="pipeline-progress-bar">
                                    <div class="pipeline-progress-fill"
                                        style="width: {{ $width }}%; background: {{ $stage['color'] }};">
                                    </div>
                                </div>

                                <span class="pipeline-count">{{ $stage['count'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection