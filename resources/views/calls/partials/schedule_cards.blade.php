@forelse($calls as $call)
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="schedule-card p-3 cursor-pointer" data-url="{{ route('calls.profile', $call->id) }}">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="card-date-badge">
                    <div class="small text-muted text-uppercase" style="font-size: 10px;">{{ $call->demo_date->format('M') }}</div>
                    <div class="fw-bold h4 mb-0">{{ $call->demo_date->format('d') }}</div>
                </div>
                <div class="time-badge">
                    <i class="far fa-clock me-1"></i> {{ $call->demo_time ? date('h:i A', strtotime($call->demo_time)) : 'TBD' }}
                </div>
            </div>

            <h5 class="fw-bold mb-1 text-truncate">{{ $call->contact->name ?? 'Unknown' }}</h5>
            <div class="text-muted small mb-3">
                <i class="fas fa-phone-alt me-1"></i> {{ $call->caller_number }}
            </div>

            <div class="p-2 bg-light rounded-3 mb-3 small text-truncate-2" style="height: 40px;">
                {{ $call->followup_note ?? 'No specific demo instructions.' }}
            </div>

            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                <div class="d-flex align-items-center">
                    <div class="staff-avatar me-2">
                        {{ strtoupper(substr($call->assignedStaff->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="small fw-bold text-muted">{{ $call->assignedStaff->name ?? 'Unassigned' }}</div>
                </div>
                <span class="badge bg-soft-primary text-primary small">Demo</span>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="text-center p-5 bg-white rounded-3 shadow-sm border border-dashed" style="border-width: 2px;">
            <div class="mb-4">
                <i class="fas fa-calendar-times fa-4x text-light opacity-50"></i>
            </div>
            <h4 class="fw-bold text-muted">No Scheduled Demos</h4>
            <p class="text-secondary mb-0">There are no upcoming demos matching your current selection.</p>
        </div>
    </div>
@endforelse
