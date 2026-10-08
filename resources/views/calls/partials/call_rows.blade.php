@forelse($calls as $call)
    <tr class="lead-details" style="cursor: pointer;" data-id="{{ $call->id }}" data-url="{{ route('leads.show', encrypt($call->lead_id ?? 0)) }}"> 
        <td>
            <span class="text-primary fw-bold">
                {{ $call->contact->name ?? $call->contact->first_name ?? ($call->lead->name ?? 'Unknown Caller') }}
            </span>
        </td>
        <td>{{ $call->caller_number }}</td>
        <td><span class="badge bg-success bg-opacity-10 text-success">{{ $call->lead->source->name ?? ($call->contact->source ?? 'Website') }}</span></td>
        <td>
            @if(strtolower($call->direction) == 'incoming')
            <span class="badge bg-success bg-opacity-10 text-success"><i class="align-middle" data-feather="arrow-down-left"></i> Incoming</span>
            @else
            <span class="badge bg-primary bg-opacity-10 text-primary"><i class="align-middle" data-feather="arrow-up-right"></i> Outgoing</span>
            @endif
        </td>
        <td>{{ gmdate("i:s", $call->call_duration ?? 0) }}</td>
        <td>
            <span class="badge bg-soft-primary text-primary px-2 py-1 rounded-pill border border-primary border-opacity-25">
                {{ strtoupper($call->lead_tag ?? $call->status ?? 'NEW LEAD') }}
            </span>
        </td>
        <td class="text-end">{{ $call->call_start ? \Carbon\Carbon::parse($call->call_start)->format('M d, h:i A') : 'N/A' }}</td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-4">
            <div class="text-muted">
                <i class="fas fa-phone-slash fa-3x mb-3 d-block"></i>
                No calls found matching your criteria.
            </div>
        </td>
    </tr>
@endforelse
