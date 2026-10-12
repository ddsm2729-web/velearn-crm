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
                <button class="btn-export" style="background: var(--card-bg);" onclick="window.print()">
                    <i class="fas fa-file-excel text-success"></i> Export Report
                </button>
            </div>
        </div>

        <div class="filter-tabs">
            <button class="filter-tab active filter-btn" data-filter="all">
                <i class="fas fa-layer-group" style="color: var(--brand-blue)"></i> All Batches ({{ count($batches) }})
            </button>
            @foreach($courses as $course)
                @php
                    $courseBatchesCount = $batches->where('course_id', $course->id)->count();
                    $colors = ['var(--brand-blue)', 'var(--accent-pink)', 'var(--accent-orange)', 'var(--accent-green)', 'var(--accent-purple)'];
                    $icons = ['fa-bolt', 'fa-palette', 'fa-bullhorn', 'fa-chart-bar', 'fa-code'];
                    $color = $colors[$loop->index % count($colors)];
                    $icon = $icons[$loop->index % count($icons)];
                @endphp
                <button class="filter-tab filter-btn" data-filter="course-{{ $course->id }}">
                    <i class="fas {{ $icon }}" style="color: {{ $color }}"></i> {{ $course->title }} ({{ $courseBatchesCount }})
                </button>
            @endforeach
        </div>

        <div class="main-card">
            <div class="section-heading">
                <i class="fas fa-bolt"></i> <span id="section-heading-title">Active Batches</span> <span class="badge-count"
                    id="section-heading-count" style="background: #e2e8f0; color: var(--text-primary);">{{ count($batches) }}</span>
                <span class="section-heading-sub">Click any batch for student-level breakdown</span>
            </div>

            @forelse($batches as $index => $batch)
                @php
                    $stats = $batchStats[$batch->id] ?? [
                        'course' => 0,
                        'syllabus' => 0,
                        'attendance' => 0,
                        'score' => 0,
                        'overall' => 0,
                        'submissions' => 0,
                        'total_students' => $batch->joined_student_count ?? 0,
                    ];

                    $startDate = $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('j M Y') : 'N/A';
                    $endDate = $batch->end_date ? \Carbon\Carbon::parse($batch->end_date)->format('j M Y') : ($batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->addYear()->format('j M Y') : 'N/A');
                @endphp

                <div class="batch-card batch-item course-{{ $batch->course_id }}" data-course-id="course-{{ $batch->course_id }}">
                    <div class="batch-header">
                        <div class="batch-title-wrapper">
                            <div class="batch-title">
                                <i class="fas fa-bolt"></i> {{ $batch->batch_code }}
                            </div>
                            <div class="batch-subtitle">
                                {{ $batch->course->title ?? 'Live Course' }} &middot;
                                {{ strtolower($batch->batch_type ?? 'Weekday') }} &middot;
                                <i class="fas fa-user-circle"></i> {{ $batch->instructor->name ?? 'Unassigned' }} &middot;
                                <i class="fas fa-users"></i> {{ $stats['total_students'] }} students
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
                        <div class="progress-item color-blue" title="{{ $stats['completed_modules'] ?? 0 }} of {{ $stats['total_modules'] ?? 0 }} modules completed">
                            <div class="progress-label">
                                <i class="fas fa-book-open" style="color: var(--brand-blue)"></i> COURSE PROGRESS
                            </div>
                            <div class="progress-val">{{ $stats['course'] }}%</div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $stats['course'] }}%;"></div>
                            </div>
                        </div>

                        <!-- Avg Student Score (Assignments, Mini, Main Projects) -->
                        <div class="progress-item color-purple" title="Average across Assignments, Mini Projects & Main Projects">
                            <div class="progress-label">
                                <i class="fas fa-star" style="color: var(--accent-orange)"></i> AVG STUDENT SCORE
                            </div>
                            <div class="progress-val">{{ $stats['score'] }}%</div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $stats['score'] }}%;"></div>
                            </div>
                        </div>

                        <!-- Avg Attendance (From Meeting Logs) -->
                        <div class="progress-item color-green" title="Dynamic attendance marked from meeting logs">
                            <div class="progress-label">
                                <i class="fas fa-calendar-check" style="color: var(--brand-blue)"></i> AVG ATTENDANCE
                            </div>
                            <div class="progress-val">{{ $stats['attendance'] }}%</div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $stats['attendance'] }}%;"></div>
                            </div>
                        </div>

                        <!-- Syllabus Done (From Curriculum & Topic Logs) -->
                        <div class="progress-item color-indigo" title="Curriculum syllabus coverage">
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
                            <i class="fas fa-inbox"></i> {{ $stats['submissions'] }} submissions
                        </div>

                        <div class="footer-dates">
                            <i class="far fa-calendar-alt"></i> {{ $startDate }} &rarr; {{ $endDate }}
                        </div>

                        <a href="{{ route('batches.show', $batch->id) }}" class="btn-detail">
                            <i class="far fa-eye"></i> View Detail
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center py-5">
                    <i class="fas fa-layer-group d-block mb-3 h2 opacity-25"></i>
                    No active batches found to display progress.
                </p>
            @endforelse
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const batchItems = document.querySelectorAll('.batch-item');
            const countBadge = document.getElementById('section-heading-count');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const filter = btn.getAttribute('data-filter');
                    let visibleCount = 0;

                    batchItems.forEach(item => {
                        if (filter === 'all' || item.classList.contains(filter)) {
                            item.style.display = 'block';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    if (countBadge) {
                        countBadge.textContent = visibleCount;
                    }
                });
            });
        });
    </script>
@endpush