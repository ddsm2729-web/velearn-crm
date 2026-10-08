@extends('layouts.master')

@section('pageTitle', 'Placement Staff Dashboard')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/placement-dashboard.css') }}">
@endpush

@section('content')
    <div class="d-flex justify-content-end mb-3">
        @include('partials.daterange_picker')
    </div>

    <div class="placement-dashboard-wrapper">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="text-dark pd-fw-600 pd-mb-0">Placement Staff Dashboard</h3>
                <p class="text-muted pd-mb-0" style="font-size: 0.85rem;">Overview of current placement activity.</p>


            </div>
        </div>

        <!-- Stats Grid -->
        <div class="pd-stats-grid">
            @foreach($stats as $stat)
                <div class="pd-stat-card">
                    <div class="pd-stat-icon-wrapper {{ $stat['icon'] }}">
                        @if($stat['icon'] == 'blue')
                            <i class="fas fa-building"></i>
                        @elseif($stat['icon'] == 'green')
                            <i class="fas fa-check-circle"></i>
                        @elseif($stat['icon'] == 'orange')
                            <i class="fas fa-star"></i>
                        @elseif($stat['icon'] == 'purple')
                            <i class="fas fa-briefcase"></i>
                        @elseif($stat['icon'] == 'red')
                            <i class="fas fa-bell"></i>
                        @endif
                    </div>
                    <div class="pd-stat-title">{{ $stat['title'] }}</div>
                    <div class="pd-stat-value">{{ $stat['value'] }}</div>
                    <div class="pd-stat-desc">{{ $stat['desc'] }}</div>
                </div>
            @endforeach
        </div>

        <!-- Split Section: Vacancies & Interviews -->
        <div class="pd-split-grid mb-4">

            <!-- Today's Vacancies -->
            <div class="pd-card">
                <div class="pd-card-header">
                    <h4 class="pd-card-title">
                        <span class="pd-card-title-dot blue"></span>
                        Today's Vacancies <span class="pd-card-subtitle">(active company openings)</span>
                    </h4>
                    <a href="#" class="pd-view-all-btn">View all</a>
                </div>
                <div class="pd-card-body p-0">
                    @forelse($vacancies as $vacancy)
                        <div class="pd-list-item pd-flex-row">
                            <div style="flex: 1;">
                                <span class="pd-item-label">Company</span>
                                <span class="pd-item-val">{{ $vacancy->company->company_name ?? 'N/A' }}</span>
                            </div>
                            <div style="flex: 1.5;">
                                <span class="pd-item-label">Role</span>
                                <span class="pd-item-val">{{ $vacancy->job_role ?? 'N/A' }}</span>
                            </div>
                            <div style="flex: 0.8;">
                                <span class="pd-item-label">CTC</span>
                                <span class="pd-item-val pd-text-white pd-fw-600">{{ $vacancy->ctc_salary ?? 'N/A' }}</span>
                            </div>
                            <div style="flex: 0.5;">
                                <span class="pd-item-label">Slots</span>
                                <span class="pd-item-val">{{ $vacancy->number_of_openings ?? 0 }}</span>
                            </div>
                            <div style="flex: 1.5;">
                                <span class="pd-item-label">Skills</span>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @if($vacancy->skills_required)
                                        @foreach(explode(',', $vacancy->skills_required) as $skill)
                                            <span class="pd-badge pd-badge-blue"
                                                style="font-size:0.55rem; padding: 2px 6px;">{{ trim($skill) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted" style="font-size:0.75rem;">Not specified</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            No active vacancies found for today.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Today's Interviews -->
            <div class="pd-card">
                <div class="pd-card-header">
                    <h4 class="pd-card-title">
                        <span class="pd-card-title-dot blue"></span>
                        Today's Interviews <span class="pd-card-subtitle">(students lined up)</span>
                    </h4>
                    <a href="#" class="pd-view-all-btn">View all</a>
                </div>
                <div class="pd-card-body p-0">
                    @forelse($interviews as $interview)
                        <div class="pd-list-item">
                            <div class="pd-flex-row mb-2">
                                <span class="pd-item-val pd-fw-600"
                                    style="font-size: 0.95rem;">{{ $interview['student'] }}</span>
                                <span
                                    class="pd-badge pd-badge-{{ $interview['status_color'] }}">{{ $interview['status'] }}</span>
                            </div>
                            <div class="pd-flex-row" style="align-items: flex-start;">
                                <div style="flex: 1;">
                                    <span class="pd-item-label">Phone</span>
                                    <span class="pd-item-val">{{ $interview['phone'] }}</span>
                                </div>
                                <div style="flex: 1;">
                                    <span class="pd-item-label">Company</span>
                                    <span class="pd-item-val">{{ $interview['company'] }}</span>
                                </div>
                            </div>
                            <div class="pd-flex-row" style="align-items: flex-start;">
                                <div style="flex: 1;">
                                    <span class="pd-item-label">Course</span>
                                    <span class="pd-item-val">{{ $interview['course'] }}</span>
                                </div>
                                <div style="flex: 1;">
                                    <span class="pd-item-label">Batch Type</span>
                                    <span class="pd-item-val">{{ $interview['batch_type'] }}</span>
                                </div>
                            </div>
                            <div class="pd-flex-row" style="align-items: flex-start;">
                                <div style="flex: 1;">
                                    <span class="pd-item-label">Batch Code</span>
                                    <span class="pd-item-val"
                                        style="color:var(--pd-text-muted);">{{ $interview['batch_code'] }}</span>
                                </div>
                                <div style="flex: 1;">
                                    <span class="pd-item-label">Time</span>
                                    <span class="pd-item-val">{{ $interview['time'] }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            No interviews scheduled for today.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pending Follow-ups -->
        <div class="pd-card mb-4">
            <div class="pd-card-header">
                <h4 class="pd-card-title">
                    <span class="pd-card-title-dot purple"></span>
                    Pending Follow-ups <span class="pd-card-subtitle">(leads assigned to self)</span>
                </h4>
                <a href="#" class="pd-view-all-btn">View all</a>
            </div>
            <div class="table-responsive">
                <table class="pd-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Entity</th>
                            <th>HR / Contact</th>
                            <th>Phone</th>
                            <th>Follow-up</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($followups as $followup)
                            <tr>
                                <td style="color:var(--pd-text-muted);">{{ $followup['id'] }}</td>
                                <td class="pd-text-white pd-fw-600">{{ $followup['entity'] }}</td>
                                <td>{{ $followup['contact'] }}</td>
                                <td>{{ $followup['phone'] }}</td>
                                <td class="pd-text-white">{{ $followup['date'] }}</td>
                                <td><span
                                        class="pd-badge pd-badge-{{ $followup['status_color'] }}">{{ $followup['status'] }}</span>
                                </td>
                                <td
                                    style="color:var(--pd-text-muted); max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $followup['reason'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-4 text-muted">No pending follow-ups assigned to you.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Overall Students Status -->
        <div class="pd-card">
            <div class="pd-card-header">
                <h4 class="pd-card-title">
                    <span class="pd-card-title-dot green"></span>
                    Overall Students Status <span class="pd-card-subtitle">(every student tracked)</span>
                </h4>
                <a href="#" class="pd-view-all-btn"
                    style="background-color: var(--pd-accent-blue); color: white; border: none;">View all <i
                        class="fas fa-arrow-right ms-1"></i></a>
            </div>
            <div class="table-responsive">
                <table class="pd-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Batch</th>
                            <th>Code</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Count</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentStatus as $student)
                            <tr>
                                <td class="pd-text-white pd-fw-600">{{ $student['name'] }}</td>
                                <td>{{ $student['phone'] }}</td>
                                <td>{{ $student['course'] }}</td>
                                <td>{{ $student['batch'] }}</td>
                                <td class="pd-text-white">{{ $student['code'] }}</td>
                                <td>{{ $student['company'] }}</td>
                                <td>{{ $student['location'] }}</td>
                                <td>{{ $student['count'] }}</td>
                                <td>{{ $student['date'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center p-4 text-muted">No student data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection