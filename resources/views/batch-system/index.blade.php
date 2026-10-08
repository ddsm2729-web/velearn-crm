@extends('layouts.master')

@section('pageTitle', 'Batch System')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/tech_batch_system.css') }}">
@endpush

@section('content')
    <div class="tech-batch-wrapper" style="background: transparent;">
        <!-- Header -->
        <div class="mockup-page-header">
            <h1 class="mockup-title">Batch <span>System</span></h1>

            <div class="mockup-header-right">
                <div class="mockup-search-container">
                    <i class="fas fa-search mockup-search-icon"></i>
                    <input type="text" id="searchInput" class="mockup-search-input"
                        placeholder="Search students, batches...">
                </div>

                @can('Batch Create')
                    <a href="{{ route('batches.create') }}" class="mockup-btn-new">
                        <i class="fas fa-plus"></i> New Batch
                    </a>
                @endcan
            </div>
        </div>

        <div class="mockup-main-card">
            <div class="mockup-section-header">
                <h2 class="mockup-section-title">All Batches</h2>
                <span class="mockup-badge">{{ count($batches) }} Batches</span>
            </div>

            <div class="mockup-filter-tabs">
                <button class="mockup-filter-tab active filter-btn" data-filter="all">
                    <span style="color: #3b82f6; font-weight: bold;">All Courses</span>
                </button>
                @php
                    $courseIcons = [
                        'Full Stack' => '<i class="fas fa-bolt text-warning"></i>',
                        'UI/UX Design' => '<i class="fas fa-palette text-danger"></i>',
                        'Digital Marketing' => '<i class="fas fa-bullhorn text-warning"></i>',
                        'Data Analytics' => '<i class="fas fa-chart-bar text-success"></i>'
                    ];
                @endphp
                @foreach($courses as $course)
                    @php
                        $icon = '<i class="fas fa-book text-primary"></i>';
                        foreach ($courseIcons as $key => $val) {
                            if (stripos($course->title ?? $course->title ?? '', $key) !== false) {
                                $icon = $val;
                                break;
                            }
                        }
                    @endphp
                    <button class="mockup-filter-tab filter-btn" data-filter="course-{{ $course->id }}">
                        {!! $icon !!} {{ $course->title ?? $course->title }}
                    </button>
                @endforeach
                <button class="mockup-filter-tab filter-btn" data-filter="all" data-status="Completed">
                    <i class="fas fa-check-circle text-success"></i> Completed Batches
                </button>
            </div>

            <!-- Hidden filters to maintain JS functionality without breaking layout -->
            <div class="d-none">
                <select id="trackFilter" class="form-select custom-select">
                    <option value="">All Tracks</option>
                </select>
                <select id="statusFilter" class="form-select custom-select">
                    <option value="">All Status</option>
                </select>
            </div>

            <div id="batchesContainer">
                @include('batch-system.partials.list', ['batches' => $batches])
            </div>
        </div>
    </div>

    <!-- Connect Modal -->
    <div class="modal fade" id="connectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered border-0" style="max-width: 500px;">
            <div class="modal-content border-0 shadow-lg"
                style="border-radius: 20px; overflow: hidden; background: #ffffff;">

                <!-- Premium Header -->
                <div class="modal-header border-0 p-4 position-relative"
                    style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid #e2e8f0 !important;">
                    <div class="d-flex align-items-center gap-3 w-100">
                        <div class="modal-icon-container d-flex justify-content-center align-items-center flex-shrink-0"
                            style="width: 54px; height: 54px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 14px; box-shadow: 0 8px 16px rgba(37, 99, 235, 0.2);">
                            <i class="fas fa-video fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="modal-title-custom mb-1"
                                style="font-size: 1.3rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px;">Batch
                                Connect</h5>
                            <div class="modal-subtitle m-0 text-muted" style="font-size: 0.85rem; font-weight: 500;">Start a
                                group session for <span id="modalBatchIdText" class="fw-bold"
                                    style="color: #3b82f6;"></span></div>
                        </div>
                        <button type="button" class="btn-close shadow-none flex-shrink-0" data-bs-dismiss="modal"
                            aria-label="Close"
                            style="background-color: rgba(15, 23, 42, 0.05); border-radius: 50%; padding: 0.75rem; transition: background-color 0.2s;"></button>
                    </div>
                </div>

                <div class="modal-body p-4 pt-4 pb-5">
                    <div class="mb-4 position-relative">
                        <label class="form-label text-muted d-block mb-2"
                            style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">Batch
                            ID</label>
                        <div class="position-relative">
                            <i class="fas fa-hashtag position-absolute text-muted"
                                style="left: 16px; top: 50%; transform: translateY(-50%); z-index: 10;"></i>
                            <input type="text" class="form-control form-control-custom bg-white" id="modalBatchId" readonly
                                style="font-weight: 700; color: #1e293b; padding-left: 2.5rem; border: 2px solid #e2e8f0; border-radius: 12px; height: 50px;">
                        </div>
                    </div>

                    <div class="mb-4 position-relative">
                        <label class="form-label text-muted d-block mb-2"
                            style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">Session
                            Title</label>
                        <div class="position-relative">
                            <i class="fas fa-edit position-absolute text-muted"
                                style="left: 16px; top: 50%; transform: translateY(-50%); z-index: 10;"></i>
                            <input type="text" class="form-control form-control-custom bg-white" id="modalSessionTitle"
                                value=""
                                style="color: #0f172a; font-weight: 600; padding-left: 2.5rem; border: 2px solid #e2e8f0; border-radius: 12px; height: 50px; transition: all 0.3s;"
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between gap-3 mt-5">
                        <button type="button" class="btn btn-modal-cancel w-50 fw-bold" data-bs-dismiss="modal"
                            style="border-radius: 12px; background: #f8fafc; color: #64748b; border: 2px solid #e2e8f0; height: 50px; transition: all 0.2s;"
                            onmouseover="this.style.background='#f1f5f9'; this.style.color='#0f172a';"
                            onmouseout="this.style.background='#f8fafc'; this.style.color='#64748b';">Cancel</button>
                        <button type="button" id="startConnectBtn"
                            class="btn btn-primary-custom w-50 fw-bold d-flex justify-content-center align-items-center gap-2"
                            style="border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; height: 50px; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); transition: all 0.3s;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(16, 185, 129, 0.4)';"
                            onmouseout="this.style.transform='none'; this.style.boxShadow='0 8px 20px rgba(16, 185, 129, 0.3)';">
                            <i class="fas fa-video"></i> Start Connect
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('searchInput');
            const trackFilter = document.getElementById('trackFilter');
            const statusFilter = document.getElementById('statusFilter');
            const batchesContainer = document.getElementById('batchesContainer');

            let currentCourseId = 'all';
            let currentStatus = '';

            function fetchBatches() {
                const searchQuery = searchInput.value;
                const track = trackFilter.value;
                const status = currentStatus || statusFilter.value;

                // Build query params
                const params = new URLSearchParams({
                    course_id: currentCourseId,
                    search: searchQuery,
                    track: track,
                    status: status
                });

                // Show loading state (optional)
                batchesContainer.style.opacity = '0.5';

                fetch(`{{ route('batch-system.index') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                    .then(response => response.text())
                    .then(html => {
                        batchesContainer.innerHTML = html;
                        batchesContainer.style.opacity = '1';
                    })
                    .catch(error => {
                        console.error('Error fetching batches:', error);
                        batchesContainer.style.opacity = '1';
                    });
            }

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    currentCourseId = btn.getAttribute('data-filter') || 'all';
                    currentStatus = btn.getAttribute('data-status') || '';
                    fetchBatches();
                });
            });

            // Add event listeners for dynamic filters
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchBatches, 300); // debounce
            });

            trackFilter.addEventListener('change', fetchBatches);
            statusFilter.addEventListener('change', fetchBatches);
        });

        function openConnectModal(batchId, course, type, meetingLink) {
            document.getElementById('modalBatchIdText').innerText = batchId;
            document.getElementById('modalBatchId').value = batchId;
            document.getElementById('modalSessionTitle').value = `${course} — ${batchId} (${type})`;

            const connectBtn = document.getElementById('startConnectBtn');
            if (connectBtn) {
                connectBtn.onclick = function () {
                    if (meetingLink && meetingLink !== 'null' && meetingLink !== '') {
                        window.open(meetingLink, '_blank');
                    } else {
                        alert('No meeting link is currently set for this batch.');
                    }
                };
            }

            var myModal = new bootstrap.Modal(document.getElementById('connectModal'));
            myModal.show();
        }
    </script>
@endpush