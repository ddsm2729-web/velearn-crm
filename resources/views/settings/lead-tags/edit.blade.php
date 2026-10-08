<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Edit Lead Tag: {{ $tag->name }}</h5>
    </div>
    <div class="card-body">
        <form id="tag-form" action="{{ route('lead-tags.update', $tag->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Tag Name</label>
                <input type="text" name="name" class="form-control" value="{{ $tag->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Visible to Roles</label>
                <div class="row">
                    @php $assignedRoles = $tag->roles->pluck('id')->toArray(); @endphp
                    @foreach($roles as $role)
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="role_ids[]" value="{{ $role->id }}" 
                                    id="role_{{ $role->id }}" {{ in_array($role->id, $assignedRoles) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-light" onclick="$('#tag-form-section').hide()">Cancel</button>
                <button type="submit" class="btn btn-theme">Update Tag</button>
            </div>
        </form>
    </div>
</div>
