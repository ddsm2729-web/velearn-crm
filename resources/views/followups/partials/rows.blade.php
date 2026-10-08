{{-- Missed Follow-ups --}}
@foreach($missed as $lead)
    <a href="{{ route('leads.show', encrypt($lead->id)) }}" class="text-decoration-none d-block">
        <div class="followup-card missed py-2 px-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="dot red"></span>
                <div>
                    <div class="lead-title">{{ $lead->name }}</div>
                    <div class="lead-subtitle">{{ $lead->latestActivity->note ?? 'No notes provided' }}</div>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-4">
                <div class="date-info">
                    <div class="followup-date">
                        <i class="far fa-calendar-alt me-1"></i> {{ $lead->latestActivity->next_followup_date->format('d M, Y') }}
                    </div>
                    @if($lead->phone)
                        <div class="phone-text">{{ $lead->phone }}</div>
                    @endif
                    <div class="added-date">Added: {{ $lead->created_at->format('d M h:i A') }}</div>
                </div>
                <div class="badges d-flex gap-2 align-items-center">
                    <span class="badge-custom badge-status">{{ $lead->status->name ?? 'CONTACTED' }}</span>
                    <span class="badge-custom badge-type">
                        @if($lead->source && stripos($lead->source->name, 'outbound') !== false)
                            <i class="fas fa-user-plus me-1"></i> Outbound
                        @else
                            <i class="fas fa-phone-alt me-1"></i> Inbound
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </a>
@endforeach

{{-- Upcoming Follow-ups --}}
@foreach($upcoming as $lead)
    <a href="{{ route('leads.show', encrypt($lead->id)) }}" class="text-decoration-none d-block">
        <div class="followup-card upcoming py-2 px-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="dot green"></span>
                <div>
                    <div class="lead-title">{{ $lead->name }}</div>
                    <div class="lead-subtitle">{{ $lead->latestActivity->note ?? 'No notes provided' }}</div>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-4">
                <div class="date-info">
                    <div class="followup-date">
                        <i class="far fa-calendar-alt me-1"></i> {{ $lead->latestActivity->next_followup_date->format('d M, Y') }}
                    </div>
                    @if($lead->phone)
                        <div class="phone-text">{{ $lead->phone }}</div>
                    @endif
                    <div class="added-date">Added: {{ $lead->created_at->format('d M h:i A') }}</div>
                </div>
                <div class="badges d-flex gap-2 align-items-center">
                    <span class="badge-custom badge-status">{{ $lead->status->name ?? 'CONTACTED' }}</span>
                    <span class="badge-custom badge-type">
                        @if($lead->source && stripos($lead->source->name, 'outbound') !== false)
                            <i class="fas fa-user-plus me-1"></i> Outbound
                        @else
                            <i class="fas fa-phone-alt me-1"></i> Inbound
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </a>
@endforeach

@if($missed->isEmpty() && $upcoming->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fas fa-calendar-check fa-3x mb-3 text-light"></i>
        <h5>No follow-ups found.</h5>
        <p>You're all caught up!</p>
    </div>
@endif
