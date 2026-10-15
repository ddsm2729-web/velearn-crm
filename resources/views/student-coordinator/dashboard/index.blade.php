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

    <!-- Placement Outreach & Pipeline Overview (Final Placement CRM Workflow) -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-user-graduate text-primary me-2"></i> Placement Pipeline & Outreach
                </h5>
                <p class="text-muted mb-0 small">Monitor student eligibility, applications sent to HRs, and interview outcomes.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('eligible-students.index') }}" class="btn btn-outline-primary btn-sm px-3 fw-bold">
                    <i class="fas fa-users me-1"></i> Eligible Students
                </a>
                <a href="{{ route('applied-openings.index') }}" class="btn btn-primary btn-sm px-3 fw-bold">
                    <i class="fas fa-briefcase me-1"></i> Applied Openings
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <a href="{{ route('eligible-students.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-info">
                        <div class="text-uppercase text-muted fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Eligible Students</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $placementStats['eligible_students'] ?? 0 }}</div>
                        <div class="text-muted small">Cleared for outreach</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('applied-openings.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-primary">
                        <div class="text-uppercase text-muted fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Profile Sent</div>
                        <div class="fs-3 fw-bold text-primary mt-1">{{ $placementStats['profile_sent'] ?? 0 }}</div>
                        <div class="text-muted small">Sent to HRs</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('interview-status.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-secondary">
                        <div class="text-uppercase text-muted fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Interview Scheduled</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $placementStats['interview_scheduled'] ?? 0 }}</div>
                        <div class="text-muted small">Date confirmed</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('interview-status.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-success">
                        <div class="text-uppercase text-muted fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Selected / Placed</div>
                        <div class="fs-3 fw-bold text-success mt-1">{{ $placementStats['selected'] ?? 0 }}</div>
                        <div class="text-muted small">Offers received</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('interview-status.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-danger">
                        <div class="text-uppercase text-muted fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Rejected</div>
                        <div class="fs-3 fw-bold text-danger mt-1">{{ $placementStats['rejected'] ?? 0 }}</div>
                        <div class="text-muted small">Not selected</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('interview-status.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-warning">
                        <div class="text-uppercase text-muted fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">On Hold</div>
                        <div class="fs-3 fw-bold text-warning mt-1">{{ $placementStats['on_hold'] ?? 0 }}</div>
                        <div class="text-muted small">Result awaited</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('interview-status.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-dark">
                        <div class="text-uppercase text-muted fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Rescheduled</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $placementStats['rescheduled'] ?? 0 }}</div>
                        <div class="text-muted small">Pending reslot</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('placement-followups.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-danger">
                        <div class="text-uppercase text-muted fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Pending Follow-ups</div>
                        <div class="fs-3 fw-bold text-danger mt-1">{{ $placementStats['pending_followups'] ?? 0 }}</div>
                        <div class="text-muted small">Follow-up calls due</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Split Grid: Today's Scheduled Interviews & Recent Applications -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fas fa-calendar-day text-primary me-2"></i> Today's Interviews
                        </h6>
                        <a href="{{ route('interview-status.index') }}" class="small text-decoration-none">View all</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0" style="font-size:0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Company</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todaysInterviews as $ti)
                                        <tr>
                                            <td class="fw-semibold">{{ $ti->name }}</td>
                                            <td>{{ $ti->company }}</td>
                                            <td>{{ $ti->time ?? '—' }}</td>
                                            <td>
                                                <span class="badge bg-primary px-2 py-1">{{ ucfirst($ti->status) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                No interviews scheduled for today.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fas fa-inbox text-warning me-2"></i> Recent Student Applications
                        </h6>
                        <a href="{{ route('applied-openings.index') }}" class="small text-decoration-none">View all</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0" style="font-size:0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Company & Role</th>
                                        <th>Applied On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentApplications as $rapp)
                                        <tr>
                                            <td class="fw-semibold">{{ $rapp->user->name ?? 'Student #'.$rapp->user_id }}</td>
                                            <td>
                                                <div>{{ $rapp->opening->company->company_name ?? '—' }}</div>
                                                <div class="text-muted small">{{ $rapp->opening->job_role ?? '—' }}</div>
                                            </td>
                                            <td>{{ $rapp->created_at ? $rapp->created_at->format('d M') : '—' }}</td>
                                            <td>
                                                <a href="{{ route('applied-openings.show', $rapp->opening_id) }}" class="btn btn-xs btn-outline-primary py-1 px-2" style="font-size:0.75rem;">
                                                    Review
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                No recent applications.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top pt-4 mb-3">
        <h5 class="fw-bold text-dark mb-1">
            <i class="fas fa-graduation-cap text-secondary me-2"></i> Course Projects & Academics
        </h5>
        <p class="text-muted small mb-0">Overview of active student projects, assignments, and submissions.</p>
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
