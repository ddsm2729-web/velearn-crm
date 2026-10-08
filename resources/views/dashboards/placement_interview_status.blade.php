@extends('layouts.header')
@section('pageTitle', 'Interview Status | ' . config('app.name'))

@push('css')
<style>
    :root {
        --is-bg-card: #ffffff;
        --is-border: #e2e8f0;
        --is-border-light: #f1f5f9;
        --is-text-primary: #0f172a;
        --is-text-secondary: #64748b;
        --is-text-muted: #94a3b8;
        --is-primary: #2563eb;
        --is-success: #10b981;
        --is-warning: #f59e0b;
        --is-danger: #ef4444;
        --is-purple: #8b5cf6;
        --is-orange: #ea580c;
        --is-teal: #06b6d4;
    }

    .is-page-wrapper {
        padding: 0;
        margin-bottom: 2.5rem;
    }

    /* Page Header */
    .is-header-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--is-text-primary);
        letter-spacing: -0.3px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .is-coord-badge {
        background: rgba(37, 99, 235, 0.08);
        color: var(--is-primary);
        border: 1px solid rgba(37, 99, 235, 0.2);
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 3px 10px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    .is-header-subtitle {
        color: var(--is-text-secondary);
        font-size: 0.85rem;
        margin-top: 4px;
        margin-bottom: 0;
    }

    /* Pill Tabs */
    .is-tab-container {
        display: inline-flex;
        background: #f1f5f9;
        border: 1px solid var(--is-border);
        padding: 4px;
        border-radius: 10px;
        gap: 4px;
    }

    .is-tab-btn {
        background: transparent;
        border: none;
        color: var(--is-text-secondary);
        font-size: 0.85rem;
        font-weight: 600;
        padding: 8px 20px;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .is-tab-btn:hover {
        color: var(--is-text-primary);
    }

    .is-tab-btn.active {
        background: #ffffff;
        color: var(--is-text-primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    /* Search & Filter Bar */
    .is-filter-bar {
        margin-top: 20px;
        margin-bottom: 24px;
    }

    .is-search-box {
        position: relative;
        min-width: 260px;
    }

    .is-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--is-text-muted);
        font-size: 0.85rem;
    }

    .is-search-input {
        background: #ffffff;
        border: 1px solid var(--is-border);
        color: var(--is-text-primary);
        font-size: 0.85rem;
        padding: 8px 14px 8px 38px;
        border-radius: 8px;
        width: 100%;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .is-search-input:focus {
        background: #ffffff;
        border-color: var(--is-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .is-filter-select {
        background-color: #ffffff;
        border: 1px solid var(--is-border);
        color: var(--is-text-primary);
        font-size: 0.85rem;
        font-weight: 500;
        padding: 8px 32px 8px 14px;
        border-radius: 8px;
        appearance: none;
        outline: none;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 14px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        transition: all 0.2s;
    }

    .is-filter-select:focus {
        border-color: var(--is-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    /* Table Wrapper & Styling */
    .is-table-wrapper {
        background: #ffffff;
        border: 1px solid var(--is-border);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .is-custom-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1050px;
    }

    .is-custom-table th {
        background-color: #f8fafc;
        font-size: 0.68rem;
        text-transform: uppercase;
        color: var(--is-text-secondary);
        font-weight: 700;
        letter-spacing: 0.6px;
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid var(--is-border);
        white-space: nowrap;
    }

    .is-custom-table td {
        padding: 16px 16px;
        border-bottom: 1px solid var(--is-border-light);
        vertical-align: middle;
        font-size: 0.88rem;
    }

    .is-custom-table tr:last-child td {
        border-bottom: none;
    }

    .is-clickable-row {
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }

    .is-clickable-row:hover {
        background-color: #f8fafc;
    }

    .is-clickable-row:hover .is-val-student {
        color: var(--is-primary);
    }

    /* Typography inside cells */
    .is-val-id {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--is-text-secondary);
    }

    .is-val-student {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--is-text-primary);
        transition: color 0.15s;
    }

    .is-val-sub {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--is-text-secondary);
    }

    .is-val-highlight {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--is-text-primary);
    }

    /* Status Pills with Bullet Dot */
    .is-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 9999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        white-space: nowrap;
        border: 1px solid transparent;
    }

    .is-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .is-status-scheduled {
        background: rgba(37, 99, 235, 0.08);
        color: #2563eb;
        border-color: rgba(37, 99, 235, 0.2);
    }
    .is-status-scheduled .is-status-dot { background-color: #2563eb; }

    .is-status-rescheduled {
        background: rgba(245, 158, 11, 0.08);
        color: #d97706;
        border-color: rgba(245, 158, 11, 0.2);
    }
    .is-status-rescheduled .is-status-dot { background-color: #d97706; }

    .is-status-selected {
        background: rgba(16, 185, 129, 0.08);
        color: #059669;
        border-color: rgba(16, 185, 129, 0.2);
    }
    .is-status-selected .is-status-dot { background-color: #059669; }

    .is-status-rejected {
        background: rgba(239, 68, 68, 0.08);
        color: #dc2626;
        border-color: rgba(239, 68, 68, 0.2);
    }
    .is-status-rejected .is-status-dot { background-color: #dc2626; }

    .is-status-hold {
        background: rgba(139, 92, 246, 0.08);
        color: #7c3aed;
        border-color: rgba(139, 92, 246, 0.2);
    }
    .is-status-hold .is-status-dot { background-color: #7c3aed; }

    .is-status-notattended, .is-status-noshow {
        background: rgba(234, 88, 12, 0.08);
        color: #ea580c;
        border-color: rgba(234, 88, 12, 0.2);
    }
    .is-status-notattended .is-status-dot, .is-status-noshow .is-status-dot { background-color: #ea580c; }

    .is-status-attended {
        background: rgba(6, 182, 212, 0.08);
        color: #0891b2;
        border-color: rgba(6, 182, 212, 0.2);
    }
    .is-status-attended .is-status-dot { background-color: #0891b2; }

    .is-status-nextround {
        background: rgba(79, 70, 229, 0.08);
        color: #4f46e5;
        border-color: rgba(79, 70, 229, 0.2);
    }
    .is-status-nextround .is-status-dot { background-color: #4f46e5; }

    /* Company Group Accordion / Cards */
    .is-company-card {
        background: #ffffff;
        border: 1px solid var(--is-border);
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .is-company-header {
        background: #f8fafc;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--is-border);
    }

    .empty-state-card {
        background: #ffffff;
        border: 1px dashed var(--is-border);
        border-radius: 12px;
        padding: 48px 20px;
        text-align: center;
        color: var(--is-text-secondary);
    }
</style>
@endpush

@section('section')
<div class="container-fluid is-page-wrapper">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <div class="is-header-title">
                Interview Status
                <span class="is-coord-badge">PLACEMENT COORD</span>
            </div>
            <p class="is-header-subtitle">Per-student & per-company interview tracking</p>
        </div>
        <div class="d-none d-md-block text-muted small">
            <span>Per-student & per-company view</span>
        </div>
    </div>

    {{-- Tab Switcher --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
        <div class="is-tab-container">
            <button type="button" class="is-tab-btn {{ $activeTab === 'students' ? 'active' : '' }}" id="tabBtnStudents" onclick="switchView('students')">
                Students Interview Status
            </button>
            <button type="button" class="is-tab-btn {{ $activeTab === 'companies' ? 'active' : '' }}" id="tabBtnCompanies" onclick="switchView('companies')">
                Company Interview Status
            </button>
        </div>
    </div>

    {{-- Filter Bar Form --}}
    <form id="filterForm" action="{{ route('interview-status.index') }}" method="GET" class="is-filter-bar">
        <input type="hidden" name="tab" id="activeTabInput" value="{{ $activeTab }}">

        <div class="d-flex flex-wrap align-items-center gap-2">
            {{-- Search Box --}}
            <div class="is-search-box flex-grow-1" style="max-width: 320px;">
                <i class="fas fa-search is-search-icon"></i>
                <input type="text" name="search" class="is-search-input"
                    placeholder="Search student or company..."
                    value="{{ request('search') }}"
                    onkeydown="if(event.key === 'Enter'){ this.form.submit(); }">
            </div>

            {{-- Status Filter --}}
            <div>
                <select name="status" class="is-filter-select" onchange="this.form.submit()">
                    <option value="All" {{ request('status', 'All') === 'All' ? 'selected' : '' }}>Status: All</option>
                    <option value="scheduled" {{ strtolower(request('status')) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="re-scheduled" {{ strtolower(request('status')) === 're-scheduled' ? 'selected' : '' }}>Re-Scheduled</option>
                    <option value="selected" {{ strtolower(request('status')) === 'selected' ? 'selected' : '' }}>Selected</option>
                    <option value="rejected" {{ strtolower(request('status')) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="on hold" {{ strtolower(request('status')) === 'on hold' ? 'selected' : '' }}>Hold</option>
                    <option value="not attended" {{ strtolower(request('status')) === 'not attended' ? 'selected' : '' }}>Not Attended</option>
                    <option value="no show" {{ strtolower(request('status')) === 'no show' ? 'selected' : '' }}>No Show</option>
                    <option value="attended" {{ strtolower(request('status')) === 'attended' ? 'selected' : '' }}>Attended</option>
                </select>
            </div>

            {{-- Date Filter --}}
            <div>
                <select name="date" class="is-filter-select" onchange="this.form.submit()">
                    <option value="All" {{ request('date', 'All') === 'All' ? 'selected' : '' }}>Date: All</option>
                    @foreach($availableDates as $d)
                        <option value="{{ $d }}" {{ request('date') === $d ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($d)->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Month Filter --}}
            <div>
                <select name="month" class="is-filter-select" onchange="this.form.submit()">
                    <option value="All" {{ request('month', 'All') === 'All' ? 'selected' : '' }}>Month: All</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Company Filter --}}
            <div>
                <select name="company" class="is-filter-select" onchange="this.form.submit()">
                    <option value="All" {{ request('company', 'All') === 'All' ? 'selected' : '' }}>Company: All</option>
                    @foreach($companies as $comp)
                        <option value="{{ $comp }}" {{ request('company') === $comp ? 'selected' : '' }}>{{ $comp }}</option>
                    @endforeach
                </select>
            </div>

            @if(request()->hasAny(['search', 'status', 'date', 'month', 'company']))
            <div>
                <a href="{{ route('interview-status.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.8rem;">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
            @endif
        </div>
    </form>

    {{-- VIEW 1: Students Interview Status Responsive Table --}}
    <div id="studentsViewSection" style="{{ $activeTab === 'students' ? 'display: block;' : 'display: none;' }}">
        <div class="is-table-wrapper">
            <div class="table-responsive">
                <table class="is-custom-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th style="width: 170px;">STUDENT</th>
                            <th style="width: 140px;">PHONE</th>
                            <th style="width: 180px;">COURSE</th>
                            <th style="width: 150px;">COMPANY</th>
                            <th style="width: 160px;">ROLE</th>
                            <th style="width: 90px;">DATE</th>
                            <th style="width: 100px;">TIME</th>
                            <th class="text-center" style="width: 130px;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interviews as $interview)
                            @php
                                $courseUserId = $interview->courseUser->id ?? \App\Models\CourseUser::where('auth_id', $interview->user_id)->value('id');
                                $studentShowUrl = $courseUserId ? route('eligible-students.show', $courseUserId) : route('eligible-students.index');

                                $studentCode = 'S' . str_pad($interview->user_id ?? $interview->id, 3, '0', STR_PAD_LEFT);

                                $rawStatus = strtolower(trim($interview->status ?? 'scheduled'));
                                $statusClass = 'is-status-scheduled';
                                $displayStatus = 'SCHEDULED';

                                if (in_array($rawStatus, ['selected'])) {
                                    $statusClass = 'is-status-selected';
                                    $displayStatus = 'SELECTED';
                                } elseif (in_array($rawStatus, ['rejected'])) {
                                    $statusClass = 'is-status-rejected';
                                    $displayStatus = 'REJECTED';
                                } elseif (in_array($rawStatus, ['re-scheduled', 'rescheduled'])) {
                                    $statusClass = 'is-status-rescheduled';
                                    $displayStatus = 'RE-SCHEDULED';
                                } elseif (in_array($rawStatus, ['hold', 'on hold'])) {
                                    $statusClass = 'is-status-hold';
                                    $displayStatus = 'HOLD';
                                } elseif (in_array($rawStatus, ['not attended'])) {
                                    $statusClass = 'is-status-notattended';
                                    $displayStatus = 'NOT ATTENDED';
                                } elseif (in_array($rawStatus, ['no show'])) {
                                    $statusClass = 'is-status-noshow';
                                    $displayStatus = 'NO SHOW';
                                } elseif (in_array($rawStatus, ['attended'])) {
                                    $statusClass = 'is-status-attended';
                                    $displayStatus = 'ATTENDED';
                                } elseif (in_array($rawStatus, ['next round'])) {
                                    $statusClass = 'is-status-nextround';
                                    $displayStatus = 'NEXT ROUND';
                                }
                            @endphp
                            <tr class="is-clickable-row" onclick="window.location.href='{{ $studentShowUrl }}'" title="Click to open student profile">
                                <td><span class="is-val-id">{{ $studentCode }}</span></td>
                                <td><span class="is-val-student">{{ $interview->name }}</span></td>
                                <td><span class="is-val-sub">{{ $interview->phone }}</span></td>
                                <td><span class="is-val-sub">{{ $interview->course }}</span></td>
                                <td><span class="is-val-highlight">{{ $interview->company }}</span></td>
                                <td><span class="is-val-sub">{{ $interview->role ?? 'N/A' }}</span></td>
                                <td><span class="is-val-highlight">{{ $interview->date ? \Carbon\Carbon::parse($interview->date)->format('d M') : 'N/A' }}</span></td>
                                <td><span class="is-val-highlight">{{ $interview->time ? \Carbon\Carbon::parse($interview->time)->format('h:i A') : 'N/A' }}</span></td>
                                <td class="text-center">
                                    <span class="is-status-pill {{ $statusClass }}">
                                        <span class="is-status-dot"></span>
                                        {{ $displayStatus }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state-card border-0 py-5">
                                        <i class="fas fa-calendar-times mb-3 text-muted" style="font-size: 2.2rem;"></i>
                                        <h6 class="fw-bold text-dark">No Scheduled Interviews Found</h6>
                                        <p class="small text-muted mb-0">No interview schedule matches your filter criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($interviews->hasPages())
                <div class="d-flex justify-content-end p-3 border-top">
                    {{ $interviews->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    {{-- VIEW 2: Company Interview Status View --}}
    <div id="companiesViewSection" style="{{ $activeTab === 'companies' ? 'display: block;' : 'display: none;' }}">
        @forelse($groupedByCompany as $companyName => $companyInterviews)
            <div class="is-company-card">
                <div class="is-company-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-building text-primary"></i>
                        <h6 class="mb-0 fw-bold text-dark">{{ $companyName ?: 'Unknown Company' }}</h6>
                    </div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold" style="font-size: 0.72rem;">
                        {{ $companyInterviews->count() }} {{ \Illuminate\Support\Str::plural('Interview', $companyInterviews->count()) }}
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="is-custom-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th style="width: 180px;">STUDENT</th>
                                <th style="width: 140px;">PHONE</th>
                                <th style="width: 180px;">COURSE</th>
                                <th style="width: 160px;">ROLE</th>
                                <th style="width: 100px;">DATE</th>
                                <th style="width: 100px;">TIME</th>
                                <th class="text-center" style="width: 130px;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companyInterviews as $interview)
                                @php
                                    $courseUserId = $interview->courseUser->id ?? \App\Models\CourseUser::where('auth_id', $interview->user_id)->value('id');
                                    $studentShowUrl = $courseUserId ? route('eligible-students.show', $courseUserId) : route('eligible-students.index');

                                    $studentCode = 'S' . str_pad($interview->user_id ?? $interview->id, 3, '0', STR_PAD_LEFT);

                                    $rawStatus = strtolower(trim($interview->status ?? 'scheduled'));
                                    $statusClass = 'is-status-scheduled';
                                    $displayStatus = 'SCHEDULED';

                                    if (in_array($rawStatus, ['selected'])) {
                                        $statusClass = 'is-status-selected';
                                        $displayStatus = 'SELECTED';
                                    } elseif (in_array($rawStatus, ['rejected'])) {
                                        $statusClass = 'is-status-rejected';
                                        $displayStatus = 'REJECTED';
                                    } elseif (in_array($rawStatus, ['re-scheduled', 'rescheduled'])) {
                                        $statusClass = 'is-status-rescheduled';
                                        $displayStatus = 'RE-SCHEDULED';
                                    } elseif (in_array($rawStatus, ['hold', 'on hold'])) {
                                        $statusClass = 'is-status-hold';
                                        $displayStatus = 'HOLD';
                                    } elseif (in_array($rawStatus, ['not attended'])) {
                                        $statusClass = 'is-status-notattended';
                                        $displayStatus = 'NOT ATTENDED';
                                    } elseif (in_array($rawStatus, ['no show'])) {
                                        $statusClass = 'is-status-noshow';
                                        $displayStatus = 'NO SHOW';
                                    } elseif (in_array($rawStatus, ['attended'])) {
                                        $statusClass = 'is-status-attended';
                                        $displayStatus = 'ATTENDED';
                                    } elseif (in_array($rawStatus, ['next round'])) {
                                        $statusClass = 'is-status-nextround';
                                        $displayStatus = 'NEXT ROUND';
                                    }
                                @endphp
                                <tr class="is-clickable-row" onclick="window.location.href='{{ $studentShowUrl }}'" title="Click to open student profile">
                                    <td><span class="is-val-id">{{ $studentCode }}</span></td>
                                    <td><span class="is-val-student">{{ $interview->name }}</span></td>
                                    <td><span class="is-val-sub">{{ $interview->phone }}</span></td>
                                    <td><span class="is-val-sub">{{ $interview->course }}</span></td>
                                    <td><span class="is-val-sub">{{ $interview->role ?? 'N/A' }}</span></td>
                                    <td><span class="is-val-highlight">{{ $interview->date ? \Carbon\Carbon::parse($interview->date)->format('d M') : 'N/A' }}</span></td>
                                    <td><span class="is-val-highlight">{{ $interview->time ? \Carbon\Carbon::parse($interview->time)->format('h:i A') : 'N/A' }}</span></td>
                                    <td class="text-center">
                                        <span class="is-status-pill {{ $statusClass }}">
                                            <span class="is-status-dot"></span>
                                            {{ $displayStatus }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="empty-state-card">
                <i class="fas fa-building mb-3 text-muted" style="font-size: 2.2rem;"></i>
                <h6 class="fw-bold text-dark">No Company Interviews Found</h6>
                <p class="small text-muted mb-0">No company groups match your filter criteria.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('script')
<script>
    function switchView(tab) {
        document.getElementById('activeTabInput').value = tab;
        if (tab === 'students') {
            document.getElementById('studentsViewSection').style.display = 'block';
            document.getElementById('companiesViewSection').style.display = 'none';
            document.getElementById('tabBtnStudents').classList.add('active');
            document.getElementById('tabBtnCompanies').classList.remove('active');
        } else {
            document.getElementById('studentsViewSection').style.display = 'none';
            document.getElementById('companiesViewSection').style.display = 'block';
            document.getElementById('tabBtnCompanies').classList.add('active');
            document.getElementById('tabBtnStudents').classList.remove('active');
        }
    }
</script>
@endpush
