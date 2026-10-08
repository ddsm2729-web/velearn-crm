<div class="card">
    <div class="card-header">
        <h5 class="card-title">Edit Roles</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('roles.update', encrypt($role->id)) }}" onsubmit="return false;"
            id="update-role-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="role_id" value="{{ $role->id }}">
            <div class="mb-3">
                <label for="role_name" class="form-label">Role Name <span class="text-danger">*</span> :</label>
                <input id="role_name" type="text" class="form-control @error('role_name') is-invalid @enderror"
                    name="role_name" value="{{ $role->name }}" autocomplete="off" autofocus>

                <span class="invalid-feedback" role="alert" id="role_name_err"></span>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" id="cancel-role-open">
                    Cancel
                </button>

                <button type="submit" class="btn btn-theme float-end" id="update-role-submit">
                    <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                    {{ __('Submit') }}
                </button>
            </div>
        </form>
    </div>
</div>
