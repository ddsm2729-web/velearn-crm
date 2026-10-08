@extends('layouts.header')

@section('pageTitle')
    {{ __('Webinars List') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3"><strong>Webinar</strong> List</h1>

            @can('Webinar Create')
                <a href="{{ route('webinars.create') }}" class="btn btn-theme"> Add Webinar</a>
            @endcan
        </div>

        <div class="row">
            @can('Webinar List')
                <div class="col-12 col-xl-12 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <!-- FILTER -->
                            <div class="col-12">
                                <form method="GET" action="{{ route('webinars.index') }}"
                                    class="row g-2 mb-2 align-items-end">

                                    <!-- Search -->
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <input type="text" name="search" class="form-control"
                                            value="{{ request('search') }}"
                                            placeholder="Search Webinar By Title / Professor..."
                                            autocomplete="off">
                                    </div>

                                    <!-- Status Filter -->
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <select name="status" class="select2 form-control w-100">
                                            <option value="" disabled {{ request('status') === null ? 'selected' : '' }}>
                                                Filter By Status
                                            </option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-12 col-md-12 col-xl-auto d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-theme">
                                            Search Webinar
                                        </button>

                                        <a href="{{ route('webinars.index') }}" class="btn btn-danger">
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
                                            <th>Category</th>
                                            <th>Course Level</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Seats</th>
                                            <th>Professor</th>
                                            <th>Status</th>
                                            <th class="text-end sticky-action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-nowrap">
                                        @forelse ($webinars as $key => $wb)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $wb->title }}</td>
                                                <td>{{ $wb->category }}</td>
                                                <td>{{ $wb->course_level }}</td>
                                                <td>{{ date('d F, Y', strtotime($wb->date)) }}</td>
                                                <td>
                                                    {{ date('h:i A', strtotime($wb->from_time)) }}
                                                    -
                                                    {{ date('h:i A', strtotime($wb->to_time)) }}
                                                </td>
                                                <td>{{ $wb->seats }}</td>
                                                <td>{{ $wb->professor }}</td>

                                                <!-- STATUS -->
                                                <td>
                                                    @can('Webinar Status')
                                                        @if ($wb->status == 1)
                                                            <span data-href="{{ route('webinars.update.status') }}"
                                                                data-id="{{ encrypt($wb->id) }}"
                                                                data-change-status="0"
                                                                class="status-webinar cursor-pointer"
                                                                title="Enable/Disable Webinar">
                                                                <img src="{{ asset('assets/images/icon-toggle-on.png') }}">
                                                            </span>
                                                        @else
                                                            <span data-href="{{ route('webinars.update.status') }}"
                                                                data-id="{{ encrypt($wb->id) }}"
                                                                data-change-status="1"
                                                                class="status-webinar cursor-pointer"
                                                                title="Enable/Disable Webinar">
                                                                <img src="{{ asset('assets/images/icon-toggle-off.png') }}">
                                                            </span>
                                                        @endif
                                                    @endcan
                                                </td>

                                                <!-- ACTION -->
                                                <td class="text-end sticky-action">
                                                    @can('Webinar Edit')
                                                        <a href="{{ route('webinars.edit', encrypt($wb->id)) }}"
                                                            title="Edit Webinar">
                                                            <img src="{{ asset('assets/images/icon-edit.png') }}">
                                                        </a>
                                                    @endcan
 
                                                    @can('Webinar Delete')
                                                        <a href="javascript:void(0)"
                                                            data-id="{{ encrypt($wb->id) }}"
                                                            class="ms-2 delete-webinar"
                                                            title="Delete Webinar">
                                                            <img src="{{ asset('assets/images/delete.png') }}">
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No webinars found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-end mt-4">
                                    {{ $webinars->links() }}
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
    <script src="{{ asset('assets/js/webinar.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('body').find('.select2').select2();
        });
    </script>
@endpush
