@extends('layouts.header')

@section('section')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Placement Classes</h2>
        @can('Placement Classes Create')
        <a href="{{ route('placement-classes.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Class
        </a>
        @endcan
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white border-bottom-0">
            <h6 class="m-0 font-weight-bold text-primary">All Placement Classes</h6>
            <div class="d-flex" style="width: 300px;" id="placementFilterForm" data-url="{{ route('placement-classes.index') }}">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" id="placementSearchInput" name="search" placeholder="Search day or title..." value="{{ request('search') }}" autocomplete="off">
                    <button type="button" class="btn btn-outline-danger {{ request('search') ? '' : 'd-none' }}" id="placementSearchClear" title="Clear">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div id="placementTableContainer" style="transition: opacity .2s;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">Day</th>
                                <th scope="col">Title</th>
                                <th scope="col">Video</th>
                                <th scope="col" class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $cls)
                            <tr>
                                <td class="ps-4 fw-bold">Day {{ $cls->day }}</td>
                                <td>{{ $cls->title }}</td>
                                <td class="text-muted text-truncate" style="max-width: 250px;">
                                    @if($cls->video)
                                        <a href="{{ $cls->video }}" target="_blank" class="text-decoration-none">
                                            <i class="fas {{ $cls->video_path ? 'fa-play-circle' : 'fa-external-link-alt' }} me-1 text-primary"></i> Watch Video
                                        </a>
                                        @if($cls->video_path)
                                            <span class="badge bg-success-subtle text-success ms-1">Uploaded</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning ms-1">Link</span>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic">No video</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('placement-class-questions.index', ['class_id' => $cls->id]) }}" class="btn btn-sm btn-outline-info me-2" title="Manage Quiz">
                                        <i class="fas fa-question-circle"></i> Quiz
                                    </a>
                                    @can('Placement Classes Edit')
                                    <a href="{{ route('placement-classes.edit', $cls->id) }}" class="btn btn-sm btn-outline-warning me-2" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @endcan
                                    @can('Placement Classes Delete')
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-form-id="delete-form-{{ $cls->id }}" title="Delete">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                    <form id="delete-form-{{ $cls->id }}" action="{{ route('placement-classes.destroy', $cls->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                    <h5>No placement classes found</h5>
                                    <p class="mb-0">Try adjusting your search or add a new class.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($classes->hasPages())
            <div class="card-footer bg-white py-3 border-top-0 d-flex justify-content-center">
                {{ $classes->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        let searchTimeout;
        let currentRequest = null;
        const $form = $('#placementFilterForm');
        const $input = $('#placementSearchInput');
        const $clear = $('#placementSearchClear');
        const $container = $('#placementTableContainer');

        function loadResults(url) {
            window.history.pushState({}, '', url);
            $container.css('opacity', 0.5);

            if (currentRequest) {
                currentRequest.abort();
            }

            currentRequest = $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    $container.html($(response).find('#placementTableContainer').html());
                },
                error: function (xhr, status) {
                    if (status !== 'abort') {
                        console.error('Failed to fetch placement classes.');
                    }
                },
                complete: function () {
                    $container.css('opacity', 1);
                }
            });
        }

        function searchUrl() {
            const search = $input.val().trim();
            const base = $form.data('url');
            return search ? base + '?search=' + encodeURIComponent(search) : base;
        }

        $input.on('input', function () {
            $clear.toggleClass('d-none', $(this).val().trim() === '');
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () {
                loadResults(searchUrl());
            }, 400);
        });

        $input.on('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                loadResults(searchUrl());
            }
        });

        $clear.on('click', function () {
            $input.val('').focus();
            $clear.addClass('d-none');
            loadResults(searchUrl());
        });

        $(document).on('click', '#placementTableContainer .pagination a', function (e) {
            e.preventDefault();
            loadResults($(this).attr('href'));
        });

        // Delete Modal Logic
        let deleteFormId = null;
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            deleteFormId = button.data('form-id');
        });
        $('#confirmDeleteBtn').on('click', function () {
            if(deleteFormId) {
                $('#' + deleteFormId).submit();
            }
        });
    });
</script>
@endpush
