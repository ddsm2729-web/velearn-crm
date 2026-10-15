@extends('layouts.header')
@section('pageTitle')
    {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3"><strong>Manage</strong> Permissions</h1>
            <a href="javascript:history.back()" class="btn btn-theme"><i class="fas fa-arrow-left me-2"></i> Back</a>
        </div>

        <div class="row">
            <div class="col-12 col-xl-12 mt-1">
                <div class="card">
                    <h1 class="card-header h3"><strong>{{ __('Role: ') . $roleName }}</strong></h1>
                    <div class="text-justify">
                        <h4 class="fst-italic ms-3 pe-4">Please review the following before assigning permissions to a
                            <strong>Any</strong> Role:
                        </h4 class="fst-italic ms-3">
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3"><strong> Assign </strong>Permissions</h1>
                        <span class="invalid-feedback" role="alert" id="permission_assign_err"></span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('roles.assign') }}" onsubmit="return false;"
                            id="manage-permission-form">
                            @csrf
                            <input type="hidden" name="role_id" id="role_id" value="{{ $roleId }}" />
                            @if (count($permissions) > 0)
                                <div class="row">
                                    @foreach ($permissions as $key => $pmsn)
                                        <div class="col-md-3 mb-3">
                                            <div class="accordion-custom">
                                                <div class="accordion-item">
                                                    <input type="checkbox" id="{{ strtolower(\Str::slug($key)) }}"
                                                        class="accordion-toggle" hidden />

                                                    <label for="{{ strtolower(\Str::slug($key)) }}" class="accordion-header"
                                                        id="{{ strtolower(\Str::slug($key)) }}-label">
                                                        <div
                                                            class="accordion-header-left d-flex justify-content-between align-items-center">
                                                            <span>{{ $key }}</span>
                                                        </div>
                                                    </label>

                                                    <div class="accordion-content">

                                                        <!-- ✅ Check All Checkbox -->
                                                        <div class="col-md-12">
                                                            <div class="mb-2">
                                                                <div class="input-group">
                                                                    <div class="input-group-text">
                                                                        <input type="checkbox" class="check-all"
                                                                            data-section="{{ strtolower(\Str::slug($key)) }}">
                                                                    </div>
                                                                    <span type="text" class="form-control">Check
                                                                        All</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if (count($pmsn) > 0)
                                                            @foreach ($pmsn as $pm)
                                                                <div class="col-md-12">
                                                                    <div class="mb-2">
                                                                        <div class="input-group">
                                                                            <div class="input-group-text">
                                                                                <input type="checkbox"
                                                                                    name="permission_assign[]"
                                                                                    class="assign-role-checkbox"
                                                                                    data-section="{{ strtolower(\Str::slug($key)) }}"
                                                                                    value="{{ $pm->id }}"
                                                                                    @if (in_array($pm->name, $assignedPermission)) checked="checked" @endif
                                                                                    @if ($pm->name == 'Dashboard') onclick="return false;" style="pointer-events: none; opacity: 0.7;" @endif />
                                                                            </div>
                                                                            <span type="text"
                                                                                class="form-control">{{ $pm->name }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <button type="submit" class="btn btn-theme float-end" id="manage-permission-submit">
                                <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                                {{ __('Submit') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/role-manage.js') }}"></script>
    <script>
        $(document).ready(function() {
            // ✅ Handle "Check All" click
            $('.check-all').on('change', function() {
                const section = $(this).data('section');
                const isChecked = $(this).is(':checked');

                // Check or uncheck all permission checkboxes in the same section
                $(`.assign-role-checkbox[data-section="${section}"]`).prop('checked', isChecked);
            });

            // ✅ Keep "Check All" synced when individual boxes change
            $('.assign-role-checkbox').on('change', function() {
                const section = $(this).data('section');
                const total = $(`.assign-role-checkbox[data-section="${section}"]`).length;
                const checked = $(`.assign-role-checkbox[data-section="${section}"]:checked`).length;

                // If all permissions are checked → mark "Check All" as checked
                $(`.check-all[data-section="${section}"]`).prop('checked', total === checked);
            });

            // ✅ Initialize "Check All" states on page load
            $('.check-all').each(function() {
                const section = $(this).data('section');
                const total = $(`.assign-role-checkbox[data-section="${section}"]`).length;
                const checked = $(`.assign-role-checkbox[data-section="${section}"]:checked`).length;
                $(this).prop('checked', total === checked);
            });
        });
    </script>
@endpush
