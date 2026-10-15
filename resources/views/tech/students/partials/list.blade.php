@forelse($admissions as $admission)
    @php
        $name = $admission->contact->name ?? 'Unknown';
        $email = $admission->contact->email ?? $admission->contact->caller_number ?? 'No email';
        $initials = strtoupper(substr($name, 0, 2));
        $courseName = $admission->course->title ?? 'Unknown Course';
        $batchCode = $admission->batch->batch_code ?? 'Unassigned';

        $colors = ['#f59e0b', '#ec4899', '#10b981', '#3b82f6', '#8b5cf6'];
        $color = $colors[($admission->course_id ?? 0) % count($colors)];

        $badgeBg = '#ffffff';
        if ($color == '#f59e0b')
            $badgeBg = '#fff7ed'; // orange
        else if ($color == '#ec4899')
            $badgeBg = '#fdf2f8'; // pink
        else if ($color == '#10b981')
            $badgeBg = '#ecfdf5'; // green
        else
            $badgeBg = '#eff6ff'; // blue

        $attend = isset($admission->attendance_percentage) 
            ? (int) $admission->attendance_percentage 
            : (\App\Http\Controllers\StudentsManagementController::calculateStudentAttendance($admission)['percentage'] ?? 0);

        $progColor = '#10b981'; // green >= 70
        if ($attend < 50)
            $progColor = '#ef4444'; // red
        else if ($attend < 70)
            $progColor = '#f59e0b'; // orange
    @endphp
    <tr class="student-row course-{{ $admission->course_id }}" style="cursor: pointer;" onclick="window.location='{{ route('tech.students.show', $admission->id) }}'">
        <td>
            <div class="student-info">
                <div class="student-avatar" style="background: {{ $color }};">
                    {{ $initials }}
                </div>
                <div>
                    <div class="student-name">{{ $name }}</div>
                    <div class="student-email">{{ $email }}</div>
                </div>
            </div>
        </td>
        <td>
            <span class="batch-text">{{ $batchCode }}</span>
        </td>
        <td>
            <div class="course-badge" style="background: {{ $badgeBg }}; color: {{ $color }};">
                <i class="fas fa-book-open"></i> {{ $courseName }}
            </div>
        </td>
        <td>
            <span
                class="date-text">{{ $admission->created_at ? $admission->created_at->format('d M Y') : '-' }}</span>
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <div class="attend-progress-wrapper" title="{{ $attend }}% Attendance for {{ $batchCode }} ({{ $courseName }})"
                    style="cursor: pointer;" onclick="event.stopPropagation(); showAttendanceDetails({{ $admission->id }});">
                    <div class="attend-progress-track">
                        <div class="attend-progress-fill"
                            style="width: {{ $attend }}%; background: {{ $progColor }};"></div>
                    </div>
                    <span class="attend-val" style="color: {{ $attend > 0 ? $progColor : '#94a3b8' }};">{{ $attend }}%</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 rounded-pill fw-semibold border"
                    style="font-size: 0.72rem; line-height: 1.6; white-space: nowrap; background: #fff;"
                    onclick="event.stopPropagation(); showAttendanceDetails({{ $admission->id }});"
                    title="View dynamic attendance details for {{ $name }}">
                    <i class="fas fa-list-check me-1"></i> Details
                </button>
            </div>
        </td>
        @php
            $rawStatus = strtolower($admission->status);
            $feeStatus = $admission->fee_details['fee_status'] ?? 'Paid';
            
            if ($rawStatus == 'completed') {
                $displayStatus = 'Completed';
                $statusClass = 'completed';
            } elseif ($feeStatus === 'Paid' || in_array($rawStatus, ['active', 'ongoing'])) {
                $displayStatus = 'Active';
                $statusClass = 'active';
            } elseif ($feeStatus === 'Partial') {
                $displayStatus = 'Active';
                $statusClass = 'active';
            } elseif ($rawStatus == 'pending_fee' || $feeStatus === 'Pending') {
                $displayStatus = 'Pending';
                $statusClass = 'pending_fee';
            } else {
                $displayStatus = 'Active';
                $statusClass = 'active';
            }
        @endphp
        <td>
            <span
                class="status-text status-{{ $statusClass }}">{{ $displayStatus }}</span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted py-4">No students found matching your search.</td>
    </tr>
@endforelse

@if($admissions->count() > 0)
    <tr id="empty-filter-msg" style="display: none;">
        <td colspan="6" class="text-center text-muted py-4">No students found for this course filter.</td>
    </tr>
@endif
