<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Create New Lead Status</h5>
    </div>
    <div class="card-body">
        <form id="status-form" action="{{ route('lead-statuses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Status Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Active, Converted" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Color</label>
                <input type="color" name="color" class="form-control form-control-color" value="#3b82f6" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Visible to Roles</label>
                <div class="row">
                    @foreach($roles as $role)
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="role_ids[]" value="{{ $role->id }}" id="role_{{ $role->id }}">
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
                <button type="submit" class="btn btn-theme">Save Status</button>
            </div>
        </form>
    </div>
</div>
