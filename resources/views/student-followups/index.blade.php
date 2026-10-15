@extends('layouts.header')

@section('title', 'Follow-ups / Lead Management')

@push('css')
<style>
    :root {
        --bg-elev-1: #ffffff;
        --bg-elev-2: #ffffff;
        --bg-elev-3: #f1f5f9;
        --border: rgba(0, 0, 0, 0.08);
        --border-light: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --accent: #3b82f6;
        --accent-hover: #2563eb;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
    }

    .page-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .page-subtitle {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }
    
    .badge-role {
        background: rgba(6, 182, 212, 0.1);
        color: #0891b2;
        font-size: 0.7rem;
        font-weight: 700;
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        padding: 2px 8px;
    }

    .count-badge {
        background: var(--text-primary);
        color: #ffffff;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Tabs Pill Switcher */
    .fu-tabs-container {
        display: inline-flex;
        background: var(--bg-elev-3);
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 4px;
        gap: 4px;
    }

    .fu-tab-btn {
        background: transparent;
        color: var(--text-secondary);
        border: none;
        border-radius: 8px;
        padding: 6px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }
    .fu-tab-btn:hover {
        color: var(--text-primary);
    }
    .fu-tab-btn.active {
        background: #ffffff;
        color: var(--text-primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    /* Filters Bar */
    .fu-filter-bar {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 1rem;
        margin-bottom: 1.25rem;
    }

    .fu-filter-search-box {
        position: relative;
        flex: 1;
        min-width: 240px;
    }
    .fu-filter-search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 0.85rem;
    }
    .fu-filter-search-input {
        width: 100%;
        background: #ffffff;
        border: 1px solid var(--border-light);
        color: var(--text-primary);
        font-size: 0.86rem;
        border-radius: 8px;
        padding: 0.5rem 1rem 0.5rem 2.4rem;
        outline: none;
        transition: border-color 0.2s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }
    .fu-filter-search-input:focus {
        border-color: var(--accent);
    }
    .fu-filter-search-input::placeholder {
        color: var(--text-secondary);
    }

    .fu-select {
        background-color: #ffffff;
        border: 1px solid var(--border-light);
        color: var(--text-primary);
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.5rem 2.2rem 0.5rem 1rem;
        border-radius: 8px;
        appearance: none;
        outline: none;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 14px 10px;
        min-width: 160px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }
    .fu-select:focus {
        border-color: var(--accent);
    }

    /* Table Design */
    .table-wrapper {
        background-color: var(--bg-elev-2);
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .custom-table th {
        background-color: var(--bg-elev-3);
        font-size: 0.65rem;
        text-transform: uppercase;
        color: var(--text-secondary);
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 1rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .custom-table td {
        padding: 1rem 1rem;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
        font-size: 0.88rem;
    }

    .custom-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .custom-table tr:last-child td {
        border-bottom: none;
    }

    .col-val {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
    }
    .col-val-sub {
        font-weight: 500;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .link-company {
        color: var(--accent);
        font-weight: 700;
        text-decoration: none;
        transition: color 0.15s;
    }
    .link-company:hover {
        color: var(--accent-hover);
        text-decoration: underline;
    }

    /* Modal Form Styling */
    .fu-modal-dialog {
        max-width: 650px;
    }
    .fu-modal-content {
        border: 1px solid var(--border-light);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .fu-modal-header {
        border-bottom: 1px solid var(--border-light);
        padding: 1rem 1.25rem;
        background-color: #f8fafc;
    }
    .fu-modal-body {
        padding: 1.25rem;
        max-height: calc(85vh - 120px);
        overflow-y: auto;
    }
    .fu-modal-footer {
        border-top: 1px solid var(--border-light);
        padding: 0.75rem 1.25rem;
        background-color: #f8fafc;
    }
    .fu-form-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 4px;
    }
    .fu-form-control {
        border: 1px solid var(--border-light);
        color: var(--text-primary);
        border-radius: 6px;
        padding: 0.45rem 0.75rem;
        font-size: 0.85rem;
    }
    .fu-form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
    }
</style>
@endpush

@section('section')
<div class="container-fluid py-4" style="max-width: 1400px;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <h1 class="page-title mb-0">Follow-ups / Lead Management</h1>
                <span class="badge-role">STUDENT COORD</span>
            </div>
            <p class="page-subtitle mb-0">Post-interview status & pending follow-ups</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#entryModal" style="background-color: var(--accent); border-color: var(--accent); border-radius: 8px;">
                <i class="fas fa-plus me-2"></i> Entry
            </button>
        </div>
    </div>

    <!-- Title & Tabs -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2">
            <h5 class="mb-0 fw-bold" style="font-size: 1.1rem; color: var(--text-primary);">Follow-ups</h5>
            <span class="count-badge">{{ $totalCount }}</span>
        </div>
        <div class="fu-tabs-container">
            <a href="{{ route('student-followups.index', ['tab' => 'student']) }}" 
               class="fu-tab-btn {{ $activeTab === 'student' ? 'active' : '' }}">
               Student Follow-ups
            </a>
            <a href="{{ route('student-followups.index', ['tab' => 'company']) }}" 
               class="fu-tab-btn {{ $activeTab === 'company' ? 'active' : '' }}">
               Company Follow-ups
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <form id="filterForm" action="{{ route('student-followups.index') }}" method="GET">
        <input type="hidden" name="tab" value="{{ $activeTab }}">
        <div class="fu-filter-bar">
            <!-- Search Box -->
            <div class="fu-filter-search-box">
                <i class="fas fa-search"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       class="fu-filter-search-input" 
                       placeholder="{{ $activeTab === 'student' ? 'Search student or company...' : 'Search company or HR...' }}">
            </div>

            <!-- Purpose Filter -->
            <div>
                <select name="purpose" class="fu-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="all">Purpose: All</option>
                    @foreach($purposeOptions as $opt)
                        <option value="{{ $opt }}" {{ request('purpose') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date Filter -->
            <div>
                <select name="date" class="fu-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="all">Date: All</option>
                    @foreach($dates as $d)
                        <option value="{{ $d }}" {{ request('date') == $d ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($d)->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <select name="month" class="fu-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="all">Month: All</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(2026, $m, 1)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            @if(request()->hasAny(['search', 'purpose', 'date', 'month']))
                <div>
                    <a href="{{ route('student-followups.index', ['tab' => $activeTab]) }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; padding: 0.45rem 0.85rem;">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
            @endif
        </div>
    </form>

    <!-- Table Container -->
    <div class="table-wrapper">
        <div class="table-responsive border-0">
            <table class="custom-table">
                @if($activeTab === 'student')
                    <!-- Student Follow-ups Table -->
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 100px;">ID</th>
                            <th style="width: 220px;">STUDENT</th>
                            <th style="width: 180px;">PHONE</th>
                            <th style="width: 180px;">COURSE</th>
                            <th style="width: 180px;">COMPANY</th>
                            <th>PURPOSE</th>
                            <th style="width: 140px;">CALL DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($followups as $item)
                            <tr>
                                <td class="ps-4"><div class="col-val col-val-sub">{{ $item->followup_code }}</div></td>
                                <td><div class="col-val">{{ $item->student_name }}</div></td>
                                <td><div class="col-val col-val-sub">{{ $item->phone ?? 'N/A' }}</div></td>
                                <td><div class="col-val col-val-sub">{{ $item->course ?? 'N/A' }}</div></td>
                                <td><div class="col-val" style="font-weight: 500;">{{ $item->company_name }}</div></td>
                                <td><div class="col-val col-val-sub">{{ $item->purpose }}</div></td>
                                <td><div class="col-val">{{ \Carbon\Carbon::parse($item->call_date)->format('d M Y') }}</div></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No student follow-ups recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                @else
                    <!-- Company Follow-ups Table -->
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 100px;">ID</th>
                            <th style="width: 180px;">COMPANY</th>
                            <th style="width: 180px;">HR</th>
                            <th style="width: 170px;">HR NO.</th>
                            <th style="width: 200px;">ROLE</th>
                            <th style="width: 200px;">STUDENT</th>
                            <th>PURPOSE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($followups as $item)
                            <tr>
                                <td class="ps-4"><div class="col-val col-val-sub">{{ $item->followup_code }}</div></td>
                                <td>
                                    <a href="javascript:void(0)" class="link-company">
                                        {{ $item->company_name }}
                                    </a>
                                </td>
                                <td><div class="col-val">{{ $item->hr_name ?? 'N/A' }}</div></td>
                                <td><div class="col-val col-val-sub">{{ $item->hr_phone ?? 'N/A' }}</div></td>
                                <td><div class="col-val col-val-sub">{{ $item->role ?? 'N/A' }}</div></td>
                                <td><div class="col-val">{{ $item->student_name }}</div></td>
                                <td><div class="col-val col-val-sub">{{ $item->purpose }}</div></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No company follow-ups recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                @endif
            </table>
        </div>

        @if($followups->hasPages())
            <div class="d-flex justify-content-end p-3 border-top" style="border-color: var(--border-light) !important;">
                {{ $followups->links() }}
            </div>
        @endif
    </div>
</div>

<!-- + Entry Modal -->
<div class="modal fade" id="entryModal" tabindex="-1" aria-labelledby="entryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered fu-modal-dialog modal-dialog-scrollable">
        <form action="{{ route('student-followups.store') }}" method="POST" style="width: 100%;">
            @csrf
            <div class="modal-content fu-modal-content">
                <div class="modal-header fu-modal-header">
                    <h5 class="modal-title fw-bold fs-6 mb-0" id="entryModalLabel">
                        <i class="fas fa-plus-circle me-2 text-primary"></i> New Follow-up Entry
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fu-modal-body">
                    <!-- Type Selector Pill -->
                    <div class="mb-3">
                        <label class="fu-form-label d-block mb-1">Follow-up Category <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type_student" value="student" {{ $activeTab === 'student' ? 'checked' : '' }} onchange="toggleFormFields('student')">
                                <label class="form-check-label fw-bold" for="type_student" style="font-size: 0.85rem;">
                                    Student Follow-up
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type_company" value="company" {{ $activeTab === 'company' ? 'checked' : '' }} onchange="toggleFormFields('company')">
                                <label class="form-check-label fw-bold" for="type_company" style="font-size: 0.85rem;">
                                    Company Follow-up
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Common Fields Row 1 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-6" id="field_student_name">
                            <label class="fu-form-label">Student Name <span class="text-danger">*</span></label>
                            <input type="text" name="student_name" class="form-control fu-form-control" placeholder="e.g. Sneha Raghavan" required>
                        </div>
                        <div class="col-md-6" id="field_student_phone">
                            <label class="fu-form-label">Student Phone</label>
                            <input type="text" name="phone" class="form-control fu-form-control" placeholder="e.g. +91 98765 44444">
                        </div>
                    </div>

                    <!-- Common Fields Row 2 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-6" id="field_course">
                            <label class="fu-form-label">Course</label>
                            <input type="text" name="course" class="form-control fu-form-control" placeholder="e.g. Full Stack Dev">
                        </div>
                        <div class="col-md-6">
                            <label class="fu-form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control fu-form-control" placeholder="e.g. Infosys / Zoho Corp" required>
                        </div>
                    </div>

                    <!-- Company Specific Fields (HR & Role) -->
                    <div class="row g-2 mb-2" id="company_fields_group" style="display: {{ $activeTab === 'company' ? 'flex' : 'none' }};">
                        <div class="col-md-4">
                            <label class="fu-form-label">HR Name</label>
                            <input type="text" name="hr_name" class="form-control fu-form-control" placeholder="e.g. Priya Sharma">
                        </div>
                        <div class="col-md-4">
                            <label class="fu-form-label">HR Contact No.</label>
                            <input type="text" name="hr_phone" class="form-control fu-form-control" placeholder="e.g. +91 98765 56789">
                        </div>
                        <div class="col-md-4">
                            <label class="fu-form-label">Job Role</label>
                            <input type="text" name="role" class="form-control fu-form-control" placeholder="e.g. System Engineer">
                        </div>
                    </div>

                    <!-- Purpose & Call Date -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="fu-form-label">Purpose <span class="text-danger">*</span></label>
                            <select name="purpose" id="modal_purpose_select" class="form-select fu-form-control" required>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fu-form-label">Call Date <span class="text-danger">*</span></label>
                            <input type="date" name="call_date" class="form-control fu-form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <!-- Notes / Summary -->
                    <div class="mb-1">
                        <label class="fu-form-label">Discussion Notes / Remarks</label>
                        <textarea name="notes" rows="2" class="form-control fu-form-control" placeholder="Brief notes about the call or feedback..."></textarea>
                    </div>
                </div>
                <div class="modal-footer fu-modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 6px;">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3" style="border-radius: 6px; background: var(--accent); border-color: var(--accent);">
                        <i class="fas fa-check me-1"></i> Save Follow-up
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const studentPurposes = [
        'Inform about Interview',
        'Ask Interview Experience',
        'Other'
    ];

    const companyPurposes = [
        'Ask Interview Date',
        'Ask Interview Status',
        'Other'
    ];

    function toggleFormFields(type) {
        const companyGroup = document.getElementById('company_fields_group');
        const purposeSelect = document.getElementById('modal_purpose_select');
        
        purposeSelect.innerHTML = '';
        const list = type === 'student' ? studentPurposes : companyPurposes;

        list.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p;
            opt.textContent = p;
            purposeSelect.appendChild(opt);
        });

        if (type === 'company') {
            companyGroup.style.display = 'flex';
        } else {
            companyGroup.style.display = 'none';
        }
    }

    // Initialize select with active tab
    document.addEventListener('DOMContentLoaded', function() {
        const currentType = '{{ $activeTab }}';
        toggleFormFields(currentType);
    });
</script>
@endsection