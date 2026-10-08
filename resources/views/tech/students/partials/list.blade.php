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

        $attend = rand(60, 95); // Placeholder for attendance logic
        $progColor = '#10b981'; // green > 70
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
            <div class="attend-progress-wrapper">
                <div class="attend-progress-track">
                    <div class="attend-progress-fill"
                        style="width: {{ $attend }}%; background: {{ $progColor }};"></div>
                </div>
                <span class="attend-val" style="color: {{ $progColor }};">{{ $attend }}%</span>
            </div>
        </td>
        @php
            $rawStatus = strtolower($admission->status);
            if (in_array($rawStatus, ['active', 'ongoing'])) {
                $displayStatus = 'Active';
                $statusClass = 'active';
            } elseif ($rawStatus == 'pending_fee') {
                $displayStatus = 'Pending';
                $statusClass = 'pending_fee';
            } elseif ($rawStatus == 'completed') {
                $displayStatus = 'Completed';
                $statusClass = 'completed';
            } else {
                $displayStatus = 'Inactive';
                $statusClass = 'inactive';
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
