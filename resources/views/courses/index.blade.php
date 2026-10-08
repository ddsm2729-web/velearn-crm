@extends('layouts.header')

@section('pageTitle')
    {{ __('Courses List') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3"><strong>Course</strong> List</h1>

            @can('Course Create')
                <a href="{{ route('courses.create') }}" class="btn btn-theme">Add Course</a>
            @endcan
        </div>

        <div class="row">
            @can('Course List')
                <div class="col-12 col-xl-12 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <!-- FILTER -->
                            <div class="col-12">
                                <form method="GET" action="{{ route('courses.index') }}"
                                    class="row g-2 mb-2 align-items-end">

                                    <!-- Search -->
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <input type="text" name="search" class="form-control"
                                            value="{{ request('search') }}" placeholder="Search Course By Title..."
                                            autocomplete="off">
                                    </div>

                                    <!-- Status Filter -->
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <select name="status" class="select2 form-control w-100">
                                            <option value="" disabled
                                                {{ request('status') === null ? 'selected' : '' }}>
                                                Filter By Status
                                            </option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-12 col-md-12 col-xl-auto d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-theme">
                                            Search Course
                                        </button>

                                        <a href="{{ route('courses.index') }}" class="btn btn-danger">
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
                                            <th>Language</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Videos</th>
                                            <th class="text-end sticky-action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-nowrap">
                                        @forelse ($courses as $key => $course)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td class="text-capitalize">{{ $course->title }}</td>
                                                <td class="text-capitalize">{{ $course->language }}</td>
                                                <td class="text-capitalize">{{ $course->course_type }}</td>
                                                <td>
                                                    @if ($course->course_type === 'free')
                                                        Free
                                                    @else
                                                        ₹{{ number_format($course->buy_price, 2) }}
                                                    @endif
                                                </td>

                                                <!-- STATUS -->
                                                <td>
                                                    @can('Course Status')
                                                        @if ($course->status == 1)
                                                            <span data-href="{{ route('courses.update.status') }}"
                                                                data-id="{{ encrypt($course->id) }}" data-change-status="0"
                                                                class="status-course cursor-pointer"
                                                                title="Enable/Disable Course">
                                                                <img src="{{ asset('assets/images/icon-toggle-on.png') }}">
                                                            </span>
                                                        @else
                                                            <span data-href="{{ route('courses.update.status') }}"
                                                                data-id="{{ encrypt($course->id) }}"
                                                                data-change-status="{{ $course->status == 1 ? 0 : 1 }}"
                                                                class="status-course cursor-pointer"
                                                                title="Enable/Disable Course">
                                                                <img
                                                                    src="{{ asset($course->status ? 'assets/images/icon-toggle-on.png' : 'assets/images/icon-toggle-off.png') }}">
                                                            </span>
                                                        @endif
                                                    @endcan
                                                </td>

                                                <td>
                                                    <span class="badge bg-primary">
                                                        {{ $course->videos_count }}
                                                    </span>
                                                </td>

                                                <!-- ACTION -->
                                                <td class="text-end sticky-action">
                                                    @can('Course Edit')
                                                        <a href="{{ route('courses.edit', encrypt($course->id)) }}"
                                                            title="Edit Course">
                                                            <img src="{{ asset('assets/images/icon-edit.png') }}">
                                                        </a>
                                                    @endcan

                                                    @can('Course Create')
                                                        <a href="{{ route('courses.upload-video', encrypt($course->id)) }}"
                                                            title="Upload Video" class="ms-2">
                                                            <img src="{{ asset('assets/images/upload.png') }}">
                                                        </a>
                                                    @endcan

                                                    @can('Course List')
                                                        <a href="{{ route('courses.videos.index', encrypt($course->id)) }}"
                                                            title="View Videos" class="ms-2">
                                                            <img src="{{ asset('assets/images/folder.png') }}">
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No courses found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-end mt-4">
                                    {{ $courses->links() }}
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
    <script src="{{ asset('assets/js/course.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
