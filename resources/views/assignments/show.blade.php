@extends('layouts.header')

@section('pageTitle', 'View Assignment')

@section('section')
<div class="container-fluid p-0">
    <div class="mb-4">
        <h3 class="fw-bold">Assignment: {{ $assignment->title }}</h3>
    </div>
    <div class="card">
        <div class="card-body">
            <p>{{ $assignment->description }}</p>
            <!-- submissions list here -->
        </div>
    </div>
</div>
@endsection
