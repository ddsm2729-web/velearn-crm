{{-- Hidden Meta for Client Stats Sync --}}
<div id="resultsMeta" data-total="{{ $totalCount ?? $calls->total() }}" data-missed="{{ $overdueCount ?? 0 }}" data-upcoming="{{ $upcomingCount ?? 0 }}" style="display:none;"></div>

{{-- Dynamic Empty Message when a specific tab filter has no leads --}}
<div id="tabEmptyMessage" style="display:none;" class="empty-state-box my-3">
    <div class="empty-icon-circle">
        <i class="fas fa-check-circle fa-2x text-success"></i>
    </div>
    <h6 class="fw-bold mb-1 text-dark">No Follow-ups in this View</h6>
    <p class="text-muted small mb-0">Switch to "All Follow-ups" or adjust your filters above.</p>
</div>

@forelse($calls as $call)
    @php
        $tagLabel = $call->tags->first()->name ?? 'NEW';
        $tLower = strtolower($tagLabel);

        $tagBg = '#f1f5f9';
        $tagColor = '#475569';
        $tagBorder = '#e2e8f0';

        if(str_contains($tLower, 'hot') || str_contains($tLower, 'lost')) {
            $tagBg = '#fff1f2'; $tagColor = '#be123c'; $tagBorder = '#fecdd3';
        } elseif(str_contains($tLower, 'warm') || str_contains($tLower, 'book')) {
            $tagBg = '#fffbeb'; $tagColor = '#b45309'; $tagBorder = '#fde68a';
        } elseif(str_contains($tLower, 'confirm') || str_contains($tLower, 'convert') || str_contains($tLower, 'attend')) {
            $tagBg = '#ecfdf5'; $tagColor = '#047857'; $tagBorder = '#a7f3d0';
        } elseif(str_contains($tLower, 'cold')) {
            $tagBg = '#f0f9ff'; $tagColor = '#0284c7'; $tagBorder = '#bae6fd';
        }

        $isPending = $call->is_pending ?? false;

        $words = array_filter(explode(' ', trim($call->name ?? '')));
        $initials = '';
        if (count($words) >= 2) {
            $initials = mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1);
        } else {
            $initials = mb_substr($call->name ?? 'C', 0, 2);
        }
        $initials = strtoupper($initials);

        $fDate = $call->next_followup ? \Carbon\Carbon::parse($call->next_followup) : null;
        $profileUrl = route('support.calls.profile', $call->phone);
    @endphp

    <div class="followup-card-modern {{ $isPending ? 'is-missed' : 'is-upcoming' }}" onclick="window.location.href='{{ $profileUrl }}'">
        <div class="d-flex align-items-start gap-3 flex-grow-1">
            <!-- Initials Avatar -->
            <div class="lead-avatar-circle {{ $isPending ? 'avatar-missed' : 'avatar-upcoming' }}" title="{{ $call->name }}">
                {{ $initials }}
            </div>

            <!-- Lead Primary Info -->
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <span class="lead-name-text">{{ $call->name ?? 'Unknown Caller' }}</span>
                    <span class="badge-status-pill" style="background-color: {{ $tagBg }}; color: {{ $tagColor }}; border: 1px solid {{ $tagBorder }};">
                        {{ strtoupper($tagLabel) }}
                    </span>
                    <span class="badge-type-pill">
                        @if($call->source && stripos($call->source->name, 'myoperator') !== false)
                            <i class="fas fa-arrow-down-left me-1 text-primary" style="font-size: 0.65rem;"></i> Inbound
                        @else
                            <i class="fas fa-arrow-up-right me-1 text-secondary" style="font-size: 0.65rem;"></i> Outbound
                        @endif
                    </span>
                </div>

                <!-- Contact Details Row -->
                <div class="d-flex align-items-center flex-wrap gap-3 my-1">
                    @if($call->phone)
                        <a href="tel:{{ $call->phone }}" class="text-decoration-none text-slate-700 small fw-semibold" onclick="event.stopPropagation();" title="Click to call">
                            <i class="fas fa-phone-alt text-primary me-1"></i> {{ $call->phone }}
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $call->phone) }}" target="_blank" class="text-decoration-none text-success small fw-semibold" onclick="event.stopPropagation();" title="Chat on WhatsApp">
                            <i class="fab fa-whatsapp me-1"></i> WhatsApp
                        </a>
                    @endif
                    @if($call->email)
                        <span class="text-muted small"><i class="far fa-envelope me-1"></i> {{ $call->email }}</span>
                    @endif
                </div>

                <!-- Activity Note Snippet -->
                <div class="lead-note-box {{ $isPending ? 'note-missed' : '' }}">
                    <i class="far fa-comment-dots {{ $isPending ? 'text-danger' : 'text-primary' }} me-1"></i>
                    <span>{{ $call->latestActivity->notes ?? $call->latestActivity->note ?? 'No follow-up notes recorded' }}</span>
                </div>
            </div>
        </div>

        <!-- Right Side Info & Actions -->
        <div class="right-side-info text-end flex-shrink-0 d-flex flex-column align-items-end justify-content-between">
            <div>
                @if($fDate)
                    <div class="followup-time-badge {{ $isPending ? 'text-danger' : 'text-primary' }} justify-content-end">
                        <i class="fas {{ $isPending ? 'fa-clock-rotate-left' : 'fa-calendar-alt' }}"></i>
                        <span>{{ $fDate->format('d M, Y') }}</span>
                        @if($isPending)
                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle ms-1" style="font-size: 0.72rem;">Overdue ({{ $fDate->diffForHumans() }})</span>
                        @elseif($fDate->isToday())
                            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle ms-1" style="font-size: 0.72rem;">Due Today</span>
                        @elseif($fDate->isTomorrow())
                            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle ms-1" style="font-size: 0.72rem;">Tomorrow</span>
                        @endif
                    </div>
                @endif
                <div class="text-muted small mt-1">
                    <i class="far fa-clock me-1"></i> Added: {{ $call->created_at->format('d M, h:i A') }}
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="right-side-actions d-flex align-items-center gap-2 mt-3">
                <a href="{{ $profileUrl }}" class="contact-action-btn btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                    <span>View Profile</span> <i class="fas fa-arrow-right" style="font-size: 0.72rem;"></i>
                </a>
                @if($call->phone)
                    <a href="tel:{{ $call->phone }}" class="contact-action-btn btn btn-sm btn-success text-white" onclick="event.stopPropagation();">
                        <i class="fas fa-phone-alt"></i> Call
                    </a>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="empty-state-box my-3">
        <div class="empty-icon-circle">
            <i class="fas fa-clipboard-check fa-2x"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">All Caught Up!</h5>
        <p class="text-muted small mb-3" style="max-width: 420px; margin: 0 auto;">No follow-up leads matching your filters were found. You are completely up to date!</p>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5" id="btnEmptyReset">
            <i class="fas fa-rotate-left me-1"></i> Reset Filters
        </button>
    </div>
@endforelse
