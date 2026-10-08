@extends('layouts.header')
@section('pageTitle')
    {{ config('app.name') }} | Lead Statuses
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3"><strong>Lead Statuses</strong> Settings</h1>
            @can('Lead Status Create')
            <button class="btn btn-theme" id="create-status-btn"> Create Status</button>
            @endcan
        </div>

        <div class="row">
            <div class="col-12 col-xl-8 mt-1">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="status-tbl">
                            <thead>
                                <tr>
                                    <th>Sr no.</th>
                                    <th>Status Name</th>
                                    <th>Color</th>
                                    <th>Roles</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statuses as $key => $status)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $status->name }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $status->color }}">{{ $status->color }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $badgeColors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning text-dark', 'bg-secondary', 'bg-dark', 'bg-purple'];
                                            @endphp
                                            @foreach($status->roles as $ri => $role)
                                                <span class="badge {{ $badgeColors[$ri % count($badgeColors)] }}">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-end">
                                            @can('Lead Status Edit')
                                            <span data-href="{{ route('lead-statuses.edit', $status->id) }}" class="edit-status cursor-pointer" title="Edit Status">
                                                <img src="{{ asset('assets/images/icon-edit.png') }}" alt="icon-edit.png">
                                            </span>
                                            &nbsp;
                                            @endcan
                                            @can('Lead Status Delete')
                                            <span data-href="{{ route('lead-statuses.destroy', $status->id) }}" class="delete-status cursor-pointer" title="Delete Status">
                                                <img src="{{ asset('assets/images/icon-del.png') }}" alt="icon-delete.png">
                                            </span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4 mt-1" id="status-form-section" style="display:none;">
                {{-- DYNAMIC FORM PAGE LOAD --}}
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#status-tbl').DataTable();

            $('#create-status-btn').on('click', function() {
                $.get("{{ route('lead-statuses.create') }}", function(data) {
                    $('#status-form-section').html(data).show();
                    $('html, body').animate({
                        scrollTop: $("#status-form-section").offset().top
                    }, 500);
                });
            });

            $(document).on('click', '.edit-status', function() {
                $.get($(this).data('href'), function(data) {
                    $('#status-form-section').html(data).show();
                    $('html, body').animate({
                        scrollTop: $("#status-form-section").offset().top
                    }, 500);
                });
            });

            $(document).on('click', '.delete-status', function() {
                const url = $(this).data('href');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: { _token: "{{ csrf_token() }}" },
                            success: function(response) {
                                if (response.status) {
                                    toastr.success(response.message);
                                    location.reload();
                                }
                            }
                        });
                    }
                });
            });

            $(document).on('submit', '#status-form', function(e) {
                e.preventDefault();
                const form = $(this);
                const btn = form.find('button[type="submit"]');
                const originalText = btn.text().trim();
                btn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Something went wrong.');
                            btn.prop('disabled', false).text(originalText);
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text(originalText);
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else if (xhr.status === 419) {
                            toastr.error('Session expired. Please refresh the page.');
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
