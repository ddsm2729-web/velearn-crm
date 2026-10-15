{{-- Hidden Meta for Client Stats Sync --}}
<div id="resultsMeta" data-total="{{ $missed->count() + $upcoming->count() }}" data-missed="{{ $missed->count() }}" data-upcoming="{{ $upcoming->count() }}" style="display:none;"></div>

{{-- Dynamic Empty Message when a specific tab filter has no leads --}}
<div id="tabEmptyMessage" style="display:none;" class="empty-state-box my-3">
    <div class="empty-icon-circle">
        <i class="fas fa-check-circle fa-2x text-success"></i>
    </div>
    <h6 class="fw-bold mb-1 text-dark">No Follow-ups in this View</h6>
    <p class="text-muted small mb-0">Switch to "All Follow-ups" or adjust your filters above.</p>
</div>

{{-- ================= OVERDUE FOLLOW-UPS ================= --}}
@if($missed->isNotEmpty())
    <div class="followup-section-overdue mb-4">
        <div class="section-banner-title banner-overdue">
            <div class="d-flex align-items-center">
                <i class="fas fa-circle-exclamation text-danger me-2 fa-lg"></i>
                <span class="fw-bold text-danger fs-6">Overdue Follow-ups</span>
                <span class="badge rounded-pill pill-subtle-danger ms-2">{{ $missed->count() }}</span>
            </div>
            <span class="text-danger small fw-medium d-none d-md-inline"><i class="fas fa-bolt me-1"></i> Requires Immediate Attention</span>
        </div>

        @foreach($missed as $lead)
            @php
                $words = array_filter(explode(' ', trim($lead->name ?? '')));
                $initials = '';
                if (count($words) >= 2) {
                    $initials = mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1);
                } else {
                    $initials = mb_substr($lead->name ?? 'L', 0, 2);
                }
                $initials = strtoupper($initials);

                $statusName = strtolower($lead->status->name ?? 'contacted');
                $statusClass = 'pill-subtle-primary';
                if(str_contains($statusName, 'confirm') || str_contains($statusName, 'convert') || str_contains($statusName, 'attend')) {
                    $statusClass = 'pill-subtle-success';
                } elseif(str_contains($statusName, 'hot') || str_contains($statusName, 'lost') || str_contains($statusName, 'drop')) {
                    $statusClass = 'pill-subtle-danger';
                } elseif(str_contains($statusName, 'warm') || str_contains($statusName, 'book')) {
                    $statusClass = 'pill-subtle-warning';
                } elseif(str_contains($statusName, 'cold')) {
                    $statusClass = 'pill-subtle-info';
                }
                $fDate = $lead->latestActivity->next_followup_date;
            @endphp

            <div class="followup-card-modern is-missed" onclick="window.location.href='{{ route('leads.show', encrypt($lead->id)) }}'">
                <div class="d-flex align-items-start gap-3 flex-grow-1">
                    <!-- Initials Avatar -->
                    <div class="lead-avatar-circle avatar-missed" title="{{ $lead->name }}">
                        {{ $initials }}
                    </div>

                    <!-- Lead Primary Info -->
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <span class="lead-name-text">{{ $lead->name }}</span>
                            <span class="badge-status-pill {{ $statusClass }}">{{ $lead->status->name ?? 'CONTACTED' }}</span>
                            <span class="badge-type-pill">
                                @if($lead->source && stripos($lead->source->name, 'outbound') !== false)
                                    <i class="fas fa-arrow-up-right me-1 text-secondary" style="font-size: 0.65rem;"></i> Outbound
                                @else
                                    <i class="fas fa-arrow-down-left me-1 text-primary" style="font-size: 0.65rem;"></i> Inbound
                                @endif
                            </span>

                            @if($lead->tags && $lead->tags->isNotEmpty())
                                @foreach($lead->tags as $tag)
                                    <span class="badge bg-light text-secondary border rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                        <i class="fas fa-tag me-1 text-muted" style="font-size: 0.65rem;"></i>{{ $tag->name }}
                                    </span>
                                @endforeach
                            @endif
                        </div>

                        <!-- Contact Details Row -->
                        <div class="d-flex align-items-center flex-wrap gap-3 my-1">
                            @if($lead->phone)
                                <a href="tel:{{ $lead->phone }}" class="text-decoration-none text-slate-700 small fw-semibold" onclick="event.stopPropagation();" title="Click to call">
                                    <i class="fas fa-phone-alt text-primary me-1"></i> {{ $lead->phone }}
                                </a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" class="text-decoration-none text-success small fw-semibold" onclick="event.stopPropagation();" title="Chat on WhatsApp">
                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                </a>
                            @endif
                            @if($lead->email)
                                <span class="text-muted small"><i class="far fa-envelope me-1"></i> {{ $lead->email }}</span>
                            @endif
                        </div>

                        <!-- Activity Note Snippet -->
                        <div class="lead-note-box note-missed">
                            <i class="far fa-comment-dots text-danger me-1"></i>
                            <span>{{ $lead->latestActivity->note ?? 'No follow-up notes recorded' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side Info & Actions -->
                <div class="right-side-info text-end flex-shrink-0 d-flex flex-column align-items-end justify-content-between">
                    <div>
                        <div class="followup-time-badge text-danger justify-content-end">
                            <i class="fas fa-clock-rotate-left"></i>
                            <span>{{ $fDate->format('d M, Y') }}</span>
                            <span class="badge rounded-pill pill-subtle-danger ms-1">Overdue ({{ $fDate->diffForHumans() }})</span>
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="far fa-clock me-1"></i> Added: {{ $lead->created_at->format('d M, h:i A') }}
                        </div>
                    </div>

                    <!-- Quick Action Buttons -->
                    <div class="right-side-actions d-flex align-items-center gap-2 mt-3">
                        <a href="{{ route('leads.show', encrypt($lead->id)) }}" class="contact-action-btn btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                            <span>View Lead</span> <i class="fas fa-arrow-right" style="font-size: 0.72rem;"></i>
                        </a>
                        @if($lead->phone)
                            <a href="tel:{{ $lead->phone }}" class="contact-action-btn btn btn-sm btn-success text-white" onclick="event.stopPropagation();">
                                <i class="fas fa-phone-alt"></i> Call
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- ================= UPCOMING FOLLOW-UPS ================= --}}
@if($upcoming->isNotEmpty())
    <div class="followup-section-upcoming mb-4">
        <div class="section-banner-title banner-upcoming">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-check text-success me-2 fa-lg"></i>
                <span class="fw-bold text-success fs-6">Upcoming Follow-ups</span>
                <span class="badge rounded-pill pill-subtle-success ms-2">{{ $upcoming->count() }}</span>
            </div>
            <span class="text-success small fw-medium d-none d-md-inline"><i class="far fa-clock me-1"></i> Scheduled Follow-ups</span>
        </div>

        @foreach($upcoming as $lead)
            @php
                $words = array_filter(explode(' ', trim($lead->name ?? '')));
                $initials = '';
                if (count($words) >= 2) {
                    $initials = mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1);
                } else {
                    $initials = mb_substr($lead->name ?? 'L', 0, 2);
                }
                $initials = strtoupper($initials);

                $statusName = strtolower($lead->status->name ?? 'contacted');
                $statusClass = 'pill-subtle-primary';
                if(str_contains($statusName, 'confirm') || str_contains($statusName, 'convert') || str_contains($statusName, 'attend')) {
                    $statusClass = 'pill-subtle-success';
                } elseif(str_contains($statusName, 'hot') || str_contains($statusName, 'lost') || str_contains($statusName, 'drop')) {
                    $statusClass = 'pill-subtle-danger';
                } elseif(str_contains($statusName, 'warm') || str_contains($statusName, 'book')) {
                    $statusClass = 'pill-subtle-warning';
                } elseif(str_contains($statusName, 'cold')) {
                    $statusClass = 'pill-subtle-info';
                }
                $fDate = $lead->latestActivity->next_followup_date;
            @endphp

            <div class="followup-card-modern is-upcoming" onclick="window.location.href='{{ route('leads.show', encrypt($lead->id)) }}'">
                <div class="d-flex align-items-start gap-3 flex-grow-1">
                    <!-- Initials Avatar -->
                    <div class="lead-avatar-circle avatar-upcoming" title="{{ $lead->name }}">
                        {{ $initials }}
                    </div>

                    <!-- Lead Primary Info -->
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <span class="lead-name-text">{{ $lead->name }}</span>
                            <span class="badge-status-pill {{ $statusClass }}">{{ $lead->status->name ?? 'CONTACTED' }}</span>
                            <span class="badge-type-pill">
                                @if($lead->source && stripos($lead->source->name, 'outbound') !== false)
                                    <i class="fas fa-arrow-up-right me-1 text-secondary" style="font-size: 0.65rem;"></i> Outbound
                                @else
                                    <i class="fas fa-arrow-down-left me-1 text-primary" style="font-size: 0.65rem;"></i> Inbound
                                @endif
                            </span>

                            @if($lead->tags && $lead->tags->isNotEmpty())
                                @foreach($lead->tags as $tag)
                                    <span class="badge bg-light text-secondary border rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                        <i class="fas fa-tag me-1 text-muted" style="font-size: 0.65rem;"></i>{{ $tag->name }}
                                    </span>
                                @endforeach
                            @endif
                        </div>

                        <!-- Contact Details Row -->
                        <div class="d-flex align-items-center flex-wrap gap-3 my-1">
                            @if($lead->phone)
                                <a href="tel:{{ $lead->phone }}" class="text-decoration-none text-slate-700 small fw-semibold" onclick="event.stopPropagation();" title="Click to call">
                                    <i class="fas fa-phone-alt text-primary me-1"></i> {{ $lead->phone }}
                                </a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" class="text-decoration-none text-success small fw-semibold" onclick="event.stopPropagation();" title="Chat on WhatsApp">
                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                </a>
                            @endif
                            @if($lead->email)
                                <span class="text-muted small"><i class="far fa-envelope me-1"></i> {{ $lead->email }}</span>
                            @endif
                        </div>

                        <!-- Activity Note Snippet -->
                        <div class="lead-note-box">
                            <i class="far fa-comment-dots text-primary me-1"></i>
                            <span>{{ $lead->latestActivity->note ?? 'No follow-up notes recorded' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side Info & Actions -->
                <div class="right-side-info text-end flex-shrink-0 d-flex flex-column align-items-end justify-content-between">
                    <div>
                        <div class="followup-time-badge text-primary justify-content-end">
                            <i class="far fa-calendar-alt"></i>
                            <span>{{ $fDate->format('d M, Y') }}</span>
                            @if($fDate->isToday())
                                <span class="badge rounded-pill pill-subtle-warning ms-1"><i class="fas fa-bell me-1"></i> Due Today</span>
                            @elseif($fDate->isTomorrow())
                                <span class="badge rounded-pill pill-subtle-info ms-1">Tomorrow</span>
                            @else
                                <span class="badge rounded-pill pill-subtle-success ms-1">in {{ (int) $fDate->diffInDays(now()) + 1 }}d</span>
                            @endif
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="far fa-clock me-1"></i> Added: {{ $lead->created_at->format('d M, h:i A') }}
                        </div>
                    </div>

                    <!-- Quick Action Buttons -->
                    <div class="right-side-actions d-flex align-items-center gap-2 mt-3">
                        <a href="{{ route('leads.show', encrypt($lead->id)) }}" class="contact-action-btn btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                            <span>View Lead</span> <i class="fas fa-arrow-right" style="font-size: 0.72rem;"></i>
                        </a>
                        @if($lead->phone)
                            <a href="tel:{{ $lead->phone }}" class="contact-action-btn btn btn-sm btn-success text-white" onclick="event.stopPropagation();">
                                <i class="fas fa-phone-alt"></i> Call
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- ================= OVERALL EMPTY STATE ================= --}}
@if($missed->isEmpty() && $upcoming->isEmpty())
    <div class="empty-state-box my-4">
        <div class="empty-icon-circle">
            <i class="fas fa-clipboard-check fa-2x"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">No Follow-ups Found</h5>
        <p class="text-muted small mb-3" style="max-width: 420px; margin: 0 auto;">There are currently no follow-up leads matching your criteria. Great work staying ahead of schedule!</p>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5" onclick="$('#resetFilters').trigger('click');">
            <i class="fas fa-rotate-left me-1"></i> Reset Filters
        </button>
    </div>
@endif
