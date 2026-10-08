@extends('layouts.header')
@section('pageTitle')
    {{ __('Call History') }} | {{ config('app.name') }}
@endsection

@push('css')
    <style>
        .header-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background-color: #EEF2FF;
            color: #4F46E5;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .filter-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .filter-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        .stats-card {
            border-radius: 12px;
            background: #fff;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            height: 100%;
        }
        .stats-icon {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.25rem;
        }
        .staff-group {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .staff-group-header {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border-bottom: 1px solid #F3F4F6;
        }
        .staff-avatar {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            margin-right: 1rem;
        }
        .staff-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.35rem 0.6rem;
            border-radius: 6px;
            text-transform: uppercase;
        }
        .calls-logged-btn {
            background-color: #F3F4F6;
            color: #4B5563;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.4rem 0.75rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s;
        }
        .calls-logged-btn:hover {
            background-color: #E5E7EB;
            color: #374151;
        }
        .calls-logged-btn i {
            width: 14px;
            height: 14px;
            margin-right: 0.4rem;
        }
        .table-custom th {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6B7280;
            background-color: #F9FAFB;
            border-bottom: 1px solid #F3F4F6;
            border-top: 1px solid #F3F4F6;
            padding: 1rem 1.5rem;
        }
        .table-custom td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            color: #374151;
            font-size: 0.875rem;
            border-bottom: 1px solid #F3F4F6;
        }
        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }
        .badge-soft-success { background-color: #ECFDF5; color: #059669; }
        .badge-soft-primary { background-color: #EFF6FF; color: #2563EB; }
        .badge-soft-warning { background-color: #FFFBEB; color: #D97706; }
        .form-control-custom {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        .btn-filter {
            background-color: #3B82F6;
            color: white;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        .stats-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #6B7280;
            margin-bottom: 0.25rem;
        }
        .stats-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0;
            line-height: 1.2;
        }
    </style>
@endpush

@section('section')
    <div class="container-fluid p-0 pb-4">
        
        <!-- Header -->
        <div class="header-card">
            <div class="d-flex align-items-center">
                <div class="header-icon-box me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold text-dark" style="font-size: 1.15rem;">Call History</h4>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Comprehensive log of Incoming & outgoing calls across all staff members</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('calls.history') }}" class="btn btn-light border bg-white text-dark d-flex align-items-center gap-2 px-3 rounded-pill" style="font-size: 0.85rem; font-weight: 500;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                    Refresh
                </a>
                <button class="btn btn-light border bg-white text-dark d-flex align-items-center gap-2 px-3 rounded-pill" style="font-size: 0.85rem; font-weight: 500;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                    Export CSV
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <div class="row align-items-end g-3">
                <div class="col-md-4">
                    <div class="filter-label">SEARCH CALL RECORDS</div>
                    <div class="position-relative">
                        <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </span>
                        <input type="text" class="form-control form-control-custom ps-5" placeholder="Search by contact, number, or staff...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-label">FILTER BY STAFF</div>
                    <select class="form-select form-control-custom">
                        <option>All Staff Members</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="filter-label">OUTCOME</div>
                    <select class="form-select form-control-custom">
                        <option>All Outcomes</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="filter-label">DIRECTION</div>
                    <select class="form-select form-control-custom">
                        <option>All Directions</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-filter w-100 border-0">Filter</button>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row mb-4 g-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon" style="background-color: #EDE9FE; color: #6D28D9;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </div>
                    <div>
                        <p class="stats-title">TOTAL CALLS</p>
                        <h3 class="stats-value">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon" style="background-color: #D1FAE5; color: #059669;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                    </div>
                    <div>
                        <p class="stats-title">INCOMING CALLS</p>
                        <h3 class="stats-value">{{ $stats['incoming'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon" style="background-color: #DBEAFE; color: #2563EB;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                    </div>
                    <div>
                        <p class="stats-title">OUTGOING CALLS</p>
                        <h3 class="stats-value">{{ $stats['outgoing'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon" style="background-color: #FEF3C7; color: #D97706;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div>
                        <p class="stats-title">AVG DURATION (PAGE)</p>
                        <h3 class="stats-value">{{ gmdate("i:s", $stats['avg_duration']) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unassigned Calls -->
        @if($unassignedCalls->count() > 0)
        <div class="staff-group">
            <div class="staff-group-header">
                <div class="d-flex align-items-center">
                    <div class="staff-avatar" style="background-color: #4F46E5;">S</div>
                    <div>
                        <h5 class="mb-0 fw-bold d-inline-block text-dark" style="font-size: 0.95rem;">System / Unassigned</h5>
                        <span class="badge staff-badge ms-2" style="background-color: #EFF6FF; color: #3B82F6;">STAFF MEMBER</span>
                    </div>
                </div>
                <a href="{{ route('calls.unassigned_history') }}" class="calls-logged-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.4rem"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    {{ \App\Models\CallLog::whereNull('staff_id')->count() }} Calls Logged
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom table-hover border-0 mb-0">
                    <thead>
                        <tr>
                            <th style="width: 20%;">CONTACT</th>
                            <th style="width: 15%;">PHONE</th>
                            <th style="width: 10%;">SOURCE</th>
                            <th style="width: 15%;">DIRECTION</th>
                            <th style="width: 10%;">DURATION</th>
                            <th style="width: 15%;">OUTCOME</th>
                            <th style="width: 15%;">TIME</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unassignedCalls->unique('caller_number')->take(5) as $call)
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('leads.show', encrypt($call->lead_id)) }}'">
                            <td class="fw-bold text-dark">{{ $call->contact ? $call->contact->first_name . ' ' . $call->contact->last_name : ($call->lead->name ?? 'Unknown') }}</td>
                            <td>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                {{ $call->caller_number }}
                            </td>
                            <td><span class="badge staff-badge" style="background-color: #ECFDF5; color: #059669; font-size: 0.65rem;">MyOperator</span></td>
                            <td>
                                @if($call->direction == 'incoming')
                                <span class="badge staff-badge" style="background-color: #ECFDF5; color: #059669; font-size: 0.65rem; padding: 0.4rem 0.6rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                                    Incoming
                                </span>
                                @else
                                <span class="badge staff-badge" style="background-color: #EFF6FF; color: #2563EB; font-size: 0.65rem; padding: 0.4rem 0.6rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                                    Outgoing
                                </span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ gmdate("i:s", $call->call_duration) }}</td>
                            <td><span class="badge staff-badge" style="background-color: #EFF6FF; color: #3B82F6; font-size: 0.65rem; font-weight: 500; text-transform:none;">{{ strtoupper($call->lead_tag ?? $call->status ?? 'NEW LEAD') }}</span></td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($call->call_start)->format('M d, h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Staff Call Logs -->
        @foreach($staffs as $staff)
        <div class="staff-group">
            <div class="staff-group-header">
                <div class="d-flex align-items-center">
                    @php 
                        $initials = strtoupper(substr($staff->first_name, 0, 1) . substr($staff->last_name, 0, 1));
                    @endphp
                    <div class="staff-avatar" style="background-color: #F43F5E;">{{ $initials }}</div>
                    <div>
                        <h5 class="mb-0 fw-bold d-inline-block text-dark" style="font-size: 0.95rem;">{{ $staff->first_name }} {{ $staff->last_name }}</h5>
                        @if($staff->role)
                        <span class="badge staff-badge ms-2" style="background-color: #EFF6FF; color: #3B82F6;">{{ strtoupper($staff->role->name) }}</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('calls.staff_history', $staff->id) }}" class="calls-logged-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.4rem"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    {{ $staff->callLogs()->count() }} Calls Logged
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom table-hover border-0 mb-0">
                    <thead>
                        <tr>
                            <th style="width: 20%;">CONTACT</th>
                            <th style="width: 15%;">PHONE</th>
                            <th style="width: 10%;">SOURCE</th>
                            <th style="width: 15%;">DIRECTION</th>
                            <th style="width: 10%;">DURATION</th>
                            <th style="width: 15%;">OUTCOME</th>
                            <th style="width: 15%;">TIME</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff->callLogs->unique('caller_number')->take(5) as $call)
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('leads.show', encrypt($call->lead_id)) }}'">
                            <td class="fw-bold text-dark">{{ $call->contact ? $call->contact->first_name . ' ' . $call->contact->last_name : ($call->lead->name ?? 'Unknown') }}</td>
                            <td>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                {{ $call->caller_number }}
                            </td>
                            <td><span class="badge staff-badge" style="background-color: #ECFDF5; color: #059669; font-size: 0.65rem;">{{ strtoupper($call->lead->source->name ?? 'MyOperator') }}</span></td>
                            <td>
                                @if($call->direction == 'incoming')
                                <span class="badge staff-badge" style="background-color: #ECFDF5; color: #059669; font-size: 0.65rem; padding: 0.4rem 0.6rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                                    Incoming
                                </span>
                                @else
                                <span class="badge staff-badge" style="background-color: #EFF6FF; color: #2563EB; font-size: 0.65rem; padding: 0.4rem 0.6rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                                    Outgoing
                                </span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ gmdate("i:s", $call->call_duration) }}</td>
                            <td><span class="badge staff-badge" style="background-color: #EFF6FF; color: #3B82F6; font-size: 0.65rem; font-weight: 500; text-transform:none;">{{ strtoupper($call->lead_tag ?? $call->status ?? 'NEW LEAD') }}</span></td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($call->call_start)->format('M d, h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

    </div>
@endsection
