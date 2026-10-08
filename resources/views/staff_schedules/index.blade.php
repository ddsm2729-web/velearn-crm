@extends('layouts.master')

@section('pageTitle', 'Staff Schedule Management')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/staff_schedules-index.css') }}">
@endpush

@section('content')
<div class="container-fluid p-4">

    {{-- ── Header Banner ─────────────────────────────── --}}
    <div class="schedule-header d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1 class="text-white"><i class="fas fa-calendar-alt me-2" style="opacity:.85;"></i> Staff Schedule Management</h1>
            <p>Manage availability slots for Admission Officers &amp; Technical Staff</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if ($selectedStaffId && $totalSlots > 0)
                <span class="stat-pill total"><i class="fas fa-th-large me-1"></i> {{ $totalSlots }} Total</span>
                <span class="stat-pill available"><i class="fas fa-check-circle me-1"></i> {{ $availableSlots }} Available</span>
                <span class="stat-pill busy"><i class="fas fa-clock me-1"></i> {{ $busySlots }} Busy</span>
                <span class="stat-pill off"><i class="fas fa-times-circle me-1"></i> {{ $offSlots }} Off</span>
            @endif
        </div>
    </div>

    {{-- ── Filter Card ─────────────────────────────────── --}}
    <div class="filter-card d-flex flex-wrap align-items-end gap-3">
        <form action="{{ route('staff.schedule.index') }}" method="GET" class="d-flex flex-wrap gap-3 align-items-end">
            <div style="width:300px;">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Staff Member</label>
                @can('Schedule Create')
                    <select name="staff_id" class="form-select rounded-3 select2" onchange="this.form.submit()">
                        <option value="">— Choose Staff —</option>
                        @foreach ($staffs as $roleName => $roleStaffs)
                            <optgroup label="{{ $roleName }}">
                                @foreach ($roleStaffs as $staff)
                                    <option value="{{ $staff->id }}" {{ $selectedStaffId == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->first_name }} {{ $staff->last_name }}
                                        @if (in_array($staff->id, $assignedStaffIds)) ✓ @endif
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                @else
                    <input type="text" class="form-control rounded-3 bg-light" value="{{ auth()->user()->name }}" readonly>
                    <input type="hidden" name="staff_id" value="{{ auth()->id() }}">
                @endcan
            </div>
        </form>

        {{-- Action Buttons --}}
        <div class="d-flex flex-wrap gap-2">
            @can('Schedule Create')
                @if ($selectedStaffId && $totalSlots > 0)
                    <button class="btn btn-outline-primary rounded-pill px-4" onclick="editSchedule({{ $selectedStaffId }})">
                        <i class="fas fa-edit me-2"></i> Edit Schedule
                    </button>
                @endif
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#generateModal" id="openGenerateBtn">
                    <i class="fas fa-magic me-2"></i> Generate Schedule
                </button>
            @endcan
            @can('Schedule Delete')
                @if ($selectedStaffId && $totalSlots > 0)
                    <button class="btn btn-outline-danger rounded-pill px-4" onclick="confirmClearAll({{ $selectedStaffId }})">
                        <i class="fas fa-trash-alt me-2"></i> Clear All
                    </button>
                @endif
            @endcan
        </div>
    </div>

    {{-- ── Legend --}}
    @if ($selectedStaffId && $totalSlots > 0)
    <div class="d-flex align-items-center gap-3 mb-3 ms-1">
        <span class="small text-muted fw-semibold">Legend:</span>
        <span class="small"><span class="legend-dot available"></span>Available</span>
        <span class="small"><span class="legend-dot busy"></span>Busy</span>
        <span class="small"><span class="legend-dot off"></span>Off</span>
    </div>
    @endif

    {{-- ── Schedule Grid ─────────────────────────────── --}}
    @if ($selectedStaffId)
        @if ($schedules->count())
            <div class="row g-3">
                @foreach($schedules as $day => $slots)
                    @php $dayClass = 'day-' . strtolower($day); @endphp
                    <div class="col-md-4 col-12">
                        <div class="day-card {{ $dayClass }}">
                            <div class="day-card-header">
                                <span class="day-title">
                                    {{ $day }}
                                </span>
                                <span class="day-count">{{ $slots->count() }} slots</span>
                            </div>
                            <div class="slots-scroll">
                                @foreach ($slots as $slot)
                                    <div class="slot-item status-{{ $slot->status }}" id="slot-{{ $slot->id }}">
                                        <span class="slot-time">
                                            {{ date('h:i A', strtotime($slot->schedule_time)) }}
                                        </span>

                                        @can('Schedule Edit')
                                            <select class="slot-status-select" onchange="updateStatus({{ $slot->id }}, this.value)">
                                                <option value="available" {{ $slot->status === 'available' ? 'selected' : '' }}>✓ Available</option>
                                                <option value="busy"      {{ $slot->status === 'busy'      ? 'selected' : '' }}>⏳ Busy</option>
                                                <option value="off"       {{ $slot->status === 'off'       ? 'selected' : '' }}>✗ Off</option>
                                            </select>
                                        @else
                                            <span class="small fw-bold text-uppercase" style="flex:1;">{{ $slot->status }}</span>
                                        @endcan

                                        @can('Schedule Delete')
                                            <button class="slot-delete-btn" onclick="deleteSlot({{ $slot->id }})" title="Remove slot">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endcan
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="icon"><i class="fas fa-calendar-times"></i></div>
                <h5>No Schedule Generated Yet</h5>
                <p>Click <strong>Generate Schedule</strong> to set up availability slots for this staff member.</p>
                @can('Schedule Create')
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn-primary rounded-pill px-5" data-bs-toggle="modal" data-bs-target="#generateModal" id="openGenerateBtn2">
                            <i class="fas fa-magic me-2"></i> Generate Now
                        </button>
                    </div>
                @endcan
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="icon"><i class="fas fa-user-clock"></i></div>
            <h5>Select a Staff Member</h5>
            <p>Choose a staff member from the dropdown above to view or manage their schedule.</p>
        </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════
     Generate / Edit Schedule Modal
