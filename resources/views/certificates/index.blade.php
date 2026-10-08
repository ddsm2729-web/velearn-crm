@extends('layouts.master')

@section('pageTitle', 'Certificate Dispatch')

@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/certificates.css') }}">
    @endpush

    <div class="cert-page">

        {{-- Header Section --}}
        <div class="cert-header">
            <h2>Certificate dispatch</h2>
            <div class="cert-dropdown">
                <select name="course" id="courseFilter">
                    <option value="all">All courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- List Section --}}
        <div class="cert-list">
            @foreach($certificates as $cert)
                <div class="cert-card">

                    {{-- Left Side: Icon + Details --}}
                    <div class="cert-info">
                        <div class="cert-icon-box">
                            <i class="fa-solid fa-cube"></i>
                        </div>

                        <div class="cert-details">
                            <h4 class="cert-name">{{ $cert['name'] }}</h4>
                            <p class="cert-course">{{ $cert['course'] }}</p>
                            <div class="cert-meta">
                                <span><i class="fa-regular fa-clock"></i> {{ $cert['date'] }}</span>
                                <span><i class="fa-solid fa-certificate"></i> {{ $cert['grade'] }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: Status + Action --}}
                    <div class="cert-actions">
                        <span class="cert-status status-{{ strtolower($cert['status']) }}">
                            {{ $cert['status'] }}
                        </span>

                        @if($cert['status'] === 'PENDING')
                            <button class="cert-btn-download btn-disabled" disabled>
                                <i class="fa-solid fa-arrow-down"></i> Download
                            </button>
                        @else
                            <button class="cert-btn-download">
                                <i class="fa-solid fa-arrow-down"></i> Download
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    </div>

@endsection