@extends('layouts.header')
@section('pageTitle')
    {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3"><strong>Roles</strong> List</h1>
            
            @if(auth()->user()->can('Role Create'))
                <button class="btn btn-theme" id="create-role-btn"> Create Roles</button>
            @endif
        </div>

        <div class="row">
            @if(auth()->user()->can('Role List'))
                <div class="col-12 col-xl-7 mt-1">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" id="role-tbl">
                                <thead>
                                    <tr>
                                        <th>Sr no.</th>
                                        <th>Roles Name</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($roles) > 0)
                                        @foreach($roles as $key => $rl)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $rl->name }}</td>
                                                <td class="text-end">
                                                    @if(auth()->user()->can('Role Edit'))
                                                        <span data-href="{{ route('roles.edit', encrypt($rl->id)) }}" class="edit-role cursor-pointer" title="Edit Role Name">
                                                            <img src="{{ asset('assets/images/icon-edit.png') }}" alt="icon-edit.png">
                                                        </span>
                                                    @endif
                                                    
                                                    @if(auth()->user()->can('Role Delete'))
                                                        &nbsp;
                                                        <span data-href="{{ route('roles.destroy', encrypt($rl->id)) }}" class="delete-role cursor-pointer" title="Delete Role Name">
                                                            <img src="{{ asset('assets/images/icon-del.png') }}" alt="icon-delete.png">
                                                            <span></span>
                                                        </span>
                                                    @endif
                                                    
                                                    @if(auth()->user()->can('Role Assign'))
                                                        &nbsp;
                                                        <span data-href="{{ route('roles.show', encrypt($rl->id)) }}" class="manage-role-permission cursor-pointer" title="Manage Permission">
                                                            <img src="{{ asset('assets/images/icon-show.png') }}" alt="icon-delete.png">
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

            <div class="col-12 col-xl-5 mt-1" id="role-form-section" style="display:none;">
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
    <script src="{{ asset('assets/js/role-manage.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Initialize Table into DataTable
            $('body').find('#role-tbl').DataTable(); 
        });
    </script>
@endpush