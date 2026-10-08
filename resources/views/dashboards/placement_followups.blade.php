@extends('layouts.header')
@section('pageTitle', 'Placement Followups | ' . config('app.name'))

@section('section')
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h1 class="h3 mb-0 text-dark fw-bold">Placement <span class="text-primary">Followups</span></h1>
            <div class="d-flex gap-2">
                <a href="{{ route('placement-followups.index', ['filter' => 'all']) }}" class="btn btn-sm shadow-sm rounded-pill px-3 {{ $filter === 'all' ? 'btn-primary' : 'btn-white border bg-white text-muted' }}">All</a>
                <a href="{{ route('placement-followups.index', ['filter' => 'overdue']) }}" class="btn btn-sm shadow-sm rounded-pill px-3 {{ $filter === 'overdue' ? 'btn-danger' : 'btn-white border bg-white text-danger' }}">Overdue ({{ $overdueCount }})</a>
                <a href="{{ route('placement-followups.index', ['filter' => 'today']) }}" class="btn btn-sm shadow-sm rounded-pill px-3 {{ $filter === 'today' ? 'btn-warning text-dark' : 'btn-white border bg-white text-warning' }}" style="{{ $filter !== 'today' ? 'color: #d97706 !important;' : '' }}">Today ({{ $todayCount }})</a>
                <a href="{{ route('placement-followups.index', ['filter' => 'upcoming']) }}" class="btn btn-sm shadow-sm rounded-pill px-3 {{ $filter === 'upcoming' ? 'btn-info text-white' : 'btn-white border bg-white text-info' }}">Upcoming ({{ $upcomingCount }})</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                @if($companies->count() > 0)
                <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="table table-hover align-middle mb-0 border-0" style="min-width: 900px;">
                        <thead style="background-color: #f8fafc; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th class="ps-4 py-3 border-0">ID</th>
                                <th class="py-3 border-0">Company</th>
                                <th class="py-3 border-0">Location</th>
                                <th class="py-3 border-0">HR Name</th>
                                <th class="py-3 border-0">Contact No.</th>
                                <th class="py-3 border-0">Status</th>
                                <th class="py-3 border-0">Follow-up</th>
                                <th class="py-3 border-0">Notes</th>
                                <th class="py-3 text-end pe-4 border-0">Action</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.85rem; border-top: none;">
                            @foreach($companies as $company)
                            @php
                                $activity = $company->activities->first(); 
                                $isOverdue = \Carbon\Carbon::parse($activity->next_followup_date)->isPast() && !\Carbon\Carbon::parse($activity->next_followup_date)->isToday();
                                $isToday = \Carbon\Carbon::parse($activity->next_followup_date)->isToday();
                                
                                $tagName = $company->leadTag->name ?? 'N/A';
                                $badgeClass = 'bg-primary text-white';
                                if(stripos($tagName, 'Active') !== false || stripos($tagName, 'Immediate') !== false) $badgeClass = 'badge-active bg-success text-white';
                                elseif(stripos($tagName, 'Contact') !== false) $badgeClass = 'bg-info text-white';
                                elseif(stripos($tagName, 'Follow') !== false || stripos($tagName, 'Later') !== false) $badgeClass = 'bg-warning text-dark';
                                elseif(stripos($tagName, 'Hot') !== false) $badgeClass = 'bg-danger text-white';
                                elseif(stripos($tagName, 'Warm') !== false) $badgeClass = 'bg-warning text-dark';
                                elseif(stripos($tagName, 'Not') !== false) $badgeClass = 'bg-danger text-white';
                            @endphp
                            <tr>
                                <td class="ps-4 py-3 fw-bold text-muted">#{{ $company->id }}</td>
                                <td class="py-3">
                                    <a href="{{ route('company-database.show', encrypt($company->id)) }}" class="fw-bold text-primary text-decoration-none">{{ $company->company_name }}</a>
                                </td>
                                <td class="py-3 text-muted">{{ $company->city_location ?? '-' }}</td>
                                <td class="py-3 fw-bold text-dark">{{ $company->hr_name ?? '-' }}</td>
                                <td class="py-3 fw-bold text-dark">{{ $company->hr_contact_number ? '+91 ' . $company->hr_contact_number : '-' }}</td>
                                <td class="py-3">
                                    <span class="badge {{ $badgeClass }} rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                        <i class="fas fa-circle me-1" style="font-size: 5px;"></i> {{ strtoupper($tagName) }}
                                    </span>
                                </td>
                                <td class="py-3 fw-bold">
                                    @if($isOverdue)
                                        <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> {{ \Carbon\Carbon::parse($activity->next_followup_date)->format('d M Y') }}</span>
                                    @elseif($isToday)
                                        <span class="text-warning text-dark"><i class="fas fa-clock me-1"></i> Today</span>
                                    @else
                                        <span class="text-dark">{{ \Carbon\Carbon::parse($activity->next_followup_date)->format('d M Y') }}</span>
                                    @endif
                                </td>
                                <td class="py-3 text-muted text-truncate" style="max-width: 200px;" title="{{ $activity->note }}">
                                    {{ $activity->note }}
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <a href="{{ route('company-database.show', encrypt($company->id)) }}" class="btn btn-sm btn-light shadow-sm border px-3">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top">
                    {{ $companies->links('pagination::bootstrap-4') }}
                </div>
                @else
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-calendar-check fa-3x mb-3 text-light"></i>
                    <h5 class="fw-bold">No followups found</h5>
                    <p class="small">You're all caught up! Check back later or add new followups from the Company Database.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
