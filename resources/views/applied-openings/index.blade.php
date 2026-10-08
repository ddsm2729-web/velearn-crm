@extends('layouts.header')
@section('pageTitle', 'Applied Openings | ' . config('app.name'))

@push('css')
<style>
    :root {
        --ao-border: #e2e8f0;
        --ao-border-light: #f1f5f9;
        --ao-text-primary: #0f172a;
        --ao-text-secondary: #64748b;
        --ao-text-muted: #94a3b8;
        --ao-primary: #2563eb;
        --ao-success: #10b981;
        --ao-warning: #f59e0b;
        --ao-danger: #ef4444;
        --ao-purple: #8b5cf6;
        --ao-orange: #ea580c;
        --ao-teal: #06b6d4;
    }

    .ao-page-wrapper {
        padding: 0;
        margin-bottom: 2.5rem;
    }

    /* Page Header */
    .ao-header-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ao-text-primary);
        letter-spacing: -0.3px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ao-badge {
        background: rgba(37, 99, 235, 0.08);
        color: var(--ao-primary);
        border: 1px solid rgba(37, 99, 235, 0.2);
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 3px 10px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    .ao-header-subtitle {
        color: var(--ao-text-secondary);
        font-size: 0.85rem;
        margin-top: 4px;
        margin-bottom: 0;
    }

    /* Summary Cards */
    .ao-stat-card {
        border: 1px solid var(--ao-border);
        border-radius: 12px;
        padding: 18px 22px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .ao-stat-card:hover {
        border-color: #c7d2fe;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.07);
    }

    .ao-stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--ao-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
    }

    .ao-stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--ao-text-primary);
        line-height: 1;
    }

    .ao-stat-desc {
        font-size: 0.78rem;
        color: var(--ao-text-secondary);
        margin-top: 4px;
    }

    /* Filters */
    .ao-filter-bar {
        border: 1px solid var(--ao-border);
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 20px;
    }

    .ao-search-input {
        border: 1px solid var(--ao-border);
        border-radius: 8px;
        padding: 9px 14px 9px 38px;
        font-size: 0.88rem;
        color: var(--ao-text-primary);
        background: transparent;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
    }

    .ao-search-input:focus {
        outline: none;
        border-color: var(--ao-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .ao-search-wrap {
        position: relative;
    }

    .ao-search-wrap .ao-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ao-text-muted);
        pointer-events: none;
    }

    .ao-select {
        border: 1px solid var(--ao-border);
        border-radius: 8px;
        padding: 9px 30px 9px 12px;
        font-size: 0.88rem;
        color: var(--ao-text-primary);
        background: transparent;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .ao-select:focus {
        outline: none;
        border-color: var(--ao-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .ao-filter-btn {
        border: 1px solid var(--ao-border);
        border-radius: 8px;
        padding: 9px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ao-text-secondary);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .ao-filter-btn:hover,
    .ao-filter-btn.active {
        border-color: var(--ao-primary);
        color: var(--ao-primary);
    }

    /* Table */
    .ao-table-wrap {
        border: 1px solid var(--ao-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .ao-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .ao-table thead th {
        padding: 13px 16px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--ao-text-muted);
        border-bottom: 1px solid var(--ao-border);
        white-space: nowrap;
    }

    .ao-table tbody tr {
        border-bottom: 1px solid var(--ao-border-light);
        transition: background 0.15s;
        cursor: pointer;
    }

    .ao-table tbody tr:last-child {
        border-bottom: none;
    }

    .ao-table tbody tr:hover {
        background: #f8fafc;
    }

    .ao-table tbody td {
        padding: 14px 16px;
        color: var(--ao-text-primary);
        vertical-align: middle;
    }

    .ao-company-logo {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid var(--ao-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--ao-primary);
        flex-shrink: 0;
    }

    .ao-company-name {
        font-weight: 600;
        color: var(--ao-text-primary);
        font-size: 0.9rem;
    }

    .ao-role-text {
        font-size: 0.8rem;
        color: var(--ao-text-secondary);
        margin-top: 2px;
    }

    .ao-location-text {
        font-size: 0.8rem;
        color: var(--ao-text-muted);
    }

    /* Pipeline Badges */
    .ao-pipeline {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .ao-pipe-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid;
        border-radius: 6px;
        padding: 3px 9px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .ao-pipe-total    { color: var(--ao-primary);  border-color: rgba(37,99,235,0.25);  }
    .ao-pipe-new      { color: var(--ao-orange);   border-color: rgba(234,88,12,0.25);  }
    .ao-pipe-hr       { color: var(--ao-teal);     border-color: rgba(6,182,212,0.25);  }
    .ao-pipe-sched    { color: var(--ao-warning);  border-color: rgba(245,158,11,0.25); }
    .ao-pipe-selected { color: var(--ao-success);  border-color: rgba(16,185,129,0.25); }

    /* Status badge */
    .ao-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid;
        border-radius: 6px;
        padding: 3px 10px;
        font-size: 0.73rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .ao-status-badge.active   { color: #10b981; border-color: rgba(16,185,129,0.3);  }
    .ao-status-badge.hold     { color: #f59e0b; border-color: rgba(245,158,11,0.3);  }
    .ao-status-badge.closed   { color: #ef4444; border-color: rgba(239,68,68,0.3);   }
    .ao-status-badge.filled   { color: #8b5cf6; border-color: rgba(139,92,246,0.3);  }
    .ao-status-badge.old      { color: #94a3b8; border-color: rgba(148,163,184,0.3); }

    .ao-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .active .ao-dot    { background: #10b981; }
    .hold .ao-dot      { background: #f59e0b; }
    .closed .ao-dot    { background: #ef4444; }
    .filled .ao-dot    { background: #8b5cf6; }
    .old .ao-dot       { background: #94a3b8; }

    /* Empty state */
    .ao-empty-state {
        padding: 60px 24px;
        text-align: center;
    }

    .ao-empty-icon {
        font-size: 2.5rem;
        margin-bottom: 12px;
        opacity: 0.4;
    }

    .ao-empty-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--ao-text-secondary);
        margin-bottom: 6px;
    }

    .ao-empty-sub {
        font-size: 0.85rem;
        color: var(--ao-text-muted);
    }

    /* Pagination */
    .ao-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-top: 1px solid var(--ao-border);
        flex-wrap: wrap;
        gap: 10px;
    }

    .ao-pagination-info {
        font-size: 0.82rem;
        color: var(--ao-text-muted);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .ao-stat-value { font-size: 1.5rem; }
        .ao-table thead th:nth-child(4),
        .ao-table tbody td:nth-child(4),
        .ao-table thead th:nth-child(5),
        .ao-table tbody td:nth-child(5) {
            display: none;
        }
        .ao-pipeline { gap: 4px; }
        .ao-pipe-item { padding: 2px 6px; font-size: 0.7rem; }
    }

    @media (max-width: 576px) {
        .ao-table thead th:nth-child(3),
        .ao-table tbody td:nth-child(3) {
            display: none;
        }
        .ao-header-title { font-size: 1.2rem; }
    }
</style>
@endpush

@section('section')
<div class="ao-page-wrapper">

    {{-- ── Page Header ─────────────────────────────────────────────── --}}
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h1 class="ao-header-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#2563eb">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    <line x1="12" y1="11" x2="12" y2="17"/>
                    <line x1="9" y1="14" x2="15" y2="14"/>
                </svg>
                Applied Openings
                <span class="ao-badge">Placement</span>
            </h1>
            <p class="ao-header-subtitle">Track all job openings and their student application pipeline dynamically.</p>
        </div>
        <a href="{{ route('current-openings.index') }}" class="ao-filter-btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            All Openings
        </a>
    </div>

    {{-- ── Summary Cards ────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="ao-stat-card">
                <div class="ao-stat-label">Total Openings</div>
                <div class="ao-stat-value">{{ $openings->total() }}</div>
                <div class="ao-stat-desc">Listed positions</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="ao-stat-card">
                <div class="ao-stat-label">With Applications</div>
                <div class="ao-stat-value" style="color:var(--ao-primary)">{{ $totalOpeningsWithApplications }}</div>
                <div class="ao-stat-desc">Openings receiving applications</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="ao-stat-card">
                <div class="ao-stat-label">Total Applied</div>
                <div class="ao-stat-value" style="color:var(--ao-orange)">{{ $totalAppliedCount }}</div>
                <div class="ao-stat-desc">Across all openings</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="ao-stat-card">
                <div class="ao-stat-label">Active Openings</div>
                <div class="ao-stat-value" style="color:var(--ao-success)">
                    {{ $openings->getCollection()->where('opening_status', 'Active')->count() }}
                </div>
                <div class="ao-stat-desc">Currently hiring</div>
            </div>
        </div>
    </div>

    {{-- ── Filters ──────────────────────────────────────────────────── --}}
    <div class="ao-filter-bar">
        <form method="GET" action="{{ route('applied-openings.index') }}" class="d-flex flex-wrap align-items-center gap-2">
            <div class="ao-search-wrap flex-grow-1" style="min-width:200px;max-width:360px;">
                <svg class="ao-search-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    class="ao-search-input"
                    placeholder="Search company, role, location…"
                    value="{{ request('search') }}"
                >
            </div>

            <select name="status" class="ao-select">
                <option value="all" {{ request('status','all') == 'all' ? 'selected' : '' }}>All Statuses</option>
                @foreach(['Active','Filled','Hold','Closed','Old'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>

            <button type="submit" class="ao-filter-btn" style="border-color:var(--ao-primary);color:var(--ao-primary);">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
                </svg>
                Filter
            </button>

            @if(request('search') || (request('status') && request('status') !== 'all'))
                <a href="{{ route('applied-openings.index') }}" class="ao-filter-btn">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- ── Table ────────────────────────────────────────────────────── --}}
    <div class="ao-table-wrap">
        <div class="table-responsive">
            <table class="ao-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Company / Role</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Application Pipeline</th>
                        <th>CTC</th>
                        <th>Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($openings as $index => $opening)
                        <tr onclick="window.location='{{ route('current-openings.show', $opening->id) }}'" style="cursor:pointer;">
                            <td style="color:var(--ao-text-muted);font-size:0.8rem;">{{ $openings->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="ao-company-logo">
                                        {{ strtoupper(substr($opening->company->company_name ?? 'C', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="ao-company-name">{{ $opening->company->company_name ?? '—' }}</div>
                                        <div class="ao-role-text">{{ $opening->job_role ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="ao-location-text">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:3px;">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    {{ $opening->job_location ?? '—' }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $st = strtolower($opening->opening_status ?? 'old');
                                @endphp
                                <span class="ao-status-badge {{ $st }}">
                                    <span class="ao-dot"></span>
                                    {{ ucfirst($st) }}
                                </span>
                            </td>
                            <td>
                                <div class="ao-pipeline">
                                    <span class="ao-pipe-item ao-pipe-total" title="Total Applied">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        {{ $opening->total_applied }}
                                    </span>
                                    @if($opening->new_applied > 0)
                                        <span class="ao-pipe-item ao-pipe-new" title="New Applications">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                            {{ $opening->new_applied }} new
                                        </span>
                                    @endif
                                    @if($opening->sent_hr_count > 0)
                                        <span class="ao-pipe-item ao-pipe-hr" title="Sent to HR">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                            {{ $opening->sent_hr_count }} HR
                                        </span>
                                    @endif
                                    @if($opening->scheduled_count > 0)
                                        <span class="ao-pipe-item ao-pipe-sched" title="Interview Scheduled">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                            {{ $opening->scheduled_count }} Sched.
                                        </span>
                                    @endif
                                    @if($opening->selected_count > 0)
                                        <span class="ao-pipe-item ao-pipe-selected" title="Selected">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                            {{ $opening->selected_count }} Selected
                                        </span>
                                    @endif
                                    @if($opening->total_applied === 0)
                                        <span style="font-size:0.78rem;color:var(--ao-text-muted);">No applications</span>
                                    @endif
                                </div>
                            </td>
                            <td style="font-size:0.82rem;color:var(--ao-text-secondary);white-space:nowrap;">
                                {{ $opening->ctc_salary ? '₹ ' . $opening->ctc_salary : '—' }}
                            </td>
                            <td style="font-size:0.82rem;color:var(--ao-text-muted);white-space:nowrap;">
                                @if($opening->application_deadline)
                                    @php
                                        $deadline = \Carbon\Carbon::parse($opening->application_deadline);
                                        $isPast   = $deadline->isPast();
                                    @endphp
                                    <span style="{{ $isPast ? 'color:var(--ao-danger)' : '' }}">
                                        {{ $deadline->format('d M Y') }}
                                        @if($isPast) <small>(Expired)</small> @endif
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="ao-empty-state">
                                    <div class="ao-empty-icon">📋</div>
                                    <div class="ao-empty-title">No openings found</div>
                                    <div class="ao-empty-sub">
                                        @if(request('search') || request('status'))
                                            Try adjusting your filters or <a href="{{ route('applied-openings.index') }}" style="color:var(--ao-primary);">clear them</a>.
                                        @else
                                            No job openings are currently available.
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($openings->hasPages())
            <div class="ao-pagination">
                <span class="ao-pagination-info">
                    Showing {{ $openings->firstItem() }}–{{ $openings->lastItem() }} of {{ $openings->total() }} openings
                </span>
                <div>
                    {{ $openings->links() }}
                </div>
            </div>
        @else
            <div class="ao-pagination">
                <span class="ao-pagination-info">
                    {{ $openings->total() }} {{ Str::plural('opening', $openings->total()) }} found
                </span>
            </div>
        @endif
    </div>

</div>
@endsection
