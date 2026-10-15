@extends('layouts.master')

@section('pageTitle', 'Student Management')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/tech_students.css') }}">
@endpush

@section('content')
    <div class="tech-student-wrapper">
        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">Student <span>Management</span></h1>

            <div class="header-actions">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="search-input" placeholder="Search students, batches...">
                </div>
            </div>
        </div>

        <div class="main-card">
            <div class="filter-tabs">
                <button class="filter-tab active filter-btn" data-filter="all">
                    All Students
                </button>
                @foreach($courses as $course)
                    <button class="filter-tab filter-btn" data-filter="course-{{ $course->id }}">
                        {{ $course->title }}
                    </button>
                @endforeach
            </div>

            <div class="table-responsive-custom">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>STUDENT</th>
                            <th>BATCH CODE</th>
                            <th>COURSE</th>
                            <th>JOINED</th>
                            <th>ATTEND.</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="student-table-body">
                        @include('tech.students.partials.list')
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ATTENDANCE DETAILS MODAL -->
    <div class="modal fade" id="attendanceDetailsModal" tabindex="-1" aria-labelledby="attendanceDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-bottom py-3 px-4 bg-light">
                    <div class="d-flex align-items-center gap-3">
                        <div id="modal-student-avatar" class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle shadow-sm" style="width: 44px; height: 44px; font-size: 1rem; background: #6366f1;">
                            SN
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0 text-dark" id="modal-student-name">Student Attendance Details</h5>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" id="modal-batch-code">FSD001</span>
                                <span class="text-muted small" id="modal-course-title">Full Stack Web Development</span>
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 ms-1" id="modal-fee-status">Fee: Paid</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <!-- Loading State -->
                    <div id="attendance-modal-loader" class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <div class="text-muted small">Loading dynamic attendance records...</div>
                    </div>

                    <!-- Modal Content Container -->
                    <div id="attendance-modal-content" style="display: none;">
                        <!-- Metrics Row -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded-3 text-center border">
                                    <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Attendance</div>
                                    <div class="fs-4 fw-bold mt-1" id="modal-attend-rate" style="color: #10b981;">0%</div>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar" id="modal-attend-bar" style="width: 0%; background: #10b981;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded-3 text-center border">
                                    <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Classes Held</div>
                                    <div class="fs-4 fw-bold mt-1 text-primary" id="modal-classes-held">0</div>
                                    <div class="text-muted small" style="font-size: 0.72rem;">Scheduled sessions</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded-3 text-center border">
                                    <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Attended</div>
                                    <div class="fs-4 fw-bold mt-1 text-success" id="modal-present-count">0</div>
                                    <div class="text-muted small" style="font-size: 0.72rem;">Full & half sessions</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded-3 text-center border">
                                    <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Missed / Leave</div>
                                    <div class="fs-4 fw-bold mt-1 text-danger" id="modal-missed-count">0</div>
                                    <div class="text-muted small" style="font-size: 0.72rem;">Absent sessions</div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Month Calendar -->
                        <div class="card border rounded-3 p-3 mb-4 bg-white shadow-sm" id="modal-calendar-card">
                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                <div class="fw-bold text-dark d-flex align-items-center" style="font-size: 0.85rem;">
                                    <i class="far fa-calendar-alt text-primary me-2"></i> <span id="modal-calendar-month-name">October 2026</span> Attendance Calendar
                                </div>
                                <div class="d-flex align-items-center gap-2 small flex-wrap" style="font-size: 0.72rem;">
                                    <span class="d-flex align-items-center gap-1"><span class="badge bg-success p-1 rounded-circle" style="width: 8px; height: 8px;"></span> Present</span>
                                    <span class="d-flex align-items-center gap-1"><span class="badge bg-warning p-1 rounded-circle" style="width: 8px; height: 8px;"></span> Half Day</span>
                                    <span class="d-flex align-items-center gap-1"><span class="badge bg-danger p-1 rounded-circle" style="width: 8px; height: 8px;"></span> Leave</span>
                                    <span class="d-flex align-items-center gap-1"><span class="badge bg-light border p-1 rounded-circle" style="width: 8px; height: 8px;"></span> No Session</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1" id="modal-calendar-days-container">
                                <!-- Populated dynamically -->
                            </div>
                        </div>

                        <!-- Attendance Table -->
                        <h6 class="fw-bold mb-2 text-dark d-flex align-items-center">
                            <i class="fas fa-calendar-check text-primary me-2"></i> Session Attendance History
                        </h6>
                        <div class="table-responsive border rounded-3 overflow-hidden mb-0">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                <thead class="bg-light text-muted" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em;">
                                    <tr>
                                        <th class="ps-3 py-2">Date</th>
                                        <th class="py-2">Session Schedule</th>
                                        <th class="py-2 text-center">Class Duration</th>
                                        <th class="py-2 text-center">Attended Time</th>
                                        <th class="py-2 text-center">Attendance %</th>
                                        <th class="py-2 text-end pe-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-attendance-table-body">
                                    <!-- Populated dynamically -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div id="modal-empty-attendance" class="text-center py-4 border rounded-3 bg-light" style="display: none;">
                            <i class="fas fa-calendar-times text-muted fs-3 mb-2"></i>
                            <p class="text-muted small mb-0">No live class attendance recorded for this batch yet.</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="modal-full-profile-link" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                        View Full Student Profile <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Reapply frontend filters after ajax
            function applyCurrentFilter() {
                const activeBtn = document.querySelector('.filter-tab.active');
                if (activeBtn) {
                    const filter = activeBtn.getAttribute('data-filter');
                    filterTable(filter);
                }
            }

            // Frontend course filtering
            function filterTable(filter) {
                const studentRows = document.querySelectorAll('.student-row');
                let visibleCount = 0;
                studentRows.forEach(row => {
                    if (filter === 'all' || row.classList.contains(filter)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const emptyRow = document.getElementById('empty-filter-msg');
                if (emptyRow) {
                    if (visibleCount === 0 && studentRows.length > 0) {
                        emptyRow.style.display = '';
                    } else {
                        emptyRow.style.display = 'none';
                    }
                }
            }

            // Click listener for filter tabs
            const filterBtns = document.querySelectorAll('.filter-btn');
            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    filterTable(btn.getAttribute('data-filter'));
                });
            });

            // AJAX Search
            const searchInput = document.getElementById('search-input');
            const tableBody = document.getElementById('student-table-body');
            let timeout = null;

            searchInput.addEventListener('keyup', function(e) {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const searchVal = e.target.value;
                    
                    fetch('{{ route("tech.students.index") }}?search=' + encodeURIComponent(searchVal), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableBody.innerHTML = html;
                        applyCurrentFilter(); // Re-apply course filter to new rows
                    });
                }, 400); // 400ms debounce
            });

        });

        // Global Modal Function for Attendance Details
        window.showAttendanceDetails = function(admissionId) {
            const modalEl = document.getElementById('attendanceDetailsModal');
            if (!modalEl) return;
            
            const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            
            const loader = document.getElementById('attendance-modal-loader');
            const content = document.getElementById('attendance-modal-content');
            loader.style.display = 'block';
            content.style.display = 'none';
            
            bsModal.show();
            
            fetch('/tech/students/' + admissionId + '/attendance-details', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.status) throw new Error('Failed to load data');
                
                const s = data.student;
                const att = data.attendance;
                
                document.getElementById('modal-student-name').textContent = s.name;
                document.getElementById('modal-batch-code').textContent = s.batch_code || 'Unassigned';
                document.getElementById('modal-course-title').textContent = s.course;
                document.getElementById('modal-student-avatar').textContent = (s.name.substring(0, 2)).toUpperCase();
                document.getElementById('modal-full-profile-link').href = s.profile_url;
                
                // Fee Status
                const feeBadge = document.getElementById('modal-fee-status');
                if (feeBadge && s.fee) {
                    feeBadge.textContent = 'Fee: ' + s.fee.status;
                    let feeColor = s.fee.status === 'Paid' ? 'success' : (s.fee.status === 'Partial' ? 'warning' : 'danger');
                    feeBadge.className = `badge rounded-pill bg-${feeColor} bg-opacity-10 text-${feeColor} border border-${feeColor} border-opacity-25 ms-1`;
                }

                // Metrics
                const rate = att.percentage;
                const rateColor = rate >= 70 ? '#10b981' : (rate >= 50 ? '#f59e0b' : (rate > 0 ? '#ef4444' : '#64748b'));
                const rateEl = document.getElementById('modal-attend-rate');
                rateEl.textContent = rate + '%';
                rateEl.style.color = rateColor;
                
                const barEl = document.getElementById('modal-attend-bar');
                barEl.style.width = rate + '%';
                barEl.style.backgroundColor = rateColor;
                
                document.getElementById('modal-classes-held').textContent = att.total_classes;
                document.getElementById('modal-present-count').textContent = att.attended_classes;
                document.getElementById('modal-missed-count').textContent = att.leave_count;
                
                // Monthly Calendar Days
                const calContainer = document.getElementById('modal-calendar-days-container');
                const calMonthName = document.getElementById('modal-calendar-month-name');
                const calCard = document.getElementById('modal-calendar-card');
                calContainer.innerHTML = '';

                if (att.calendar && att.calendar.days && att.calendar.days.length > 0) {
                    calCard.style.display = 'block';
                    calMonthName.textContent = att.calendar.month_name || 'Current Month';
                    
                    att.calendar.days.forEach(cDay => {
                        const dayDiv = document.createElement('div');
                        let bg = '#ffffff';
                        let textColor = '#94a3b8';
                        let border = '#e2e8f0';
                        let title = cDay.date + ': No Class Scheduled';
                        let iconHtml = '<span style="font-size: 0.6rem; color: #94a3b8;">•</span>';

                        if (cDay.status === 'Present') {
                            bg = '#ecfdf5';
                            textColor = '#059669';
                            border = '#a7f3d0';
                            title = cDay.date + ': Present (' + Math.round(cDay.percentage) + '%)';
                            iconHtml = '<i class="fas fa-check" style="font-size: 0.6rem; color: #059669;"></i>';
                        } else if (cDay.status === 'Half Day') {
                            bg = '#fffbeb';
                            textColor = '#d97706';
                            border = '#fde68a';
                            title = cDay.date + ': Half Day (' + Math.round(cDay.percentage) + '%)';
                            iconHtml = '<i class="fas fa-adjust" style="font-size: 0.6rem; color: #d97706;"></i>';
                        } else if (cDay.status === 'Leave') {
                            bg = '#fef2f2';
                            textColor = '#dc2626';
                            border = '#fecaca';
                            title = cDay.date + ': Leave / Missed (' + Math.round(cDay.percentage) + '%)';
                            iconHtml = '<i class="fas fa-times" style="font-size: 0.6rem; color: #dc2626;"></i>';
                        } else if (cDay.status === 'Future') {
                            bg = '#f8fafc';
                            textColor = '#cbd5e1';
                            border = '#f1f5f9';
                            title = cDay.date + ': Upcoming';
                        }

                        dayDiv.className = 'text-center rounded-2 p-1';
                        dayDiv.title = title;
                        dayDiv.style.cssText = `flex: 1 0 32px; min-width: 32px; height: 44px; background: ${bg}; border: 1px solid ${border}; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.15s;`;
                        dayDiv.innerHTML = `
                            <div style="font-size: 0.7rem; font-weight: 700; color: ${textColor};">${cDay.day}</div>
                            ${iconHtml}
                        `;
                        calContainer.appendChild(dayDiv);
                    });
                } else {
                    calCard.style.display = 'none';
                }

                // Records
                const tbody = document.getElementById('modal-attendance-table-body');
                const emptyState = document.getElementById('modal-empty-attendance');
                tbody.innerHTML = '';
                
                if (att.records && att.records.length > 0) {
                    emptyState.style.display = 'none';
                    tbody.closest('.table-responsive').style.display = '';
                    
                    att.records.forEach(r => {
                        let badgeClass = 'success';
                        let icon = 'check-circle';
                        if (r.status === 'Half Day') {
                            badgeClass = 'warning';
                            icon = 'adjust';
                        } else if (r.status === 'Leave' || r.percentage < 45) {
                            badgeClass = 'danger';
                            icon = 'times-circle';
                        }
                        
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="ps-3 fw-bold text-dark"><i class="far fa-calendar-alt text-muted me-1"></i> ${r.date}</td>
                            <td>
                                <div class="fw-semibold text-dark">${r.schedule}</div>
                                ${r.remarks ? `<div class="text-muted small" style="font-size:0.75rem;">${r.remarks}</div>` : ''}
                            </td>
                            <td class="text-center text-muted">${r.duration_minutes > 0 ? r.duration_minutes + ' mins' : '—'}</td>
                            <td class="text-center fw-semibold text-dark">${r.attended_minutes > 0 ? r.attended_minutes + ' mins' : '0 mins'}</td>
                            <td class="text-center fw-bold" style="color: ${r.percentage >= 70 ? '#10b981' : (r.percentage >= 45 ? '#f59e0b' : '#ef4444')}">${r.percentage}%</td>
                            <td class="text-end pe-3">
                                <span class="badge bg-${badgeClass} bg-opacity-10 text-${badgeClass} border border-${badgeClass} border-opacity-25 px-2 py-1 rounded-pill">
                                    <i class="fas fa-${icon} me-1"></i> ${r.status}
                                </span>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    emptyState.style.display = 'block';
                    tbody.closest('.table-responsive').style.display = 'none';
                }
                
                loader.style.display = 'none';
                content.style.display = 'block';
            })
            .catch(err => {
                console.error(err);
                loader.innerHTML = '<div class="text-danger py-4"><i class="fas fa-exclamation-triangle fs-3 mb-2"></i><p class="mb-0">Failed to load attendance records.</p></div>';
            });
        };
    </script>
@endpush