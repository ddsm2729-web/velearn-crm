@extends('layouts.header')

@section('pageTitle')
    E-books | {{ config('app.name') }}
@endsection

@section('section')
<div class="container-fluid p-0">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold m-0">E-books Gallery</h1>
            <p class="text-muted small mb-0">Manage digital educational resources</p>
        </div>
        <div class="col text-end">
            @can('E-Book Create')
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadEbookModal">
                    <i class="fas fa-upload me-2"></i> Upload New
                </button>
            @endcan
        </div>
    </div>

    <div class="row g-4">
        @can('E-Book List')
            @forelse($ebooks as $book)
        <div class="col-xl-3 col-lg-4 col-md-6" id="ebook-card-{{ $book->id }}">
            <div class="card h-100 shadow-sm border-0 hvr-shadow position-relative overflow-hidden {{ !$book->status ? 'opacity-75 bg-light' : '' }}">
                @if(!$book->status)
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center z-index-1" style="background: rgba(255,255,255,0.4); pointer-events: none;">
                        <span class="badge bg-danger rounded-pill px-3 shadow">INACTIVE</span>
                    </div>
                @endif
                
                <div class="position-absolute top-0 end-0 m-2 z-index-2">
                    <div class="dropdown">
                        @canany(['E-Book Edit', 'E-Book Status', 'E-Book Delete'])
                            <button class="btn btn-white btn-sm shadow-sm rounded-circle p-1" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v fa-xs"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                @can('E-Book Edit')
                                    <li><a class="dropdown-item edit-btn" href="#" 
                                        data-id="{{ $book->id }}"
                                        data-title="{{ $book->title }}"
                                        data-author="{{ $book->author }}"
                                        data-price="{{ $book->price }}"
                                        data-bs-toggle="modal" data-bs-target="#editEbookModal">
                                        <i class="fas fa-edit me-2 text-primary"></i> Edit</a></li>
                                @endcan
                                
                                @can('E-Book Status')
                                    <li><a class="dropdown-item toggle-status-btn" href="#" data-id="{{ $book->id }}">
                                        <i class="fas {{ $book->status ? 'fa-eye-slash text-warning' : 'fa-eye text-success' }} me-2"></i> 
                                        {{ $book->status ? 'Deactivate' : 'Activate' }}</a></li>
                                @endcan
 
                                @can('E-Book Delete')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item delete-btn text-danger" href="#" data-id="{{ $book->id }}">
                                        <i class="fas fa-trash-alt me-2"></i> Delete</a></li>
                                @endcan
                            </ul>
                        @endcanany
                    </div>
                </div>

                <img src="{{ $book->thumbnail ? asset($book->thumbnail) : 'https://via.placeholder.com/300x400?text=E-Book' }}" 
                     class="card-img-top" style="height: 250px; object-fit: cover;" alt="{{ $book->title }}">
                
                <div class="card-body">
                    <h6 class="fw-bold mb-1 text-truncate">{{ $book->title }}</h6>
                    <p class="text-muted small mb-2">By {{ $book->author ?? 'Velearn Team' }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge {{ $book->is_free ? 'bg-soft-success text-success' : 'bg-soft-primary text-primary' }} rounded-pill px-3">
                            {{ $book->is_free ? 'FREE' : '₹'.$book->price }}
                        </span>
                        <a href="{{ asset($book->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fas fa-download me-1"></i> Preview
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="py-5">
                <i class="fas fa-book-open fa-3x text-light mb-3"></i>
                <p class="text-muted">No e-books available. Start by uploading one!</p>
            </div>
        </div>
        @endforelse
        @endcan
    </div>

    @can('E-Book List')
        <div class="mt-4">
            {{ $ebooks->links() }}
        </div>
    @endcan
</div>

{{-- Upload Modal --}}
<div class="modal fade" id="uploadEbookModal" tabindex="-1">
    <div class="modal-dialog shadow">
        <form action="{{ route('ebooks.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Upload New E-book</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small">E-book Title</label>
                    <input type="text" name="title" class="form-control rounded-pill px-3" placeholder="Enter title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Author Name</label>
                    <input type="text" name="author" class="form-control rounded-pill px-3" placeholder="Enter author name">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Price (₹)</label>
                        <input type="number" name="price" class="form-control rounded-pill px-3" placeholder="0 for Free" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Thumbnail Image</label>
                        <input type="file" name="thumbnail" class="form-control rounded-pill" accept="image/*">
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold small">PDF File</label>
                    <input type="file" name="file" class="form-control rounded-pill" accept=".pdf" required>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow">Upload E-book</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editEbookModal" tabindex="-1">
    <div class="modal-dialog shadow">
        <form id="editEbookForm" method="POST" enctype="multipart/form-data" class="modal-content border-0">
            @csrf
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">Edit E-book</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small">E-book Title</label>
                    <input type="text" name="title" id="edit-title" class="form-control rounded-pill px-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Author Name</label>
                    <input type="text" name="author" id="edit-author" class="form-control rounded-pill px-3">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Price (₹)</label>
                        <input type="number" name="price" id="edit-price" class="form-control rounded-pill px-3">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Change Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control rounded-pill" accept="image/*">
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold small">Change PDF File</label>
                    <input type="file" name="file" class="form-control rounded-pill" accept=".pdf">
                    <small class="text-muted">Leave blank to keep current file</small>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn btn-dark w-100 rounded-pill py-2 fw-bold shadow">Update E-book</button>
            </div>
        </form>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function() {
        // Edit Button Click
        $('.edit-btn').on('click', function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            const author = $(this).data('author');
            const price = $(this).data('price');

            $('#edit-title').val(title);
            $('#edit-author').val(author);
            $('#edit-price').val(price);
            $('#editEbookForm').attr('action', `/ebooks/${id}/update`);
        });

        // Toggle Status
        $('.toggle-status-btn').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const btn = $(this);

            $.post(`/ebooks/${id}/toggle-status`, { _token: '{{ csrf_token() }}' }, function(res) {
                if(res.status) {
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 800);
                }
            });
        });

        // Delete Button Click
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            
            if(confirm('Are you sure you want to delete this e-book? This action cannot be undone.')) {
                $.ajax({
                    url: `/ebooks/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if(res.status) {
                            toastr.success(res.message);
                            $(`#ebook-card-${id}`).fadeOut();
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong!');
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection
