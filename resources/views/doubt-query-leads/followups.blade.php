@extends('layouts.master')

@section('pageTitle', 'Follow-ups | ' . config('app.name'))

@push('css')
<style>
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

    /* Lead Avatar */
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

    /* Badges */
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

    /* Empty State */
    .empty-state-box {
        background: #ffffff;
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 56px 24px;
        text-align: center;
    }

    .empty-icon-circle {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #eff6ff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        color: #2563eb;
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

@section('content')
<div class="container-fluid py-3 px-4 followups-wrapper">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1" style="color: #0f172a; font-size: 1.55rem; letter-spacing: -0.02em;">Follow-ups</h3>
            <p class="text-muted small mb-0">Your assigned follow-up leads and support queries</p>
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
                    <h3 class="fw-bold mb-0 text-dark" id="statTotal">{{ $totalCount ?? $calls->total() }}</h3>
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
                    <h3 class="fw-bold mb-0 text-danger" id="statMissed">{{ $overdueCount ?? 0 }}</h3>
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
                    <h3 class="fw-bold mb-0 text-success" id="statUpcoming">{{ $upcomingCount ?? 0 }}</h3>
                </div>
                <div class="stat-icon-box" style="background-color: #f0fdf4; color: #16a34a;">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="filter-card-container">
        <form id="filterForm" action="{{ route('doubt-query-followups.index') }}" method="GET" class="row g-2 align-items-center">
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
                    @if(isset($tags))
                        @foreach($tags as $tag)
                            <option value="{{ $tag->name }}" {{ request('tag') == $tag->name ? 'selected' : '' }}>{{ ucfirst($tag->name) }}</option>
                        @endforeach
                    @else
                        <option value="hot">Hot</option>
                        <option value="warm">Warm</option>
                        <option value="cold">Cold</option>
                        <option value="contacted">Contacted</option>
                        <option value="booked">Booked</option>
                        <option value="attended">Attended</option>
                        <option value="converted">Converted</option>
                        <option value="lost">Lost</option>
                    @endif
                </select>
            </div>

            <!-- Types Filter -->
            <div class="col-6 col-md-3">
                <select name="type" id="typeSelect" class="form-select filter-select-custom">
                    <option value="">All Types</option>
                    <option value="Inbound" {{ request('type') == 'Inbound' ? 'selected' : '' }}>Inbound</option>
                    <option value="Outbound" {{ request('type') == 'Outbound' ? 'selected' : '' }}>Outbound</option>
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
                All Follow-ups <span class="tab-count" id="tabCountAll">{{ $totalCount ?? $calls->total() }}</span>
            </button>
            <button type="button" class="filter-tab-btn" data-tab="overdue">
                <i class="fas fa-circle-exclamation text-danger" style="font-size: 0.8rem;"></i> Overdue <span class="tab-count text-danger" id="tabCountMissed">{{ $overdueCount ?? 0 }}</span>
            </button>
            <button type="button" class="filter-tab-btn" data-tab="upcoming">
                <i class="fas fa-calendar-check text-success" style="font-size: 0.8rem;"></i> Upcoming <span class="tab-count text-success" id="tabCountUpcoming">{{ $upcomingCount ?? 0 }}</span>
            </button>
        </div>
    </div>

    <!-- Leads List Results -->
    <div id="followupResults">
        @include('doubt-query-leads.partials.followup_rows', ['calls' => $calls])
    </div>

    <div class="mt-4 d-flex justify-content-center" id="paginationContainer">
        {{ $calls->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    const $form = $('#filterForm');
    const $results = $('#followupResults');
    const $searchInput = $('#searchInput');
    const $clearBtn = $('#clearSearch');
    let currentTab = 'all';

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

    function applyTabFilter(tab) {
        currentTab = tab;
        $('.filter-tab-btn').removeClass('active');
        $(`.filter-tab-btn[data-tab="${tab}"]`).addClass('active');

        const $overdueCards = $('.followup-card-modern.is-missed');
        const $upcomingCards = $('.followup-card-modern.is-upcoming');

        if (tab === 'overdue') {
            $overdueCards.show();
            $upcomingCards.hide();
            if ($overdueCards.length === 0) {
                $('#tabEmptyMessage').show();
            } else {
                $('#tabEmptyMessage').hide();
            }
        } else if (tab === 'upcoming') {
            $overdueCards.hide();
            $upcomingCards.show();
            if ($upcomingCards.length === 0) {
                $('#tabEmptyMessage').show();
            } else {
                $('#tabEmptyMessage').hide();
            }
        } else {
            $overdueCards.show();
            $upcomingCards.show();
            $('#tabEmptyMessage').hide();
        }
    }

    $(document).on('click', '.filter-tab-btn', function() {
        const tab = $(this).data('tab');
        applyTabFilter(tab);
    });

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
                if(typeof toastr !== 'undefined') toastr.error('Failed to fetch follow-ups.');
            }
        });
    }

    $form.find('select').on('change', function() {
        fetchResults();
    });

    $searchInput.on('keyup', function(e) {
        toggleClearBtn();
        if(e.key === 'Tab' || e.key === 'Shift' || e.key.includes('Arrow')) return;
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchResults();
        }, 400);
    });

    $('#resetFilters').on('click', function() {
        $searchInput.val('');
        $('#tagSelect').val('');
        $('#typeSelect').val('');
        toggleClearBtn();
        fetchResults();
    });

    $(document).on('click', '#btnEmptyReset', function() {
        $('#resetFilters').trigger('click');
    });

    $('#btnRefresh').on('click', function() {
        const $icon = $(this).find('i');
        $icon.addClass('fa-spin');
        fetchResults();
        setTimeout(() => $icon.removeClass('fa-spin'), 600);
    });
    
    $form.on('submit', function(e) {
        e.preventDefault();
        clearTimeout(searchTimeout);
        fetchResults();
    });

    syncStats();
});
</script>
@endpush