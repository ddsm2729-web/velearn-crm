@extends('layouts.master')

@section('pageTitle', 'Meetings')

@push('css')
<style>
    .meeting-section-title {
        font-weight: 700;
        color: #2b3445;
        font-size: 1.25rem;
    }
    
    .meeting-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .meeting-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .meeting-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }

    .meeting-item-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .demo-item .meeting-item-icon {
        background-color: #e0e7ff;
        color: #4f46e5;
    }

    .doubt-item .meeting-item-icon {
        background-color: #fce7f3;
        color: #db2777;
    }

    .meeting-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .meeting-meta {
        display: flex;
        gap: 15px;
        font-size: 0.85rem;
        color: #64748b;
    }

    .meeting-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .badge-status, .badge-type, .meeting-link-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-active, .status-scheduled, .status-Active, .status-Scheduled {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-completed, .status-Completed {
        background-color: #f1f5f9;
        color: #475569;
    }

    .status-cancelled, .status-Cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .status-pending, .status-Pending {
        background-color: #fef9c3;
        color: #854d0e;
    }

    .badge-type {
        background-color: #f3f4f6;
        color: #374151;
    }

    .meeting-link-badge {
        background-color: #e0f2fe;
        color: #0369a1;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .meeting-link-badge:hover {
        background-color: #bae6fd;
        color: #0284c7;
    }

    .btn-join {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .btn-join:hover:not([disabled]) {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
        color: white;
    }

    .empty-state {
        background: #ffffff;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 60px 20px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        font-size: 3.5rem;
        color: #94a3b8;
        margin-bottom: 15px;
    }
    
    .empty-state h5 {
        font-weight: 600;
        color: #334155;
        margin-bottom: 5px;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Scheduled Meetings Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <h5 class="meeting-section-title mb-0">
                <i class="fas fa-calendar-alt text-primary me-2"></i> Scheduled Meetings
            </h5>
            @include('partials.daterange_picker', ['startDate' => $startDate, 'endDate' => $endDate])
        </div>

        <!-- Meeting List -->
        <div class="meeting-list">

            @forelse($allMeetings as $meeting)
                <div class="meeting-item {{ $meeting->type === 'Demo' ? 'demo-item' : 'doubt-item' }}" data-date="{{ $meeting->raw_date }}">
                    <div class="meeting-item-icon {{ $meeting->type === 'Demo' ? 'icon-staff' : 'icon-group' }}">
                        <i class="fas {{ $meeting->type === 'Demo' ? 'fa-video' : 'fa-question-circle' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="meeting-title">
                            {{ $meeting->title }} — {{ $meeting->student_name }}
                        </div>
                        <div class="meeting-meta mb-2">
                            <span><i class="far fa-calendar-alt"></i> {{ $meeting->date_str }}</span>
                            <span><i class="far fa-clock"></i> {{ $meeting->time_str }}</span>
                            @if($meeting->topic)
                                <span><i class="fas fa-info-circle"></i> {{ Str::limit($meeting->topic, 40) }}</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <span class="badge-status status-{{ $meeting->status }}">
                                <i class="fas fa-circle" style="font-size: 6px;"></i> {{ $meeting->status }}
                            </span>
                            <span class="badge-type"><i class="fas {{ $meeting->type === 'Demo' ? 'fa-video' : 'fa-question-circle' }}"></i> {{ $meeting->type }}</span>
                            @if($meeting->link)
                                <a href="{{ $meeting->link }}" target="_blank" class="meeting-link-badge"><i class="fas fa-link"></i> {{ Str::limit(str_replace(['http://', 'https://'], '', $meeting->link), 30) }}</a>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($meeting->link)
                            <a href="{{ $meeting->link }}" target="_blank" class="btn-join"><i class="fas fa-video"></i> Join</a>
                        @else
                            <button class="btn-join" style="opacity: 0.5; background: #94a3b8; box-shadow: none;" disabled><i class="fas fa-video"></i> Join</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h5>No Scheduled Meetings</h5>
                    <p>There are no upcoming demo or doubt sessions for this date range.</p>
                </div>
            @endforelse

        </div>
    </div>
@endsection