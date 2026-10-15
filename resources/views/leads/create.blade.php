@extends('layouts.header')

@section('pageTitle')
    {{ __('Create Lead') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between mb-4">
            <h1 class="h3 mb-0">Create <strong>New Lead</strong></h1>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Leads
            </a>
        </div>

        <div class="row">
            <div class="col-12 col-xl-12 mt-1">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('leads.store') }}" id="create-lead-form" onsubmit="return false;">
                            @csrf
                            
                            <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-user-circle me-2"></i> Lead Information</h5>
                            <div class="row mb-4">
                                <div class="col-xl-6 mb-3">
                                    <label for="name" class="form-label fw-medium">Contact Name <span class="text-danger">*</span></label>
                                    <input id="name" type="text" class="form-control" name="name" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="phone" class="form-label fw-medium">Phone <span class="text-danger">*</span></label>
                                    <input id="phone" type="text" class="form-control" name="phone" pattern="[0-9]{10}" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="whatsapp" class="form-label fw-medium">WhatsApp <span class="text-danger">*</span></label>
                                    <input id="whatsapp" type="text" class="form-control" name="whatsapp" pattern="[0-9]{10}" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control" name="email" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="location" class="form-label fw-medium">Location <span class="text-danger">*</span></label>
                                    <input id="location" type="text" class="form-control" name="location" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3 text-warning"><i class="fas fa-bullseye me-2"></i> Pipeline Details</h5>
                            <div class="row mb-4">
                                <div class="col-xl-3 mb-3">
                                    <label for="lead_source_id" class="form-label fw-medium">Source <span class="text-danger">*</span></label>
                                    <select name="lead_source_id" id="lead_source_id" class="form-select" required>
                                        <option value="">Select Source</option>
                                        @forelse($sources as $src)
                                            <option value="{{ $src->id }}">{{ $src->name }}</option>
                                        @empty
                                            <option value="" disabled>No sources available</option>
                                        @endforelse
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-xl-3 mb-3">
                                    <label for="tags-select" class="form-label fw-medium">Tag <span class="text-danger">*</span></label>
                                    <select name="tag_id" id="tags-select" class="form-select" required>
                                        <option value="">Select a tag...</option>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}">{{ ucfirst($tag->name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-xl-3 mb-3">
                                    <label for="lead_status_id" class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                                    <select name="lead_status_id" id="lead_status_id" class="form-select" required>
                                        <option value="">Select Status</option>
                                        @foreach($statuses as $sts)
                                            <option value="{{ $sts->id }}" {{ $sts->name == 'Active' ? 'selected' : '' }}>{{ $sts->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="assigned_to" class="form-label fw-medium">Assign To <span class="text-danger">*</span></label>
                                    <select name="assigned_to" id="assigned_to" class="form-select" required>
                                        <option value="">Unassigned</option>
                                        @foreach($staffs as $staff)
                                            <option value="{{ $staff->id }}" {{ auth()->id() == $staff->auth_id ? 'selected' : '' }}>{{ trim($staff->first_name . ' ' . $staff->last_name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <hr>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-warning text-white px-5 fw-bold" id="create-lead-submit">
                                    <span class="spinner-border spinner-border-sm d-none me-2" id="loader-spinner"></span>
                                    <i class="fas fa-save me-1"></i> Save Lead
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
        $('#create-lead-submit').on('click', function () {
            let $btn = $(this);
            let $loader = $btn.find('#loader-spinner');
            let $form = $('#create-lead-form');

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
                type: 'POST',
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
