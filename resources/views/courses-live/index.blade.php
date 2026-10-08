@extends('layouts.header')

@section('pageTitle')
    {{ __('Live Courses List') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3"><strong>Live</strong> Courses</h1>

            @can('Course Create')
                <a href="{{ route('courses.live.create') }}" class="btn btn-theme">
                    Add Live Course
                </a>
            @endcan
        </div>

        <div class="row">
            @can('Course List')
                <div class="col-12 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <!-- FILTER -->
                            <form method="GET" action="{{ route('courses.live.index') }}"
                                class="row g-2 mb-2 align-items-end">

                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        value="{{ request('search') }}" placeholder="Search Course By Title">
                                </div>

                                <div class="col-md-3">
                                    <select name="status" class="select2 form-control">
                                        <option value="">Filter By Status</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-auto d-flex gap-2">
                                    <button class="btn btn-theme">Search</button>
                                    <a href="{{ route('courses.live.index') }}" class="btn btn-danger">
                                        Clear
                                    </a>
                                </div>
                            </form>

                            <!-- TABLE -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="stickyTableWrapper">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Language</th>
                                            <th>Type</th>
                                            <th>Live Type</th>
                                            <th>Duration</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($courses as $key => $course)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $course->title }}</td>
                                                <td class="text-capitalize">{{ $course->language }}</td>
                                                <td class="text-capitalize">{{ $course->course_type }}</td>
                                                <td class="text-capitalize">{{ $course->live_course_type }}</td>
                                                <td>{{ $course->duration }}</td>
                                                <td>₹{{ number_format($course->buy_price, 2) }}</td>

                                                <!-- STATUS -->
                                                 <td>
                                                    @can('Course Status')
                                                        <span class="status-course cursor-pointer"
                                                            data-href="{{ route('courses.update.status') }}"
                                                            data-id="{{ encrypt($course->id) }}"
                                                            data-change-status="{{ $course->status ? 0 : 1 }}">
                                                            <img
                                                                src="{{ asset($course->status ? 'assets/images/icon-toggle-on.png' : 'assets/images/icon-toggle-off.png') }}">
                                                        </span>
                                                    @endcan
                                                </td>

                                                <!-- ACTION -->
                                                 <td class="text-end text-nowrap">
                                                    @if($course->status == 1)
                                                        @can('Course View')
                                                            <a href="{{ route('courses.show', encrypt($course->id)) }}" class="btn btn-sm btn-outline-secondary me-1" title="View Course Details">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                        @endcan
                                                        @can('Assignment Create')
                                                            <a href="{{ route('assignments.create', ['course_id' => $course->id]) }}" class="btn btn-sm btn-outline-success me-1" title="Add Assignment">
                                                                + Assignment
                                                            </a>
                                                        @endcan
                                                        @can('Mini Project Create')
                                                            <a href="{{ route('mini_projects.create', ['course_id' => $course->id]) }}" class="btn btn-sm btn-outline-info me-1" title="Add Mini Project">
                                                                + Mini Project
                                                            </a>
                                                        @endcan
                                                        @can('Main Project Create')
                                                            <a href="{{ route('main_projects.create', ['course_id' => $course->id]) }}" class="btn btn-sm btn-outline-primary me-1" title="Add Main Project">
                                                                + Main Project
                                                            </a>
                                                        @endcan
                                                    @endif
                                                    
                                                    @can('Course Edit')
                                                        <a href="{{ route('courses.live.edit', encrypt($course->id)) }}" title="Edit Course">
                                                            <img src="{{ asset('assets/images/icon-edit.png') }}" alt="Edit">
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No live courses found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-end mt-3">
                                    {{ $courses->links() }}
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
    <script src="{{ asset('assets/js/live-course.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
