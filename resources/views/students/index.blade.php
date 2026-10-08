@extends('layouts.header')

@section('pageTitle', 'Registered Students')

@push('css')
<style>
    .stats-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: transform 0.2s;
    }
    .stats-card:hover { transform: translateY(-2px); }
    
    .student-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .student-card:hover { border-color: #cbd5e1; }
    
    .student-avatar {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #eff6ff;
        color: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .pipeline-status {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        font-weight: 500;
        gap: 8px;
    }
    
    .filter-container {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .filter-container .form-control, .filter-container .form-select {
        border-radius: 8px;
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }
</style>
@endpush

@section('section')
<div class='container-fluid px-0'>
    
    <div class="mb-4">
        <h1 class='h3 fw-bold mb-1 text-dark'>Registered Students</h1>
        <p class="text-muted small">Students who completed registration via Admission Officer</p>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stats-card h-100 text-center py-3">
                <h2 class="fw-bold mb-1" style="color: #0ea5e9;">{{ $totalRegistered }}</h2>
                <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Registered</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card h-100 text-center py-3">
                <h2 class="fw-bold mb-1" style="color: #f59e0b;">{{ $pendingAdmission }}</h2>
                <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Pending With Admission</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card h-100 text-center py-3">
                <h2 class="fw-bold mb-1" style="color: #10b981;">{{ $fullyRegistered }}</h2>
                <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Fully Registered</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card h-100 text-center py-3">
                <h2 class="fw-bold mb-1" style="color: #8b5cf6;">{{ $thisWeek }}</h2>
                <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">This Week</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('students.index') }}" class="filter-container" id="filterForm">
        <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control border-start-0 ps-0" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Search leads, names...">
        </div>
        
        <select class="form-select" style="max-width: 200px;" name="ao_id" id="aoSelect">
            <option value="">All Admission Officers</option>
            @foreach($admissionOfficers as $ao)
                <option value="{{ $ao->id }}" {{ request('ao_id') == $ao->id ? 'selected' : '' }}>{{ $ao->first_name }} {{ $ao->last_name }}</option>
            @endforeach
        </select>
        
        <select class="form-select" style="max-width: 150px;" name="status" id="statusSelect">
            <option value="">All Status</option>
            <option value="Converted" {{ request('status') == 'Converted' ? 'selected' : '' }}>Converted</option>
            <option value="Registered" {{ request('status') == 'Registered' ? 'selected' : '' }}>Registered</option>
        </select>
    </form>

    <!-- Student List -->
    <div class="student-list" id="studentListContainer">
        @forelse($convertedLeads as $lead)
            @php 
                $initial = strtoupper(substr($lead->name, 0, 1));
                $isRegistered = strtolower(optional($lead->status)->name) === 'registered';
                $statusColor = $isRegistered ? '#10b981' : '#f59e0b';
                $statusText = $isRegistered ? 'Registered' : 'Pending';
                $statusIcon = $isRegistered ? 'fa-check-circle' : 'fa-hourglass-half';
            @endphp
            <a href="{{ route('leads.show', encrypt($lead->id)) }}" class="text-decoration-none">
                <div class="student-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="student-avatar">{{ $initial }}</div>
                        <div>
                            <div class="fw-bold text-dark fs-6">{{ $lead->name }}</div>
                            <div class="text-muted small">
                                {{ $lead->phone }} 
                                @if($lead->courses->count() > 0)
                                    &middot; {{ $lead->courses->first()->title }}
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-5">
                        <div class="pipeline-status">
                            <span class="text-success"><i class="fas fa-check-square me-1"></i>Converted</span>
                            <span class="text-muted"><i class="fas fa-arrow-right mx-1"></i></span>
                            <span style="color: {{ $statusColor }};"><i class="fas {{ $statusIcon }} me-1"></i>{{ $statusText }}</span>
                        </div>
                        
                        <div class="text-end">
                            <span class="badge px-2 py-1 mb-1" style="background-color: {{ $isRegistered ? '#10b981' : '#10b981' }}; font-weight: 600;">
                                {{ strtoupper($lead->status->name ?? 'Converted') }}
                            </span>
                            <div class="text-muted" style="font-size: 0.7rem;">
                                AO: {{ $lead->assignee ? $lead->assignee->first_name : 'Unassigned' }}<br>
                                {{ $lead->converted_date ? $lead->converted_date->format('d M Y') : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                <h5>No registered students found</h5>
                <p>Try adjusting your filters or search terms.</p>
            </div>
        @endforelse
        
        <div class="mt-4">
            {{ $convertedLeads->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        let searchTimeout;

        function fetchStudents() {
            let formData = $('#filterForm').serialize();
            let url = $('#filterForm').attr('action') + '?' + formData;

            // Update URL without reloading
            window.history.pushState({}, '', url);

            // Fetch new data
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    // Replace the student list container with the one from the response
                    let newList = $(response).find('#studentListContainer').html();
                    $('#studentListContainer').html(newList);
                },
                error: function() {
                    console.error("Failed to fetch students data.");
                }
            });
        }

        $('#searchInput').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(fetchStudents, 500); // 500ms debounce
        });

        $('#aoSelect, #statusSelect').on('change', function() {
            fetchStudents();
        });

        // Handle pagination clicks with AJAX
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            
            window.history.pushState({}, '', url);
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    let newList = $(response).find('#studentListContainer').html();
                    $('#studentListContainer').html(newList);
                    
                    // Update the form values based on URL parameters to keep them in sync
                    let urlParams = new URLSearchParams(url.split('?')[1]);
                    if(urlParams.has('search')) $('#searchInput').val(urlParams.get('search'));
                    if(urlParams.has('ao_id')) $('#aoSelect').val(urlParams.get('ao_id'));
                    if(urlParams.has('status')) $('#statusSelect').val(urlParams.get('status'));
                }
            });
        });
    });
</script>
@endpush