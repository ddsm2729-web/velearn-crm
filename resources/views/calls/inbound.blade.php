@extends('layouts.header')

@section('pageTitle', 'Inbound Calls')

@section('section')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calls-inbound.css') }}">
@endpush

<div class="leads-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="leads-header">
            <h1>Inbound Leads</h1>
            <p>{{ $totalLeads }} total leads · {{ $newToday }} new today</p>
        </div>
        {{-- <button class="btn btn-add-lead" data-bs-toggle="modal" data-bs-target="#addLeadModal">
            + Add Lead
        </button> --}}
    </div>

    <div class="search-filter-row">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="leadSearch" placeholder="Search leads...">
        </div>
        <div class="tag-filter">
            <select id="tagFilter">
                <option>All Tags</option>
                <option>New Lead</option>
                <option>Hot</option>
                <option>Warm</option>
                <option>Cold</option>
                <option>Contacted</option>
                <option>Booked</option>
                <option>Attended</option>
                <option>Converted</option>
                <option>Lost</option>
            </select>
        </div>
    </div>

    <div class="leads-card">
        <div class="table-responsive">
            <table class="leads-table">
                <thead>
                    <tr>
                        <th>Contact Name</th>
                        <th>Phone Number</th>
                        <th>Source</th>
                        <th>Direction</th>
                        <th>Duration</th>
                        <th>Outcome / Tag</th>
                        <th class="text-end">Time</th>
                    </tr>
                </thead>
                <tbody id="leads-table-body">
                    @include('calls.partials.call_rows', ['calls' => $calls])
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4" id="pagination-container">
        {{ $calls->links() }}
    </div>
</div>

<!-- Modal for Lead Details -->
<div class="modal fade" id="leadDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white border-light text-dark shadow-lg">
            <div class="modal-header border-light">
                <h5 class="modal-title fw-bold">Lead Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="lead-details-content">
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add New Lead -->
<div class="modal fade" id="addLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white border-light text-dark shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus me-2 text-primary"></i> Add New Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addLeadForm">
                @csrf
                <div class="modal-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Priya Nair" required style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0 bg-light" style="border-radius: 8px 0 0 8px;">+91</span>
                                <input type="text" name="phone_number" class="form-control border-start-0" placeholder="XXXXX XXXXX" required style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0 8px 8px 0;">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Email ID</label>
                            <input type="email" name="email_id" class="form-control" placeholder="email@example.com" style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Source</label>
                            <select name="source" class="form-select" style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                                <option value="MyOperator">MyOperator</option>
                                <option value="Instagram">Instagram</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Google">Google</option>
                                <option value="Reference">Reference</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="City" style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Note</label>
                            <textarea name="note" class="form-control" placeholder="Add any details about this lead..." rows="2" style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4">
                    <button type="button" class="btn btn-link text-decoration-none text-dark fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius: 8px;">Save Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    let searchTimer;

    function fetchLeads() {
        const search = $('#leadSearch').val();
        const tag = $('#tagFilter').val();
        
        $.ajax({
            url: "{{ route('calls.inbound') }}",
            type: 'GET',
            data: { search, tag },
            success: function(response) {
                if(response.status) {
                    $('#leads-table-body').html(response.data);
                    $('#pagination-container').html(response.pagination);
                }
            }
        });
    }

    $('#leadSearch').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchLeads, 500);
    });

    $('#tagFilter').on('change', function() {
        fetchLeads();
    });

    // Handle pagination links
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const search = $('#leadSearch').val();
        const tag = $('#tagFilter').val();
        
        $.ajax({
            url: url,
            data: { search, tag },
            success: function(response) {
                if(response.status) {
                    $('#leads-table-body').html(response.data);
                    $('#pagination-container').html(response.pagination);
                }
            }
        });
    });

    $(document).on('click', '.lead-details', function () {
    const url = $(this).data('url');
    if (url) {
        window.location.href = url;
    }
});

    // Handle Add Lead Form Submission
    $('#addLeadForm').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const originalText = $btn.text();
        
        $btn.prop('disabled', true).text('Saving...');

        let formData = $form.serialize();

        $.ajax({
            url: "/calls/manual-store",
            type: 'POST',
            data: formData,
            success: function(response) {
                $btn.prop('disabled', false).text(originalText);
                toastr.success('Lead saved successfully!');
                $('#addLeadModal').modal('hide');
                $form[0].reset();
                fetchLeads();
            },
            error: function(xhr) {
                $btn.prop('disabled', false).text(originalText);
                toastr.error('Failed to save lead: ' + (xhr.responseJSON.message || 'Unknown error'));
            }
        });
    });
});
</script>
@endpush
