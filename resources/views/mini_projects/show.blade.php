@extends('layouts.header')

@section('pageTitle', 'View Mini Project')

@section('section')
<div class="container-fluid p-0">
    <div class="mb-4">
        <h3 class="fw-bold">Mini Project: {{ $mini_project->title }}</h3>
    </div>
    <div class="card">
        <div class="card-body">
            <p>{{ $mini_project->description }}</p>
            <!-- submissions list here -->
        </div>
    </div>
</div>
@endsection
