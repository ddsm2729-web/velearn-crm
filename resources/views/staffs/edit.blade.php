@extends('layouts.header')
@section('pageTitle')
    {{ __('Edit Staff') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3">Edit <strong> Staff </strong></h1>

            @can('Staff List')
                <a href="{{ route('staffs.index') }}" class="btn btn-theme"> Back</a>
            @endcan
        </div>

        <div class="row">
            @can('Staff Edit')
                <div class="col-12 col-xl-12 mt-1">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('staffs.update', [encrypt($staff->id)]) }}"
                                onsubmit="return false;" id="update-staffs-form">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="auth_id" id="auth_id" value="{{ $staff->auth_id }}">
                            
                                <div class="mb-3 row">
                                    <div class="col-xl-6">
                                        <label for="first_name" class="form-label">First Name <span
                                                class="text-danger">*</span> :</label>
                                        <input id="first_name" type="text"
                                            class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                            value="{{ $staff->first_name }}" autocomplete="off" maxlength="50"
                                            placeholder="Staff First Name" />

                                        <span class="invalid-feedback" role="alert" id="first_name_err"></span>
                                    </div>

                                    <div class="col-xl-6">
                                        <label for="last_name" class="form-label">Last Name <span
                                                class="text-danger">*</span> :</label>
                                        <input id="last_name" type="text"
                                            class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                            value="{{ $staff->last_name }}" autocomplete="off" maxlength="50"
                                            placeholder="Staff Last Name" />

                                        <span class="invalid-feedback" role="alert" id="last_name_err"></span>
                                    </div>
                                </div>

                                <div class="mb-3 row">

                                    <div class="col-xl-6">
                                        <label for="role_id" class="form-label">
                                            Role <span class="text-danger">*</span> :
                                        </label>
                                        <select name="role_id" id="role_id"
                                            class="select2 form-control @error('role_id') is-invalid @enderror">
                                            <option value="" selected disabled> Select Role </option>
                                            @if (count($roles) > 0)
                                                @foreach ($roles as $rl)
                                                    <option value="{{ $rl->id }}"
                                                        @if ($staff->role_id == $rl->id) selected @endif>
                                                        {{ $rl->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="invalid-feedback" role="alert" id="role_id_err"></span>
                                    </div>

                                    <div class="col-xl-6">
                                        <label for="email" class="form-label">Email :</label>
                                        <input id="email" type="text"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ $staff->email }}" autocomplete="off" maxlength="50"
                                            placeholder="Staff Email" />
                                        <span class="invalid-feedback" role="alert" id="email_err"></span>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="gender" class="form-label">Gender <span
                                                class="text-danger">*</span>
                                            :</label>
                                        <select name="gender" id="gender"
                                            class="select2 form-control @error('gender') is-invalid @enderror">
                                            <option value="" selected disabled> Select Staff Gender </option>
                                            <option @if ($staff->gender == 1) selected @endif value="1"> Male
                                            </option>
                                            <option @if ($staff->gender == 2) selected @endif value="2">
                                                Female </option>
                                        </select>

                                        <span class="invalid-feedback" role="alert" id="gender_err"></span>
                                    </div>

                                    <div class="col-xl-4 mb-3">
                                        <label for="primary_phone" class="form-label">Primary Contact <span
                                                class="text-danger">*</span> :</label>
                                        <input id="primary_phone" type="text"
                                            class="form-control @error('primary_phone') is-invalid @enderror"
                                            name="primary_phone" value="{{ $staff->primary_phone }}"
                                            autocomplete="off" maxlength="10"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                            placeholder="Staff Primary Contact" />

                                        <span class="invalid-feedback" role="alert" id="primary_phone_err"></span>
                                    </div>

                                    <div class="col-xl-4 mb-3">
                                        <label for="secondary_phone" class="form-label">Secondary Contact :</label>
                                        <input id="secondary_phone" type="text"
                                            class="form-control @error('secondary_phone') is-invalid @enderror"
                                            name="secondary_phone" value="{{ $staff->secondary_phone }}"
                                            autocomplete="off" maxlength="10"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                            placeholder="Staff Secondary Contact" />
                                    </div>
                                </div>



                                <button type="submit" class="btn btn-theme float-end" id="update-staffs-submit">
                                    <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                                    {{ __('Update') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/staff.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Table into DataTable
            $('body').find('.select2').select2();
        });
    </script>
@endpush
