@extends('layouts.header')
@section('pageTitle')
    {{ __('Add Staff') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3">Add <strong> Staff </strong></h1>

            @can('Staff List')
                <a href="javascript:history.back()" class="btn btn-theme"><i class="fas fa-arrow-left me-2"></i> Back</a>
            @endcan
        </div>

        <div class="row">
            @can('Staff Create')
                <div class="col-12 col-xl-12 mt-1">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('staffs.store') }}" onsubmit="return false;"
                                id="create-staff-form">
                                @csrf
                                <div class="mb-3 row">
                                    <div class="col-xl-6">
                                        <label for="first_name" class="form-label">First Name <span
                                                class="text-danger">*</span> :</label>
                                        <input id="first_name" type="text"
                                            class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                            value="{{ old('first_name') }}" autocomplete="off" maxlength="50"
                                            placeholder="Staff First Name" />

                                        <span class="invalid-feedback" role="alert" id="first_name_err"></span>
                                    </div>

                                    <div class="col-xl-6">
                                        <label for="last_name" class="form-label">Last Name <span
                                                class="text-danger">*</span> :</label>
                                        <input id="last_name" type="text"
                                            class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                            value="{{ old('last_name') }}" autocomplete="off" maxlength="50"
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
                                                    <option value="{{ $rl->id }}"> {{ $rl->name }} </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="invalid-feedback" role="alert" id="role_id_err"></span>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-xl-6">
                                        <label for="email" class="form-label">Email :</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" autocomplete="off" maxlength="50"
                                            placeholder="Staff Email" />
                                        <span class="invalid-feedback" role="alert" id="email_err"></span>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    
                                    <!-- Password -->
                                    <div class="col-xl-6">
                                        <label for="password" class="form-label">Password :</label>
                                        <div class="position-relative">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                placeholder="Enter Password" autocomplete="new-password" />
                                            <span class="toggle-password"
                                                style="position:absolute; top:50%; right:15px; transform: translateY(-50%); cursor:pointer;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        <span class="invalid-feedback" role="alert" id="password_err"></span>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-xl-6">
                                        <label for="password_confirmation" class="form-label">Confirm Password :</label>
                                        <div class="position-relative">
                                            <input id="password_confirmation" type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" placeholder="Confirm Password"
                                                autocomplete="new-password" />
                                            <span class="toggle-password"
                                                style="position:absolute; top:50%; right:15px; transform: translateY(-50%); cursor:pointer;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        <span class="invalid-feedback" role="alert"
                                            id="password_confirmation_err"></span>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                            <option value="1"> Male </option>
                                            <option value="2"> Female </option>
                                        </select>

                                        <span class="invalid-feedback" role="alert" id="gender_err"></span>
                                    </div>

                                    <div class="col-xl-4 mb-3">
                                        <label for="primary_phone" class="form-label">Primary Contact <span
                                                class="text-danger">*</span> :</label>
                                        <input id="primary_phone" type="text"
                                            class="form-control @error('primary_phone') is-invalid @enderror"
                                            name="primary_phone" value="{{ old('primary_phone') }}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off"
                                            maxlength="10" placeholder="Staff Primary Contact" />

                                        <span class="invalid-feedback" role="alert" id="primary_phone_err"></span>
                                    </div>

                                    <div class="col-xl-4 mb-3">
                                        <label for="secondary_phone" class="form-label">Secondary Contact :</label>
                                        <input id="secondary_phone" type="text"
                                            class="form-control @error('secondary_phone') is-invalid @enderror"
                                            name="secondary_phone" value="{{ old('secondary_phone') }}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off"
                                            maxlength="10" placeholder="Staff Secondary Contact" />
                                    </div>

                                </div>


                                <button type="submit" class="btn btn-theme float-end" id="create-staff-submit">
                                    <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                                    {{ __('Submit') }}
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
    <script>
        document.querySelectorAll('.toggle-password').forEach(function(eye) {
            eye.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input.type === "password") {
                    input.type = "text";
                    this.innerHTML = '<i class="fa fa-eye-slash"></i>';
                } else {
                    input.type = "password";
                    this.innerHTML = '<i class="fa fa-eye"></i>';
                }
            });
        });
    </script>
@endpush
