@extends('layouts.header')

@section('pageTitle', 'Company Details - ' . $company->company_name)

@push('css')
<style>
    .initials-avatar {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        border-radius: 10px;
    }
    
    .detail-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 12px 16px;
        height: 100%;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }
    
    .detail-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 700;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }
    
    .detail-value {
        font-size: 0.85rem;
        color: #334155;
        font-weight: 600;
        word-break: break-word;
    }

    .badge-active { background: #dcfce7; color: #166534; }
    .badge-secondary-light { background: #f1f5f9; color: #475569; }
</style>
@endpush

@section('section')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="initials-avatar shadow-sm">
                    {{ strtoupper(substr($company->company_name, 0, 2)) }}
                </div>
                <div>
                    <h4 class="mb-1 fw-bold text-dark">{{ $company->company_name }}</h4>
                    <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size: 0.8rem;">
                        @php
                            $tagName = $company->leadTag->name ?? 'N/A';
                            $badgeClass = 'bg-primary text-white';
                            if(stripos($tagName, 'Active') !== false) $badgeClass = 'badge-active';
                            elseif(stripos($tagName, 'Contact') !== false) $badgeClass = 'bg-info text-white';
                            elseif(stripos($tagName, 'Follow') !== false) $badgeClass = 'bg-warning text-dark';
                            elseif(stripos($tagName, 'Hot') !== false) $badgeClass = 'bg-danger text-white';
                            elseif(stripos($tagName, 'Warm') !== false) $badgeClass = 'bg-warning text-dark';
                        @endphp
                        <span class="badge {{ $badgeClass }} rounded-pill px-2 py-1">
                            <i class="fas fa-circle me-1" style="font-size: 5px;"></i> {{ strtoupper($tagName) }}
                        </span>
                        <span class="text-muted fw-bold">ID: {{ $company->id }}</span>
                        <span class="text-muted">&bull;</span>
                        <span class="text-muted">{{ $company->industry_sector ?? 'N/A' }}</span>
                        <span class="text-muted">&bull;</span>
                        <span class="text-muted">{{ $company->city_location ?? 'N/A' }}</span>
                        <span class="text-muted">&bull;</span>
                        <span class="text-muted">{{ $company->source->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                @can('Company Database Edit')
                <a href="{{ route('company-database.edit', encrypt($company->id)) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center bg-white shadow-sm">
                    <i class="fas fa-edit me-2"></i> Edit
                </a>
                @endcan
                @can('Current Openings Create')
                <a href="{{ route('current-openings.create') }}" class="btn btn-sm btn-primary d-flex align-items-center shadow-sm">
                    <i class="fas fa-plus me-2"></i> Add Opening
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>

<div class="row g-2 mb-4">
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">HR Name</div>
            <div class="detail-value">{{ $company->hr_name ?? 'N/A' }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">HR Phone</div>
            <div class="detail-value">{{ $company->hr_contact_number ? '+91 ' . $company->hr_contact_number : 'N/A' }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">HR Email</div>
            <div class="detail-value">{{ $company->hr_email_id ?? 'N/A' }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">Company Mail</div>
            <div class="detail-value">{{ $company->company_mail_id ?? 'N/A' }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">Website</div>
            <div class="detail-value">
                @if($company->company_website)
                    <a href="{{ $company->company_website }}" target="_blank" class="text-primary text-decoration-none">{{ $company->company_website }}</a>
                @else
                    N/A
                @endif
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">LinkedIn</div>
            <div class="detail-value">
                @if($company->linkedin_url)
                    <a href="{{ $company->linkedin_url }}" target="_blank" class="text-primary text-decoration-none" style="word-break: break-all;">{{ $company->linkedin_url }}</a>
                @else
                    N/A
                @endif
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">Source</div>
            <div class="detail-value">{{ $company->source->name ?? 'N/A' }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">City</div>
            <div class="detail-value">{{ $company->city_location ?? 'N/A' }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">Industry</div>
            <div class="detail-value">{{ $company->industry_sector ?? 'N/A' }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">Date Added</div>
            <div class="detail-value">{{ $company->created_at ? $company->created_at->format('d M Y') : 'N/A' }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="detail-card">
            <div class="detail-label">Current Openings?</div>
            <div class="detail-value {{ $company->current_openings ? 'text-success' : 'text-muted' }}">
                {{ $company->current_openings ? 'Yes' : 'No' }}
            </div>
        </div>
    </div>
</div>

<!-- Linked Openings -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Linked Openings <span class="badge badge-secondary-light rounded-pill ms-2 text-muted">{{ $company->openings->count() }}</span></h5>
    </div>
    <div class="card-body p-0">
        @if($company->openings->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="text-uppercase text-muted" style="font-size: 0.75rem; background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">ROLE</th>
                        <th class="py-3">CTC</th>
                        <th class="py-3">SLOTS</th>
                        <th class="py-3">MODE</th>
                        <th class="py-3">DEADLINE</th>
                        <th class="py-3">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($company->openings as $opening)
                    <tr>
                        <td class="align-middle ps-4 fw-bold text-muted">{{ $loop->iteration }}</td>
                        <td class="align-middle fw-bold text-dark">{{ $opening->job_role }}</td>
                        <td class="align-middle fw-bold">{{ $opening->ctc_salary }}</td>
                        <td class="align-middle fw-bold text-muted">{{ $opening->number_of_openings }}</td>
                        <td class="align-middle text-muted">{{ $opening->interview_mode }}</td>
                        <td class="align-middle text-muted">{{ $opening->application_deadline ? $opening->application_deadline->format('d M Y') : 'N/A' }}</td>
                        <td class="align-middle">
                            @php
                                $badgeClass = 'badge-active';
                                $statusStr = strtolower($opening->opening_status);
                                if($statusStr === 'hold') $badgeClass = 'bg-warning text-dark';
                                elseif($statusStr === 'filled') $badgeClass = 'bg-info text-white';
                                elseif($statusStr === 'closed') $badgeClass = 'bg-secondary text-white';
                                elseif($statusStr === 'old') $badgeClass = 'bg-light text-muted';
                            @endphp
                            <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2 text-uppercase d-inline-flex align-items-center">
                                <i class="fas fa-circle me-2" style="font-size: 6px;"></i> {{ $opening->opening_status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-4 text-center text-muted">
            <p class="mb-0">No active openings linked to this company.</p>
        </div>
        @endif
    </div>
</div>

<!-- Call History -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Call History <span class="text-muted small ms-2 fw-normal">(every call logged)</span></h5>
        <button class="btn btn-sm btn-primary d-flex align-items-center shadow-sm" data-bs-toggle="modal" data-bs-target="#addEntryModal">
            <i class="fas fa-plus me-2"></i> Add Entry
        </button>
    </div>
    <div class="card-body p-4">
        @if(count($timeline) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-uppercase text-muted" style="font-size: 0.75rem; background-color: #f8f9fa;">
                        <tr>
                            <th class="ps-4 py-3">TIME</th>
                            <th class="py-3">STAFF</th>
                            <th class="py-3">TYPE</th>
                            <th class="py-3">NOTE</th>
                            <th class="py-3">TAG</th>
                            <th class="py-3">NEXT FOLLOW-UP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeline as $item)
                        @php 
                            $data = $item['data']; 
                            $isCallLog = ($item['type'] === 'call_log');
                        @endphp
                        <tr>
                            <td class="align-middle ps-4">
                                <div class="fw-bold text-dark">{{ $isCallLog ? \Carbon\Carbon::parse($data->call_start)->format('h:i A') : $data->created_at->format('h:i A') }}</div>
                                <div class="small text-muted">{{ $isCallLog ? \Carbon\Carbon::parse($data->call_start)->format('d M Y') : $data->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="align-middle">
                                <div class="fw-bold">{{ $data->staff ? $data->staff->first_name . ' ' . $data->staff->last_name : 'System' }}</div>
                            </td>
                            <td class="align-middle">
                                @if($isCallLog)
                                    <span class="badge bg-secondary text-white border"><i class="fas fa-headset me-1"></i> MyOperator Call</span>
                                    <div class="small text-muted mt-1">{{ ucfirst($data->direction) }}</div>
                                @else
                                    <span class="badge bg-light text-dark border">{{ $data->type }}</span>
                                @endif
                            </td>
                            <td class="align-middle text-wrap" style="max-width: 300px;">
                                @if($isCallLog)
                                    <div><strong>Duration:</strong> {{ $data->call_duration }}s</div>
                                    @if($data->recording_url)
                                        <div class="mt-1">
                                            <audio controls style="height: 30px; width: 200px;">
                                                <source src="{{ $data->recording_url }}" type="audio/mpeg">
                                            </audio>
                                        </div>
                                    @endif
                                @else
                                    {{ $data->note }}
                                @endif
                            </td>
                            <td class="align-middle">
                                @if(!$isCallLog && $data->tag)
                                    <span class="badge bg-info text-white rounded-pill px-3">{{ $data->tag->name }}</span>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if(!$isCallLog && $data->next_followup_date)
                                    <div class="small text-info fw-bold">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($data->next_followup_date)->format('d M Y h:i A') }}
                                    </div>
                                @else
                                    <span class="text-muted small">None</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-5 text-center text-muted">
                <i class="fas fa-phone-slash fa-3x mb-3 text-light"></i>
                <p class="mb-0 fw-bold">No call history available</p>
                <p class="small">Call logs for this company will appear here.</p>
            </div>
        @endif
    </div>
</div>

<!-- Add Entry Modal -->
<div class="modal fade" id="addEntryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Conversation Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add-entry-form" action="{{ route('company-database.activity.store', encrypt($company->id)) }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <input type="hidden" name="type" value="Call">
                        <div class="col-12">
                            <label class="form-label fw-bold">Conversation Note <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="note" rows="4" placeholder="Enter conversation details..." required></textarea>
                            <div class="invalid-feedback">Conversation note is strictly required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Next Follow-up</label>
                            <input type="datetime-local" class="form-control" name="next_followup_date">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Update Lead Tag</label>
                            <select class="form-select" name="tag_id">
                                <option value="">Select Tag (Optional)</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ $company->lead_tag_id == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="add-entry-submit">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="entry-loader"></span>
                        Save Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function () {
    $('#add-entry-form').on('submit', function (e) {
        e.preventDefault();
        let $form = $(this);
        let $btn = $('#add-entry-submit');
        let $loader = $('#entry-loader');

        $form.find('.invalid-feedback').text('').hide();
        $form.find('.form-control').removeClass('is-invalid');

        if(!$form.find('textarea[name="note"]').val().trim()) {
            $form.find('textarea[name="note"]').addClass('is-invalid').siblings('.invalid-feedback').show();
            return;
        }

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
                $btn.attr('disabled', true);
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(res.message || 'Something went wrong.');
                    $btn.attr('disabled', false);
                }
            },
            error: function (xhr) {
                $btn.attr('disabled', false);
                toastr.error('Something went wrong. Please try again.');
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });
});
</script>
@endpush
