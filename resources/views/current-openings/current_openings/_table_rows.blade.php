@forelse($openings as $o)
<tr>
    <td class="align-middle ps-4 fw-bold text-muted">{{ $o->formatted_id }}</td>
    <td class="align-middle text-primary fw-bold">{{ $o->company->company_name ?? 'N/A' }}</td>
    <td class="align-middle fw-bold">{{ $o->job_role }}</td>
    <td class="align-middle text-muted">{{ $o->job_location }}</td>
    <td class="align-middle fw-bold">{{ $o->ctc_salary }}</td>
    <td class="align-middle fw-bold text-muted">{{ $o->number_of_openings }}</td>
    <td class="align-middle text-muted">{{ $o->experience_required }}</td>
    <td class="align-middle text-muted">{{ $o->interview_mode }}</td>
    <td class="align-middle fw-bold text-muted">{{ $o->created_at->format('d M') }}</td>
    <td class="align-middle text-center">
        @php
            $badgeClass = 'badge-active';
            $statusStr = strtolower($o->opening_status);
            if($statusStr === 'hold') $badgeClass = 'badge-hold';
            elseif($statusStr === 'filled') $badgeClass = 'badge-filled';
            elseif($statusStr === 'closed') $badgeClass = 'badge-closed';
            elseif($statusStr === 'old') $badgeClass = 'badge-old';
        @endphp
        <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2 text-uppercase d-inline-flex align-items-center">
            <i class="fas fa-circle me-2" style="font-size: 6px;"></i> {{ $o->opening_status }}
        </span>
    </td>
    @canany(['Current Openings Edit', 'Current Openings Delete'])
    <td class="text-end pe-4 sticky-action align-middle">
        <div class="d-flex justify-content-end gap-2">
            @can('Current Openings Edit')
            <a href="{{ route('current-openings.edit', encrypt($o->id)) }}"
                class="btn btn-sm btn-light-primary shadow-none" title="Edit Entry">
                <i class="fas fa-edit"></i>
            </a>
            <button type="button" 
                class="btn btn-sm btn-light-info shadow-none send-mail-btn" 
                data-href="{{ route('current-openings.send-mail', encrypt($o->id)) }}"
                title="Send Mail">
                <i class="fas fa-envelope"></i>
            </button>
            @endcan
            @can('Current Openings Delete')
            <button type="button" 
                class="btn btn-sm btn-light-danger shadow-none delete-opening" 
                data-href="{{ route('current-openings.destroy', encrypt($o->id)) }}"
                title="Delete Entry">
                <i class="fas fa-trash-alt"></i>
            </button>
            @endcan
        </div>
    </td>
    @endcanany
</tr>
@empty
<tr>
    <td colspan="11" class="text-center py-5 text-muted">No current openings found. Add a new entry to get started!</td>
</tr>
@endforelse
