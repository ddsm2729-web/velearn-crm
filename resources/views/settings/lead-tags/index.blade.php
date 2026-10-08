@extends('layouts.header')
@section('pageTitle')
    {{ config('app.name') }} | Lead Tags
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3"><strong>Lead Tags</strong> Settings</h1>
            <button class="btn btn-theme" id="create-tag-btn"> Create Tag</button>
        </div>

        <div class="row">
            <div class="col-12 col-xl-8 mt-1">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="tag-tbl">
                            <thead>
                                <tr>
                                    <th>Sr no.</th>
                                    <th>Tag Name</th>
                                    <th>Roles</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tags as $key => $tag)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $tag->name }}</td>
                                        <td>
                                            @php
                                                $badgeColors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning text-dark', 'bg-secondary', 'bg-dark', 'bg-purple'];
                                            @endphp
                                            @foreach($tag->roles as $ri => $role)
                                                <span class="badge {{ $badgeColors[$ri % count($badgeColors)] }}">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-end">
                                            <span data-href="{{ route('lead-tags.edit', $tag->id) }}" class="edit-tag cursor-pointer" title="Edit Tag">
                                                <img src="{{ asset('assets/images/icon-edit.png') }}" alt="icon-edit.png">
                                            </span>
                                            &nbsp;
                                            <span data-href="{{ route('lead-tags.destroy', $tag->id) }}" class="delete-tag cursor-pointer" title="Delete Tag">
                                                <img src="{{ asset('assets/images/icon-del.png') }}" alt="icon-delete.png">
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4 mt-1" id="tag-form-section" style="display:none;">
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
            $('#tag-tbl').DataTable();

            $('#create-tag-btn').on('click', function() {
                $.get("{{ route('lead-tags.create') }}", function(data) {
                    $('#tag-form-section').html(data).show();
                    $('html, body').animate({
                        scrollTop: $("#tag-form-section").offset().top
                    }, 500);
                });
            });

            $(document).on('click', '.edit-tag', function() {
                $.get($(this).data('href'), function(data) {
                    $('#tag-form-section').html(data).show();
                    $('html, body').animate({
                        scrollTop: $("#tag-form-section").offset().top
                    }, 500);
                });
            });

            $(document).on('click', '.delete-tag', function() {
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

            $(document).on('submit', '#tag-form', function(e) {
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
