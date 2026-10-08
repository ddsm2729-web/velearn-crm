@extends('layouts.header')

@section('pageTitle')
    {{ __('Leads List') }} | {{ config('app.name') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
    <style>
        .daterangepicker { border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); font-family: inherit; }
        .daterangepicker .ranges li.active { background-color: #4f46e5; border-radius: 6px; }
        .daterangepicker .ranges li:hover { background-color: #f1f5f9; border-radius: 6px; }
        .daterangepicker td.active, .daterangepicker td.active:hover { background-color: #4f46e5; }
        
        /* New Styles for Clean UI */
        .leads-dashboard {
            min-height: calc(100vh - 60px);
        }

        .filter-tabs {
            display: inline-flex;
            gap: 4px;
            background: #f8fafc;
            padding: 4px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
            overflow-x: auto;
            max-width: 100%;
        }
        .filter-tab {
            padding: 8px 16px;
            border-radius: 8px;
            color: #64748b;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s;
        }
        .filter-tab:hover { color: #334155; }
        .filter-tab.active {
            background: #fff;
            color: #0f172a;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            font-weight: 600;
        }

        .clean-table-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-top: 20px;
        }
        .clean-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }
        .clean-table th {
            background: #fff;
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            padding: 16px 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .clean-table td {
            padding: 16px 24px;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
            font-size: 0.875rem;
            color: #334155;
        }
        .clean-table tr:hover td { background-color: #f8fafc; }
        .clean-table tr:last-child td { border-bottom: none; }

        .avatar-square {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 10px;
            background: #eff6ff;
            color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            font-size: 0.9rem;
            margin-right: 16px;
        }

        .pill-source {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
            border: 1px solid #e2e8f0;
        }
        .pill-stage {
            background: #eff6ff;
            color: #3b82f6;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }
        .lead-name-link {
            color: #1e293b;
            text-decoration: none;
            font-weight: 500;
        }
        .lead-name-link:hover { color: #3b82f6; }
        
        .action-btn-group .premium-action-btn {
            padding: 6px 10px;
            border-radius: 6px;
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
            margin-left: 4px;
            transition: all 0.2s;
        }
        .action-btn-group .premium-action-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        
        .staff-filter-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
        .filter-field-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        .custom-search-input, .custom-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            font-size: 0.875rem;
        }
    </style>
@endpush

@section('section')
    <div class="container-fluid p-4 leads-dashboard">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Leads Pipeline
                </h3>
                <div class="text-muted small">
                    Manage your prospect lifecycle and pipeline
                </div>
            </div>

            @can('Lead Create')
            <div>
                <a href="{{ route('leads.create') }}" class="btn btn-primary" style="border-radius: 8px; font-weight: 500;">
                    <i class="fas fa-plus me-1"></i> Add New Lead
                </a>
            </div>
            @endcan
        </div>
        
        <!-- Status Filter Tabs -->
        <div class="filter-tabs">
            <a href="{{ route('leads.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">All</a>
            @foreach($statuses as $sts)
                <a href="{{ route('leads.index', ['status' => $sts->id]) }}" class="filter-tab {{ request('status') == $sts->id ? 'active' : '' }}">
                    {{ $sts->name }}
                </a>
            @endforeach
        </div>

        <!-- Search & Filter Card -->
        <div class="staff-filter-card">
            <form method="GET" action="{{ route('leads.index') }}" class="row g-3 align-items-end" id="filterForm">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="col-12 col-md-3">
                    <label class="filter-field-label">Search Lead</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            value="{{ request('search') }}" placeholder="Name, email, phone..." autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <label class="filter-field-label">Date Added</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="far fa-calendar-alt text-muted"></i></span>
                        <input type="text" name="date_filter" id="date_filter" class="form-control border-start-0 ps-0" value="{{ request('date_filter') }}" placeholder="Select Dates" autocomplete="off" style="cursor: pointer;">
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <label class="filter-field-label">Assigned To</label>
                    <select name="assignee" class="form-select custom-select">
                        <option value="">Anyone</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}" {{ request('assignee') == $staff->id ? 'selected' : '' }}>{{ trim($staff->first_name . ' ' . $staff->last_name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="filter-field-label">Priority</label>
                    <select name="priority" class="form-select custom-select">
                        <option value="">All</option>
                        <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                        <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="filter-field-label">Tag</label>
                    <select name="tag" id="tag-filter" class="form-select custom-select">
                        <option value="">All Tags</option>
                        @foreach(\App\Models\LeadTag::orderBy('name')->get() as $lt)
                            <option value="{{ $lt->id }}" {{ request('tag') == $lt->id ? 'selected' : '' }}>{{ ucfirst($lt->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-1">
                    <a href="{{ route('leads.index') }}" class="btn btn-light w-100 border text-muted" title="Reset Filters" style="border-radius: 8px;">
                        <i class="fas fa-redo-alt"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="clean-table-container" id="table-container">
            <div class="table-responsive">
                <table class="clean-table align-middle" id="stickyTableWrapper">
                    <thead>
                        <tr>
                            <th>CONTACT DETAIL</th>
                            <th>CONTACT INFO</th>
                            <th>DATE ADDED</th>
                            <th>LEAD SOURCE</th>
                            <th>STAGE & ASSIGNMENT</th>
                            <th class="text-end">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($leads) > 0)
                            @foreach ($leads as $key => $lead)
                                <tr onclick="if(!$(event.target).closest('a, button, .action-btn-group').length) { window.location='{{ route('leads.show', encrypt($lead->id)) }}' }" style="cursor: pointer;">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-square">
                                                {{ strtoupper(substr($lead->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <a href="{{ route('leads.show', encrypt($lead->id)) }}" class="lead-name-link d-block mb-1">
                                                    {{ $lead->name }}
                                                </a>
                                                <div class="d-flex gap-1 align-items-center">
                                                    @php
                                                        $pColor = $lead->priority == 'High' ? 'danger' : ($lead->priority == 'Low' ? 'success' : 'warning');
                                                    @endphp
                                                    <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} border border-{{ $pColor }} border-opacity-25 rounded-pill px-2" style="font-size: 0.65rem;">
                                                        {{ $lead->priority ?? 'Medium' }}
                                                    </span>
                                                    
                                                    @if($lead->customer)
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2" style="font-size: 0.65rem;">
                                                        Converted
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @if($lead->phone)
                                                <div class="text-muted" style="font-size: 0.8rem;"><i class="fas fa-phone-alt me-1 opacity-50"></i> {{ $lead->phone }}</div>
                                            @endif
                                            @if($lead->email)
                                                <div class="text-muted" style="font-size: 0.8rem;"><i class="fas fa-envelope me-1 opacity-50"></i> {{ $lead->email }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-dark" style="font-size: 0.8rem;">{{ $lead->created_at->format('d M, Y') }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $lead->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        @if($lead->source)
                                            <span class="pill-source">{{ $lead->source->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1 align-items-start">
                                            @if($lead->status)
                                                <span class="pill-stage" style="background-color: {{ $lead->status->color }}15; color: {{ $lead->status->color }};">
                                                    {{ $lead->status->name }}
                                                </span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                            <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                                <i class="fas fa-user-tie me-1"></i> {{ $lead->assignee ? trim($lead->assignee->first_name . ' ' . $lead->assignee->last_name) : 'Unassigned' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="action-btn-group">
                                            <a href="{{ route('leads.show', encrypt($lead->id)) }}" class="premium-action-btn" title="View Lead">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('Lead Edit')
                                            <a href="{{ route('leads.edit', encrypt($lead->id)) }}" class="premium-action-btn edit-btn" title="Edit Lead">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            @endcan
                                            {{-- @can('Lead Delete')
                                            <form action="{{ route('leads.destroy', encrypt($lead->id)) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this lead? All timeline history will be lost.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="premium-action-btn delete-btn" title="Delete Lead">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                            @endcan --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <div style="width: 70px; height: 70px; background: #fffbeb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                            <i class="fas fa-bullseye text-warning" style="font-size: 28px;"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No Leads Found</h6>
                                        <p class="text-muted small mb-3">Your pipeline is empty or no leads matched the filter.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            @if($leads->hasPages())
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-white">
                    <div class="small text-muted fw-medium">
                        Showing <strong>{{ $leads->firstItem() }}</strong> to <strong>{{ $leads->lastItem() }}</strong> of <strong>{{ $leads->total() }}</strong> leads
                    </div>
                    <nav>
                        {{ $leads->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        const tableContainer = document.getElementById('table-container');
        
        let timeout = null;
        
        // Initialize Date Range Picker
        $('#date_filter').daterangepicker({
            autoUpdateInput: false,
            opens: 'left',
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        $('#date_filter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            fetchLeads();
        });

        $('#date_filter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            fetchLeads();
        });
        
        function fetchLeads() {
            const url = new URL(form.action);
            const formData = new FormData(form);
            const searchParams = new URLSearchParams(formData);
            url.search = searchParams.toString();
            
            // Add a visual loading state
            tableContainer.style.opacity = '0.5';
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.getElementById('table-container');
                
                if (newTable) {
                    tableContainer.innerHTML = newTable.innerHTML;
                }
                tableContainer.style.opacity = '1';
                
                // Re-bind pagination links
                bindPagination();
            })
            .catch(error => {
                console.error('Error fetching leads:', error);
                tableContainer.style.opacity = '1';
            });
            
            // Update URL to allow sharing and refreshing
            window.history.pushState({}, '', url);
        }
        
        // Input event for search box (with debounce)
        const searchInput = form.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(fetchLeads, 500); // 500ms debounce
            });
        }
        
        // Change event for selects
        const selects = form.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', fetchLeads);
        });
        
        // Prevent form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchLeads();
        });
        
        function bindPagination() {
            const links = tableContainer.querySelectorAll('.pagination a');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const url = new URL(this.href);
                    tableContainer.style.opacity = '0.5';
                    
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTable = doc.getElementById('table-container');
                        
                        if (newTable) {
                            tableContainer.innerHTML = newTable.innerHTML;
                        }
                        tableContainer.style.opacity = '1';
                        
                        bindPagination();
                    })
                    .catch(error => {
                        console.error('Error fetching leads:', error);
                        tableContainer.style.opacity = '1';
                    });
                    
                    window.history.pushState({}, '', url);
                });
            });
        }
        
        // Bind pagination initially
        bindPagination();
    });
</script>
@endpush
