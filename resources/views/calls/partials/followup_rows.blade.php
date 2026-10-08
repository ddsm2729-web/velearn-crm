@forelse($calls as $call)
    @php
        $tagLabel = $call->lead_tag ?? 'NEW';
        $tagClass = 'tag-' . strtolower(str_replace(' ', '-', $tagLabel));
        $borderClass = 'border-' . strtolower(str_replace(' ', '-', $tagLabel));
        
        // Use the is_pending flag set in the controller
        $isPending = $call->is_pending ?? false;
        $dotClass = $isPending ? 'dot-pending' : 'dot-new';
        if(!$isPending) {
            if($tagLabel == 'hot') $dotClass = 'dot-hot';
            elseif($tagLabel == 'warm') $dotClass = 'dot-warm';
            elseif($tagLabel == 'cold') $dotClass = 'dot-cold';
        }
        
        $cardClass = $isPending ? 'pending-highlight' : '';
    @endphp
    <div class="lead-card {{ $borderClass }} {{ $cardClass }}" data-url="{{ route('calls.profile', $call->id) }}">
        <div class="d-flex align-items-center">
            <div class="status-dot {{ $dotClass }}"></div>
            <div>
                <h5 class="mb-0 fw-bold">{{ $call->contact->name ?? 'Unknown Caller' }}</h5>
                <p class="text-muted small mb-0">{{ $call->note ?? 'No notes provided' }}</p>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-3 gap-md-4 flex-wrap mt-3 mt-md-0">
            <div class="text-end">
                <div class="fw-bold small text-primary mb-1">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ ($call->followup_date ?? $call->demo_date)->format('d M, Y') }}
                </div>
                <div class="fw-bold small">{{ $call->caller_number }}</div>
                <div class="text-muted" style="font-size: 11px;">
                    Added: {{ $call->created_at->isToday() ? 'Today ' . $call->created_at->format('h:i A') : $call->created_at->format('d M h:i A') }}
                </div>
            </div>
            
            <span class="tag-badge {{ $tagClass }}">{{ $tagLabel }}</span>
            
            <div class="source-badge">
                <i class="fas {{ $call->contact->source == 'MyOperator' ? 'fa-phone-alt' : 'fa-user-edit' }} me-1"></i>
                {{ $call->contact->source == 'MyOperator' ? 'Inbound' : 'Outbound' }}
            </div>
        </div>
    </div>
@empty
    <div class="text-center p-5 bg-white rounded-3 shadow-sm border border-dashed" style="border-width: 2px;">
        <div class="mb-4">
            <i class="fas fa-calendar-check fa-4x text-light opacity-50"></i>
        </div>
        <h4 class="fw-bold text-muted">All Caught Up!</h4>
        <p class="text-secondary mb-0">No follow-up leads matching your filters were found.</p>
        <p class="small text-muted mt-1">Try adjusting your search or filters to see more leads.</p>
    </div>
@endforelse
