@extends('layouts.header')

@section('title', 'Eligible Students')

@push('css')
<style>
    :root {
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --border-color: #e2e8f0;
        --primary-blue: #2563eb;
    }

    body {
        background-color: #f8fafc !important;
    }

    .page-title {
        font-size: 1.55rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #0f172a;
    }
    
    .badge-role {
        background: rgba(6, 182, 212, 0.1);
        color: #0891b2;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        border: 1px solid rgba(6, 182, 212, 0.25);
        border-radius: 999px;
        padding: 3px 10px;
    }

    /* Push Mode Active Banner */
    .push-mode-banner {
        background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 50%, #f8fafc 100%);
        border: 1px solid #bfdbfe !important;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        position: relative;
        overflow: hidden;
    }
    .push-mode-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #2563eb 0%, #3b82f6 100%);
    }
    .pulse-dot {
        width: 8px;
        height: 8px;
        background-color: #22c55e;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        animation: pulse-green 1.8s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    .meta-chip {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #334155;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    /* Filter Toolbar Card */
    .filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 18px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .filter-dropdown {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 7px 30px 7px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #334155;
        outline: none;
        cursor: pointer;
        transition: all 0.2s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        min-width: 140px;
    }
    .filter-dropdown:hover {
        background-color: #ffffff;
        border-color: #94a3b8;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }
    .filter-dropdown:focus {
        background-color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    /* Modern Table Card */
    .table-container-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }
    .modern-table {
        width: 100%;
        min-width: 1100px;
        border-collapse: separate;
        border-spacing: 0;
    }
    .modern-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 13px 16px;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
        vertical-align: middle;
    }
    .modern-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.88rem;
    }
    .modern-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .modern-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .modern-table tbody tr.row-selected {
        background-color: #eff6ff !important;
    }
    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Custom Checkbox */
    .student-cb-custom {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid #94a3b8;
        cursor: pointer;
        transition: all 0.15s;
        accent-color: #2563eb;
    }

    /* Student Avatar Circle */
    .student-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.82rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
    }

    /* Status Pills */
    .status-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .status-tag.in-progress {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
        border-color: rgba(245, 158, 11, 0.25);
    }
    .status-tag.sent {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border-color: rgba(16, 185, 129, 0.25);
    }
    .status-tag.rejected {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border-color: rgba(239, 68, 68, 0.25);
    }
    .status-tag.not-applied {
        background: #f1f5f9;
        color: #64748b;
        border-color: #e2e8f0;
    }

    .bg-soft-success {
        background-color: rgba(16, 185, 129, 0.1) !important;
        color: #059669 !important;
    }
    .bg-soft-primary {
        background-color: rgba(37, 99, 235, 0.1) !important;
        color: #2563eb !important;
    }
</style>
@endpush

