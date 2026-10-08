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
</style>
@endpush

@section('section')
<a href="{{ route('current-openings.index') }}" class="back-btn"><i class="fas fa-arrow-left me-2"></i> Back</a>

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
                
                @if(auth()->check() && (auth()->user()->hasRole('Student Coordinator') || auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
                <a href="{{ route('eligible-students.index') }}?tab=eligible&opening={{ encrypt($opening->id) }}" 
                   class="btn btn-primary shadow-sm fw-bold px-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-paper-plane me-2"></i> Push Openings
                </a>
                @endif
                
                @if(auth()->check() && (auth()->user()->hasRole('Placement Coordinator') || auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
                <button class="btn btn-info text-white shadow-sm fw-bold px-3 d-flex align-items-center justify-content-center">
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

@endsection
