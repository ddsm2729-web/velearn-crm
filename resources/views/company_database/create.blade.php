@extends('layouts.header')
@section('pageTitle', 'Add Company | ' . config('app.name'))

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between mb-4">
            <h1 class="h3 mb-0">Add <strong> Company </strong></h1>
            <a href="{{ route('company-database.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>

        <div class="row">
            <div class="col-12 col-xl-12 mt-1">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('company-database.store') }}" onsubmit="return false;" id="create-company-form">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">COMPANY NAME <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control" autocomplete="off">
                                    <span class="invalid-feedback" role="alert" id="company_name_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">CITY / LOCATION</label>
                                    <input type="text" name="city_location" class="form-control" autocomplete="off">
                                    <span class="invalid-feedback" role="alert" id="city_location_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">INDUSTRY / SECTOR</label>
                                    <input type="text" name="industry_sector" class="form-control" placeholder="IT, Finance, Healthcare..." autocomplete="off">
                                    <span class="invalid-feedback" role="alert" id="industry_sector_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">COMPANY WEBSITE</label>
                                    <input type="text" name="company_website" class="form-control" autocomplete="off">
                                    <span class="invalid-feedback" role="alert" id="company_website_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">COMPANY MAIL ID</label>
                                    <input type="email" name="company_mail_id" class="form-control" autocomplete="off">
                                    <span class="invalid-feedback" role="alert" id="company_mail_id_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">HR NAME</label>
                                    <input type="text" name="hr_name" class="form-control" autocomplete="off">
                                    <span class="invalid-feedback" role="alert" id="hr_name_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">HR CONTACT NUMBER</label>
                                    <input type="text" name="hr_contact_number" class="form-control" autocomplete="off">
                                    <span class="invalid-feedback" role="alert" id="hr_contact_number_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">HR EMAIL ID</label>
                                    <input type="email" name="hr_email_id" class="form-control" autocomplete="off">
                                    <span class="invalid-feedback" role="alert" id="hr_email_id_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">LINKEDIN / SOCIAL URL</label>
                                    <input type="text" name="linkedin_url" class="form-control" autocomplete="off">
                                    <span class="invalid-feedback" role="alert" id="linkedin_url_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">SOURCE</label>
                                    <select name="source_id" class="form-select select2">
                                        <option value="" selected disabled>Select Source</option>
                                        @foreach($leadSources as $src)
                                            <option value="{{ $src->id }}">{{ $src->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" role="alert" id="source_id_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">CURRENT OPENINGS?</label>
                                    <select name="current_openings" class="form-select">
                                        <option value="" selected disabled>Select...</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <span class="invalid-feedback" role="alert" id="current_openings_err"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">LEAD STATUS (TAG)</label>
                                    <select name="lead_tag_id" class="form-select select2">
                                        <option value="" selected disabled>Select Status Tag</option>
                                        @foreach($leadTags as $tag)
                                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" role="alert" id="lead_tag_id_err"></span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-muted text-uppercase text-xs fw-bold">NOTES</label>
                                    <textarea name="notes" class="form-control" rows="4" placeholder="Any context, hiring process, preferences..."></textarea>
                                    <span class="invalid-feedback" role="alert" id="notes_err"></span>
                                </div>
                            </div>
                            
                            <hr class="mt-4 mb-3">
                            <button type="submit" class="btn btn-primary float-end px-4" id="create-company-submit">
                                <span class="spinner-border spinner-border-sm d-none me-2" id="loader-spinner"></span>
                                {{ __('Save Entry') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/company.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('body').find('.select2').select2();
        });
    </script>
@endpush
