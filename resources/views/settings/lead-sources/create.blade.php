<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Create New Lead Source</h5>
    </div>
    <div class="card-body">
        <form id="source-form" action="{{ route('lead-sources.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Source Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Website, App, Instagram" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Visible to Roles</label>
                <div class="row">
                    @foreach($roles as $role)
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="role_ids[]" value="{{ $role->id }}" id="role_src_{{ $role->id }}">
                                <label class="form-check-label" for="role_src_{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-light" onclick="$('#source-form-section').hide()">Cancel</button>
                <button type="submit" class="btn btn-theme">Save Source</button>
            </div>
        </form>
    </div>
</div>
