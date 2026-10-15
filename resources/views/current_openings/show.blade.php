@extends('layouts.header')

@section('pageTitle', 'Current Opening Details')

@push('css')
<style>
    .page-title-box {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
    }
    .page-badge {
        background: #0f172a;
        color: #fff;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-left: 1rem;
        text-transform: uppercase;
    }
    .back-btn {
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        margin-bottom: 1rem;
        transition: color 0.2s;
    }
    .back-btn:hover {
        color: #0f172a;
    }
    .detail-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    /* Header Section */
    .opening-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .company-logo {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: #818cf8;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        margin-right: 1.25rem;
        flex-shrink: 0;
    }
    .opening-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    .badges-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }
    .detail-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
    }
    .detail-badge.active-badge {
        background: #ecfdf5;
        color: #059669;
        border-color: #d1fae5;
    }
    
    /* Grid Section */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
        padding: 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
    }
    .info-item {
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .info-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.04);
    }
    .info-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        letter-spacing: 0.8px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    .info-value {
        font-size: 0.95rem;
        color: #0f172a;
        font-weight: 500;
    }
    
    /* Card Subheaders */
    .card-subheader {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        font-weight: 600;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    /* Call History Table */
    .call-history-table {
        width: 100%;
        border-collapse: collapse;
    }
    .call-history-table th {
        text-align: left;
        padding: 0.75rem 1.5rem;
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .call-history-table td {
        padding: 1rem 1.5rem;
        font-size: 0.85rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Modal Overlays & Modals */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        padding: 20px;
        animation: fadeInModal 0.2s ease-out;
    }
    .modal-overlay.show {
        display: flex !important;
    }
    @keyframes fadeInModal {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .modal-overlay .modal {
        position: relative !important;
        top: auto !important;
        left: auto !important;
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        max-width: 720px !important;
        max-height: 90vh !important;
        background: #ffffff !important;
        border-radius: 16px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important;
        overflow: hidden !important;
        z-index: 100000 !important;
        transform: none !important;
        margin: 0 !important;
        pointer-events: auto !important;
        animation: slideUpModal 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes slideUpModal {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .modal-overlay .modal-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
    }
    .modal-overlay .modal-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.3px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-overlay .modal-close {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #64748b;
        transition: all 0.2s;
    }
    .modal-overlay .modal-close:hover {
        color: #ef4444;
        background: #fee2e2;
        border-color: #fca5a5;
    }
    .modal-overlay .modal-body {
        padding: 22px 24px;
        overflow-y: auto;
        max-height: calc(90vh - 145px);
    }
    #modalOverlay .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    #modalOverlay .form-grid .full {
        grid-column: 1 / -1;
    }
    #modalOverlay .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    #modalOverlay .form-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: #64748b;
        font-weight: 700;
        margin: 0;
    }
    #modalOverlay .form-input,
    #modalOverlay .form-select {
        width: 100%;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 9px 12px;
        color: #1e293b;
        font-size: 13.5px;
        outline: none;
        transition: all 0.15s;
        font-family: inherit;
    }
    #modalOverlay .form-input:focus,
    #modalOverlay .form-select:focus {
        border-color: #2563eb;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    #modalOverlay .form-input[readonly] {
        background: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }
    #modalOverlay .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #f8fafc;
    }
    #modalOverlay .btn-ghost {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
    }
    #modalOverlay .btn-ghost:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    #modalOverlay .btn-primary {
        background: #2563eb;
        border: 1px solid #2563eb;
        color: #ffffff;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.15s;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }
    #modalOverlay .btn-primary:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }
    #modalOverlay .btn-primary svg {
        width: 16px;
        height: 16px;
    }

    /* Select2 inside Schedule Interview Modal */
    #modalOverlay .select2-container {
        width: 100% !important;
        z-index: 100005 !important;
    }
    #modalOverlay .select2-container--default .select2-selection--single {
        height: 42px !important;
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        display: flex !important;
        align-items: center !important;
        padding: 0 12px !important;
        transition: border-color 0.2s, box-shadow 0.2s !important;
    }
    #modalOverlay .select2-container--default.select2-container--open .select2-selection--single,
    #modalOverlay .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
        outline: none !important;
    }
    #modalOverlay .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
        font-size: 13.5px !important;
        line-height: normal !important;
        padding-left: 0 !important;
        padding-right: 24px !important;
        font-weight: 500 !important;
    }
    #modalOverlay .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #64748b !important;
        font-size: 13px !important;
    }
    #modalOverlay .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        right: 12px !important;
        top: 0 !important;
        display: flex !important;
        align-items: center !important;
    }
    #modalOverlay .select2-container--default .select2-selection--single .select2-selection__clear {
        font-size: 16px !important;
        color: #94a3b8 !important;
        margin-right: 20px !important;
        cursor: pointer !important;
    }
    #modalOverlay .select2-container--default .select2-selection--single .select2-selection__clear:hover {
        color: #ef4444 !important;
    }
    .select2-dropdown {
        z-index: 100010 !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        box-shadow: 0 12px 30px -5px rgba(0, 0, 0, 0.18) !important;
        overflow: hidden !important;
        background-color: #ffffff !important;
    }
    .select2-search--dropdown {
        padding: 8px 10px !important;
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .select2-search--dropdown .select2-search__field {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 6px !important;
        padding: 7px 10px !important;
        font-size: 13px !important;
        outline: none !important;
        width: 100% !important;
        background-color: #ffffff !important;
    }
    .select2-search--dropdown .select2-search__field:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
    }
    .select2-results__option {
        padding: 9px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        font-weight: 600 !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
    }

    /* Mini Status Summary */
    .mini-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 12px;
    }
    .mini-stat {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        text-align: center;
        transition: all 0.2s ease;
    }
    .mini-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        background: #ffffff;
    }
    .mini-stat .mlbl {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .mini-stat .mval {
        font-family: 'SF Mono', Monaco, 'Courier New', monospace;
        font-size: 26px;
        font-weight: 700;
        line-height: 1;
    }
    .mini-stat.accent .mval { color: #2563eb; }
    .mini-stat.green .mval { color: #16a34a; }
    .mini-stat.orange .mval { color: #ea580c; }
    .mini-stat.red .mval { color: #dc2626; }
    .mini-stat.purple .mval { color: #9333ea; }
    .mini-stat.cyan .mval { color: #0891b2; }
    .mini-stat.amber .mval { color: #d97706; }

    /* Scheduled Interviews Strip List */
    .strip-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .strip {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        gap: 20px;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }
    .strip:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateX(3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }
    .strip-cell {
        display: flex;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
        flex-shrink: 0;
    }
    .strip-cell.strip-id { min-width: 65px; }
    .strip-cell.strip-student { min-width: 150px; }
    .strip-cell.strip-date { min-width: 100px; }
    .strip-cell.strip-time { min-width: 90px; }
    .strip-cell.strip-mode { min-width: 90px; }
    .strip-cell.grow {
        flex: 1;
        min-width: 0;
    }
    .strip-cell .lbl {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: #64748b;
        font-weight: 700;
    }
    .strip-cell .val {
        font-size: 13.5px;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .strip-cell .val.mono {
        font-family: 'SF Mono', Monaco, 'Courier New', monospace;
        font-weight: 600;
    }
    .strip-cell .val.muted {
        color: #64748b;
        font-weight: 500;
    }

    /* Tag Pills */
    .tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        border-radius: 999px;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .tag .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        display: inline-block;
    }
    .tag-scheduled { background: rgba(37, 99, 235, 0.1); color: #2563eb; border-color: rgba(37, 99, 235, 0.25); }
    .tag-attended { background: rgba(22, 163, 74, 0.1); color: #16a34a; border-color: rgba(22, 163, 74, 0.25); }
    .tag-selected { background: rgba(16, 185, 129, 0.1); color: #059669; border-color: rgba(16, 185, 129, 0.25); }
    .tag-rejected { background: rgba(220, 38, 38, 0.1); color: #dc2626; border-color: rgba(220, 38, 38, 0.25); }
    .tag-hold { background: rgba(147, 51, 234, 0.1); color: #9333ea; border-color: rgba(147, 51, 234, 0.25); }
    .tag-notattended { background: rgba(234, 88, 12, 0.1); color: #ea580c; border-color: rgba(234, 88, 12, 0.25); }
    .tag-noshow { background: rgba(220, 38, 38, 0.1); color: #dc2626; border-color: rgba(220, 38, 38, 0.25); }
    .tag-rescheduled { background: rgba(217, 119, 6, 0.1); color: #d97706; border-color: rgba(217, 119, 6, 0.25); }

    @media (max-width: 768px) {
        .strip {
            flex-wrap: wrap;
            gap: 12px;
            padding: 12px 14px;
        }
        .strip-cell.strip-id { min-width: 50px; }
        .strip-cell.strip-student { min-width: 120px; }
        .strip-cell.strip-date { min-width: 80px; }
        .strip-cell.strip-time { min-width: 75px; }
        .strip-cell.strip-mode { min-width: 70px; }
    }
</style>
@endpush

@section('section')
<a href="javascript:history.back()" class="back-btn"><i class="fas fa-arrow-left me-2"></i> Back</a>

<div class="page-title-box">
    <h1 class="page-title">Current Openings</h1>
    <span class="page-badge">Student Coord</span>
</div>

<!-- Main Details Card -->
<div class="detail-card">
    <div class="opening-header position-relative overflow-hidden p-4">
        <div class="d-flex align-items-center w-100 position-relative z-1">
            @php
                $companyName = $opening->company->company_name ?? 'N/A';
                $initial = substr($companyName, 0, 1);
            @endphp
            <div class="company-logo bg-primary bg-gradient shadow-sm rounded-circle me-3">{{ strtoupper($initial) }}</div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center mb-1">
                    <h2 class="opening-title fw-bold text-dark mb-0" style="font-size: 1.5rem;">{{ $companyName }} &middot; {{ $opening->job_role }}</h2>
                </div>
                
                <div class="d-flex flex-wrap align-items-center gap-2 mt-2 text-muted" style="font-size: 0.85rem;">
                    @php
                        $status = $opening->opening_status ?? 'N/A';
                        $statusClass = strtolower($status) === 'active' ? 'text-success' : 'text-muted';
                    @endphp
                    <span class="fw-bold {{ $statusClass }}"><i class="fas fa-circle me-1" style="font-size: 5px;"></i> {{ strtoupper($status) }}</span>
                    
                    <span>&middot;</span>
                    <span>{{ str_pad($opening->id, 4, '0', STR_PAD_LEFT) }}</span>
                    
                    <span>&middot;</span>
                    <span>{{ $opening->job_location ?? 'N/A' }}</span>
                    
                    <span>&middot;</span>
                    <span>{{ $opening->ctc_salary ?? 'N/A' }}</span>
                    
                    <span>&middot;</span>
                    <span>{{ $opening->experience_required ?? 'Fresher' }}</span>
                    
                    <span>&middot;</span>
                    <span>{{ $opening->number_of_openings ?? '0' }} openings</span>
                </div>
            </div>
            <div class="d-flex gap-2 ms-auto">
                @can('Current Openings Edit')
                <a href="{{ route('current-openings.edit', encrypt($opening->id)) }}" class="btn btn-outline-secondary shadow-sm fw-bold px-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-edit me-2"></i> Edit
                </a>
                @endcan
                
                @if(auth()->check() && (auth()->user()->hasRole('Student Coordinator') || auth()->user()->hasRole('Placement Coordinator') || auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
                <button type="button" class="btn btn-outline-info shadow-sm fw-bold px-3 d-flex align-items-center justify-content-center"
                    onclick="sendOpeningEmail('{{ route('current-openings.send-mail', encrypt($opening->id)) }}', '{{ addslashes($opening->hr_email_id ?? $opening->company_mail_id ?? ($opening->company->hr_email ?? '')) }}', '{{ addslashes($opening->job_role) }}', '{{ addslashes($companyName) }}')">
                    <i class="fas fa-envelope me-2"></i> Send Mail
                </button>
                @endif
                
                @if(auth()->check() && (auth()->user()->hasRole('Student Coordinator') || auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
                <a href="{{ route('eligible-students.index') }}?tab=eligible&opening={{ encrypt($opening->id) }}" 
                    class="btn btn-primary shadow-sm fw-bold px-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-paper-plane me-2"></i> Push Openings
                </a>
                @endif
                
                @if(auth()->check() && (auth()->user()->hasRole('Placement Coordinator') || auth()->user()->hasRole('Student Coordinator') || auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
                <button type="button" class="btn btn-info text-white shadow-sm fw-bold px-3 d-flex align-items-center justify-content-center" onclick="openScheduleInterviewModal()">
                    <i class="fas fa-calendar-check me-2"></i> Schedule Interview
                </button>
                @endif
            </div>
        </div>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Company Mail</span>
            <span class="info-value" style="word-break: break-word;">{{ $opening->company_mail_id ?? ($opening->company->company_mail_id ?? 'N/A') }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">HR Name</span>
            <span class="info-value">{{ $opening->hr_name ?? ($opening->company->hr_name ?? 'N/A') }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">HR Phone</span>
            <span class="info-value">{{ $opening->hr_contact_number ?? ($opening->company->hr_contact_number ?? 'N/A') }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">HR Email</span>
            <span class="info-value" style="word-break: break-word;">{{ $opening->hr_email_id ?? ($opening->company->hr_email_id ?? 'N/A') }}</span>
        </div>

        <div class="info-item">
            <span class="info-label">Job Location</span>
            <span class="info-value">{{ $opening->job_location ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Qualification</span>
            <span class="info-value">{{ $opening->minimum_qualification ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Experience</span>
            <span class="info-value">{{ $opening->experience_required ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">CTC / Salary</span>
            <span class="info-value">{{ $opening->ctc_salary ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Openings Count</span>
            <span class="info-value">{{ $opening->number_of_openings ?? '0' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Application Deadline</span>
            <span class="info-value">{{ $opening->application_deadline ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Interview Mode</span>
            <span class="info-value">{{ $opening->interview_mode ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Interview Process</span>
            <span class="info-value">{{ $opening->interview_process ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Bond</span>
            <span class="info-value">{{ $opening->bond_service_agmt ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Date Added</span>
            <span class="info-value">{{ $opening->created_at ? $opening->created_at->format('j M Y') : 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Added By</span>
            <span class="info-value">{{ $opening->staff->name ?? 'Admin' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Skills</span>
            <span class="info-value">{{ $opening->skills_required ?? 'N/A' }}</span>
        </div>
    </div>
</div>

<!-- Job Description Card -->
<div class="detail-card">
    <div class="card-subheader">
        <span><i class="fas fa-file-alt text-muted me-2"></i> Job Description</span>
        <button class="btn btn-sm btn-light shadow-none fw-bold"><i class="fas fa-copy me-1"></i> Copy</button>
    </div>
    <div class="p-4" style="color: #475569; font-size: 0.9rem; white-space: pre-line;">
        {{ $opening->job_description_text ?? 'No description provided.' }}
    </div>
</div>

<!-- Interview Status Summary Card -->
<div class="detail-card">
    <div class="card-subheader">
        <span><i class="fas fa-chart-pie text-primary me-2"></i> Interview Status Summary</span>
    </div>
    <div class="p-3 p-md-4">
        <div class="mini-summary">
            <div class="mini-stat accent">
                <div class="mlbl">Scheduled</div>
                <div class="mval">{{ $interviewSummary['scheduled'] ?? 0 }}</div>
            </div>
            <div class="mini-stat green">
                <div class="mlbl">Attended</div>
                <div class="mval">{{ $interviewSummary['attended'] ?? 0 }}</div>
            </div>
            <div class="mini-stat orange">
                <div class="mlbl">Not Attended</div>
                <div class="mval">{{ $interviewSummary['not_attended'] ?? 0 }}</div>
            </div>
            <div class="mini-stat green">
                <div class="mlbl">Selected</div>
                <div class="mval">{{ $interviewSummary['selected'] ?? 0 }}</div>
            </div>
            <div class="mini-stat red">
                <div class="mlbl">Rejected</div>
                <div class="mval">{{ $interviewSummary['rejected'] ?? 0 }}</div>
            </div>
            <div class="mini-stat purple">
                <div class="mlbl">Hold</div>
                <div class="mval">{{ $interviewSummary['hold'] ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Interviews Scheduled Card -->
<div class="detail-card">
    <div class="card-subheader d-flex align-items-center justify-content-between flex-wrap gap-2">
        <span>
            <i class="fas fa-calendar-check text-primary me-2"></i> Interviews Scheduled 
            <small class="text-muted fw-normal ms-1">({{ count($interviews ?? []) }})</small>
        </span>
        @if(auth()->check() && (auth()->user()->hasRole('Placement Coordinator') || auth()->user()->hasRole('Student Coordinator') || auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm fw-bold d-flex align-items-center" onclick="openScheduleInterviewModal()">
            <i class="fas fa-plus me-1"></i> Schedule Interview
        </button>
        @endif
    </div>
    <div class="p-3 p-md-4">
        @if(isset($interviews) && count($interviews) > 0)
            <div class="strip-list">
                @foreach($interviews as $intv)
                    @php
                        $intvId = 'S' . str_pad($intv->id, 3, '0', STR_PAD_LEFT);
                        $formattedDate = $intv->date ? \Carbon\Carbon::parse($intv->date)->format('d M') : 'N/A';
                        $formattedTime = $intv->time ? \Carbon\Carbon::parse($intv->time)->format('h:i A') : 'N/A';
                        
                        $rawStatus = strtolower($intv->status ?? 'scheduled');
                        $rawAttendance = strtolower($intv->attendance ?? '');
                        $rawResult = strtolower($intv->result ?? '');
                        
                        $tagCls = 'tag-scheduled';
                        $tagLabel = 'SCHEDULED';
                        
                        if ($rawResult === 'selected') {
                            $tagCls = 'tag-selected';
                            $tagLabel = 'SELECTED';
                        } elseif ($rawResult === 'rejected') {
                            $tagCls = 'tag-rejected';
                            $tagLabel = 'REJECTED';
                        } elseif (in_array($rawResult, ['on hold', 'hold'])) {
                            $tagCls = 'tag-hold';
                            $tagLabel = 'HOLD';
                        } elseif (in_array($rawAttendance, ['not attended', 'no show'])) {
                            $tagCls = 'tag-notattended';
                            $tagLabel = 'NOT ATTENDED';
                        } elseif ($rawAttendance === 'attended') {
                            $tagCls = 'tag-attended';
                            $tagLabel = 'ATTENDED';
                        } elseif ($rawStatus === 'selected') {
                            $tagCls = 'tag-selected';
                            $tagLabel = 'SELECTED';
                        } elseif ($rawStatus === 'rejected') {
                            $tagCls = 'tag-rejected';
                            $tagLabel = 'REJECTED';
                        } elseif (in_array($rawStatus, ['on hold', 'hold'])) {
                            $tagCls = 'tag-hold';
                            $tagLabel = 'HOLD';
                        } elseif (in_array($rawStatus, ['not attended', 'no show', 'not_attended', 'no_show'])) {
                            $tagCls = 'tag-notattended';
                            $tagLabel = 'NOT ATTENDED';
                        } elseif ($rawStatus === 'attended') {
                            $tagCls = 'tag-attended';
                            $tagLabel = 'ATTENDED';
                        }
                    @endphp
                    <div class="strip" onclick="window.location.href='{{ route('schedule-interviews.show', encrypt($intv->id)) }}'" title="Click to view candidate details, interview timeline and call history">
                        <div class="strip-cell strip-id">
                            <div class="lbl">ID</div>
                            <div class="val mono">{{ $intvId }}</div>
                        </div>
                        <div class="strip-cell strip-student">
                            <div class="lbl">STUDENT</div>
                            <div class="val text-primary fw-bold">{{ $intv->name }}</div>
                        </div>
                        <div class="strip-cell strip-date">
                            <div class="lbl">DATE</div>
                            <div class="val mono">{{ $formattedDate }}</div>
                        </div>
                        <div class="strip-cell strip-time">
                            <div class="lbl">TIME</div>
                            <div class="val mono">{{ $formattedTime }}</div>
                        </div>
                        <div class="strip-cell strip-mode">
                            <div class="lbl">MODE</div>
                            <div class="val muted">{{ $intv->interview_mode ?? 'Online' }}</div>
                        </div>
                        <div class="strip-cell grow text-end d-flex align-items-center justify-content-end gap-2">
                            @if(!empty($intv->meeting_link))
                                <a href="{{ $intv->meeting_link }}" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1 text-decoration-none" onclick="event.stopPropagation();" title="Join Meeting">
                                    <i class="fas fa-video me-1"></i> Join
                                </a>
                            @endif
                            <span class="tag {{ $tagCls }}">
                                <span class="dot"></span>
                                {{ $tagLabel }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <div class="mb-2">
                    <i class="fas fa-calendar-times fa-3x text-secondary opacity-25"></i>
                </div>
                <h6 class="fw-bold text-secondary mb-1">No Interviews Scheduled Yet</h6>
                <p class="text-muted small mb-3">There are no candidates scheduled for this job opening at present.</p>
                @if(auth()->check() && (auth()->user()->hasRole('Placement Coordinator') || auth()->user()->hasRole('Student Coordinator') || auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold" onclick="openScheduleInterviewModal()">
                    <i class="fas fa-plus me-1"></i> Schedule First Interview
                </button>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Call History Timeline Card -->
<div class="detail-card">
    <div class="card-subheader">
        <span><i class="fas fa-history text-primary me-2"></i> Timeline <span class="text-muted fw-normal ms-1">(Previous calls)</span></span>
        <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#callLogModal">
            <i class="fas fa-plus me-1"></i> Add Entry
        </button>
    </div>
    
    <div class="p-4">
        @php
            $currentLog = null;
            $previousLogs = collect();
            
            if ($callLogs && count($callLogs) > 0) {
                $currentLog = $callLogs->first();
                $previousLogs = $callLogs->slice(1);
            }
        @endphp

        {{-- PREVIOUS SECTION --}}
        <div class="timeline-section mb-5">
            <div class="p-3 rounded-3 d-flex align-items-center justify-content-between mb-2"
                style="cursor: pointer; background: #f1f3f5; transition: all 0.2s;"
                data-bs-toggle="collapse" data-bs-target="#previousTimeline" aria-expanded="false">
                <div class="d-flex align-items-center">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.75rem;">PREVIOUS</span>
                    <span class="text-muted ms-2" style="font-size: 0.8rem;">({{ count($previousLogs) }} records - already spoken)</span>
                </div>
                <i class="fas fa-chevron-down text-muted"></i>
            </div>

            <div class="collapse" id="previousTimeline">
                <div class="table-responsive mt-3 border rounded">
                    <table class="call-history-table mb-0">
                        <thead>
                            <tr>
                                <th>TIME</th>
                                <th>DIRECTION/TYPE</th>
                                <th>RECORDING</th>
                                <th>DURATION</th>
                                <th>OWNED BY</th>
                                <th>NOTES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($previousLogs as $log)
                            <tr>
                                <td>{{ $log->call_start ? \Carbon\Carbon::parse($log->call_start)->format('d M, h:i A') : 'N/A' }}</td>
                                <td>
                                    @if(strtolower($log->direction) == 'inbound')
                                        <span class="badge bg-light text-primary border border-primary"><i class="fas fa-arrow-down me-1"></i> Inbound</span>
                                    @else
                                        <span class="badge bg-light text-success border border-success"><i class="fas fa-arrow-up me-1"></i> Outbound</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->recording_url)
                                        <a href="{{ $log->recording_url }}" target="_blank" class="text-primary"><i class="fas fa-play-circle"></i> Listen</a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $log->call_duration ?? '0' }}s</td>
                                <td>{{ $log->staff->name ?? 'System' }}</td>
                                <td>{{ $log->note ?? 'No notes added.' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted small">No previous call history found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- CURRENT SECTION --}}
        @if($currentLog)
        <div class="timeline-section mb-2">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-circle text-primary me-2" style="font-size: 0.5rem;"></i>
                <span class="text-uppercase fw-bold text-primary me-2" style="font-size: 0.75rem;">CURRENT</span>
                <span class="text-muted" style="font-size: 0.8rem;">(Most recent conversation)</span>
            </div>
            
            <div class="table-responsive mt-3 border rounded">
                <table class="call-history-table mb-0">
                    <thead>
                        <tr>
                            <th>TIME</th>
                            <th>DIRECTION/TYPE</th>
                            <th>RECORDING</th>
                            <th>DURATION</th>
                            <th>OWNED BY</th>
                            <th>NOTES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $currentLog->call_start ? \Carbon\Carbon::parse($currentLog->call_start)->format('d M, h:i A') : 'N/A' }}</td>
                            <td>
                                @if(strtolower($currentLog->direction) == 'inbound')
                                    <span class="badge bg-light text-primary border border-primary"><i class="fas fa-arrow-down me-1"></i> Inbound</span>
                                @else
                                    <span class="badge bg-light text-success border border-success"><i class="fas fa-arrow-up me-1"></i> Outbound</span>
                                @endif
                            </td>
                            <td>
                                @if($currentLog->recording_url)
                                    <a href="{{ $currentLog->recording_url }}" target="_blank" class="text-primary"><i class="fas fa-play-circle"></i> Listen</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $currentLog->call_duration ?? '0' }}s</td>
                            <td>{{ $currentLog->staff->name ?? 'System' }}</td>
                            <td>{{ $currentLog->note ?? 'No notes added.' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>

<!-- Call Log Modal -->
<div class="modal fade" id="callLogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Log a Call</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('current-openings.call-log.store', encrypt($opening->id)) }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Call Note <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="note" rows="4" placeholder="Enter details about the call with HR..." required></textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-secondary shadow-none px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary shadow-none px-4 fw-bold">Save Log</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Interview Modal Overlay & Modal -->
<div class="modal-overlay" id="modalOverlay" onclick="if(event.target === this) closeModal()">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modalTitle">
        <i class="fas fa-calendar-alt text-primary me-2"></i> Schedule Interview
      </div>
      <div class="modal-close" onclick="closeModal()" title="Close">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </div>
    </div>
    <div class="modal-body" id="modalBody">
      @if(isset($students) && count($students) > 0)
      <div class="mb-3 p-3 rounded" style="background: #f0fdf4; border: 1.5px dashed #86efac;">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <label class="form-label" style="color: #166534; font-weight: 700; margin: 0;">
            <i class="fas fa-user-check me-1"></i> Quick Select Enrolled Student (Optional)
          </label>
          <span class="text-muted" style="font-size: 11px;">Search & select to auto-fill details</span>
        </div>
        <select class="form-select select2-student" id="quickSelectStudent" style="width: 100%;">
          <option value=""></option>
          @foreach($students as $st)
            @php
              $stName = $st->contact->name ?? ($st->admission_no ?? 'Student #' . $st->id);
              $stPhone = $st->contact->caller_number ?? ($st->contact->phone ?? '');
              $stEmail = $st->contact->email ?? '';
              $stCourse = $st->course->title ?? ($st->course->course_name ?? '');
              $stDuration = $st->course->duration ?? '3 Months';
              $stBatch = $st->batch->batch_code ?? ($st->batch_code ?? '');
              $stBatchType = $st->batch->batch_type ?? 'Weekday';
              
              $labelParts = [$stName];
              if (!empty($stCourse)) {
                  $labelParts[] = $stCourse;
              }
              if (!empty($stBatch)) {
                  $labelParts[] = '(' . $stBatch . ')';
              }
              $optionLabel = implode(' · ', $labelParts);
            @endphp
            <option value="{{ $st->id }}"
              data-id="{{ $st->id }}"
              data-name="{{ $stName }}"
              data-phone="{{ $stPhone }}"
              data-email="{{ $stEmail }}"
              data-course="{{ $stCourse }}"
              data-duration="{{ $stDuration }}"
              data-batch="{{ $stBatch }}"
              data-batchtype="{{ $stBatchType }}">
              {{ $optionLabel }}
            </option>
          @endforeach
        </select>
      </div>
      @endif

      <form id="scheduleInterviewForm" onsubmit="event.preventDefault(); submitModal();">
        <input type="hidden" name="user_id" id="modalStudentUserId" value="">
        <div class="form-grid">
          <div class="form-group full">
            <label class="form-label">Student Name *</label>
            <input class="form-input" type="text" name="name" id="modalStudentName" placeholder="Enter student name" required value="">
          </div>
          <div class="form-group ">
            <label class="form-label">Phone Number *</label>
            <input class="form-input" type="tel" name="phone" id="modalStudentPhone" placeholder="Enter contact phone" required value="">
          </div>
          <div class="form-group ">
            <label class="form-label">Email *</label>
            <input class="form-input" type="email" name="email" id="modalStudentEmail" placeholder="student@example.com" required value="">
          </div>
          <div class="form-group ">
            <label class="form-label">Course *</label>
            <input class="form-input" type="text" name="course" id="modalCourse" placeholder="e.g. Full Stack Development" required value="">
          </div>
          <div class="form-group ">
            <label class="form-label">Course Duration *</label>
            <input class="form-input" type="text" name="duration" id="modalDuration" placeholder="e.g. 3 Months" required value="">
          </div>
          <div class="form-group ">
            <label class="form-label">Batch Code *</label>
            <input class="form-input" type="text" name="batchCode" id="modalBatchCode" placeholder="e.g. FSD-2026-B1" required value="">
          </div>
          <div class="form-group ">
            <label class="form-label">Batch Type *</label>
            <select class="form-select" name="batchType" id="modalBatchType">
              <option value="Weekday">Weekday</option>
              <option value="Weekend">Weekend</option>
              <option value="Bootcamp">Bootcamp</option>
              <option value="Online">Online</option>
            </select>
          </div>
          <div class="form-group ">
            <label class="form-label">Company Name *</label>
            <input class="form-input" type="text" name="company" id="modalCompany" value="{{ $companyName }}" readonly="readonly">
          </div>
          <div class="form-group ">
            <label class="form-label">Job Role</label>
            <input class="form-input" type="text" name="role" id="modalRole" value="{{ $opening->job_role }}" readonly="readonly">
          </div>
          <div class="form-group ">
            <label class="form-label">Location</label>
            <input class="form-input" type="text" name="location" id="modalLocation" value="{{ $opening->job_location }}" readonly="readonly">
          </div>
          <div class="form-group ">
            <label class="form-label">Scheduled Date *</label>
            <input class="form-input" type="date" name="date" id="modalDate" value="{{ date('Y-m-d') }}" required>
          </div>
          <div class="form-group ">
            <label class="form-label">Scheduled Time *</label>
            <input class="form-input" type="time" name="time" id="modalTime" value="10:00" required>
          </div>
          <div class="form-group ">
            <label class="form-label">Interview Mode *</label>
            <select class="form-select" name="interviewMode" id="modalInterviewMode">
              <option value="Online">Online</option>
              <option value="Offline">Offline</option>
              <option value="Walk-in">Walk-in</option>
              <option value="Hybrid">Hybrid</option>
            </select>
          </div>
          <div class="form-group full">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
              <label class="form-label" style="margin: 0;">Meeting Link <span class="text-muted fw-normal" style="text-transform: none; font-size: 11px;">(Optional)</span></label>
              <button type="button" class="btn btn-sm" style="background: #0284c7; color: #ffffff; font-size: 11.5px; font-weight: 600; padding: 4px 12px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; border: none;" onclick="generateInterviewZoomLink()" id="btnGenerateZoom">
                <i class="fas fa-video"></i>
                <span id="btnZoomText">Generate Zoom Meeting Link</span>
              </button>
            </div>
            <div style="position: relative; display: flex; align-items: center;">
              <input class="form-input" type="url" name="meetingLink" id="modalMeetingLink" placeholder="Paste link or click 'Generate Zoom Meeting Link' (Optional)" value="" style="padding-right: 36px;" oninput="toggleClearZoomBtn()">
              <button type="button" id="btnClearZoomLink" onclick="clearMeetingLink()" title="Clear meeting link" style="display: none; position: absolute; right: 10px; background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px; font-size: 14px;">
                <i class="fas fa-times-circle"></i>
              </button>
            </div>
            <div id="zoomNoticeMessage" class="small mt-1 text-success fw-semibold" style="display: none; font-size: 11.5px;">
              <i class="fas fa-check-circle me-1"></i> <span id="zoomNoticeText">Zoom meeting link generated and inserted!</span>
            </div>
            <div class="text-muted mt-1" style="font-size: 11px;">
              <i class="fas fa-info-circle me-1"></i> Meeting link is optional. It will <strong>NOT</strong> be generated automatically unless you click the <strong>Generate Zoom Meeting Link</strong> button above.
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
      <button type="button" class="btn btn-primary" onclick="submitModal()" id="modalSubmitBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span id="modalSubmitText">Done</span>
      </button>
    </div>
  </div>
</div>

<!-- Interview Detail & Outcome Modal Overlay -->
<div class="modal-overlay" id="interviewDetailModalOverlay" onclick="if(event.target === this) closeInterviewDetailModal()">
  <div class="modal" style="max-width: 680px;">
    <div class="modal-header">
      <div class="modal-title">
        <i class="fas fa-id-badge text-primary me-2"></i> Interview Details
        <span class="badge bg-light text-dark border ms-2" id="detailModalId"></span>
      </div>
      <div class="modal-close" onclick="closeInterviewDetailModal()" title="Close">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </div>
    </div>
    <div class="modal-body" style="padding: 20px 24px;">
      <!-- Student & Interview Summary Header -->
      <div class="d-flex align-items-center justify-content-between p-3 rounded mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
        <div>
          <h5 class="fw-bold text-dark mb-1" id="detailModalName"></h5>
          <div class="text-muted small" id="detailModalSub"></div>
        </div>
        <div id="detailModalStatusBadge"></div>
      </div>

      <!-- Quick Info Grid -->
      <div class="row g-3 mb-3">
        <div class="col-sm-6">
          <div class="p-3 rounded border bg-light h-100">
            <div class="text-muted small fw-bold text-uppercase mb-2"><i class="fas fa-user-graduate me-1 text-primary"></i> Academic Profile</div>
            <div class="small mb-1"><strong>Course:</strong> <span id="detailModalCourse"></span></div>
            <div class="small mb-1"><strong>Batch:</strong> <span id="detailModalBatch"></span></div>
            <div class="small"><strong>Duration:</strong> <span id="detailModalDuration"></span></div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="p-3 rounded border bg-light h-100">
            <div class="text-muted small fw-bold text-uppercase mb-2"><i class="fas fa-calendar-alt me-1 text-primary"></i> Schedule Info</div>
            <div class="small mb-1"><strong>Date & Time:</strong> <span id="detailModalDateTime"></span></div>
            <div class="small mb-1"><strong>Mode:</strong> <span id="detailModalMode"></span></div>
            <div class="small"><strong>Location:</strong> <span id="detailModalLocation"></span></div>
          </div>
        </div>
      </div>

      <!-- Contact & Links -->
      <div class="p-3 rounded border mb-3" style="background: #ffffff;">
        <div class="row g-2">
          <div class="col-sm-6">
            <span class="text-muted small"><strong>Phone:</strong> <span id="detailModalPhone"></span></span>
          </div>
          <div class="col-sm-6">
            <span class="text-muted small"><strong>Email:</strong> <span id="detailModalEmail"></span></span>
          </div>
          <div class="col-12 mt-2" id="detailModalLinkContainer" style="display: none;">
            <div class="p-2 rounded d-flex align-items-center justify-content-between" style="background: #eff6ff; border: 1px solid #bfdbfe;">
              <div class="text-truncate me-2 small">
                <i class="fas fa-video text-primary me-1"></i>
                <a href="#" id="detailModalLinkAnchor" target="_blank" class="fw-bold text-primary text-decoration-none"></a>
              </div>
              <a href="#" id="detailModalLinkBtn" target="_blank" class="btn btn-xs btn-primary rounded-pill px-3 py-1 text-nowrap fw-bold">
                Join Meeting
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Outcome & Status Update Section -->
      @if(auth()->check() && (auth()->user()->hasRole('Placement Coordinator') || auth()->user()->hasRole('Student Coordinator') || auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
      <div class="p-3 rounded border" style="background: #fdfdfd; border-color: #e2e8f0;">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <span class="fw-bold text-dark"><i class="fas fa-tasks text-success me-2"></i> Update Interview Outcome</span>
        </div>
        <form id="interviewOutcomeForm" onsubmit="submitInterviewOutcome(event)">
          <input type="hidden" id="outcomeInterviewId" value="">
          <input type="hidden" id="outcomeUrl" value="">
          <div class="row g-2">
            <div class="col-sm-6">
              <label class="form-label small fw-bold">Attendance Status *</label>
              <select class="form-select form-select-sm" id="outcomeAttendance" required onchange="toggleOutcomeFields()">
                <option value="Attended">Attended</option>
                <option value="Not Attended">Not Attended</option>
                <option value="No Show">No Show</option>
              </select>
            </div>
            <div class="col-sm-6" id="outcomeResultCol">
              <label class="form-label small fw-bold">Interview Result</label>
              <select class="form-select form-select-sm" id="outcomeResult">
                <option value="">-- Select Result --</option>
                <option value="Selected">Selected</option>
                <option value="Rejected">Rejected</option>
                <option value="On Hold">On Hold</option>
                <option value="Next Round">Next Round</option>
              </select>
            </div>
            <div class="col-12" id="outcomeFeedbackCol">
              <label class="form-label small fw-bold">HR Feedback / Notes</label>
              <textarea class="form-control form-control-sm" id="outcomeHrFeedback" rows="2" placeholder="Feedback from HR or company interviewer..."></textarea>
            </div>
            <div class="col-12" id="outcomeReasonCol" style="display: none;">
              <label class="form-label small fw-bold text-danger">Reason for Absence *</label>
              <textarea class="form-control form-control-sm" id="outcomeReason" rows="2" placeholder="Reason why candidate did not attend..."></textarea>
            </div>
          </div>
          <div class="mt-3 text-end">
            <button type="submit" class="btn btn-sm btn-success px-4 fw-bold shadow-sm" id="saveOutcomeBtn">
              <i class="fas fa-check me-1"></i> Save Outcome
            </button>
          </div>
        </form>
      </div>
      @endif
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary btn-sm px-4" onclick="closeInterviewDetailModal()">Close</button>
    </div>
  </div>
</div>

<script>
function openScheduleInterviewModal() {
    const modal = document.getElementById('modalOverlay');
    if (modal) {
        resetScheduleModal();
        modal.classList.add('show');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        toggleClearZoomBtn();
        setTimeout(function() {
            initModalSelect2();
        }, 30);
    }
}

function closeModal() {
    const modal = document.getElementById('modalOverlay');
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = '';
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            $('.select2-hidden-accessible').select2('close');
        }
        resetScheduleModal();
    }
}

function resetScheduleModal() {
    // 1. Reset form element
    const form = document.getElementById('scheduleInterviewForm');
    if (form) form.reset();

    // 2. Clear student inputs
    clearStudentFormFields();

    // 3. Reset Select2 student dropdown
    const $studentSelect = $('#quickSelectStudent');
    if ($studentSelect.length) {
        if ($studentSelect.hasClass('select2-hidden-accessible')) {
            $studentSelect.val('').trigger('change.select2');
        } else {
            $studentSelect.val('');
        }
    }

    // 4. Reset Batch Type & Interview Mode
    const $batchType = $('#modalBatchType');
    if ($batchType.length && $batchType.hasClass('select2-hidden-accessible')) {
        $batchType.val('Weekday').trigger('change.select2');
    }
    const $interviewMode = $('#modalInterviewMode');
    if ($interviewMode.length && $interviewMode.hasClass('select2-hidden-accessible')) {
        $interviewMode.val('Online').trigger('change.select2');
    }

    // 5. Restore Opening prefilled details
    const compEl = document.getElementById('modalCompany');
    if (compEl) compEl.value = {!! json_encode($companyName) !!};
    const roleEl = document.getElementById('modalRole');
    if (roleEl) roleEl.value = {!! json_encode($opening->job_role ?? '') !!};
    const locEl = document.getElementById('modalLocation');
    if (locEl) locEl.value = {!! json_encode($opening->job_location ?? '') !!};

    // 6. Reset Date & Time
    const dateEl = document.getElementById('modalDate');
    if (dateEl) dateEl.value = '{{ date("Y-m-d") }}';
    const timeEl = document.getElementById('modalTime');
    if (timeEl) timeEl.value = '10:00';

    // 7. Clear meeting link
    clearMeetingLink();
}

function initModalSelect2() {
    if (typeof jQuery === 'undefined' || typeof jQuery.fn.select2 === 'undefined') return;

    const $studentSelect = $('#quickSelectStudent');
    if ($studentSelect.length && !$studentSelect.hasClass('select2-hidden-accessible')) {
        $studentSelect.select2({
            dropdownParent: $('#modalOverlay'),
            width: '100%',
            placeholder: '-- Search Candidate by Name, Course, or Batch --',
            allowClear: true,
            templateResult: function(state) {
                if (!state.id) return state.text;
                var $el = $(state.element);
                var name = $el.data('name') || state.text;
                var course = $el.data('course') || '';
                var batch = $el.data('batch') || '';
                return $(
                    '<div class="d-flex justify-content-between align-items-center py-1">' +
                        '<div>' +
                            '<strong class="text-dark">' + $('<div>').text(name).html() + '</strong>' +
                            (course ? '<span class="text-muted ms-2 small">' + $('<div>').text(course).html() + '</span>' : '') +
                        '</div>' +
                        (batch ? '<span class="badge bg-light text-primary border ms-2">' + $('<div>').text(batch).html() + '</span>' : '') +
                    '</div>'
                );
            },
            templateSelection: function(state) {
                if (!state.id) return state.text;
                var $el = $(state.element);
                var name = $el.data('name') || state.text;
                var course = $el.data('course') || '';
                var batch = $el.data('batch') || '';
                if (batch && course) {
                    return name + ' · ' + course + ' (' + batch + ')';
                } else if (batch) {
                    return name + ' · (' + batch + ')';
                } else if (course) {
                    return name + ' · ' + course;
                }
                return name;
            }
        });

        $studentSelect.on('select2:select change', function() {
            fillStudentDetails(this);
        }).on('select2:clear', function() {
            clearStudentFormFields();
        });
    }

    // Also style Batch Type and Interview Mode with Select2
    $('#modalBatchType, #modalInterviewMode').each(function() {
        if ($(this).length && !$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                dropdownParent: $('#modalOverlay'),
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        }
    });
}

function clearStudentFormFields() {
    document.getElementById('modalStudentName').value = '';
    document.getElementById('modalStudentPhone').value = '';
    document.getElementById('modalStudentEmail').value = '';
    document.getElementById('modalCourse').value = '';
    document.getElementById('modalDuration').value = '';
    document.getElementById('modalBatchCode').value = '';
    document.getElementById('modalStudentUserId').value = '';
}

function toggleClearZoomBtn() {
    const input = document.getElementById('modalMeetingLink');
    const clearBtn = document.getElementById('btnClearZoomLink');
    if (clearBtn) {
        clearBtn.style.display = (input && input.value.trim().length > 0) ? 'inline-block' : 'none';
    }
}

function clearMeetingLink() {
    const input = document.getElementById('modalMeetingLink');
    const clearBtn = document.getElementById('btnClearZoomLink');
    const notice = document.getElementById('zoomNoticeMessage');
    if (input) input.value = '';
    if (clearBtn) clearBtn.style.display = 'none';
    if (notice) notice.style.display = 'none';
}

// Close on Escape key press
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeInterviewDetailModal();
    }
});

function openInterviewInfoModal(data) {
    if (!data) return;
    const modal = document.getElementById('interviewDetailModalOverlay');
    if (!modal) return;

    // Header info
    document.getElementById('detailModalId').innerText = data.display_id || ('S' + data.id);
    document.getElementById('detailModalName').innerText = data.name || 'Candidate';
    document.getElementById('detailModalSub').innerText = (data.company || '') + (data.role ? ' · ' + data.role : '');
    
    // Status Badge
    const badgeEl = document.getElementById('detailModalStatusBadge');
    if (badgeEl) {
        badgeEl.innerHTML = '<span class="tag ' + (data.tag_cls || 'tag-scheduled') + '"><span class="dot"></span>' + (data.status || 'SCHEDULED') + '</span>';
    }

    // Academic Profile
    document.getElementById('detailModalCourse').innerText = data.course || 'N/A';
    document.getElementById('detailModalBatch').innerText = (data.batch_code || 'N/A') + (data.batch_type ? ' (' + data.batch_type + ')' : '');
    document.getElementById('detailModalDuration').innerText = data.duration || 'N/A';

    // Schedule Info
    document.getElementById('detailModalDateTime').innerText = (data.date || '') + ' at ' + (data.time || '');
    document.getElementById('detailModalMode').innerText = data.interview_mode || 'Online';
    document.getElementById('detailModalLocation').innerText = data.location || 'N/A';

    // Contact
    document.getElementById('detailModalPhone').innerText = data.phone || 'N/A';
    document.getElementById('detailModalEmail').innerText = data.email || 'N/A';

    // Meeting Link
    const linkContainer = document.getElementById('detailModalLinkContainer');
    const linkAnchor = document.getElementById('detailModalLinkAnchor');
    const linkBtn = document.getElementById('detailModalLinkBtn');
    if (data.meeting_link && data.meeting_link.trim()) {
        linkContainer.style.display = 'block';
        linkAnchor.href = data.meeting_link;
        linkAnchor.innerText = data.meeting_link;
        linkBtn.href = data.meeting_link;
    } else {
        linkContainer.style.display = 'none';
    }

    // Outcome Form
    const idEl = document.getElementById('outcomeInterviewId');
    const urlEl = document.getElementById('outcomeUrl');
    const attEl = document.getElementById('outcomeAttendance');
    const resEl = document.getElementById('outcomeResult');
    const feedEl = document.getElementById('outcomeHrFeedback');
    const rsnEl = document.getElementById('outcomeReason');

    if (idEl) idEl.value = data.id || '';
    if (urlEl) urlEl.value = data.outcome_url || '';
    if (attEl) attEl.value = data.attendance || 'Attended';
    if (resEl) resEl.value = data.result || '';
    if (feedEl) feedEl.value = data.hr_feedback || '';
    if (rsnEl) rsnEl.value = data.reason || '';

    toggleOutcomeFields();

    modal.classList.add('show');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeInterviewDetailModal() {
    const modal = document.getElementById('interviewDetailModalOverlay');
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function toggleOutcomeFields() {
    const attVal = document.getElementById('outcomeAttendance')?.value;
    const resCol = document.getElementById('outcomeResultCol');
    const feedCol = document.getElementById('outcomeFeedbackCol');
    const rsnCol = document.getElementById('outcomeReasonCol');
    const rsnInput = document.getElementById('outcomeReason');

    if (attVal === 'Attended') {
        if (resCol) resCol.style.display = 'block';
        if (feedCol) feedCol.style.display = 'block';
        if (rsnCol) rsnCol.style.display = 'none';
        if (rsnInput) rsnInput.required = false;
    } else {
        if (resCol) resCol.style.display = 'none';
        if (feedCol) feedCol.style.display = 'none';
        if (rsnCol) rsnCol.style.display = 'block';
        if (rsnInput) rsnInput.required = true;
    }
}

function submitInterviewOutcome(e) {
    if (e) e.preventDefault();
    const url = document.getElementById('outcomeUrl')?.value;
    if (!url) {
        alert('Outcome endpoint URL missing.');
        return;
    }

    const attendance = document.getElementById('outcomeAttendance')?.value;
    const result = document.getElementById('outcomeResult')?.value;
    const hrFeedback = document.getElementById('outcomeHrFeedback')?.value;
    const reason = document.getElementById('outcomeReason')?.value;

    const btn = document.getElementById('saveOutcomeBtn');
    if (btn) btn.disabled = true;

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            attendance: attendance,
            result: attendance === 'Attended' ? result : null,
            hr_feedback: attendance === 'Attended' ? hrFeedback : null,
            reason: attendance !== 'Attended' ? reason : null
        },
        success: function(res) {
            closeInterviewDetailModal();
            const msg = res.message || 'Interview outcome updated successfully!';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Outcome Updated',
                    text: msg,
                    confirmButtonColor: '#2563eb'
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.success(msg);
            } else {
                alert(msg);
            }
            setTimeout(function() {
                location.reload();
            }, 1200);
        },
        error: function(err) {
            let errorMsg = 'Failed to update interview outcome.';
            if (err.responseJSON && err.responseJSON.errors) {
                errorMsg = Object.values(err.responseJSON.errors).flat().join('<br>');
            } else if (err.responseJSON && err.responseJSON.message) {
                errorMsg = err.responseJSON.message;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    html: errorMsg,
                    confirmButtonColor: '#ef4444'
                });
            } else {
                alert(errorMsg);
            }
        },
        complete: function() {
            if (btn) btn.disabled = false;
        }
    });
}

function fillStudentDetails(select) {
    if (!select || !select.value) {
        clearStudentFormFields();
        return;
    }
    const opt = select.options[select.selectedIndex];
    if (!opt) return;

    document.getElementById('modalStudentName').value = opt.dataset.name || '';
    document.getElementById('modalStudentPhone').value = opt.dataset.phone || '';
    document.getElementById('modalStudentEmail').value = opt.dataset.email || '';
    document.getElementById('modalCourse').value = opt.dataset.course || '';
    document.getElementById('modalDuration').value = opt.dataset.duration || '';
    document.getElementById('modalBatchCode').value = opt.dataset.batch || '';

    const batchtype = opt.dataset.batchtype || 'Weekday';
    const batchTypeEl = document.getElementById('modalBatchType');
    if (batchTypeEl) {
        batchTypeEl.value = batchtype;
        if (typeof jQuery !== 'undefined' && $('#modalBatchType').hasClass('select2-hidden-accessible')) {
            $('#modalBatchType').val(batchtype).trigger('change.select2');
        }
    }
    document.getElementById('modalStudentUserId').value = opt.dataset.id || '';
}

function generateInterviewZoomLink() {
    const btn = document.getElementById('btnGenerateZoom');
    const btnText = document.getElementById('btnZoomText');
    const linkInput = document.getElementById('modalMeetingLink');
    const notice = document.getElementById('zoomNoticeMessage');
    const noticeText = document.getElementById('zoomNoticeText');

    const role = document.getElementById('modalRole')?.value || 'Interview';
    const company = document.getElementById('modalCompany')?.value || 'Company';
    const studentName = document.getElementById('modalStudentName')?.value || 'Candidate';
    const date = document.getElementById('modalDate')?.value || '{{ date("Y-m-d") }}';
    const time = document.getElementById('modalTime')?.value || '10:00';

    const originalText = btnText.innerHTML;
    btn.disabled = true;
    btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generating...';

    $.ajax({
        url: '{{ route("meetings.generate-zoom-link") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            topic: `Interview: ${role} - ${company} (${studentName})`,
            date: date,
            time: time,
            duration: 60
        },
        success: function(res) {
            if (res && res.link) {
                linkInput.value = res.link;
                toggleClearZoomBtn();
                if (notice) {
                    notice.style.display = 'block';
                    if (noticeText) noticeText.innerText = res.message || 'Zoom meeting link generated and inserted!';
                }
                if (typeof toastr !== 'undefined') {
                    toastr.success(res.message || 'Zoom link generated!');
                }
            } else {
                alert('Could not generate meeting link. Please paste link manually.');
            }
        },
        error: function(err) {
            console.error('Zoom link error:', err);
            const fallback = 'https://meet.jit.si/velearn-' + Math.random().toString(36).substring(2, 10);
            linkInput.value = fallback;
            toggleClearZoomBtn();
            if (notice) {
                notice.style.display = 'block';
                if (noticeText) noticeText.innerText = 'Online meeting room generated: ' + fallback;
            }
        },
        complete: function() {
            btn.disabled = false;
            btnText.innerHTML = originalText;
        }
    });
}

function submitModal() {
    const name = document.getElementById('modalStudentName')?.value?.trim();
    const phone = document.getElementById('modalStudentPhone')?.value?.trim();
    const email = document.getElementById('modalStudentEmail')?.value?.trim();
    const course = document.getElementById('modalCourse')?.value?.trim();
    const duration = document.getElementById('modalDuration')?.value?.trim();
    const batchCode = document.getElementById('modalBatchCode')?.value?.trim();
    const batchType = document.getElementById('modalBatchType')?.value;
    const company = document.getElementById('modalCompany')?.value?.trim();
    const role = document.getElementById('modalRole')?.value?.trim();
    const location = document.getElementById('modalLocation')?.value?.trim();
    const date = document.getElementById('modalDate')?.value;
    const time = document.getElementById('modalTime')?.value;
    const interviewMode = document.getElementById('modalInterviewMode')?.value;
    const meetingLink = document.getElementById('modalMeetingLink')?.value?.trim();
    const userId = document.getElementById('modalStudentUserId')?.value || null;

    if (!name || !phone || !email || !course || !duration || !batchCode || !company || !date || !time) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Required Fields Missing',
                text: 'Please fill in all mandatory fields marked with an asterisk (*).',
                confirmButtonColor: '#2563eb'
            });
        } else {
            alert('Please fill in all mandatory fields (*).');
        }
        return;
    }

    const submitBtn = document.getElementById('modalSubmitBtn');
    const submitText = document.getElementById('modalSubmitText');
    const originalText = submitText ? submitText.innerText : 'Done';
    submitBtn.disabled = true;
    if (submitText) submitText.innerText = 'Scheduling...';

    $.ajax({
        url: '{{ route("schedule-interviews.store") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            user_id: userId,
            name: name,
            phone: phone,
            email: email,
            course: course,
            duration: duration,
            batchCode: batchCode,
            batchType: batchType,
            company: company,
            role: role,
            location: location,
            date: date,
            time: time,
            interviewMode: interviewMode,
            meetingLink: meetingLink
        },
        success: function(res) {
            closeModal();
            const successMsg = res.message || 'Interview scheduled successfully and call letter sent!';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Interview Scheduled!',
                    text: successMsg,
                    confirmButtonColor: '#2563eb'
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.success(successMsg);
            } else {
                alert(successMsg);
            }

            // Reset modal form
            resetScheduleModal();
            setTimeout(function() {
                location.reload();
            }, 1200);
        },
        error: function(err) {
            let errorMsg = 'Failed to schedule interview.';
            if (err.responseJSON && err.responseJSON.errors) {
                errorMsg = Object.values(err.responseJSON.errors).flat().join('<br>');
            } else if (err.responseJSON && err.responseJSON.message) {
                errorMsg = err.responseJSON.message;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Scheduling Failed',
                    html: errorMsg,
                    confirmButtonColor: '#ef4444'
                });
            } else {
                alert(errorMsg);
            }
        },
        complete: function() {
            submitBtn.disabled = false;
            if (submitText) submitText.innerText = originalText;
        }
    });
}

function sendOpeningEmail(url, defaultEmail, role, company) {
    if (typeof Swal === 'undefined') {
        let email = prompt('Enter recipient email address:', defaultEmail);
        if (!email) return;
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(res => res.json())
        .then(data => alert(data.message || (data.status ? 'Email sent successfully!' : 'Failed to send email.')))
        .catch(() => alert('Error sending email.'));
        return;
    }

    Swal.fire({
        title: 'Send Opening via Email',
        html: `<div class="text-muted small mb-2">Send opening details for <strong class="text-dark">${role}</strong> ${company ? `(${company})` : ''} to:</div>`,
        input: 'email',
        inputValue: defaultEmail,
        inputPlaceholder: 'Enter recipient email address',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-paper-plane me-1"></i> Send Email',
        confirmButtonColor: '#24b8ec',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        inputValidator: (value) => {
            if (!value) {
                return 'Please enter a valid recipient email address!';
            }
        },
        preConfirm: (email) => {
            return $.ajax({
                url: url,
                type: 'POST',
                data: { 
                    _token: '{{ csrf_token() }}',
                    email: email 
                },
            }).catch(error => {
                Swal.showValidationMessage(`Request failed: ${error.responseJSON?.message || error.statusText}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value && result.value.status) {
                if (typeof toastr !== 'undefined') {
                    toastr.success(result.value.message);
                } else {
                    alert(result.value.message);
                }
            } else if (result.value) {
                if (typeof toastr !== 'undefined') {
                    toastr.error(result.value.message || 'Failed to send email.');
                } else {
                    alert(result.value.message || 'Failed to send email.');
                }
            }
        }
    });
}
</script>
@endsection

@push('script')
<script>
$(document).ready(function() {
    initModalSelect2();
});
</script>
@endpush
