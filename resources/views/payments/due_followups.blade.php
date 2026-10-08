@extends('layouts.header')

@section('pageTitle', 'Follow-ups')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/payments-due_followups.css') }}">
@endpush

@section('section')
<div class="followups-container">
    <div class="page-header-row">
        <div>
            <h6>ASSIGNED TO ME</h6>
            <h2>Follow-ups</h2>
        </div>
        <button class="btn-send-reminders">
            <i class="fas fa-paper-plane"></i> Send Reminders
        </button>
    </div>

    <div class="queue-card">
        <div class="queue-header">
            <i class="fas fa-circle"></i>
            <h5>Follow-up Queue</h5>
            <span>11 students scheduled</span>
        </div>
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Phone No.</th>
                        <th>Course</th>
                        <th>Payment Date</th>
                        <th>Payment Status</th>
                        <th>Follow-up Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($followups as $followup)
                    <tr>
                        <td>
                            <div class="student-info">
                                @php
                                    $name = $followup->admission->contact->name ?? 'Unknown';
                                    $initials = strtoupper(substr($name, 0, 1));
                                    $colors = ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#06b6d4', '#ef4444', '#ec4899', '#6366f1'];
                                    $color = $colors[$loop->index % count($colors)];
                                @endphp
                                <div class="student-avatar" style="background: {{ $color }}20; color: {{ $color }}">
                                    {{ $initials }}
                                </div>
                                <div class="student-name-group">
                                    <span class="name">{{ $name }}</span>
                                    <span class="sub">{{ $followup->admission->course->title ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="phone-col">{{ $followup->admission->contact->caller_number ?? 'N/A' }}</td>
                        <td class="course-col">{{ $followup->admission->course->title ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($followup->payment_date)->format('d M, Y') }}</td>
                        <td><span class="pay-status-badge">{{ $followup->status }}</span></td>
                        <td class="follow-date {{ $followup->payment_date < now()->toDateString() ? 'overdue' : '' }}">
                            {{ \Carbon\Carbon::parse($followup->payment_date)->format('d M, Y') }}
                        </td>
                        <td>
                            <span class="status-pill status-{{ strtolower($followup->status) }}">
                                {{ $followup->status }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="#" class="action-btn"><i class="fas fa-phone-alt"></i></a>
                                <a href="#" class="action-btn wa"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

