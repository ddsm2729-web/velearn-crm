@extends('layouts.header')

@section('pageTitle')
    {{ __('Edit Lead') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between mb-4">
            <h1 class="h3 mb-0">Edit <strong>Lead</strong></h1>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Leads
            </a>
        </div>

        <div class="row">
            <div class="col-12 col-xl-12 mt-1">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('leads.update', encrypt($lead->id)) }}" id="edit-lead-form" onsubmit="return false;">
                            @csrf
                            @method('PUT')
                            
                            <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-user-circle me-2"></i> Lead Information</h5>
                            <div class="row mb-4">
                                <div class="col-xl-6 mb-3">
                                    <label for="name" class="form-label fw-medium">Contact Name <span class="text-danger">*</span></label>
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $lead->name }}" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="phone" class="form-label fw-medium">Phone <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">+91</span>
                                        <input id="phone" type="text" class="form-control" name="phone" value="{{ str_replace('+91', '', $lead->phone) }}" pattern="[0-9]{10}" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required />
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="whatsapp" class="form-label fw-medium">WhatsApp <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">+91</span>
                                        <input id="whatsapp" type="text" class="form-control" name="whatsapp" value="{{ str_replace('+91', '', $lead->whatsapp) }}" pattern="[0-9]{10}" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required />
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $lead->email }}" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="location" class="form-label fw-medium">Location <span class="text-danger">*</span></label>
                                    <input id="location" type="text" class="form-control" name="location" value="{{ $lead->location }}" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3 text-warning"><i class="fas fa-bullseye me-2"></i> Pipeline Details</h5>
                            <div class="row mb-4">
                                <div class="col-xl-4 mb-3">
                                    <label for="lead_source_id" class="form-label fw-medium">Source <span class="text-danger">*</span></label>
                                    <select name="lead_source_id" id="lead_source_id" class="form-select" required>
                                        <option value="">Select Source</option>
                                        @forelse($sources as $src)
                                            <option value="{{ $src->id }}" {{ $lead->lead_source_id == $src->id ? 'selected' : '' }}>{{ $src->name }}</option>
                                        @empty
                                            <option value="" disabled>No sources available</option>
                                        @endforelse
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label for="tags-select" class="form-label fw-medium">Tag <span class="text-danger">*</span></label>
                                    @php $selectedTagId = $lead->tags->first()?->id; @endphp
                                    <select name="tag_id" id="tags-select" class="form-select" required>
                                        <option value="">Select a tag...</option>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" {{ $selectedTagId == $tag->id ? 'selected' : '' }}>{{ ucfirst($tag->name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label for="lead_status_id" class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                                    <select name="lead_status_id" id="lead_status_id" class="form-select" required>
                                        <option value="">Select Status</option>
                                        @foreach($statuses as $sts)
                                            <option value="{{ $sts->id }}" {{ $lead->lead_status_id == $sts->id ? 'selected' : '' }}>{{ $sts->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <input type="hidden" name="assigned_to" value="{{ $lead->assigned_to }}">
                            </div>
                            <hr>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-warning text-white px-5 fw-bold" id="edit-lead-submit">
                                    <span class="spinner-border spinner-border-sm d-none me-2" id="loader-spinner"></span>
                                    <i class="fas fa-save me-1"></i> Update Lead
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
    $(document).ready(function () {
        // Initialize Select2 on all dropdowns
        $('#lead_source_id').select2({ placeholder: 'Select Source', allowClear: true, width: '100%' });
        $('#lead_status_id').select2({ placeholder: 'Select Status', allowClear: true, width: '100%' });
        $('#assigned_to').select2({ placeholder: 'Select Assignee', allowClear: true, width: '100%' });
        $('#tags-select').select2({ placeholder: 'Select a tag...', allowClear: true, width: '100%' });
        $('#edit-lead-submit').on('click', function () {
            let $btn = $(this);
            let $loader = $btn.find('#loader-spinner');
            let $form = $('#edit-lead-form');

            let form = $form[0];
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            $form.find('.invalid-feedback').text('').hide();
            $form.find('.form-control, .form-select').removeClass('is-invalid');

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: $form.attr('action'),
                type: 'POST', // Form handles _method=PUT
                data: $form.serialize(),
                beforeSend: function () {
                    $loader.removeClass('d-none');
                    $btn.attr('disabled', true);
                },
                success: function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        setTimeout(function () { window.location.href = "{{ route('leads.index') }}"; }, 1000);
                    } else {
                        toastr.error(res.message || 'Something went wrong.');
                        $btn.attr('disabled', false);
                    }
                },
                error: function (xhr) {
                    $btn.attr('disabled', false);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON?.errors || {};
                        $.each(errors, function (key, value) {
                            let $input = $form.find('[name="' + key + '"]');
                            $input.addClass('is-invalid');
                            $input.siblings('.invalid-feedback').css('display', 'block').text(value[0]);
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                complete: function () {
                    $loader.addClass('d-none');
                }
            });
        });
    });
    </script>
@endpush
