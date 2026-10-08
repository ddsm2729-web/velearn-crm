@extends('layouts.header')

@section('pageTitle', 'Demo Tracking')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calls-demo_tracking.css') }}">
@endpush

@section('section')
    <div class="tracking-page p-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Demo Tracking</h3>
                <p class="text-muted small">Track all demos and their outcomes</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="live-indicator">
                    <span class="live-dot"></span>
                    Live
                </div>
                @can('Schedule Demo')
                    <a href="{{ route('schedules.index') }}" class="btn btn-primary rounded-pill px-4 fw-bold">Schedule Demo</a>
                @endcan
            </div>
        </div>

        <div class="filter-tabs">
            @php
                $statuses = ['All', 'Demo booked', 'Demo Attended', 'Demo Missed', 'Converted', 'Lost'];
                $currentStatus = request('status', 'All');
            @endphp
            @foreach($statuses as $status)
                <button class="filter-tab {{ $currentStatus == $status ? 'active' : '' }}"
                    onclick="window.location.href='?status={{ $status }}'">
                    {{ ucwords($status) }}
                </button>
            @endforeach
        </div>

        <div class="tracking-table shadow-lg">
            <div class="table-responsive" style="min-height: 350px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Date</th>
                            <th>Slot</th>
                            <th>Staff</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demos as $demo)
                            <tr class="table-row-hover">
                                <td>
                                    @php
                                        $person = $demo->contact ?? $demo->lead;
                                        $name = $person ? $person->name : 'Unknown';
                                        $phone = $person ? ($person->caller_number ?? $person->phone) : 'Unknown';
                                        $type = $demo->contact ? 'CONTACT' : ($demo->lead ? 'LEAD' : '');
                                    @endphp
                                    <div class="fw-bold text-dark">
                                        {{ $name }}
                                        @if($type)
                                            <span class="badge bg-secondary ms-1" style="font-size:0.6rem;">{{ $type }}</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted">{{ $phone }}</div>
                                </td>
                                <td>{{ date('d M', strtotime($demo->demo_date)) }}</td>
                                <td>
                                    <div class="text-primary fw-bold" style="font-size: 0.9rem;">
                                        {{ date('h:i A', strtotime($demo->demo_time)) }}
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="staff-badge text-muted">{{ $demo->assignedStaff ? $demo->assignedStaff->first_name . ' ' . $demo->assignedStaff->last_name : 'Unassigned' }}</span>
                                </td>
                                <td>
                                    @php
                                        $status = $demo->lead_tag ?? ($demo->demo_status ?? ($demo->direction ?? 'Call'));
                                        $statusClass = 'status-' . strtolower(str_replace(['Demo ', ' '], ['', '-'], $status));
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ strtoupper($status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($demo->zoom_meet_link)
                                            <a href="{{ $demo->zoom_meet_link }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary rounded-pill fw-bold"
                                                style="font-size:0.75rem;">
                                                <i class="fas fa-video me-1"></i>Join
                                            </a>
                                        @endif
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light rounded-circle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                                                style="font-size: 0.85rem;">
                                                <li>
                                                    <h6 class="dropdown-header">Update Status</h6>
                                                </li>
                                                <li><a class="dropdown-item fw-bold text-success" href="#"
                                                        onclick="updateDemoStatus({{ $demo->id }}, 'Demo Attended', event)"><i
                                                            class="fas fa-check-circle me-2"></i>Attended</a></li>
                                                <li><a class="dropdown-item fw-bold text-danger" href="#"
                                                        onclick="updateDemoStatus({{ $demo->id }}, 'Demo Missed', event)"><i
                                                            class="fas fa-times-circle me-2"></i>Missed</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item fw-bold text-primary" href="#"
                                                        onclick="updateDemoStatus({{ $demo->id }}, 'Converted', event)"><i
                                                            class="fas fa-trophy me-2"></i>Converted</a></li>
                                                <li><a class="dropdown-item fw-bold text-secondary" href="#"
                                                        onclick="updateDemoStatus({{ $demo->id }}, 'Lost', event)"><i
                                                            class="fas fa-ban me-2"></i>Lost</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No demos found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $demos->links() }}
        </div>
    </div>

    <!-- Status Confirmation Modal -->
    <div class="modal fade" id="confirmStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Confirm Status Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4 text-center">
                    <div class="text-warning mb-3">
                        <i class="fas fa-exclamation-circle fa-3x"></i>
                    </div>
                    <h6 id="confirmStatusText" class="mb-0 text-dark">Are you sure you want to update the status?</h6>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" id="confirmStatusBtn">Confirm
                        Update</button>
                </div>
            </div>
        </div>
    </div>


    @push('script')
        <script>
            function updateDemoStatus(id, status, event) {
                event.preventDefault();

                // Set modal data
                var modalEl = document.getElementById('confirmStatusModal');
                var confirmModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                confirmModal.show();
                $('#confirmStatusText').text(`Are you sure you want to update the status to "${status}"?`);

                // Remove previous event listeners
                $('#confirmStatusBtn').off('click').on('click', function () {
                    // Disable button to prevent multiple clicks
                    const btn = $(this);
                    const originalText = btn.html();
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');

                    $.ajax({
                        url: "{{ route('demo.tracking.status', ['id' => ':id']) }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: status
                        },
                        success: function (res) {
                            if (res.status) {
                                var confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmStatusModal'));
                                if (confirmModal) {
                                    confirmModal.hide();
                                }
                                toastr.success(res.message);
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        },
                        error: function () {
                            $('#confirmStatusBtn').prop('disabled', false).html('Confirm Update');
                            toastr.error('Something went wrong!');
                        }
                    });
                });
            }
        </script>
    @endpush
@endsection