@extends('layouts.header')

@section('pageTitle')
    {{ __('Edit Blog') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3">Edit <strong>Blog</strong></h1>

            @can('Blog List')
                <a href="javascript:history.back()" class="btn btn-theme"><i class="fas fa-arrow-left me-2"></i> Back</a>
            @endcan
        </div>

        @can('Blog Edit')
            <div class="card mt-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('blogs-info.update', encrypt($blog->id)) }}"
                        enctype="multipart/form-data" onsubmit="return false;" id="edit-blog-form">
                        @csrf
                        @method('PUT')

                        <!-- BASIC DETAILS -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Blog Title *</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $blog->title) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Author *</label>
                                <input type="text" name="author" class="form-control"
                                    value="{{ old('author', $blog->author) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Published Date *</label>
                                <input type="date" name="published_date" class="form-control"
                                    value="{{ old('published_date', $blog->published_date) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-control select2">
                                    <option value="">Select status</option>
                                    <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>
                                        Draft</option>
                                    <option value="published"
                                        {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- IMAGE -->
                        <div class="mb-3">
                            <label class="form-label">Blog Image</label>
                            <input type="file" name="image" class="form-control">
                            @if ($blog->image)
                                <div class="mt-2">
                                    <img src="{{ asset('blogs/' . $blog->image) }}" alt="Blog Image" width="120">
                                </div>
                            @endif
                        </div>

                        <!-- DESCRIPTIONS -->
                        <div class="mb-4">
                            <label class="form-label">Blog Descriptions *</label>

                            <div id="description-wrapper">
                                @php
                                    $descriptions = old('descriptions', $blog->info->pluck('descriptions')->toArray());
                                @endphp

                                @forelse($descriptions as $desc)
                                    <div class="d-flex gap-2 mb-2 description-item">
                                        <textarea name="descriptions[]" class="form-control" rows="3" placeholder="Blog description">{{ $desc }}</textarea>
                                        <button type="button" class="btn btn-danger remove-description">X</button>
                                    </div>
                                @empty
                                    <div class="d-flex gap-2 mb-2 description-item">
                                        <textarea name="descriptions[]" class="form-control" rows="3" placeholder="Blog description"></textarea>
                                        <button type="button" class="btn btn-danger remove-description">X</button>
                                    </div>
                                @endforelse
                            </div>

                            <button type="button" id="add-description" class="btn btn-sm btn-outline-primary">
                                + Add description
                            </button>
                        </div>

                        <!-- SUBMIT -->
                        <button type="submit" class="btn btn-theme float-end" id="edit-blog-submit">
                            <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                            Update
                        </button>

                    </form>
                </div>
            </div>
        @endcan
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/blogs.js') }}"></script>

    <script>
        $('.select2').select2();

        // Add description
        $('#add-description').on('click', function() {
            $('#description-wrapper').append(`
            <div class="d-flex gap-2 mb-2 description-item">
                <textarea name="descriptions[]" class="form-control" rows="3" placeholder="Blog description"></textarea>
                <button type="button" class="btn btn-danger remove-description">X</button>
            </div>
        `);
        });

        // Remove description
        $('body').on('click', '.remove-description', function() {
            if ($('.description-item').length > 1) {
                $(this).closest('.description-item').remove();
            } else {
                alert('At least one description is required.');
            }
        });
    </script>
@endpush
