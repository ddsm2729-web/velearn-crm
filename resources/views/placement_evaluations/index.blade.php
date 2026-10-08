@extends('layouts.header')
@section('pageTitle', 'Placement Evaluations')
@section('section')
<div class='row mb-2 mb-xl-3'>
    <div class='col-auto d-none d-sm-block'>
        <h3><strong>Placement Evaluations</strong></h3>
    </div>
    <div class='col-auto ms-auto text-end mt-n1'>
        @can('Placement Evaluation Create')
        <a href='{{ route("placement-evaluations.create") }}' class='btn btn-primary d-flex align-items-center shadow-sm'>
            <i class='fas fa-plus me-2'></i> Create
        </a>
        @endcan
    </div>
</div>
<div class='row'>
    <div class='col-12'>
        <div class='card'>
            <div class='card-body p-0'>
                <div class='table-responsive'>
                    <table class='table table-hover mb-0'>
                        <thead class='text-uppercase text-muted' style='font-size: 0.75rem; background-color: #f8f9fa;'>
                            <tr>
                                <th class='ps-4 py-3'>ID</th>
                                <th>COURSE</th>
                                <th>TITLE</th>
                                <th>TIMING (MINS)</th>
                                <th>CORRECTION</th>
                                <th class='pe-4 py-3 text-end'>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluations as $eval)
                            <tr>
                                <td class='ps-4'>{{ $eval->id }}</td>
                                <td>{{ $eval->course->title ?? $eval->course_id }}</td>
                                <td>{{ $eval->title }}</td>
                                <td>{{ $eval->test_timing }}</td>
                                <td>{{ ucfirst($eval->correction_type) }}</td>
                                <td class='pe-4 text-end'>
                                    @can('Placement Evaluation Edit')
                                    <a href='{{ route("placement-evaluations.edit", $eval->id) }}' class='btn btn-sm btn-outline-primary'>Edit</a>
                                    @endcan
                                    @can('Placement Evaluation Delete')
                                    <form action='{{ route("placement-evaluations.destroy", $eval->id) }}' method='POST' class='d-inline'>
                                        @csrf
                                        @method('DELETE')
                                        <button class='btn btn-sm btn-outline-danger' onclick='return confirm("Delete this item?")'>Delete</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                            @if($evaluations->isEmpty())
                            <tr><td colspan='6' class='text-center py-4'>No records found.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