═══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="generateModal" tabindex="-1" aria-labelledby="generateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="fw-bold mb-0" id="generateModalLabel">
                    <i class="fas fa-magic me-2 text-primary"></i>
                    <span id="modalTitleText">Generate Recurring Schedule</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="scheduleForm" action="{{ route('staff.schedule.generate') }}" method="POST">
                @csrf
                <div class="modal-body p-4">

                    {{-- Step 1: Staff --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted mb-2">
                            <span class="badge bg-primary me-2">1</span> Staff Member
                        </label>
                        <select name="staff_id" id="modal_staff_id" class="form-select rounded-3 select2" required>
                            <option value="" disabled selected>— Choose Staff —</option>
                            @foreach ($staffs as $roleName => $roleStaffs)
                                <optgroup label="{{ $roleName }}">
                                    @foreach ($roleStaffs as $staff)
                                        <option value="{{ $staff->id }}" {{ in_array($staff->id, $assignedStaffIds) ? 'data-assigned=true' : '' }}>
                                            {{ $staff->first_name }} {{ $staff->last_name }}
                                            @if (in_array($staff->id, $assignedStaffIds)) (Has Schedule) @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    {{-- Step 2: Days --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted mb-2">
                            <span class="badge bg-primary me-2">2</span> Working Days
                        </label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <label class="day-chip {{ in_array($day, ['Monday','Tuesday','Wednesday','Thursday','Friday']) ? 'selected' : '' }}" id="chip_{{ $day }}">
                                    <input type="checkbox" name="days[]" value="{{ $day }}"
                                        {{ in_array($day, ['Monday','Tuesday','Wednesday','Thursday','Friday']) ? 'checked' : '' }}
                                        onchange="toggleChip(this, 'chip_{{ $day }}')">
                                    {{ substr($day, 0, 3) }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Step 3: Time Slots --}}
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-uppercase text-muted mb-2">
                            <span class="badge bg-primary me-2">3</span> Time Slots
                            <span class="ms-2 text-muted fw-normal" style="text-transform:none;">(30 min intervals)</span>
                        </label>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Click to toggle slots</small>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="selectAllTimes(true)">All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="selectAllTimes(false)">None</button>
                            </div>
                        </div>
                        <div class="row g-2 p-3 bg-light rounded-3" style="max-height:220px; overflow-y:auto;">
                            @php
                                $cur = Carbon\Carbon::parse('09:00');
                                $end = Carbon\Carbon::parse('18:00');
                            @endphp
                            @while ($cur <= $end)
                                @php $tid = 'time_' . $cur->format('Hi'); @endphp
                                <div class="col-xl-2 col-md-3 col-4">
                                    <label class="time-chip w-100 selected" id="{{ $tid }}_chip">
                                        <input type="checkbox" name="times[]"
                                            value="{{ $cur->format('H:i:s') }}"
                                            id="{{ $tid }}"
                                            checked
                                            onchange="toggleChip(this, '{{ $tid }}_chip')">
                                        {{ $cur->format('h:i A') }}
                                    </label>
                                </div>
                                @php $cur->addMinutes(30); @endphp
                            @endwhile
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="scheduleForm" class="btn btn-primary rounded-pill px-5 fw-bold">
                    <i class="fas fa-save me-2"></i> Save Schedule
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden delete forms --}}
<form id="deleteSlotForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
<form id="clearAllForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        // Fix Select2 inside Bootstrap Modal
        $('#modal_staff_id').select2({
            dropdownParent: $('#generateModal'),
            width: '100%',
            placeholder: '— Choose Staff —'
        });
    });

    /* ── Chip toggle ─────────────────────────────── */
    function toggleChip(checkbox, chipId) {
        const chip = document.getElementById(chipId);
        if (!chip) return;
        chip.classList.toggle('selected', checkbox.checked);
    }

    function selectAllTimes(checked) {
        document.querySelectorAll('input[name="times[]"]').forEach(function (cb) {
            cb.checked = checked;
            const chipId = cb.id + '_chip';
            const chip   = document.getElementById(chipId);
            if (chip) chip.classList.toggle('selected', checked);
        });
    }

    /* ── Edit Schedule (prefill modal) ──────────── */
    function editSchedule(staffId) {
        document.getElementById('modalTitleText').textContent = 'Edit Master Schedule';
        document.getElementById('modal_staff_id').value = staffId;
        $('#modal_staff_id').trigger('change');

        $.get("{{ url('staff-schedule/fetch') }}/" + staffId, function (res) {
            if (!res.status) return;

            // Reset all
            document.querySelectorAll('input[name="days[]"]').forEach(cb => {
                cb.checked = false;
                toggleChip(cb, 'chip_' + cb.value);
            });
            document.querySelectorAll('input[name="times[]"]').forEach(cb => {
                cb.checked = false;
                const chipId = cb.id + '_chip';
                const chip   = document.getElementById(chipId);
                if (chip) chip.classList.remove('selected');
            });

            // Re-check from server
            res.days.forEach(day => {
                const cb = document.querySelector(`input[name="days[]"][value="${day}"]`);
                if (cb) { cb.checked = true; toggleChip(cb, 'chip_' + day); }
            });
            res.times.forEach(time => {
                const clean  = time.replace(/:/g, '').substring(0, 4);
                const chipId = 'time_' + clean + '_chip';
                const cb     = document.getElementById('time_' + clean);
                if (cb) { cb.checked = true; const chip = document.getElementById(chipId); if (chip) chip.classList.add('selected'); }
            });

            new bootstrap.Modal(document.getElementById('generateModal')).show();
        });
    }

    /* ── Reset modal on Generate button ─────────── */
    function resetGenerateModal() {
        document.getElementById('modalTitleText').textContent = 'Generate Recurring Schedule';
        document.getElementById('scheduleForm').reset();
        
        // Use the selected staff ID if available, otherwise clear
        const currentStaffId = document.querySelector('select[name="staff_id"]:not(#modal_staff_id)').value;
        if (currentStaffId) {
            document.getElementById('modal_staff_id').value = currentStaffId;
            $('#modal_staff_id').trigger('change');
        } else {
            document.getElementById('modal_staff_id').value = '';
            $('#modal_staff_id').trigger('change');
        }

        // Default: weekdays checked, all times checked
        document.querySelectorAll('input[name="days[]"]').forEach(cb => {
            cb.checked = ['Monday','Tuesday','Wednesday','Thursday','Friday'].includes(cb.value);
            toggleChip(cb, 'chip_' + cb.value);
        });
        selectAllTimes(true);
    }

    document.getElementById('openGenerateBtn')?.addEventListener('click', resetGenerateModal);
    document.getElementById('openGenerateBtn2')?.addEventListener('click', resetGenerateModal);

    /* ── Update Status ───────────────────────────── */
    function updateStatus(id, status) {
        const slotEl = document.getElementById('slot-' + id);
        $.post("{{ route('staff.schedule.updateStatus') }}", {
            _token: "{{ csrf_token() }}",
            id: id,
            status: status
        }, function (res) {
            if (res.status) {
                toastr.success(res.message);
                // Update class
                slotEl.classList.remove('status-available', 'status-busy', 'status-off');
                slotEl.classList.add('status-' + status);
            }
        }).fail(function () {
            toastr.error('Failed to update status.');
        });
    }

    /* ── Delete single slot ──────────────────────── */
    function deleteSlot(id) {
        if (!confirm('Remove this time slot?')) return;
        const form = document.getElementById('deleteSlotForm');
        form.action = '{{ route("staff.schedule.destroy", "__ID__") }}'.replace('__ID__', id);
        form.submit();
    }

    /* ── Clear all for a staff member ───────────── */
    function confirmClearAll(staffId) {
        if (!confirm('Are you sure you want to remove ALL schedule slots for this staff member? This cannot be undone.')) return;
        const form = document.getElementById('clearAllForm');
        form.action = '{{ route("staff.schedule.clearAll", "__ID__") }}'.replace('__ID__', staffId);
        form.submit();
    }
</script>
@endpush
