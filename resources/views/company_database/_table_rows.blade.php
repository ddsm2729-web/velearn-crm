@forelse($companies as $c)
<tr style="cursor: pointer;" onclick="window.location='{{ route('company-database.show', encrypt($c->id)) }}'">
    <td class="align-middle ps-4 fw-bold text-muted">{{ ($companies->currentPage() - 1) * $companies->perPage() + $loop->iteration }}</td>
    <td class="align-middle"><span class="text-primary fw-bold">{{ $c->company_name }}</span></td>
    <td class="align-middle text-muted">{{ $c->city_location }}</td>
    <td class="align-middle text-muted">{{ $c->industry_sector }}</td>
    <td class="align-middle fw-bold">{{ $c->hr_name }}</td>
    <td class="align-middle fw-bold">+91 {{ $c->hr_contact_number }}</td>
    <td class="align-middle text-muted">{{ $c->source->name ?? 'N/A' }}</td>
    <td class="align-middle fw-bold {{ $c->current_openings ? 'text-success' : 'text-muted' }}">{{ $c->current_openings ? 'Yes' : 'No' }}</td>
    <td class="align-middle fw-bold text-muted">{{ $c->created_at->format('d M') }}</td>
    <td class="align-middle text-center">
        @php
            $tagName = $c->leadTag->name ?? 'N/A';
            $badgeClass = 'badge-new';
            if(stripos($tagName, 'Active') !== false) $badgeClass = 'badge-active';
            elseif(stripos($tagName, 'Contact') !== false) $badgeClass = 'badge-contacted';
            elseif(stripos($tagName, 'Follow') !== false) $badgeClass = 'badge-followup';
        @endphp
        <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2 text-uppercase d-inline-flex align-items-center">
            <i class="fas fa-circle me-2" style="font-size: 6px;"></i> {{ $tagName }}
        </span>
    </td>
    @canany(['Company Database Edit', 'Company Database Delete'])
    <td class="text-end pe-4 sticky-action align-middle" onclick="event.stopPropagation()">
        <div class="d-flex justify-content-end gap-2">
            @can('Company Database Edit')
            <a href="{{ route('company-database.edit', encrypt($c->id)) }}"
                class="btn btn-sm btn-light-primary shadow-none" title="Edit Company">
                <i class="fas fa-edit"></i>
            </a>
            @endcan
            @can('Company Database Delete')
            <button type="button" 
                class="btn btn-sm btn-light-danger shadow-none delete-company" 
                data-href="{{ route('company-database.destroy', encrypt($c->id)) }}"
                title="Delete Company">
                <i class="fas fa-trash-alt"></i>
            </button>
            @endcan
        </div>
    </td>
    @endcanany
</tr>
@empty
<tr>
    <td colspan="11" class="text-center py-5 text-muted">No companies found.</td>
</tr>
@endforelse
