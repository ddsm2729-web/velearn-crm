@extends('layouts.header')

@section('pageTitle', 'Follow-ups | ' . config('app.name'))

@push('css')
<style>
    /* Followups Modern Page Styles */
    .followups-wrapper {
        color: #1e293b;
    }

    /* Metric Stat Cards */
    .followup-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .followup-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
        border-color: #cbd5e1;
    }

    .stat-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Filter Toolbar Card */
    .filter-card-container {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
    }

    .search-input-wrap {
        position: relative;
    }

    .search-input-wrap .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.9rem;
        pointer-events: none;
    }

    .search-input-wrap input {
        padding-left: 42px !important;
        padding-right: 36px !important;
        border-radius: 10px !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #f8fafc !important;
        font-size: 0.88rem !important;
        transition: all 0.2s ease !important;
    }

    .search-input-wrap input:focus {
        background-color: #ffffff !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12) !important;
    }

    .search-clear-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
        display: none;
    }

    .search-clear-btn:hover {
        color: #475569;
    }

    .filter-select-custom {
        border-radius: 10px !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #f8fafc !important;
        font-size: 0.88rem !important;
        color: #334155 !important;
        padding: 9px 14px !important;
        transition: all 0.2s ease !important;
    }

    .filter-select-custom:focus {
        background-color: #ffffff !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12) !important;
    }

    /* Filter Tabs */
    .filter-tabs-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        padding-top: 14px;
        margin-top: 14px;
        border-top: 1px solid #f1f5f9;
    }

    .filter-tab-btn {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 0.8125rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .filter-tab-btn:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .filter-tab-btn.active {
        background: #0f172a;
        border-color: #0f172a;
        color: #ffffff;
    }

    .filter-tab-btn .tab-count {
        font-size: 0.72rem;
        padding: 2px 7px;
        border-radius: 9999px;
        background: #e2e8f0;
        color: #475569;
    }

    .filter-tab-btn.active .tab-count {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    /* Modern Follow-up Card */
    .followup-card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 12px;
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .followup-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
        transition: width 0.2s ease;
    }

    .followup-card-modern.is-missed {
        border-color: #fecaca;
    }

    .followup-card-modern.is-missed::before {
        background: #ef4444;
    }

    .followup-card-modern.is-upcoming::before {
        background: #10b981;
    }

    .followup-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.07);
        border-color: #cbd5e1;
    }

    .followup-card-modern:hover::before {
        width: 6px;
    }

    /* Lead Avatar Initials */
    .lead-avatar-circle {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        flex-shrink: 0;
        letter-spacing: -0.5px;
    }

    .avatar-missed {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }

    .avatar-upcoming {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #0369a1;
        border: 1px solid #7dd3fc;
    }

    .lead-name-text {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
        margin-bottom: 3px;
        transition: color 0.2s;
    }

    .followup-card-modern:hover .lead-name-text {
        color: #2563eb;
    }

    .lead-note-box {
        background: #f8fafc;
        border-left: 3px solid #cbd5e1;
        padding: 5px 12px;
        border-radius: 4px 8px 8px 4px;
        font-size: 0.815rem;
        color: #475569;
        margin-top: 6px;
        max-width: 620px;
        display: inline-flex;
        align-items: baseline;
        gap: 6px;
    }

    .lead-note-box.note-missed {
        background: #fff5f5;
        border-left-color: #f87171;
        color: #991b1b;
    }

    /* Badge Utility Classes */
    .pill-subtle-success {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border: 1px solid #a7f3d0 !important;
    }
    .pill-subtle-danger {
        background-color: #fff1f2 !important;
        color: #be123c !important;
        border: 1px solid #fecdd3 !important;
    }
    .pill-subtle-warning {
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border: 1px solid #fde68a !important;
    }
    .pill-subtle-info {
        background-color: #f0f9ff !important;
        color: #0284c7 !important;
        border: 1px solid #bae6fd !important;
    }
    .pill-subtle-primary {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
    }

    /* Badge Pills */
    .badge-status-pill {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 3px 9px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-type-pill {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 9999px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
    }

    .followup-time-badge {
        font-size: 0.88rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 3px;
    }

    .contact-action-btn {
        transition: all 0.2s ease;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .contact-action-btn:hover {
        transform: translateY(-1px);
    }

    /* Section Banner */
    .section-banner-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 14px;
        border-radius: 10px;
        margin-bottom: 14px;
    }

    .banner-overdue {
        background: #fff1f2;
        border: 1px solid #ffe4e6;
    }

    .banner-upcoming {
        background: #f0fdf4;
        border: 1px solid #dcfce7;
    }

    /* Empty State */
    .empty-state-box {
        background: #ffffff;
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 50px 24px;
        text-align: center;
    }

    .empty-icon-circle {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: #f8fafc;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        color: #3b82f6;
    }

    @media (max-width: 991px) {
        .followup-card-modern {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
        }

        .followup-card-modern .right-side-info {
            width: 100%;
            border-top: 1px solid #f1f5f9;
            padding-top: 12px;
            display: flex;
            flex-direction: column;
            align-items: flex-start !important;
        }

        .followup-card-modern .right-side-actions {
            width: 100%;
            justify-content: flex-start !important;
            margin-top: 8px;
        }
    }
</style>
@endpush

@section('section')
<div class="container-fluid py-3 px-4 followups-wrapper">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1" style="color: #0f172a; font-size: 1.55rem; letter-spacing: -0.02em;">Follow-ups</h3>
            <p class="text-muted small mb-0">Track and manage all your scheduled prospect follow-up calls and tasks</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-white border shadow-sm rounded-pill px-3 py-2 text-muted fw-medium" id="btnRefresh" title="Refresh leads list">
                <i class="fas fa-rotate-right me-1 text-primary"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Summary Metric Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="followup-stat-card">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Follow-ups</span>
                    <h3 class="fw-bold mb-0 text-dark" id="statTotal">{{ $missed->count() + $upcoming->count() }}</h3>
                </div>
                <div class="stat-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="followup-stat-card">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Overdue Follow-ups</span>
                    <h3 class="fw-bold mb-0 text-danger" id="statMissed">{{ $missed->count() }}</h3>
                </div>
                <div class="stat-icon-box" style="background-color: #fef2f2; color: #ef4444;">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="followup-stat-card">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Upcoming Follow-ups</span>
                    <h3 class="fw-bold mb-0 text-success" id="statUpcoming">{{ $upcoming->count() }}</h3>
                </div>
                <div class="stat-icon-box" style="background-color: #f0fdf4; color: #16a34a;">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="filter-card-container">
        <form id="filterForm" action="{{ route('followups.index') }}" method="GET" class="row g-2 align-items-center">
            <!-- Search Input -->
            <div class="col-12 col-md-5">
                <div class="search-input-wrap">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by lead name, phone, or email...">
                    <button type="button" id="clearSearch" class="search-clear-btn" title="Clear search">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
            </div>

            <!-- Tags Filter -->
            <div class="col-6 col-md-3">
                <select name="tag" id="tagSelect" class="form-select filter-select-custom">
                    <option value="">All Tags</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->name }}" {{ request('tag') == $tag->name ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Types Filter -->
            <div class="col-6 col-md-3">
                <select name="type" id="typeSelect" class="form-select filter-select-custom">
                    <option value="">All Types</option>
                    <option value="inbound" {{ request('type') == 'inbound' ? 'selected' : '' }}>Inbound</option>
                    <option value="outbound" {{ request('type') == 'outbound' ? 'selected' : '' }}>Outbound</option>
                </select>
            </div>

            <!-- Reset Filters Button -->
            <div class="col-12 col-md-1 text-md-end">
                <button type="button" id="resetFilters" class="btn btn-light rounded-3 w-100 py-2 text-muted border" title="Reset filters">
                    <i class="fas fa-rotate-left"></i>
                </button>
            </div>
            <button type="submit" class="d-none"></button>
        </form>

        <!-- View Tabs -->
        <div class="filter-tabs-row">
            <span class="text-muted small fw-medium me-1">View:</span>
            <button type="button" class="filter-tab-btn active" data-tab="all">
                All Follow-ups <span class="tab-count" id="tabCountAll">{{ $missed->count() + $upcoming->count() }}</span>
            </button>
            <button type="button" class="filter-tab-btn" data-tab="overdue">
                <i class="fas fa-circle-exclamation text-danger" style="font-size: 0.8rem;"></i> Overdue <span class="tab-count text-danger" id="tabCountMissed">{{ $missed->count() }}</span>
            </button>
            <button type="button" class="filter-tab-btn" data-tab="upcoming">
                <i class="fas fa-calendar-check text-success" style="font-size: 0.8rem;"></i> Upcoming <span class="tab-count text-success" id="tabCountUpcoming">{{ $upcoming->count() }}</span>
            </button>
        </div>
    </div>

    <!-- Leads List Results -->
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
    const $searchInput = $('#searchInput');
    const $clearBtn = $('#clearSearch');
    let currentTab = 'all';

    // Show/hide clear button based on search input
    function toggleClearBtn() {
        if ($searchInput.val().trim().length > 0) {
            $clearBtn.show();
        } else {
            $clearBtn.hide();
        }
    }
    toggleClearBtn();

    $clearBtn.on('click', function() {
        $searchInput.val('');
        toggleClearBtn();
        fetchResults();
    });

    // Update dynamic stat cards and tab badges
    function syncStats() {
        const $meta = $('#resultsMeta');
        if ($meta.length) {
            const total = $meta.data('total');
            const missed = $meta.data('missed');
            const upcoming = $meta.data('upcoming');

            $('#statTotal').text(total);
            $('#statMissed').text(missed);
            $('#statUpcoming').text(upcoming);

            $('#tabCountAll').text(total);
            $('#tabCountMissed').text(missed);
            $('#tabCountUpcoming').text(upcoming);
        }
        applyTabFilter(currentTab);
    }

    // Apply Client Tab Filtering (All / Overdue / Upcoming)
    function applyTabFilter(tab) {
        currentTab = tab;
        $('.filter-tab-btn').removeClass('active');
        $(`.filter-tab-btn[data-tab="${tab}"]`).addClass('active');

        const $overdueSection = $('.followup-section-overdue');
        const $upcomingSection = $('.followup-section-upcoming');
        const $emptyState = $('.empty-state-box');

        if (tab === 'overdue') {
            $overdueSection.show();
            $upcomingSection.hide();
            if ($overdueSection.length === 0 || $overdueSection.find('.followup-card-modern').length === 0) {
                $('#tabEmptyMessage').show();
            } else {
                $('#tabEmptyMessage').hide();
            }
        } else if (tab === 'upcoming') {
            $overdueSection.hide();
            $upcomingSection.show();
            if ($upcomingSection.length === 0 || $upcomingSection.find('.followup-card-modern').length === 0) {
                $('#tabEmptyMessage').show();
            } else {
                $('#tabEmptyMessage').hide();
            }
        } else {
            $overdueSection.show();
            $upcomingSection.show();
            $('#tabEmptyMessage').hide();
        }
    }

    // Tab buttons click handler
    $(document).on('click', '.filter-tab-btn', function() {
        const tab = $(this).data('tab');
        applyTabFilter(tab);
    });

    // Fetch Results via AJAX
    function fetchResults() {
        $results.css('opacity', '0.5');
        $.ajax({
            url: $form.attr('action'),
            type: 'GET',
            data: $form.serialize(),
            success: function(response) {
                $results.html(response).css('opacity', '1');
                syncStats();
            },
            error: function() {
                $results.css('opacity', '1');
                toastr.error('Failed to fetch follow-ups.');
            }
        });
    }

    // Trigger on select change
    $form.find('select').on('change', function() {
        fetchResults();
    });

    // Trigger on keyup with debounce
    $searchInput.on('keyup', function(e) {
        toggleClearBtn();
        if(e.key === 'Tab' || e.key === 'Shift' || e.key.includes('Arrow')) return;
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchResults();
        }, 400);
    });

    // Reset Filters
    $('#resetFilters').on('click', function() {
        $searchInput.val('');
        $('#tagSelect').val('');
        $('#typeSelect').val('');
        toggleClearBtn();
        fetchResults();
    });

    // Refresh Button
    $('#btnRefresh').on('click', function() {
        const $icon = $(this).find('i');
        $icon.addClass('fa-spin');
        fetchResults();
        setTimeout(() => $icon.removeClass('fa-spin'), 600);
    });
    
    // Prevent default form submit
    $form.on('submit', function(e) {
        e.preventDefault();
        clearTimeout(searchTimeout);
        fetchResults();
    });

    syncStats();
});
</script>
@endpush
@endsection