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
        
        <select class="form-select" style="max-width: 220px;" name="ao_id" id="aoSelect">
            <option value="">All Admission Officers</option>
            @foreach($admissionOfficers as $ao)
                <option value="{{ $ao->id }}" {{ request('ao_id') == $ao->id ? 'selected' : '' }}>{{ $ao->first_name }} {{ $ao->last_name }}</option>
            @endforeach
        </select>
        
        <select class="form-select" style="max-width: 180px;" name="status" id="statusSelect">
            <option value="">All Status / Tags</option>
            <option value="Confirmed" {{ strtolower(request('status')) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="Converted" {{ strtolower(request('status')) == 'converted' ? 'selected' : '' }}>Converted</option>
            <option value="Registered" {{ strtolower(request('status')) == 'registered' ? 'selected' : '' }}>Registered</option>
        </select>
    </form>

    <!-- Student List -->
    <div class="student-list" id="studentListContainer">
        @forelse($convertedLeads as $lead)
            @php 
                $words = array_filter(explode(' ', trim($lead->name ?? '')));
                $initial = '';
                if (count($words) >= 2) {
                    $initial = strtoupper(mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1));
                } else {
                    $initial = strtoupper(mb_substr($lead->name ?? 'S', 0, 1));
                }

                $tagNames = $lead->tags->pluck('name')->map(fn($n) => strtolower($n))->all();
                $statusNameLower = strtolower($lead->status->name ?? '');

                $isRegistered = in_array('registered', $tagNames) || in_array('register', $tagNames) || $statusNameLower === 'registered' || $statusNameLower === 'payment done' || !empty($lead->admission);
                $isConverted = in_array('converted', $tagNames) || in_array('demo converted', $tagNames) || $statusNameLower === 'converted';
                $isConfirmed = in_array('confirmed', $tagNames) || $statusNameLower === 'confirmed';

                // Determine display badge and pipeline values
                if ($isRegistered) {
                    $badgeText = 'REGISTERED';
                    $badgeBg = '#8b5cf6';
                    $badgeColor = '#ffffff';
                    $stageText = 'Converted';
                    $stageIcon = 'fa-check-square';
                    $stageClass = 'text-success';
                    $statusText = 'Fully Registered';
                    $statusColor = '#10b981';
                    $statusIcon = 'fa-check-circle';
                } elseif ($isConfirmed && !$isConverted) {
                    $badgeText = 'CONFIRMED';
                    $badgeBg = '#3b82f6';
                    $badgeColor = '#ffffff';
                    $stageText = 'Confirmed';
                    $stageIcon = 'fa-check-circle';
                    $stageClass = 'text-primary';
                    $statusText = 'Pending Admission';
                    $statusColor = '#f59e0b';
                    $statusIcon = 'fa-hourglass-half';
                } else {
                    $badgeText = 'CONVERTED';
                    $badgeBg = '#10b981';
                    $badgeColor = '#ffffff';
                    $stageText = 'Converted';
                    $stageIcon = 'fa-check-square';
                    $stageClass = 'text-success';
                    $statusText = 'Pending Admission';
                    $statusColor = '#f59e0b';
                    $statusIcon = 'fa-hourglass-half';
                }
            @endphp
            <a href="{{ route('leads.show', encrypt($lead->id)) }}" class="text-decoration-none">
                <div class="student-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="student-avatar" style="@if($isRegistered) background: #f5f3ff; color: #7c3aed; @elseif($isConfirmed && !$isConverted) background: #eff6ff; color: #2563eb; @else background: #ecfdf5; color: #059669; @endif">
                            {{ $initial }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="fw-bold text-dark fs-6">{{ $lead->name }}</span>
                                
                                {{-- Display Lead Tags --}}
                                @if($lead->tags && $lead->tags->isNotEmpty())
                                    @foreach($lead->tags as $tag)
                                        @php $tLower = strtolower($tag->name); @endphp
                                        @if($tLower === 'confirmed')
                                            <span class="badge rounded-pill px-2 py-0.5" style="font-size: 0.68rem; font-weight: 700; background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">
                                                <i class="fas fa-tag me-1" style="font-size: 0.6rem;"></i>Confirmed
                                            </span>
                                        @elseif($tLower === 'converted' || $tLower === 'demo converted')
                                            <span class="badge rounded-pill px-2 py-0.5" style="font-size: 0.68rem; font-weight: 700; background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                                                <i class="fas fa-tag me-1" style="font-size: 0.6rem;"></i>Converted
                                            </span>
                                        @elseif(str_contains($tLower, 'register'))
                                            <span class="badge rounded-pill px-2 py-0.5" style="font-size: 0.68rem; font-weight: 700; background-color: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe;">
                                                <i class="fas fa-tag me-1" style="font-size: 0.6rem;"></i>Registered
                                            </span>
                                        @else
                                            <span class="badge bg-light text-secondary border rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">
                                                {{ ucfirst($tag->name) }}
                                            </span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="text-muted small">
                                {{ $lead->phone }} 
                                @if($lead->courses && $lead->courses->count() > 0)
                                    &middot; {{ $lead->courses->first()->title }}
                                @elseif($lead->admission && $lead->admission->course)
                                    &middot; {{ $lead->admission->course->title }}
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-4 gap-md-5">
                        <div class="pipeline-status">
                            <span class="{{ $stageClass }}"><i class="fas {{ $stageIcon }} me-1"></i>{{ $stageText }}</span>
                            <span class="text-muted"><i class="fas fa-arrow-right mx-1"></i></span>
                            <span style="color: {{ $statusColor }};"><i class="fas {{ $statusIcon }} me-1"></i>{{ $statusText }}</span>
                        </div>
                        
                        <div class="text-end">
                            <span class="badge px-2.5 py-1 mb-1 rounded-pill" style="background-color: {{ $badgeBg }}; color: {{ $badgeColor }}; font-weight: 700; font-size: 0.72rem; letter-spacing: 0.5px;">
                                {{ $badgeText }}
                            </span>
                            <div class="text-muted" style="font-size: 0.7rem;">
                                AO: {{ $lead->assignee ? $lead->assignee->first_name : 'Unassigned' }}<br>
                                {{ $lead->converted_date ? $lead->converted_date->format('d M Y') : $lead->updated_at->format('d M Y') }}
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