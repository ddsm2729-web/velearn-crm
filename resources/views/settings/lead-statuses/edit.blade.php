<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Edit Lead Status</h5>
    </div>
    <div class="card-body">
        <form id="status-form" action="{{ route('lead-statuses.update', $status->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Status Name</label>
                <input type="text" name="name" class="form-control" value="{{ $status->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Color</label>
                <input type="color" name="color" class="form-control form-control-color" value="{{ $status->color }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Visible to Roles</label>
                <div class="row">
                    @foreach($roles as $role)
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="role_ids[]" value="{{ $role->id }}" id="role_{{ $role->id }}" {{ $status->roles->contains($role->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-light" onclick="$('#status-form-section').hide()">Cancel</button>
                <button type="submit" class="btn btn-theme">Update Status</button>
            </div>
        </form>
    </div>
</div>