@section('section')
<div class="container-fluid py-4" style="max-width: 1400px;">


    @if(isset($opening) && $opening)
        <!-- Push Mode Active Banner -->
        <div class="card border-0 mb-4 push-mode-banner">
            <div class="card-body p-4 ps-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                            <span class="badge bg-primary px-3 py-1.5 rounded-pill text-uppercase d-inline-flex align-items-center gap-2" style="font-size:0.72rem; letter-spacing:0.6px; font-weight:700; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                                <span class="pulse-dot"></span> PUSH MODE ACTIVE
                            </span>
                            <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;">
                                Pushing Opening: <span class="text-primary">{{ $opening->company->company_name ?? 'Company' }}</span> 
                                <span class="text-muted fw-normal mx-1">&mdash;</span> 
                                <span class="text-dark">{{ $opening->job_role ?? 'Role' }}</span>
                            </h4>
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2 text-muted" style="font-size: 0.88rem;">
                            <span class="meta-chip"><i class="fas fa-map-marker-alt text-danger me-1.5"></i> {{ $opening->job_location ?? 'Any' }}</span>
                            <span class="meta-chip"><i class="fas fa-wallet text-success me-1.5"></i> {{ $opening->ctc_salary ?? 'N/A' }}</span>
                            <span class="meta-chip"><i class="fas fa-users text-primary me-1.5"></i> {{ $opening->number_of_openings ?? 1 }} Slot(s)</span>
                            <span class="text-secondary small ms-1">&bull; Select eligible students below to push this vacancy directly to their LMS portal.</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('eligible-students.index') }}" class="btn btn-outline-secondary btn-sm px-3.5 py-2 rounded-pill fw-semibold bg-white shadow-none">
                            <i class="fas fa-times me-1"></i> Exit Push Mode
                        </a>
                        <button type="button" id="btnPushSubmit" class="btn btn-primary btn-sm px-4 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-2" disabled>
                            <i class="fas fa-paper-plane"></i> 
                            <span>Push to LMS (<span id="pushSelectedCount" class="badge bg-white text-primary rounded-pill px-2">0</span> Selected)</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2.5 mb-1">
                <h1 class="page-title mb-0">Eligible Students</h1>
                <span class="badge-role">STUDENT COORD</span>
            </div>
            <p class="page-subtitle mb-0 text-muted">Students cleared for placement outreach</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-primary px-3.5 py-2 rounded-3 shadow-sm fw-semibold d-inline-flex align-items-center" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none;">
                <i class="fas fa-plus me-2"></i> Entry
            </button>
        </div>
    </div>

    <!-- Filters Toolbar Card -->
    <div class="filter-card mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.05rem;">Eligible Students</h5>
                <span class="badge bg-dark text-white rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">{{ $students->total() }}</span>
            </div>
            <form id="filter-form" action="{{ route('eligible-students.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center mb-0">
                @if(isset($opening) && $opening)
                    <input type="hidden" name="opening" value="{{ request('opening') }}">
                @endif
                <div>
                    <select class="filter-dropdown" id="course_filter" name="course_id">
                        <option value="all">Course: All</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select class="filter-dropdown" id="batch_filter" name="batch_id">
                        <option value="all">Batch: All</option>
                        @if(request('batch_id') && request('batch_id') !== 'all')
                            <option value="{{ request('batch_id') }}" selected>Selected Batch</option>
                        @endif
                    </select>
                </div>
                <div>
                    <select class="filter-dropdown" id="progress_filter" name="progress">
                        <option value="all" {{ request('progress') == 'all' ? 'selected' : '' }}>Progress: All</option>
                        <option value="90" {{ request('progress') == '90' ? 'selected' : '' }}>&gt; 90%</option>
                        <option value="80" {{ request('progress') == '80' ? 'selected' : '' }}>&gt; 80%</option>
                        <option value="70" {{ request('progress') == '70' ? 'selected' : '' }}>&gt; 70%</option>
                    </select>
                </div>
                <div>
                    <select class="filter-dropdown" id="placement_filter" name="placement">
                        <option value="all" {{ request('placement') == 'all' ? 'selected' : '' }}>Placement: All</option>
                        <option value="placed" {{ request('placement') == 'placed' ? 'selected' : '' }}>Placed</option>
                        <option value="unplaced" {{ request('placement') == 'unplaced' ? 'selected' : '' }}>Unplaced</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if(isset($opening) && $opening)
    <form id="pushForm" action="{{ route('eligible-students.push-opening') }}" method="POST">
        @csrf
        <input type="hidden" name="opening_id" value="{{ $opening->id }}">
    @endif

    <!-- Modern Table Container -->
    <div class="table-container-card mb-4">
        <div class="table-responsive border-0">
            <table class="modern-table">
                <thead>
                    <tr>
                        @if(isset($opening) && $opening)
                            <th class="ps-3" style="width: 44px; cursor: pointer;">
                                <input type="checkbox" id="selectAllStudents" class="student-cb-custom" title="Select All">
                            </th>
                        @endif
                        <th class="{{ isset($opening) && $opening ? '' : 'ps-4' }}" style="width: 80px;">ID</th>
                        <th style="width: 220px;">Student</th>
                        <th style="width: 150px;">Phone</th>
                        <th style="width: 180px;">Course</th>
                        <th style="width: 120px;">Batch Code</th>
                        <th style="width: 120px;">Type</th>
                        <th style="width: 140px;">Progress</th>
                        <th class="text-center" style="width: 80px;">Overall</th>
                        <th style="width: 140px;">Status</th>
                        <th style="width: 130px;">Course Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $courseUser)
                        @php
                            $user = $courseUser->user;
                            $course = $courseUser->course;
                            $batch = $courseUser->batch;
                            $placementProfile = $courseUser->placementProfile ?? $user->placementProfile ?? null;
                            
                            $progress = $courseUser->calc_progress ?? 0;
                            $overall = $courseUser->calc_overall ?? 0;

                            $overallStatus = strtolower($placementProfile->overall_status ?? '');
                            $courseUserStatus = strtolower($courseUser->status ?? '');

                            if ($overallStatus === 'approved' || $overallStatus === 'all_verified' || $overall >= 80) {
                                $pillClass = 'sent';
                                $pillText = 'PROFILE SENT';
                                $pillIcon = 'fa-paper-plane';
                            } elseif ($overallStatus === 'rejected' || $courseUserStatus === 'rejected') {
                                $pillClass = 'rejected';
                                $pillText = 'REJECTED';
                                $pillIcon = 'fa-times-circle';
                            } elseif ($overall >= 60 && ($courseUserStatus === 'completed' || empty($overallStatus))) {
                                $pillClass = 'not-applied';
                                $pillText = 'NOT APPLIED';
                                $pillIcon = 'fa-minus-circle';
                            } else {
                                $pillClass = 'in-progress';
                                $pillText = 'IN PROGRESS';
                                $pillIcon = 'fa-spinner fa-spin';
                            }

                            $displayName = $user->name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                            if (empty($displayName)) $displayName = 'Student #'.$user->id;
                            $initial = strtoupper(substr($displayName, 0, 1));
                        @endphp
                        <tr style="cursor: pointer;" onclick="{{ isset($opening) && $opening ? 'toggleStudentRow(this, event)' : 'window.location.href=\''.route('eligible-students.show', $courseUser->id).'\'' }}">
                            @if(isset($opening) && $opening)
                                <td class="ps-3" onclick="event.stopPropagation()">
                                    <input type="checkbox" name="course_user_ids[]" value="{{ $courseUser->id }}" class="student-cb-custom student-cb">
                                </td>
                            @endif
                            <td class="{{ isset($opening) && $opening ? '' : 'ps-4' }}">
                                <span class="badge bg-light text-secondary border font-monospace px-2 py-1">V{{ str_pad($user->id ?? 0, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="student-avatar me-2">{{ $initial }}</div>
                                    <div>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 170px;">{{ $displayName }}</div>
                                        <div class="text-muted smaller" style="font-size: 0.72rem;">{{ $user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-monospace text-secondary small">
                                    <i class="fas fa-phone-alt text-muted opacity-75 me-1" style="font-size: 0.7rem;"></i>{{ $user->phonenumber ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-medium small text-truncate" style="max-width: 170px;" title="{{ $course->title ?? 'N/A' }}">{{ $course->title ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <span class="badge px-2.5 py-1 rounded fw-bold" style="background: rgba(37, 99, 235, 0.08); color: #2563eb; font-size: 0.75rem;">{{ $batch->batch_code ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="text-secondary small fw-medium">{{ $batch->batch_type ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div style="width: 110px;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold small {{ $progress >= 80 ? 'text-success' : ($progress >= 50 ? 'text-primary' : 'text-muted') }}">{{ $progress }}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px; border-radius: 999px; background-color: #e2e8f0;">
                                        <div class="progress-bar {{ $progress >= 80 ? 'bg-success' : ($progress >= 50 ? 'bg-primary' : 'bg-secondary') }}" style="width: {{ $progress }}%; border-radius: 999px;"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $overall >= 80 ? 'bg-soft-success text-success border border-success' : ($overall >= 60 ? 'bg-soft-primary text-primary border border-primary' : 'bg-light text-dark border') }} px-2 py-1 fw-bold" style="font-size: 0.78rem;">{{ $overall }}%</span>
                            </td>
                            <td>
                                <span class="status-tag {{ $pillClass }}">
                                    <i class="fas {{ $pillIcon }}"></i> {{ $pillText }}
                                </span>
                            </td>
                            <td>
                                @php $cStatus = strtolower($courseUser->status ?? 'active'); @endphp
                                @if($cStatus === 'active' || $cStatus === 'completed')
                                    <span class="badge bg-soft-success text-success px-2.5 py-1 rounded-pill fw-bold border border-success border-opacity-25" style="font-size: 0.7rem;">
                                        <i class="fas fa-circle me-1" style="font-size: 5px;"></i> {{ ucfirst($courseUser->status ?? 'Active') }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-secondary px-2.5 py-1 rounded-pill fw-bold border" style="font-size: 0.7rem;">
                                        {{ ucfirst($courseUser->status ?? 'Active') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ isset($opening) && $opening ? '11' : '10' }}" class="text-center py-5 text-muted">
                                <i class="fas fa-user-graduate fa-2x mb-3 text-secondary opacity-50"></i>
                                <div class="fw-semibold">No students found matching your criteria.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($students->hasPages())
        <div class="d-flex justify-content-end p-3 border-top bg-light bg-opacity-50">
            {{ $students->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    @if(isset($opening) && $opening)
    </form>
    @endif
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
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
            $('#batch_filter').empty().append('<option value="all">Batch: All</option>').val('all');
            $('#filter-form').submit();
        });

        // Other filters change event to auto-submit
        $('#batch_filter, #progress_filter, #placement_filter').on('change', function(e) {
            if(e.originalEvent || e.isTrigger === undefined) {
                $('#filter-form').submit();
            }
        });

        // Push Mode Checkbox and Submit handler
        function updatePushCount() {
            var total = $('.student-cb').length;
            var selected = $('.student-cb:checked').length;
            $('#pushSelectedCount').text(selected);
            if (selected > 0) {
                $('#btnPushSubmit').removeAttr('disabled').prop('disabled', false);
            } else {
                $('#btnPushSubmit').attr('disabled', 'disabled').prop('disabled', true);
            }

            // Sync selectAll checkbox state
            if (total > 0 && selected === total) {
                $('#selectAllStudents').prop('checked', true).prop('indeterminate', false);
            } else if (selected > 0 && selected < total) {
                $('#selectAllStudents').prop('checked', false).prop('indeterminate', true);
            } else {
                $('#selectAllStudents').prop('checked', false).prop('indeterminate', false);
            }

            // Highlight selected rows
            $('.student-cb').each(function() {
                var row = $(this).closest('tr');
                if ($(this).is(':checked')) {
                    row.addClass('row-selected');
                } else {
                    row.removeClass('row-selected');
                }
            });
        }

        $(document).on('change', '#selectAllStudents', function() {
            var isChecked = $(this).is(':checked');
            $('.student-cb').prop('checked', isChecked);
            updatePushCount();
        });

        $(document).on('click', 'th:has(#selectAllStudents)', function(e) {
            if (e.target.id !== 'selectAllStudents') {
                var cb = $('#selectAllStudents');
                cb.prop('checked', !cb.is(':checked')).trigger('change');
            }
        });

        $(document).on('change', '.student-cb', function() {
            updatePushCount();
        });

        // Initialize state on page load
        updatePushCount();

        $('#btnPushSubmit').on('click', function(e) {
            e.preventDefault();
            var selected = $('.student-cb:checked').length;
            if (selected === 0) {
                alert('Please select at least one student to push this opening.');
                return;
            }
            if (confirm('Push this opening to ' + selected + ' selected student(s) LMS portal?')) {
                $('#pushForm').submit();
            }
        });
    });

    function toggleStudentRow(row, event) {
        if (event && (event.target.tagName === 'INPUT' || event.target.closest('input') || event.target.tagName === 'A' || event.target.closest('a') || event.target.tagName === 'BUTTON' || event.target.closest('button'))) {
            return;
        }
        var cb = $(row).find('.student-cb');
        if (cb.length) {
            cb.prop('checked', !cb.is(':checked')).trigger('change');
        }
    }
</script>
@endpush
