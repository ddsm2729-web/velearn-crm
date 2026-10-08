@extends('layouts.header')

@section('pageTitle')
    {{ __('Blogs List') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3"><strong>Blog</strong> List</h1>

            @can('Blog Create')
                <a href="{{ route('blogs-info.create') }}" class="btn btn-theme">Add Blog</a>
            @endcan
        </div>

        <div class="row">
            @can('Blog List')
                <div class="col-12 col-xl-12 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <!-- FILTER -->
                            <div class="col-12">
                                <form method="GET" action="{{ route('blogs-info.index') }}"
                                    class="row g-2 mb-2 align-items-end">

                                    <!-- Search -->
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <input type="text" name="search" class="form-control"
                                            value="{{ request('search') }}"
                                            placeholder="Search Blog By Title..."
                                            autocomplete="off">
                                    </div>

                                    <!-- Status Filter -->
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <select name="status" class="select2 form-control w-100">
                                            <option value="" disabled {{ request('status') === null ? 'selected' : '' }}>
                                                Filter By Status
                                            </option>
                                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                                                Published
                                            </option>
                                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                                                Draft
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-12 col-md-12 col-xl-auto d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-theme">
                                            Search Blog
                                        </button>

                                        <a href="{{ route('blogs-info.index') }}" class="btn btn-danger">
                                            Clear Search
                                        </a>
                                    </div>
                                </form>
                            </div>

                            <!-- TABLE -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="stickyTableWrapper">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Published Date</th>
                                            <th>Status</th>
                                            <th class="text-end sticky-action">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($blogs as $key => $blog)
                                            <tr>
                                                <td>{{ $blogs->firstItem() + $key }}</td>
                                                <td class="text-capitalize">{{ $blog->title }}</td>
                                                <td class="text-capitalize">{{ $blog->author }}</td>
                                                <td>{{ \Carbon\Carbon::parse($blog->published_date)->format('d M Y') }}</td>

                                                <!-- STATUS -->
                                                <td>
                                                    @can('Blog Status')
                                                        <span 
                                                            data-href="{{ route('blogs.update.status', $blog->id) }}"
                                                            data-id="{{ $blog->id }}"
                                                            class="status-blog cursor-pointer"
                                                            title="Enable/Disable Blog">
                                                            <img src="{{ asset($blog->status === 'published' 
                                                                ? 'assets/images/icon-toggle-on.png' 
                                                                : 'assets/images/icon-toggle-off.png') }}">
                                                        </span>
                                                    @endcan
                                                </td>
 
                                                <!-- ACTION -->
                                                <td class="text-end sticky-action">
                                                    @can('Blog Edit')
                                                        <a href="{{ route('blogs-info.edit', encrypt($blog->id)) }}"
                                                            title="Edit Blog">
                                                            <img src="{{ asset('assets/images/icon-edit.png') }}">
                                                        </a>
                                                    @endcan
 
                                                    @can('Blog Delete')
                                                        <a href="javascript:void(0)"
                                                            data-id="{{ encrypt($blog->id) }}"
                                                            class="ms-2 delete-blog"
                                                            title="Delete Blog">
                                                            <img src="{{ asset('assets/images/delete.png') }}">
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No blogs found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-end mt-4">
                                    {{ $blogs->links() }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/blogs.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
