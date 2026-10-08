@extends('layouts.header')
@section('pageTitle')
    {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3"><strong>Permissions</strong> List</h1>

            @if(auth()->user()->can('Permission Create'))
                <button class="btn btn-theme" id="create-permission-btn"> Create Permissions</button>
            @endif
        </div>
 
        <div class="row">
            @if(auth()->user()->can('Permission List'))
                <div class="col-12 col-xl-7 mt-1">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" id="permission-tbl">
                                <thead>
                                    <tr>
                                        <th>Sr no.</th>
                                        <th>Permission Name</th>
                                        <th>Group Name</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($permissions) > 0)
                                        @foreach($permissions as $key => $per)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $per->name }}</td>
                                                <td>{{ $per->group_name }}</td>
                                                <td class="text-end">
                                                    @if(auth()->user()->can('Permission Edit'))
                                                        <span data-href="{{ route('permissions.edit', encrypt($per->id)) }}" class="edit-permission cursor-pointer" title="Edit Permission Name">
                                                            <img src="{{ asset('assets/images/icon-edit.png') }}" alt="icon-edit.png">
                                                        </span>
                                                    @endif
                                                    &nbsp;
                                                    @if(auth()->user()->can('Permission Delete'))
                                                        <span data-href="{{ route('permissions.destroy', encrypt($per->id)) }}" class="delete-permission cursor-pointer" title="Delete Permission Name">
                                                            <img src="{{ asset('assets/images/icon-del.png') }}" alt="icon-delete.png">
                                                            <span></span>
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-12 col-xl-5 mt-1" id="permission-form-section" style="display:none;">
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
    <script src="{{ asset('assets/js/permission-manage.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Initialize Table into DataTable
            $('body').find('#permission-tbl').DataTable();
        });
    </script>
@endpush