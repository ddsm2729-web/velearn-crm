@extends('layouts.header')

@section('pageTitle', 'View Main Project')

@section('section')
<div class="container-fluid p-0">
    <div class="mb-4">
        <h3 class="fw-bold">Main Project: {{ $main_project->title }}</h3>
    </div>
    <div class="card">
        <div class="card-body">
            <p>{{ $main_project->description }}</p>
            <!-- submissions list here -->
        </div>
    </div>
</div>
@endsection
