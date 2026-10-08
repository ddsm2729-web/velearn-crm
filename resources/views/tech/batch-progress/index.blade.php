@extends('layouts.master')

@section('pageTitle', 'Batch Progress')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/tech_batch_progress.css') }}">
@endpush

@section('content')
    <div class="tech-batch-wrapper">
        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">Batch <span>Progress</span></h1>

            <div class="header-actions">
                <button class="btn-export" style="background: var(--card-bg);">
                    <i class="fas fa-file-excel text-success"></i> Export Report
                </button>
            </div>
        </div>

        <div class="filter-tabs">
            <button class="filter-tab active filter-btn" data-filter="all">
                <i class="fas fa-bolt" style="color: var(--brand-blue)"></i> Full Stack
            </button>
            <button class="filter-tab filter-btn" data-filter="uiux">
                <i class="fas fa-palette" style="color: var(--accent-pink)"></i> UI/UX
            </button>
            <button class="filter-tab filter-btn" data-filter="dm">
                <i class="fas fa-bullhorn" style="color: var(--accent-orange)"></i> Digital Marketing
            </button>
            <button class="filter-tab filter-btn" data-filter="ds">
                <i class="fas fa-chart-bar" style="color: var(--accent-green)"></i> Data Science
            </button>
        </div>

        <div class="main-card">
            <div class="section-heading">
                <i class="fas fa-bolt"></i> Full Stack — Active Batches <span class="badge-count"
                    style="background: #e2e8f0; color: var(--text-primary);">{{ count($batches) }}</span>
                <span class="section-heading-sub">Click any batch for student-level breakdown</span>
            </div>

            @forelse($batches as $index => $batch)
                @php
                    // Simulated data based on design
                    $progressValues = [
                        ['course' => 74, 'score' => 77, 'attendance' => 91, 'syllabus' => 74, 'overall' => 74, 'submissions' => 3],
                        ['course' => 62, 'score' => 65, 'attendance' => 84, 'syllabus' => 62, 'overall' => 62, 'submissions' => 1],
                        ['course' => 48, 'score' => 50, 'attendance' => 78, 'syllabus' => 48, 'overall' => 48, 'submissions' => 1],
                    ];

                    $stats = $progressValues[$index % count($progressValues)];
                    $startDate = $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('j M Y') : '15 Sept 2024';
                    $endDate = $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->addYear()->format('j M Y') : '15 Sept 2025';
                @endphp

                <div class="batch-card batch-item course-{{ $batch->course_id }}">
                    <div class="batch-header">
                        <div class="batch-title-wrapper">
                            <div class="batch-title">
                                <i class="fas fa-bolt"></i> {{ $batch->batch_code }}
                            </div>
                            <div class="batch-subtitle">
                                {{ $batch->course->title ?? 'Full Stack' }} &middot;
                                {{ strtolower($batch->batch_type) ?? 'morning' }} &middot;
                                <i class="fas fa-user-circle"></i> {{ $batch->instructor->name ?? 'Vikram Patel' }} &middot;
                                <i class="fas fa-users"></i> {{ $batch->joined_student_count ?? rand(15, 30) }} students
                            </div>
                        </div>
                        <div class="batch-overall">
                            <div class="batch-overall-val">{{ $stats['overall'] }}<span style="font-size: 0.8rem;">%</span>
                            </div>
                            <div class="batch-overall-label">OVERALL</div>
                        </div>
                    </div>

                    <div class="progress-grid">
                        <!-- Course Progress -->
                        <div class="progress-item color-blue">
                            <div class="progress-label">
                                <i class="fas fa-book-open" style="color: var(--brand-blue)"></i> COURSE PROGRESS
                            </div>
                            <div class="progress-val">{{ $stats['course'] }}%</div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $stats['course'] }}%;"></div>
                            </div>
                        </div>

                        <!-- Avg Student Score -->
                        <div class="progress-item color-purple">
                            <div class="progress-label">
                                <i class="fas fa-star" style="color: var(--accent-orange)"></i> AVG STUDENT SCORE
                            </div>
                            <div class="progress-val">{{ $stats['score'] }}%</div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $stats['score'] }}%;"></div>
                            </div>
                        </div>

                        <!-- Avg Attendance -->
                        <div class="progress-item color-green">
                            <div class="progress-label">
                                <i class="fas fa-calendar-check" style="color: var(--brand-blue)"></i> AVG ATTENDANCE
                            </div>
                            <div class="progress-val">{{ $stats['attendance'] }}%</div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $stats['attendance'] }}%;"></div>
                            </div>
                        </div>

                        <!-- Syllabus Done -->
                        <div class="progress-item color-indigo">
                            <div class="progress-label">
                                <i class="fas fa-list-ul" style="color: var(--accent-green)"></i> SYLLABUS DONE
                            </div>
                            <div class="progress-val">{{ $stats['syllabus'] }}%</div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $stats['syllabus'] }}%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="batch-footer">
                        <div class="footer-submissions">
                            <i class="fas fa-inbox"></i> {{ $stats['submissions'] }} recent submissions
                        </div>

                        <div class="footer-dates">
                            <i class="far fa-calendar-alt"></i> {{ $startDate }} &rarr; {{ $endDate }}
                        </div>

                        <a href="#" class="btn-detail">
                            <i class="far fa-eye"></i> View Detail
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center py-4">No batches found to display progress.</p>
            @endforelse
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Just minimal tab filtering for the UI
        document.addEventListener('DOMContentLoaded', function () {
            const filterBtns = document.querySelectorAll('.filter-btn');
            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                });
            });
        });
    </script>
@endpush