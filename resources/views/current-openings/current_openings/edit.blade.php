@extends('layouts.header')

@section('pageTitle', 'Edit Current Opening')

@section('section')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="mb-0 fw-bold">Edit Opening Entry</h3>
            <a href="{{ route('current-openings.index') }}" class="btn btn-sm btn-light shadow-sm"><i class="fas fa-times"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('current-openings.update', encrypt($opening->id)) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold">COMPANY NAME *</label>
                            <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" 
                                        data-hr-name="{{ $company->hr_name }}"
                                        data-hr-phone="{{ $company->hr_contact_number }}"
                                        data-hr-email="{{ $company->hr_email_id }}"
                                        data-company-mail="{{ $company->company_mail_id }}"
                                        data-location="{{ $company->city_location }}"
                                        {{ old('company_id', $opening->company_id) == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">JOB ROLE / DESIGNATION *</label>
                            <input type="text" name="job_role" class="form-control @error('job_role') is-invalid @enderror" value="{{ old('job_role', $opening->job_role) }}" required>
                            @error('job_role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">COMPANY MAIL ID</label>
                            <input type="email" name="company_mail_id" id="company_mail_id" class="form-control" value="{{ old('company_mail_id', $opening->company_mail_id) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">HR NAME</label>
                            <input type="text" name="hr_name" id="hr_name" class="form-control" value="{{ old('hr_name', $opening->hr_name) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">HR CONTACT NUMBER</label>
                            <input type="text" name="hr_contact_number" id="hr_contact_number" class="form-control" value="{{ old('hr_contact_number', $opening->hr_contact_number) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">HR EMAIL ID</label>
                            <input type="email" name="hr_email_id" id="hr_email_id" class="form-control" value="{{ old('hr_email_id', $opening->hr_email_id) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">JOB LOCATION *</label>
                            <input type="text" name="job_location" id="job_location" class="form-control @error('job_location') is-invalid @enderror" value="{{ old('job_location', $opening->job_location) }}" required>
                            @error('job_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold">SKILLS REQUIRED (COMMA SEP.)</label>
                            <input type="text" name="skills_required" class="form-control" value="{{ old('skills_required', $opening->skills_required) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">MINIMUM QUALIFICATION *</label>
                            <input type="text" name="minimum_qualification" class="form-control @error('minimum_qualification') is-invalid @enderror" value="{{ old('minimum_qualification', $opening->minimum_qualification) }}" required>
                            @error('minimum_qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">EXPERIENCE REQUIRED *</label>
                            <select name="experience_required" class="form-select @error('experience_required') is-invalid @enderror" required>
                                <option value="Fresher" {{ old('experience_required', $opening->experience_required) == 'Fresher' ? 'selected' : '' }}>Fresher</option>
                                <option value="0-1 yr" {{ old('experience_required', $opening->experience_required) == '0-1 yr' ? 'selected' : '' }}>0-1 yr</option>
                                <option value="1-3 yrs" {{ old('experience_required', $opening->experience_required) == '1-3 yrs' ? 'selected' : '' }}>1-3 yrs</option>
                                <option value="3+ yrs" {{ old('experience_required', $opening->experience_required) == '3+ yrs' ? 'selected' : '' }}>3+ yrs</option>
                            </select>
                            @error('experience_required')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">CTC / SALARY *</label>
                            <input type="text" name="ctc_salary" class="form-control @error('ctc_salary') is-invalid @enderror" value="{{ old('ctc_salary', $opening->ctc_salary) }}" required>
                            @error('ctc_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">NUMBER OF OPENINGS *</label>
                            <input type="number" name="number_of_openings" class="form-control @error('number_of_openings') is-invalid @enderror" min="1" value="{{ old('number_of_openings', $opening->number_of_openings) }}" required>
                            @error('number_of_openings')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">APPLICATION DEADLINE</label>
                            <input type="date" name="application_deadline" class="form-control" value="{{ old('application_deadline', $opening->application_deadline ? $opening->application_deadline->format('Y-m-d') : '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">INTERVIEW MODE *</label>
                            <select name="interview_mode" class="form-select @error('interview_mode') is-invalid @enderror" required>
                                <option value="Online" {{ old('interview_mode', $opening->interview_mode) == 'Online' ? 'selected' : '' }}>Online</option>
                                <option value="Offline" {{ old('interview_mode', $opening->interview_mode) == 'Offline' ? 'selected' : '' }}>Offline</option>
                            </select>
                            @error('interview_mode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold">INTERVIEW PROCESS</label>
                            <input type="text" name="interview_process" class="form-control" value="{{ old('interview_process', $opening->interview_process) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">BOND / SERVICE AGMT</label>
                            <input type="text" name="bond_service_agmt" class="form-control" value="{{ old('bond_service_agmt', $opening->bond_service_agmt) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">OPENING STATUS *</label>
                            <select name="opening_status" class="form-select @error('opening_status') is-invalid @enderror" required>
                                <option value="Active" {{ old('opening_status', $opening->opening_status) == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Hold" {{ old('opening_status', $opening->opening_status) == 'Hold' ? 'selected' : '' }}>Hold</option>
                                <option value="Filled" {{ old('opening_status', $opening->opening_status) == 'Filled' ? 'selected' : '' }}>Filled</option>
                                <option value="Closed" {{ old('opening_status', $opening->opening_status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                                <option value="Old" {{ old('opening_status', $opening->opening_status) == 'Old' ? 'selected' : '' }}>Old</option>
                            </select>
                            @error('opening_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold">NOTES / REMARKS</label>
                            <textarea name="notes" rows="3" class="form-control">{{ old('notes', $opening->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('current-openings.index') }}" class="btn btn-dark px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-check me-2"></i> Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#company_id').on('change', function() {
            let selected = $(this).find('option:selected');
            if (selected.val()) {
                $('#hr_name').val(selected.data('hr-name'));
                $('#hr_contact_number').val(selected.data('hr-phone'));
                $('#hr_email_id').val(selected.data('hr-email'));
                $('#company_mail_id').val(selected.data('company-mail'));
                $('#job_location').val(selected.data('location'));
            } else {
                $('#hr_name, #hr_contact_number, #hr_email_id, #company_mail_id, #job_location').val('');
            }
        });
    });
</script>
@endpush
