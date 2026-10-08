@extends('layouts.header')

@section('pageTitle', 'Company Database')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/company_database-index.css') }}">
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
    <style>
        /* Premium UI Styles */
        .filter-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
            padding: 12px 16px;
            margin-bottom: 20px;
        }
        
        .filter-select, .filter-input {
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            font-size: 0.85rem;
            color: #475569;
            background-color: #f8fafc;
            transition: all 0.2s ease;
        }
        
        .filter-select:focus, .filter-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            background-color: #ffffff;
        }

        .table-card {
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: none;
            overflow: hidden;
            background: #fff;
        }
        
        .table thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
        }
        
        .table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            font-size: 0.85rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        .btn-add-entry {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
            transition: transform 0.1s, box-shadow 0.1s;
        }
        
        .btn-add-entry:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
        }
    </style>
@endpush

@section('section')
<div class="d-flex justify-content-between align-items-center mb-3 mt-2">
    <div>
        <h4 class="fw-bold text-dark mb-0 d-flex align-items-center">
            Company Database 
            <span class="badge bg-primary bg-opacity-10 text-primary ms-2 rounded-pill" style="font-size: 0.6em; padding: 4px 10px;">{{ $companies->count() }} Companies</span>
        </h4>
        <p class="text-muted small mt-1 mb-0" style="font-size: 0.8rem;">Manage and track your placement partner companies</p>
    </div>
    @can('Company Database Create')
    <div>
        <a href="{{ route('company-database.create') }}" class="btn btn-primary btn-add-entry text-white">
            <i class="fas fa-plus me-1"></i> Add Entry
        </a>
    </div>
    @endcan
</div>

<!-- Filters -->
<div class="filter-card">
    <form id="filter-form" class="d-flex flex-wrap gap-2 align-items-center">
        <div class="position-relative" style="width: 220px;">
            <i class="fas fa-search position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 0.8rem;"></i>
            <input type="text" name="search" class="form-control form-control-sm filter-input w-100" style="padding-left: 32px;" placeholder="Search companies..." value="{{ request('search') }}">
        </div>

        <select name="lead_tag_id" class="form-select form-select-sm filter-select" style="width: 140px;">
            <option value="All">Lead Tag: All</option>
            @foreach($leadTags as $tag)
                <option value="{{ $tag->id }}" {{ request('lead_tag_id') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>
        
        <select name="date_filter" class="form-select form-select-sm filter-select" style="width: 130px;">
            <option value="All">Date: All</option>
            <option value="Today" {{ request('date_filter') == 'Today' ? 'selected' : '' }}>Today</option>
            <option value="This Week" {{ request('date_filter') == 'This Week' ? 'selected' : '' }}>This Week</option>
            <option value="This Month" {{ request('date_filter') == 'This Month' ? 'selected' : '' }}>This Month</option>
        </select>
        
        <select name="openings_flag" class="form-select form-select-sm filter-select" style="width: 130px;">
            <option value="All">Openings Flag: All</option>
            <option value="Yes" {{ request('openings_flag') == 'Yes' ? 'selected' : '' }}>Yes</option>
            <option value="No" {{ request('openings_flag') == 'No' ? 'selected' : '' }}>No</option>
        </select>

        <select name="city" class="form-select form-select-sm filter-select" style="width: 130px;">
            <option value="All">City: All</option>
            @foreach($cities as $c)
                <option value="{{ $c->city_location }}" {{ request('city') == $c->city_location ? 'selected' : '' }}>
                    {{ $c->city_location }} ({{ $c->count }})
                </option>
            @endforeach
        </select>

        <select name="industry" class="form-select form-select-sm filter-select" style="width: 130px;">
            <option value="All">Industry: All</option>
            @foreach($industries as $ind)
                <option value="{{ $ind->industry_sector }}" {{ request('industry') == $ind->industry_sector ? 'selected' : '' }}>
                    {{ $ind->industry_sector }} ({{ $ind->count }})
                </option>
            @endforeach
        </select>
    </form>
</div>

<!-- Table -->
<div class="row">
    <div class="col-12">
        <div class="card table-card position-relative">
            <div id="table-loader" class="position-absolute w-100 h-100 d-none" style="background: rgba(255,255,255,0.7); z-index: 10; display: flex; align-items: center; justify-content: center;">
                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>COMPANY</th>
                                <th>CITY</th>
                                <th>INDUSTRY</th>
                                <th>HR NAME</th>
                                <th>HR PHONE</th>
                                <th>SOURCE</th>
                                <th>OPENINGS?</th>
                                <th>ADDED</th>
                                <th class="text-center">STATUS</th>
                                @canany(['Company Database Edit', 'Company Database Delete'])
                                <th class="pe-4 text-end sticky-action">ACTIONS</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @include('company_database._table_rows')
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end p-3 border-top border-light" id="pagination-container">
                    {{ $companies->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/company.js') }}"></script>
    <script>
        $(document).ready(function() {
            let searchTimeout;

            function fetchCompanies(pageUrl) {
                $('#table-loader').removeClass('d-none');
                
                let page = 1;
                if (pageUrl) {
                    // Extract the page number from the pagination link
                    try {
                        let urlObj = new URL(pageUrl, window.location.origin);
                        page = urlObj.searchParams.get('page') || 1;
                    } catch (e) {
                        page = 1;
                    }
                }

                let formData = $('#filter-form').serialize() + '&page=' + page;
                
                $.ajax({
                    url: "{{ route('company-database.index') }}",
                    type: "GET",
                    data: formData,
                    success: function(res) {
                        $('#table-body').html(res.html);
                        $('#pagination-container').html(res.pagination);
                    },
                    error: function(err) {
                        toastr.error('Error fetching data.');
                    },
                    complete: function() {
                        $('#table-loader').addClass('d-none');
                    }
                });
            }

            $('.filter-select').on('change', function() {
                fetchCompanies();
            });

            $('input[name="search"]').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    fetchCompanies();
                }, 500);
            });

            // Prevent form submit on enter key
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                fetchCompanies();
            });

            // Handle Pagination Clicks via AJAX
            $(document).on('click', '#pagination-container a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                fetchCompanies(url);
            });
        });
    </script>
@endpush
