@extends('layouts.header')

@section('pageTitle')
    {{ __('Combo Courses List') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3"><strong>Combo</strong> Courses</h1>

            @can('Course Create')
                <a href="{{ route('courses.combo.create') }}" class="btn btn-theme">
                    Add Combo Course
                </a>
            @endcan
        </div>

        <div class="row">
            @can('Course List')
                <div class="col-12 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <!-- FILTER -->
                            <form method="GET" action="{{ route('courses.combo.index') }}"
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
                                    <a href="{{ route('courses.combo.index') }}" class="btn btn-danger">
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
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($courses as $key => $course)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $course->title }}</td>
                                                <td>{{ $course->language }}</td>
                                                <td>{{ $course->course_type }}</td>
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
                                                <td class="text-end">
                                                    @can('Course Edit')
                                                        <a href="{{ route('courses.combo.edit', encrypt($course->id)) }}">
                                                            <img src="{{ asset('assets/images/icon-edit.png') }}">
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No combo courses found</td>
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
    <script src="{{ asset('assets/js/combo-course.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
