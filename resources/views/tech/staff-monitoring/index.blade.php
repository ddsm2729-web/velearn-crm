@extends('layouts.master')

@section('pageTitle', 'Staff Monitoring')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/tech_staff_monitoring.css') }}">
    <style>
        .staff-row-clickable:hover {
            background-color: #f8fafc !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        .staff-row-clickable {
            transition: all 0.2s ease;
        }
    </style>
@endpush

@section('content')
    <div class="tech-batch-wrapper" style="padding: 1rem;">
        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">Staff <span>Monitor</span></h1>

            <div class="header-actions">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search batches, students, staff">
                </div>

                <button class="btn-quick-action">
                    <i class="fas fa-bolt text-warning"></i> Quick Action
                </button>
            </div>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <div class="card-header-flex">
                <h2 class="section-heading">
                    <i class="fas fa-user-tie"></i> Staff — Click name for detail view
                </h2>
                <button class="btn-staff-meeting">
                    <i class="fas fa-plus"></i> Staff Meeting
                </button>
            </div>

            <div class="table-responsive">
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>Staff Member</th>
                            <th>Course</th>
                            <th>Batches</th>
                            <th>Syllabus Done</th>
                            <th>Performance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffMembers as $index => $user)
                            @php
                                $staff = $user->staffProfile;
                                $name = $user->name ?? ($staff ? trim($staff->first_name . ' ' . $staff->last_name) : 'Unknown');
                                $email = $user->email;
                                $initials = collect(explode(' ', $name))->map(function ($segment) {
                                    return strtoupper(substr($segment, 0, 1));
                                })->join('');
                                $initials = substr($initials, 0, 2);

                                // Batches logic
                                $batches = [];
                                $courseName = 'N/A';
                                if ($staff || $user) {
                                    $instructorId = $staff ? $staff->id : $user->id; // Could be either depending on schema
                                    $batches = \App\Models\Batch::with('course')
                                        ->where('instructor_id', $user->id)
                                        ->orWhere('instructor_id', $instructorId)
                                        ->get();
                                    
                                    if ($batches->isNotEmpty()) {
                                        $courseName = $batches->first()->course->title ?? 'N/A';
                                    }
                                }
                                $batchesCount = count($batches);

                                // Dynamic styling based on course
                                $course_class = 'fs';
                                $course_icon = 'fa-bolt';
                                $syllabus_color = '#6366f1';

                                $courseLower = strtolower($courseName);
                                if (str_contains($courseLower, 'ui') || str_contains($courseLower, 'ux')) {
                                    $course_class = 'ux';
                                    $course_icon = 'fa-palette';
                                    $syllabus_color = '#ec4899';
                                } elseif (str_contains($courseLower, 'data')) {
                                    $course_class = 'ds';
                                    $course_icon = 'fa-chart-bar';
                                    $syllabus_color = '#10b981';
                                } elseif (str_contains($courseLower, 'marketing')) {
                                    $course_class = 'dm';
                                    $course_icon = 'fa-bullhorn';
                                    $syllabus_color = '#f59e0b';
                                }

                                // Define color palettes for avatars
                                $bgColors = ['#6366f1', '#818cf8', '#f472b6', '#fbbf24', '#34d399'];
                                $avatar_bg = $bgColors[$index % count($bgColors)];

                                // Metrics (using random for now if no real data available for syllabus/performance)
                                $syllabusDone = $batchesCount > 0 ? rand(40, 90) : 0;
                                $performance = $batchesCount > 0 ? rand(50, 95) : 0;
                                $perf_color = '#a855f7';

                                $status_perc = $batchesCount > 0 ? rand(60, 98) : 0;
                                $isActive = ($staff && $staff->status == 1);
                                // Fallback if status is string or missing
                                if ($staff && !is_numeric($staff->status)) {
                                    $isActive = strtolower($staff->status) == 'active' || $staff->status === null;
                                }

                                $status_text = $isActive ? 'Active' : 'Inactive/Leave';
                                $status_dot = $isActive ? 'active' : 'leave';

                                if ($batchesCount == 0) {
                                    $syllabus_color = '#cbd5e1';
                                    $perf_color = '#cbd5e1';
                                }
                            @endphp

                            <tr onclick="window.location='{{ route('tech.staff-monitoring.show', $user->id) }}';" style="cursor: pointer;" class="staff-row-clickable">
                                <td>
                                    <div class="user-profile">
                                        <div class="user-avatar" style="background-color: {{ $avatar_bg }}">
                                            {{ $initials }}
                                        </div>
                                        <div class="user-info">
                                            <span class="user-name" style="color: inherit; font-weight: 600;">{{ $name }}</span>
                                            <span class="user-email">{{ $email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="course-pill {{ $course_class }}">
                                        <i class="fas {{ $course_icon }}"></i> {{ $courseName }}
                                    </div>
                                </td>
                                <td>
                                    <span class="batch-count">{{ $batchesCount }}</span>
                                </td>
                                <td>
                                    <div class="progress-cell">
                                        <div class="progress-track">
                                            <div class="progress-fill"
                                                style="width: {{ $syllabusDone }}%; background-color: {{ $syllabus_color }};">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress-cell">
                                        <span class="progress-text">{{ $performance }}%</span>
                                        <div class="progress-track">
                                            <div class="progress-fill"
                                                style="width: {{ $performance }}%; background-color: {{ $perf_color }};"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="status-cell">
                                        <span>{{ $status_perc }}%</span>
                                        <div class="status-dot {{ $status_dot }}"></div>
                                        <span
                                            style="color: {{ $status_dot == 'active' ? 'var(--accent-green)' : 'var(--accent-orange)' }}">{{ $status_text }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No technical staff found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Additional scripts can go here
    </script>
@endpush