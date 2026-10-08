@if($item['type'] === 'call_log')
    @php $call = $item['data']; @endphp
    <tr>
        <td>
            <div class="fw-bold">
                {{ \Carbon\Carbon::parse($call->call_start)->format('h:i A') }}
            </div>
            <div class="text-muted smaller">
                {{ \Carbon\Carbon::parse($call->call_start)->format('d M Y') }}
            </div>
        </td>
        <td>
            @if($call->direction == 'Incoming')
                <span class="badge bg-soft-success text-success px-3 rounded-pill border border-success border-opacity-25">
                    <i class="fas fa-arrow-down me-1"></i> INCOMING
                </span>
            @elseif($call->direction == 'Outgoing')
                <span class="badge bg-soft-warning text-warning px-3 rounded-pill border border-warning border-opacity-25">
                    <i class="fas fa-arrow-up me-1"></i> OUTGOING
                </span>
            @else
                <span class="badge bg-soft-secondary text-secondary px-3 rounded-pill border border-secondary border-opacity-25">
                    <i class="fas fa-phone me-1"></i> {{ strtoupper($call->direction) }}
                </span>
            @endif
        </td>
        <td>
            @if($call->recording_url)
                <button type="button" class="btn btn-soft-primary btn-sm rounded px-3 py-1 play-btn"
                    data-id="{{ $call->id }}">
                    <i class="fas fa-play me-1"></i> Play
                </button>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            <div class="text-dark">
                @if($call->call_duration >= 3600)
                    {{ gmdate("H:i:s", $call->call_duration) }}
                @else
                    {{ gmdate("i:s", $call->call_duration) }}
                @endif
            </div>
        </td>
        <td>
            <div class="text-dark">
                {{ $call->staff ? trim($call->staff->first_name . ' ' . $call->staff->last_name) : 'System/IVR' }}
            </div>
        </td>
        <td>
            <span class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-3 py-2 text-uppercase fw-bold" style="font-size: 0.6rem;">
                @if($call->status == 1) Answered
                @elseif($call->status == 2) Missed
                @else {{ $call->status }} @endif
            </span>
        </td>
        <td>
            @if(isset($isCurrent) && $isCurrent)
                {{-- Inline form for current unhandled call log --}}
                <form action="{{ route('leads.activity.store', encrypt($lead->id)) }}" method="POST" class="inline-activity-form d-flex flex-column gap-2 bg-light p-2 rounded border" style="min-width: 250px;">
                    @csrf
                    <input type="hidden" name="type" value="Call">
                    <input type="hidden" name="outcome" value="{{ $call->status == 1 ? 'Answered' : 'Missed' }}">
                    <select name="tag_id" class="form-select form-select-sm" required>
                        <option value="">Select Tag</option>
                        @foreach($tags as $t)
                            <option value="{{ $t->id }}" {{ ($lead->tags->first()?->id == $t->id) ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                    <textarea class="form-control form-control-sm" name="note" rows="2" placeholder="Add notes here..." required></textarea>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="fas fa-save me-1"></i> Save Note</button>
                        <button type="button" class="btn btn-outline-primary btn-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#conversationModal">Detailed</button>
                    </div>
                </form>
            @else
                <div class="mb-2 p-3 rounded border bg-white shadow-sm" style="max-width: 280px; white-space: normal; word-break: break-word; overflow-wrap: break-word; line-height: 1.5;">
                    <span class="text-muted small">Call record logged.</span>
                </div>
            @endif
        </td>
    </tr>
@elseif($item['type'] === 'activity')
    @php $activity = $item['data']; @endphp
    <tr>
        <td>
            <div class="fw-bold">
                {{ $activity->created_at->format('h:i A') }}
            </div>
            <div class="text-muted smaller">
                {{ $activity->created_at->format('d M Y') }}
            </div>
        </td>
        <td>
            <span class="badge bg-soft-info text-info px-3 rounded-pill border border-info border-opacity-25">
                <i class="fas fa-sticky-note me-1"></i> LOGGED
            </span>
        </td>
        <td><span class="text-muted">—</span></td>
        <td><span class="text-muted">—</span></td>
        <td>
            <div class="text-dark">
                {{ $activity->staff ? trim($activity->staff->first_name . ' ' . $activity->staff->last_name) : 'System' }}
            </div>
        </td>
        <td>
            <div class="d-flex flex-column gap-1">
                <span class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-3 py-2 text-uppercase fw-bold" style="font-size: 0.6rem;">
                    {{ $activity->status?->name ?? $activity->lead->status?->name ?? $activity->type }}
                </span>
                @if($activity->tag)
                    <span class="badge bg-soft-warning text-warning border border-warning border-opacity-25 px-3 py-1 text-uppercase fw-bold" style="font-size: 0.6rem;">
                        {{ $activity->tag->name }}
                    </span>
                @endif
            </div>
        </td>
        <td>
            {{-- Main Note --}}
            <div class="mb-2 p-3 rounded border bg-white shadow-sm" style="max-width: 280px; white-space: normal; word-break: break-word; overflow-wrap: break-word; line-height: 1.5;">
                <span class="text-muted small">
                    {{ $activity->note }}
                </span>
                
                @php $isOwner = $activity->staff && $activity->staff->auth_id == auth()->id(); @endphp
                @if((auth()->user()->hasRole('SuperAdmin') || $isOwner || auth()->user()->can('Lead Activity Edit')) && $activity->id == $latestActivityId)
                    <div class="mt-2 text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 edit-activity-btn"
                            data-id="{{ $activity->id }}"
                            data-type="{{ $activity->type }}"
                            data-outcome="{{ $activity->outcome }}"
                            data-note="{{ $activity->note }}"
                            data-next-action="{{ $activity->next_action }}"
                            data-next-followup="{{ $activity->next_followup_date ? $activity->next_followup_date->format('Y-m-d\TH:i') : '' }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                @endif
            </div>

            {{-- Followup Note / Next Action --}}
            @if($activity->next_action)
                <div class="mb-2 p-3 rounded border bg-light-info shadow-sm" style="max-width: 280px; white-space: normal; word-break: break-word; overflow-wrap: break-word; line-height: 1.5; background: #eaf7ff; border-color: #b6e0fe !important;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-reply text-info me-2 mt-1"></i>
                        <span class="text-info small">
                            {{ $activity->next_action }}
                        </span>
                    </div>
                </div>
            @endif
            
            {{-- Scheduled Followup Date --}}
            @if($activity->next_followup_date)
                <div class="p-2 rounded border border-info border-opacity-25 bg-soft-info d-flex align-items-center" style="max-width: 280px;">
                    <i class="far fa-calendar-alt text-info me-2"></i>
                    <div>
                        <div class="text-info fw-bold" style="font-size: 0.7rem;">Follow-up Scheduled</div>
                        <div class="text-muted" style="font-size: 0.7rem;">Date: <span class="fw-bold text-dark">{{ $activity->next_followup_date->format('d M Y h:i A') }}</span></div>
                    </div>
                </div>
            @endif
        </td>
    </tr>
@endif
