<div class="card">
    <div class="card-header">
        <h5 class="card-title">Edit Permissions</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('permissions.update', encrypt($permission->id)) }}" onsubmit="return false;" id="update-permission-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="permission_id" value="{{ $permission->id }}">
            <div class="mb-3">
                <label for="permission_name" class="form-label">Permission Name <span class="text-danger">*</span> :</label>
                <input id="permission_name" type="text" class="form-control @error('permission_name') is-invalid @enderror" name="permission_name" value="{{ $permission->name }}" autocomplete="off" autofocus>

                <span class="invalid-feedback" permission="alert" id="permission_name_err"></span>
            </div>

            <button type="submit" class="btn btn-theme float-end" id="update-permission-submit">
                <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                {{ __('Submit') }}
            </button>
        </form>
    </div>
</div>