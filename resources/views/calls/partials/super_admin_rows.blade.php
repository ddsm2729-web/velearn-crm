<!-- ── Top Metric Stat Cards Row ── -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="call-stat-card">
            <div class="stat-icon-wrapper stat-icon-indigo">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div>
                <div class="stat-label">Total Calls</div>
                <div class="stat-number">{{ $totalLeads }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="call-stat-card">
            <div class="stat-icon-wrapper stat-icon-emerald">
                <i class="fas fa-arrow-down text-emerald" style="color: #047857;"></i>
            </div>
            <div>
                <div class="stat-label">Incoming Calls</div>
                <div class="stat-number">{{ $calls->filter(fn($c) => ($c->contact->source ?? '') === 'MyOperator')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="call-stat-card">
            <div class="stat-icon-wrapper stat-icon-blue">
                <i class="fas fa-arrow-up text-primary" style="color: #1d4ed8;"></i>
            </div>
            <div>
                <div class="stat-label">Outgoing Calls</div>
                <div class="stat-number">{{ $calls->filter(fn($c) => ($c->contact->source ?? '') !== 'MyOperator')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="call-stat-card">
            <div class="stat-icon-wrapper stat-icon-amber">
                <i class="fas fa-stopwatch"></i>
            </div>
            <div>
                <div class="stat-label">Avg Duration (Page)</div>
                <div class="stat-number">
                    @php
                        $totalSeconds = 0;
                        $count = 0;
                        foreach($calls as $c) {
                            if($c->call_duration) {
                                $dur = $c->call_duration;
                                if(is_numeric($dur)) {
                                    $totalSeconds += (int)$dur;
                                    $count++;
                                } else {
                                    $parts = explode(':', $dur);
                                    if(count($parts) == 2) {
                                        $totalSeconds += ($parts[0] * 60) + $parts[1];
                                        $count++;
                                    } elseif(count($parts) == 3) {
                                        $totalSeconds += ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
                                        $count++;
                                    }
                                }
                            }
                        }
                        $avgSeconds = $count > 0 ? round($totalSeconds / $count) : 0;
                        $avgMin = floor($avgSeconds / 60);
                        $avgSec = $avgSeconds % 60;
                        $avgDisplay = $avgMin . ':' . str_pad($avgSec, 2, '0', STR_PAD_LEFT);
                    @endphp
                    {{ $avgDisplay }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Grouped Calls by Staff ── -->
@php
    $groupedCalls = $calls->groupBy('staff_id');
    $colors = ['#6366f1', '#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#14b8a6', '#f43f5e'];
@endphp

@forelse($groupedCalls as $staffId => $staffCalls)
    @php
        $staff = $staffCalls->first()->staff ?? $staffCalls->first()->assignedStaff;
        $staffName = $staff ? $staff->first_name . ' ' . $staff->last_name : 'System / Unassigned';
        $staffRole = $staff && $staff->role ? $staff->role->name : 'Staff Member';
        $initials = $staff ? substr($staff->first_name, 0, 1) . substr($staff->last_name, 0, 1) : 'S';
        $bgColor = $colors[crc32($staffName) % count($colors)];
    @endphp
    <div class="staff-group-card">
        <div class="staff-group-header">
            <div class="staff-avatar" style="background: {{ $bgColor }};">
                {{ strtoupper($initials) }}
            </div>
            <div>
                <div class="staff-name">{{ $staffName }}</div>
            </div>
            <span class="staff-role-badge me-auto">{{ $staffRole }}</span>
            <span class="staff-count-badge">
                <i class="fas fa-headset me-1" style="font-size: 10px;"></i>{{ $staffCalls->count() }} Calls Logged
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-call-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 22%;">Contact</th>
                        <th style="width: 18%;">Phone</th>
                        <th style="width: 12%;">Source</th>
                        <th style="width: 14%;">Direction</th>
                        <th style="width: 10%;">Duration</th>
                        <th style="width: 12%;">Outcome</th>
                        <th style="width: 12%;">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffCalls as $call)
                        @php
                            $rawDur = $call->call_duration;
                            if (!$rawDur) {
                                $duration = '0:00';
                            } elseif (is_numeric($rawDur)) {
                                $durSec = (int)$rawDur;
                                $duration = floor($durSec / 60) . ':' . str_pad($durSec % 60, 2, '0', STR_PAD_LEFT);
                            } else {
                                $duration = $rawDur;
                            }

                            $contactSource = $call->contact->source ?? null;
                            $isIncoming = $contactSource === 'MyOperator';
                            $sourceLabel = $contactSource ?? 'Manual';
                            
                            $tagLower = strtolower($call->lead_tag ?? 'contacted');
                            $tagClass = 'out-interested';
                            $tagText = $call->lead_tag ?? 'Interested';
                            
                            if(str_contains($tagLower, 'book')) { $tagClass = 'out-booked'; $tagText = 'Demo Booked'; }
                            elseif(str_contains($tagLower, 'call') || str_contains($tagLower, 'follow')) { $tagClass = 'out-callback'; $tagText = 'Follow-up'; }
                            elseif(str_contains($tagLower, 'no') || str_contains($tagLower, 'lost')) { $tagClass = 'out-noanswer'; $tagText = 'Not Answered'; }
                            elseif(str_contains($tagLower, 'enroll') || str_contains($tagLower, 'convert')) { $tagClass = 'out-enrolled'; $tagText = 'Enrolled'; }
                        @endphp
                        <tr class="lead-details" data-url="{{ route('calls.profile', $call->id) }}" style="cursor: pointer;">
                            <!-- Contact -->
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 15px;">
                                    {{ $call->contact->name ?? 'Unknown Contact' }}
                                </div>
                            </td>

                            <!-- Phone -->
                            <td>
                                <span class="fw-medium text-secondary">
                                    <i class="fas fa-phone-alt me-1 text-muted" style="font-size: 11px;"></i>{{ $call->contact->caller_number ?? $call->caller_number }}
                                </span>
                            </td>

                            <!-- Source -->
                            <td>
                                <span class="badge-source">{{ $sourceLabel }}</span>
                            </td>

                            <!-- Direction -->
                            <td>
                                @php
                                    $actualDir = $call->direction ?: ($isIncoming ? 'Incoming' : 'Outgoing');
                                @endphp
                                
                                @if($actualDir == 'Enrollment' || $actualDir == 'Web')
                                    <span class="dir-badge-outbound" style="background: #e0f2fe; color: #0284c7; border-color: #bae6fd;">
                                        <i class="fas fa-globe" style="font-size: 10px;"></i> Enrollment
                                    </span>
                                @elseif($actualDir == 'Incoming' || $isIncoming)
                                    <span class="dir-badge-inbound">
                                        <i class="fas fa-arrow-down" style="font-size: 10px;"></i> Incoming
                                    </span>
                                @else
                                    <span class="dir-badge-outbound">
                                        <i class="fas fa-arrow-up" style="font-size: 10px;"></i> Outgoing
                                    </span>
                                @endif
                            </td>

                            <!-- Duration -->
                            <td class="fw-bold text-dark">
                                {{ $duration }}
                            </td>

                            <!-- Outcome -->
                            <td>
                                <span class="outcome-pill {{ $tagClass }}">{{ $tagText }}</span>
                            </td>

                            <!-- Time -->
                            <td class="text-secondary small fw-medium">
                                @if($call->created_at->isToday())
                                    Today, {{ $call->created_at->format('h:i A') }}
                                @elseif($call->created_at->isYesterday())
                                    Yesterday, {{ $call->created_at->format('h:i A') }}
                                @else
                                    {{ $call->created_at->format('M d, h:i A') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card border-0 shadow-sm p-5 text-center my-4" style="border-radius: 16px;">
        <div class="d-flex flex-column align-items-center justify-content-center">
            <div style="width: 70px; height: 70px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                <i class="fas fa-phone-slash text-muted" style="font-size: 28px;"></i>
            </div>
            <h6 class="fw-bold text-dark mb-1">No Call Log Records Found</h6>
            <p class="text-muted small mb-0">No call history matches your current search or filter criteria.</p>
        </div>
    </div>
@endforelse
