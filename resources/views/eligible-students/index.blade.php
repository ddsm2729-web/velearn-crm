@extends('layouts.header')

@section('title', 'Eligible Students')

@push('css')
<style>
    :root {
        --bg-elev-1: #ffffff;
        --bg-elev-2: #ffffff;
        --bg-elev-3: #f1f5f9;
        --border: rgba(0, 0, 0, 0.08);
        --border-light: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --accent: #3b82f6;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
    }

    .page-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .page-subtitle {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }
    
    .badge-role {
        background: rgba(6, 182, 212, 0.1);
        color: #0891b2;
        font-size: 0.7rem;
        font-weight: 700;
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        padding: 2px 8px;
    }

    .count-badge {
        background: var(--text-primary);
        color: #ffffff;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .filter-select {
        background-color: var(--bg-elev-1);
        border: 1px solid var(--border);
        color: var(--text-primary);
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.5rem 2.5rem 0.5rem 1rem;
        border-radius: 8px;
        appearance: none;
        outline: none;
        min-width: 140px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    
    .table-wrapper {
        background-color: var(--bg-elev-2);
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden; /* For border radius */
    }
    
    .custom-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }
    
    .custom-table th {
        background-color: var(--bg-elev-3);
        font-size: 0.65rem;
        text-transform: uppercase;
        color: var(--text-secondary);
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 1rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    
    .custom-table td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
        position: relative;
    }
    
    .custom-table tr:last-child td {
        border-bottom: none;
    }
    
    .custom-table tr {
        transition: background-color 0.15s;
    }
    
    .custom-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Status border lines */
    .td-status-border {
        position: relative;
    }
    .td-status-border::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }
    tr.status-success .td-status-border::before { background-color: var(--success); }
    tr.status-warning .td-status-border::before { background-color: var(--warning); }
    
    .col-val {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
    }
    .col-val-sub {
        font-weight: 500;
        color: #334155 !important;
    }

    .progress-bar-bg {
        background-color: var(--border-light);
        height: 6px;
        border-radius: 3px;
        width: 100px;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 3px;
    }

    .status-pill {
        border-radius: 12px;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .status-pill.sent {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border-color: rgba(16, 185, 129, 0.2);
    }
    .status-pill.rejected {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border-color: rgba(239, 68, 68, 0.2);
    }
    .status-pill.not-applied {
        background: var(--bg-elev-3);
        color: var(--text-secondary);
        border-color: var(--border);
    }
</style>
@endpush

@section('section')
<div class="container-fluid py-4" style="max-width: 1400px;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <h1 class="page-title mb-0">Eligible Students</h1>
                <span class="badge-role">STUDENT COORD</span>
            </div>
            <p class="page-subtitle mb-0">Students cleared for placement outreach</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-primary shadow-sm" style="background-color: var(--accent); border-color: var(--accent); border-radius: 8px;">
                <i class="fas fa-plus me-2"></i> Entry
            </button>
        </div>
    </div>

    <!-- Filters & Title -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2">
            <h5 class="mb-0 fw-bold" style="font-size: 1.1rem; color: var(--text-primary);">Eligible Students</h5>
            <span class="count-badge">{{ $students->total() }}</span>
        </div>
        <form id="filter-form" action="{{ route('eligible-students.index') }}" method="GET" class="d-flex gap-3 flex-wrap align-items-center">
            <div style="min-width: 200px;">
                <select class="form-select select2-filter" id="course_filter" name="course_id" data-placeholder="Select Course">
                    <option value="all">Course: All</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div style="min-width: 180px;">
                <select class="form-select select2-filter" id="batch_filter" name="batch_id" data-placeholder="Select Batch">
                    <option value="all">Batch: All</option>
                    @if(request('batch_id') && request('batch_id') !== 'all')
                        <option value="{{ request('batch_id') }}" selected>Selected Batch (Auto-loads)</option>
                    @endif
                </select>
            </div>
            <div style="min-width: 160px;">
                <select class="form-select select2-filter" id="progress_filter" name="progress" data-placeholder="Progress: All">
                    <option value="all" {{ request('progress') == 'all' ? 'selected' : '' }}>Progress: All</option>
                    <option value="90" {{ request('progress') == '90' ? 'selected' : '' }}>> 90%</option>
                    <option value="80" {{ request('progress') == '80' ? 'selected' : '' }}>> 80%</option>
                    <option value="70" {{ request('progress') == '70' ? 'selected' : '' }}>> 70%</option>
                </select>
            </div>
            <div style="min-width: 160px;">
                <select class="form-select select2-filter" id="placement_filter" name="placement" data-placeholder="Placement: All">
                    <option value="all" {{ request('placement') == 'all' ? 'selected' : '' }}>Placement: All</option>
                    <option value="placed" {{ request('placement') == 'placed' ? 'selected' : '' }}>Placed</option>
                    <option value="unplaced" {{ request('placement') == 'unplaced' ? 'selected' : '' }}>Unplaced</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Table Format -->
    <div class="table-wrapper">
        <div class="table-responsive border-0">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 80px;">ID</th>
                        <th style="width: 200px;">Student</th>
                        <th style="width: 150px;">Phone</th>
                        <th style="width: 150px;">Course</th>
                        <th style="width: 120px;">Batch Code</th>
                        <th style="width: 100px;">Type</th>
                        <th style="width: 140px;">Progress</th>
                        <th class="text-center" style="width: 80px;">Overall</th>
                        <th style="width: 130px;">Status</th>
                        <th style="width: 150px;">Course Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $courseUser)
                        @php
                            $user = $courseUser->user;
                            $course = $courseUser->course;
                            $batch = $courseUser->batch;
                            
                            // Use the calculated actual backend progress and overall score
                            $progress = $courseUser->calc_progress ?? 0;
                            $overall = $courseUser->calc_overall ?? 0;

                            $statusClass = $progress >= 80 ? 'status-success' : 'status-warning';
                            $progressColor = $progress >= 80 ? 'var(--success)' : 'var(--warning)';
                            
                            $pillClass = $overall >= 80 ? 'sent' : ($overall >= 60 ? 'not-applied' : 'rejected');
                            $pillText = $overall >= 80 ? 'PROFILE SENT' : ($overall >= 60 ? 'NOT APPLIED' : 'REJECTED');
                        @endphp
                        <tr class="{{ $statusClass }}" style="cursor: pointer;" onclick="window.location.href='{{ route('eligible-students.show', $courseUser->id) }}'">
                            <td class="ps-4 td-status-border">
                                <div class="col-val col-val-sub">V{{ str_pad($user->id ?? 0, 3, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td>
                                @php
                                    $displayName = $user->name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                                    if (empty($displayName)) $displayName = 'Student #'.$user->id;
                                @endphp
                                <div class="col-val">{{ $displayName }}</div>
                            </td>
                            <td>
                                <div class="col-val col-val-sub">{{ $user->phonenumber ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="col-val col-val-sub" style="color: var(--text-secondary) !important;">{{ $course->title ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="col-val">{{ $batch->batch_code ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="col-val col-val-sub" style="color: var(--text-secondary) !important;">{{ $batch->batch_type ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between align-items-center mb-1" style="width: 100px;">
                                    <div class="col-val" style="font-size: 0.8rem;">{{ $progress }}%</div>
                                </div>
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: {{ $progress }}%; background-color: {{ $progressColor }};"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="col-val">{{ $overall }}%</div>
                            </td>
                            <td>
                                <div class="status-pill {{ $pillClass }}"><i class="fas fa-circle" style="font-size: 6px;"></i> {{ $pillText }}</div>
                            </td>
                            <td>
                                <div class="col-val col-val-sub" style="color: var(--text-secondary) !important;">{{ $courseUser->status ?? 'Active' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($students->hasPages())
        <div class="d-flex justify-content-end p-3 border-top">
            {{ $students->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 Initialization and AJAX logic -->
<script>
    $(document).ready(function() {
        // Initialize Select2 on the filter dropdowns
        $('.select2-filter').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownAutoWidth: true
        });

        // Function to load batches via AJAX
        function loadBatches(courseId, selectedBatchId = null) {
            var batchDropdown = $('#batch_filter');
            
            if (courseId && courseId !== 'all') {
                $.ajax({
                    url: "{{ route('eligible-students.batches') }}",
                    type: 'GET',
                    data: { course_id: courseId },
                    success: function(response) {
                        batchDropdown.empty();
                        batchDropdown.append('<option value="all">Batch: All</option>');
                        
                        if (response && response.length > 0) {
                            $.each(response, function(index, batch) {
                                var isSelected = (selectedBatchId && selectedBatchId == batch.id) ? 'selected' : '';
                                batchDropdown.append('<option value="' + batch.id + '" ' + isSelected + '>' + batch.batch_name + ' (' + batch.batch_code + ')</option>');
                            });
                        }
                        // Re-initialize select2 so it updates visually without triggering change event
                        batchDropdown.select2({theme: 'bootstrap-5', width: '100%'});
                    },
                    error: function(err) {
                        console.error('Error fetching batches:', err);
                    }
                });
            }
        }

        // On load, if there's a course selected, pre-load its batches
        var currentCourseId = $('#course_filter').val();
        var currentBatchId = "{{ request('batch_id') }}";
        if (currentCourseId && currentCourseId !== 'all') {
            loadBatches(currentCourseId, currentBatchId);
        }

        // Course filter change event to load batches dynamically
        $('#course_filter').on('change', function() {
            var courseId = $(this).val();
            // Reset batch to 'all' before auto-submitting
            $('#batch_filter').empty().append('<option value="all">Batch: All</option>').val('all');
            $('#filter-form').submit();
        });

        // Other filters change event to auto-submit
        $('#batch_filter, #progress_filter, #placement_filter').on('change', function(e) {
            // Only submit if it is an actual user interaction to prevent infinite loops from AJAX loading
            if(e.originalEvent || e.isTrigger === undefined) {
                $('#filter-form').submit();
            }
        });
    });
</script>
@endpush
