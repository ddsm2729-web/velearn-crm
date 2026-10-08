@extends('layouts.master')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-3">
        <div class="col-12">
            <h5 class="mb-0 text-muted"><i class="fas fa-book-open me-2"></i> Course Details</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Course Header Card -->
            <div class="card mb-4 shadow-sm" style="border: 2px solid #4285f4; border-radius: 8px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h4 class="fw-bold mb-1" style="color: #4a5568;">{{ $course->title }}</h4>
                            <p class="text-muted mb-0 small">
                                {{ strtoupper(str_replace('-', ' ', $course->slug)) }} • {{ $course->duration ?? 'N/A' }} 
                                @if($course->live_course_type)
                                    • {{ ucfirst($course->live_course_type) }}
                                @endif
                            </p>
                        </div>
                        <div>
                            @if($course->status)
                                <span class="badge" style="background-color: #10b981; color: white;">
                                    <i class="fas fa-check me-1"></i> APPROVED
                                </span>
                            @else
                                <span class="badge bg-danger">INACTIVE</span>
                            @endif
                        </div>
                    </div>

                    <!-- Pricing Info -->
                    <div class="d-flex text-secondary" style="font-size: 0.95rem;">
                        <span class="me-4"><strong>MRP:</strong> ₹{{ number_format($course->mrp_price, 2) }}</span>
                        <span class="me-4"><strong>Min Price:</strong> ₹{{ number_format($course->buy_price, 2) }}</span>
                        <span><strong>Duration:</strong> {{ $course->duration ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-pills mb-3" id="courseTabs" role="tablist">
                <li class="nav-item me-2" role="presentation">
                    <button class="nav-link active px-4 py-2" id="syllabus-tab" data-bs-toggle="tab" data-bs-target="#syllabus" type="button" role="tab" aria-controls="syllabus" aria-selected="true" style="font-weight: 500;">
                        <i class="fas fa-book me-1"></i> Syllabus
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects" type="button" role="tab" aria-controls="projects" aria-selected="false" style="font-weight: 500;">
                        <i class="fas fa-project-diagram me-1"></i> Projects
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="courseTabsContent">
                
                <!-- Syllabus Tab -->
                <div class="tab-pane fade show active" id="syllabus" role="tabpanel" aria-labelledby="syllabus-tab">
                    
                    <!-- Syllabus / PDF Box -->
                    <div class="card bg-white mb-3 shadow-sm border-0">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center rounded border" style="background-color: #f8f9fa;">
                            <div class="d-flex align-items-center">
                                <div class="me-3 fs-3 text-primary">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-dark">{{ $course->course_pdf ? $course->course_pdf : 'No Syllabus Uploaded' }}</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.85rem;">
                                        @if($course->created_at)
                                            Uploaded {{ $course->created_at->format('d M Y') }} • 
                                        @endif
                                        {{ $course->curricula->count() }} modules 
                                        @if($course->videos && $course->videos->count() > 0)
                                            • {{ $course->videos->count() }} videos
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div>
                                @if($course->course_pdf)
                                    <a href="{{ asset('uploads/courses/' . $course->course_pdf) }}" target="_blank" class="btn btn-sm btn-primary px-3">
                                        View Syllabus
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-secondary px-3" disabled>No Syllabus</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Curriculum Modules -->
                    @if($course->curricula->count() > 0)
                        <h6 class="mb-2 text-muted fw-bold">Curriculum Modules</h6>
                        <div class="accordion shadow-sm" id="curriculumAccordion">
                            @foreach($course->curricula as $index => $curriculum)
                                <div class="accordion-item border-0 mb-1 rounded">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button collapsed py-2 px-3 border rounded shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}" style="background-color: #f8f9fa;">
                                            <span class="text-primary fw-bold me-2">Module {{ $index + 1 }}:</span> {{ $curriculum->title }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#curriculumAccordion">
                                        <div class="accordion-body text-muted p-3 bg-white border border-top-0 rounded-bottom">
                                            <ul class="mb-0">
                                            @foreach($curriculum->descriptions as $desc)
                                                <li>{{ $desc->description }}</li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted border rounded bg-white shadow-sm mt-3">
                            <i class="fas fa-folder-open fs-2 mb-2" style="color: #cbd5e1;"></i>
                            <p class="mb-0">No curriculum modules have been added to this course yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Projects Tab -->
                <div class="tab-pane fade" id="projects" role="tabpanel" aria-labelledby="projects-tab">
                    
                    <!-- Sub Tabs for Projects -->
                    <div class="p-2 bg-white rounded border shadow-sm mb-3">
                        <ul class="nav nav-pills" id="projectSubTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-1 px-3" id="proj-all-tab" data-bs-toggle="tab" data-bs-target="#proj-all" type="button" role="tab" aria-selected="true" style="font-weight: 500;">
                                    📦 <span>All</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-1 px-3" id="proj-assignments-tab" data-bs-toggle="tab" data-bs-target="#proj-assignments" type="button" role="tab" aria-selected="false" style="font-weight: 500;">
                                    📋 <span>Assignments</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-1 px-3" id="proj-mini-tab" data-bs-toggle="tab" data-bs-target="#proj-mini" type="button" role="tab" aria-selected="false" style="font-weight: 500;">
                                    🔧 <span>Mini Projects</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-1 px-3" id="proj-main-tab" data-bs-toggle="tab" data-bs-target="#proj-main" type="button" role="tab" aria-selected="false" style="font-weight: 500;">
                                    🚀 <span>Main Project</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="projectSubTabsContent">
                        
                        <!-- ALL TAB -->
                        <div class="tab-pane fade show active" id="proj-all" role="tabpanel" aria-labelledby="proj-all-tab">
                            <div class="row g-3">
                                <!-- Assignments -->
                                @foreach($course->assignments as $assignment)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card shadow-sm border border-success h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h6 class="fw-bold text-success mb-0"><i class="fas fa-clipboard-list me-1"></i> {{ $assignment->title }}</h6>
                                                    <span class="badge bg-light text-dark border">{{ $assignment->module }}</span>
                                                </div>
                                                <p class="small text-muted mb-2"><i class="far fa-calendar-alt me-1"></i> Due: {{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('d M Y') : 'N/A' }}</p>
                                                <a href="{{ route('assignments.show', $assignment->id) }}" class="btn btn-sm btn-outline-success w-100">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Mini Projects -->
                                @foreach($course->mini_projects as $mini)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card shadow-sm border border-info h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h6 class="fw-bold text-info mb-0"><i class="fas fa-layer-group me-1"></i> {{ $mini->title }}</h6>
                                                    <span class="badge bg-light text-dark border">{{ $mini->module }}</span>
                                                </div>
                                                <p class="small text-muted mb-2"><i class="fas fa-info-circle me-1"></i> Status: <span class="text-{{ $mini->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($mini->status) }}</span></p>
                                                <a href="{{ route('mini_projects.show', $mini->id) }}" class="btn btn-sm btn-outline-info w-100">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Main Projects -->
                                @foreach($course->main_projects as $main)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card shadow-sm border border-primary h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h6 class="fw-bold text-primary mb-0"><i class="fas fa-cube me-1"></i> {{ $main->title }}</h6>
                                                    <span class="badge bg-light text-dark border">{{ $main->module }}</span>
                                                </div>
                                                <p class="small text-muted mb-2"><i class="fas fa-info-circle me-1"></i> Status: <span class="text-{{ $main->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($main->status) }}</span></p>
                                                <a href="{{ route('main_projects.show', $main->id) }}" class="btn btn-sm btn-outline-primary w-100">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($course->assignments->isEmpty() && $course->mini_projects->isEmpty() && $course->main_projects->isEmpty())
                                    <div class="col-12">
                                        <div class="text-center py-5 text-muted bg-white border rounded shadow-sm">
                                            <i class="fas fa-box-open fs-2 mb-2" style="color: #cbd5e1;"></i>
                                            <p class="mb-0">No projects or assignments found for this course.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- ASSIGNMENTS TAB -->
                        <div class="tab-pane fade" id="proj-assignments" role="tabpanel" aria-labelledby="proj-assignments-tab">
                            <div class="row g-3">
                                @forelse($course->assignments as $assignment)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card shadow-sm border border-success h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h6 class="fw-bold text-success mb-0">{{ $assignment->title }}</h6>
                                                    <span class="badge bg-light text-dark border">{{ $assignment->module }}</span>
                                                </div>
                                                @if($assignment->description)
                                                    <p class="small text-muted mb-2">{{ Str::limit($assignment->description, 60) }}</p>
                                                @endif
                                                <p class="small text-muted mb-3"><i class="far fa-calendar-alt me-1"></i> Due: {{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('d M Y') : 'N/A' }}</p>
                                                <a href="{{ route('assignments.show', $assignment->id) }}" class="btn btn-sm btn-outline-success w-100">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5 text-muted bg-white border rounded shadow-sm">
                                            <i class="fas fa-clipboard-list fs-2 mb-2" style="color: #cbd5e1;"></i>
                                            <p class="mb-0">No Assignments found.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- MINI PROJECTS TAB -->
                        <div class="tab-pane fade" id="proj-mini" role="tabpanel" aria-labelledby="proj-mini-tab">
                            <div class="row g-3">
                                @forelse($course->mini_projects as $mini)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card shadow-sm border border-info h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h6 class="fw-bold text-info mb-0">{{ $mini->title }}</h6>
                                                    <span class="badge bg-light text-dark border">{{ $mini->module }}</span>
                                                </div>
                                                @if($mini->description)
                                                    <p class="small text-muted mb-2">{{ Str::limit($mini->description, 60) }}</p>
                                                @endif
                                                <p class="small text-muted mb-3"><i class="fas fa-info-circle me-1"></i> Status: <span class="text-{{ $mini->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($mini->status) }}</span></p>
                                                <a href="{{ route('mini_projects.show', $mini->id) }}" class="btn btn-sm btn-outline-info w-100">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5 text-muted bg-white border rounded shadow-sm">
                                            <i class="fas fa-layer-group fs-2 mb-2" style="color: #cbd5e1;"></i>
                                            <p class="mb-0">No Mini Projects found.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- MAIN PROJECTS TAB -->
                        <div class="tab-pane fade" id="proj-main" role="tabpanel" aria-labelledby="proj-main-tab">
                            <div class="row g-3">
                                @forelse($course->main_projects as $main)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card shadow-sm border border-primary h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h6 class="fw-bold text-primary mb-0">{{ $main->title }}</h6>
                                                    <span class="badge bg-light text-dark border">{{ $main->module }}</span>
                                                </div>
                                                @if($main->description)
                                                    <p class="small text-muted mb-2">{{ Str::limit($main->description, 60) }}</p>
                                                @endif
                                                <p class="small text-muted mb-3"><i class="fas fa-info-circle me-1"></i> Status: <span class="text-{{ $main->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($main->status) }}</span></p>
                                                <a href="{{ route('main_projects.show', $main->id) }}" class="btn btn-sm btn-outline-primary w-100">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5 text-muted bg-white border rounded shadow-sm">
                                            <i class="fas fa-cube fs-2 mb-2" style="color: #cbd5e1;"></i>
                                            <p class="mb-0">No Main Projects found.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
