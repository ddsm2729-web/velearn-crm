@extends('layouts.header')

@section('pageTitle', 'Followups | ' . config('app.name'))

@push('css')
<style>
    .followup-card {
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        margin-bottom: 10px;
        border: 1px solid #eaeaea;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    
    .followup-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    .followup-card.missed {
        border-left: 5px solid #ef4444;
        background-color: #fef2f2;
    }

    .followup-card.upcoming {
        border-left: 5px solid #10b981;
        background-color: #ffffff;
    }

    .dot {
        height: 10px;
        width: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 15px;
    }

    .dot.red { background-color: #ef4444; }
    .dot.green { background-color: #10b981; }

    .lead-title {
        font-weight: 600;
        color: #1e293b !important;
        font-size: 0.95rem;
        margin-bottom: 2px;
        text-transform: uppercase;
    }
    
    .lead-subtitle {
        color: #64748b;
        font-size: 0.8rem;
    }

    .date-info {
        text-align: right;
    }

    .followup-date {
        color: #3b82f6;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .added-date {
        color: #94a3b8;
        font-size: 0.8rem;
    }

    .phone-text {
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
    }

    .badge-custom {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-status {
        background-color: #d1fae5;
        color: #059669;
    }

    .badge-type {
        background-color: #f1f5f9;
        color: #475569;
    }
</style>
@endpush

@section('section')
<div class="container-fluid py-2 px-4">
    <div class="mb-3">
        <h3 class="fw-bold mb-1 text-slate-800" style="color: #334155;">Follow-ups</h3>
        <p class="text-muted small">Your assigned follow-up leads</p>
    </div>

    <form id="filterForm" action="{{ route('followups.index') }}" method="GET" class="row mb-3 align-items-center">
        <div class="col-md-3">
            <div class="position-relative">
                <i class="fas fa-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.85rem;"></i>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" class="form-control form-control-sm ps-4 rounded border" placeholder="Search follow-up leads...">
            </div>
        </div>
        <div class="col-md-2 mt-2 mt-md-0">
            <select name="tag" class="form-select form-select-sm rounded border">
                <option value="">All Tags</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->name }}" {{ request('tag') == $tag->name ? 'selected' : '' }}>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mt-2 mt-md-0">
            <select name="type" class="form-select form-select-sm rounded border">
                <option value="">All Types</option>
                <option value="inbound" {{ request('type') == 'inbound' ? 'selected' : '' }}>Inbound</option>
                <option value="outbound" {{ request('type') == 'outbound' ? 'selected' : '' }}>Outbound</option>
            </select>
        </div>
        <button type="submit" class="d-none"></button>
    </form>

    <div id="followupResults">
        @include('followups.partials.rows')
    </div>
</div>

@push('script')
<script>
$(document).ready(function() {
    let searchTimeout;
    const $form = $('#filterForm');
    const $results = $('#followupResults');

    function fetchResults() {
        $.ajax({
            url: $form.attr('action'),
            type: 'GET',
            data: $form.serialize(),
            success: function(response) {
                $results.html(response);
            },
            error: function() {
                toastr.error('Failed to fetch follow-ups.');
            }
        });
    }

    // Trigger ajax on select change
    $form.find('select').on('change', function() {
        fetchResults();
    });

    // Trigger ajax on keyup with debounce
    $form.find('#searchInput').on('keyup', function(e) {
        if(e.key === 'Tab' || e.key === 'Shift' || e.key.includes('Arrow')) return;
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchResults();
        }, 600);
    });
    
    // Prevent default form submit
    $form.on('submit', function(e) {
        e.preventDefault();
        clearTimeout(searchTimeout);
        fetchResults();
    });
});
</script>
@endpush
@endsection