<div class="card">
    <div class="card-header">
        <h5 class="card-title">Create Permissions</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('permissions.store') }}" onsubmit="return false;" id="create-permission-form">
            @csrf
            <div class="mb-3">
                <label for="permission_name" class="form-label">Permission Name <span class="text-danger">*</span>
                    :</label>
                <input id="permission_name" type="text"
                    class="form-control @error('permission_name') is-invalid @enderror" name="permission_name"
                    value="{{ old('permission_name') }}" autocomplete="off" autofocus>

                <span class="invalid-feedback" role="alert" id="permission_name_err"></span>
            </div>

            <div class="mb-3">
                <label for="group_name" class="form-label">Guard Name <span class="text-danger">*</span> :</label>
                <input id="group_name" type="text" class="form-control @error('group_name') is-invalid @enderror"
                    name="group_name" value="{{ old('group_name') }}" autocomplete="off">
                <span class="invalid-feedback" role="alert" id="group_name_err"></span>
            </div>

            <button type="submit" class="btn btn-theme float-end" id="create-permission-submit">
                <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                {{ __('Submit') }}
            </button>
        </form>
    </div>
</div>
