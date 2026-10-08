@extends('layouts.header')

@section('section')
    @include('placement-classes.form', ['placementClass' => $placementClass])
@endsection
