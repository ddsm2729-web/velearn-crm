@extends('layouts.header')

@section('pageTitle', 'Student Coordinator Dashboard')

@section('section')
<div class="container-fluid p-0">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h3 class="fw-bold"><strong>Student Coordinator</strong> Dashboard</h3>
        <div>
            @include('partials.daterange_picker')
        </div>
    </div>

    <!-- Global Totals Overview -->
    <div class="row mb-4 g-4">
        <!-- Live Courses Card -->
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4 position-relative text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.8rem; opacity: 0.85;">Live Courses</p>
                            <h2 class="display-5 fw-bold mb-0 text-white">{{ $totals['total_courses'] }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-play-circle fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-auto pt-3">
                        <span class="small fw-medium" style="opacity: 0.9;"><i class="fas fa-chart-line me-1"></i> Active in system</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Projects Card -->
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4 position-relative text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.8rem; opacity: 0.85;">Total Main Projects</p>
                            <h2 class="display-5 fw-bold mb-0 text-white">{{ $totals['main_total'] }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-project-diagram fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top border-light border-opacity-25">
                        <div class="text-center">
                            <span class="d-block small text-uppercase" style="opacity: 0.75; font-size: 0.7rem;">Approved</span>
                            <span class="fw-bold">{{ $totals['main_active'] }}</span>
                        </div>
                        <div class="text-center">
                            <span class="d-block small text-uppercase" style="opacity: 0.75; font-size: 0.7rem;">Pending</span>
                            <span class="fw-bold text-warning">{{ $totals['main_pending'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mini Projects Card -->
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4 position-relative text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.8rem; opacity: 0.85;">Total Mini Projects</p>
                            <h2 class="display-5 fw-bold mb-0 text-white">{{ $totals['mini_total'] }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-layer-group fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top border-light border-opacity-25">
                        <div class="text-center">
                            <span class="d-block small text-uppercase" style="opacity: 0.75; font-size: 0.7rem;">Approved</span>
                            <span class="fw-bold">{{ $totals['mini_active'] }}</span>
                        </div>
                        <div class="text-center">
                            <span class="d-block small text-uppercase" style="opacity: 0.75; font-size: 0.7rem;">Pending</span>
                            <span class="fw-bold text-warning" style="color: #ffe066 !important;">{{ $totals['mini_pending'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments Card -->
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4 position-relative" style="color: #d13076;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.8rem; opacity: 0.85;">Total Assignments</p>
                            <h2 class="display-5 fw-bold mb-0" style="color: #c02669;">{{ $totals['assignment_total'] }}</h2>
                        </div>
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(209, 48, 118, 0.15);">
                            <i class="fas fa-file-signature fs-4" style="color: #c02669;"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top" style="border-color: rgba(209, 48, 118, 0.2) !important;">
                        <div class="text-center">
                            <span class="d-block small text-uppercase" style="opacity: 0.75; font-size: 0.7rem;">Approved</span>
                            <span class="fw-bold">{{ $totals['assignment_active'] }}</span>
                        </div>
                        <div class="text-center">
                            <span class="d-block small text-uppercase" style="opacity: 0.75; font-size: 0.7rem;">Pending</span>
                            <span class="fw-bold" style="color: #e65c00;">{{ $totals['assignment_pending'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Global Totals (Students, Batches, Staff) -->
    <div class="row mb-4 g-4">
        <!-- Students Card -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4 position-relative text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.8rem; opacity: 0.85;">Total Students</p>
                            <h2 class="display-5 fw-bold mb-0 text-white">{{ $totals['students_total'] }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-user-graduate fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-auto pt-3">
                        <span class="small fw-medium" style="opacity: 0.9;"><i class="fas fa-users me-1"></i> Across all courses</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batches Card -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4 position-relative text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.8rem; opacity: 0.85;">Total Batches</p>
                            <h2 class="display-5 fw-bold mb-0 text-white">{{ $totals['batches_total'] }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-chalkboard-teacher fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-auto pt-3">
                        <span class="small fw-medium" style="opacity: 0.9;"><i class="fas fa-layer-group me-1"></i> Running batches</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Card -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4 position-relative text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.8rem; opacity: 0.85;">Total Staff</p>
                            <h2 class="display-5 fw-bold mb-0 text-white">{{ $totals['staff_total'] }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-user-tie fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-auto pt-3">
                        <span class="small fw-medium" style="opacity: 0.9;"><i class="fas fa-briefcase me-1"></i> Active staff</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-3">Course Breakdown</h4>

    @forelse($coursesData as $data)
        <div class="card shadow-sm border mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0 fw-bold text-primary">{{ $data['course']->title }}</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <!-- Main Projects -->
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="fw-bold text-muted mb-3 text-uppercase">Main Projects</h6>
                            <div class="d-flex justify-content-around align-items-center">
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $data['main_count'] }}</h3>
                                    <small class="text-muted">Total</small>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-success">{{ $data['main_active'] }}</h3>
                                    <small class="text-muted">Approved</small>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $data['main_pending'] }}</h3>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mini Projects -->
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="fw-bold text-muted mb-3 text-uppercase">Mini Projects</h6>
                            <div class="d-flex justify-content-around align-items-center">
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $data['mini_count'] }}</h3>
                                    <small class="text-muted">Total</small>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-success">{{ $data['mini_active'] }}</h3>
                                    <small class="text-muted">Approved</small>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $data['mini_pending'] }}</h3>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignments -->
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="fw-bold text-muted mb-3 text-uppercase">Assignments</h6>
                            <div class="d-flex justify-content-around align-items-center">
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $data['assignment_count'] }}</h3>
                                    <small class="text-muted">Total</small>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-success">{{ $data['assignment_active'] }}</h3>
                                    <small class="text-muted">Approved</small>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $data['assignment_pending'] }}</h3>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row text-center mt-3">
                    <!-- Students -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="fw-bold text-muted mb-3 text-uppercase">Students</h6>
                            <div class="d-flex justify-content-center align-items-center">
                                <div>
                                    <h3 class="mb-0 fw-bold text-info">{{ $data['students_count'] }}</h3>
                                    <small class="text-muted">Enrolled Students</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Batches -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="fw-bold text-muted mb-3 text-uppercase">Batches</h6>
                            <div class="d-flex justify-content-center align-items-center">
                                <div>
                                    <h3 class="mb-0 fw-bold text-primary">{{ $data['batches_count'] }}</h3>
                                    <small class="text-muted">Active Batches</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info shadow-sm">No live courses found.</div>
    @endforelse
</div>
@endsection
