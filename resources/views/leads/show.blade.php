@extends('layouts.header')

@section('pageTitle')
    {{ $lead->name }} - Lead Details | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0 lead-profile-container mb-5">
        {{-- Header / Back Button --}}
        <div class="row mb-3">
            <div class="col-12">
                <a href="javascript:history.back()"
                    class="text-primary text-decoration-none d-inline-flex align-items-center back-btn">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Lead
                </a>
            </div>
        </div>

        {{-- Main Profile Card --}}
        <div class="card profile-card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                        <div class="profile-avatar me-3" style="overflow: hidden;">
                            {{ strtoupper(substr($lead->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="mb-0 fw-bold">{{ $lead->name }}</h2>
                            <div class="d-flex align-items-center mt-1">
                                @if($lead->status)
                                    <span class="badge text-uppercase px-2 py-1 me-2" style="background-color: {{ $lead->status->color }};">{{ $lead->status->name }}</span>
                                @endif
                                <span class="text-muted small">VL{{ str_pad($lead->id, 3, '0', STR_PAD_LEFT) }}
                                    Assigned → <span class="text-primary fw-bold">
                                        {{ $lead->assignee ? $lead->assignee->first_name . ' ' . $lead->assignee->last_name : 'Unassigned' }}
                                    </span></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @can('Lead Edit')
                        <a href="{{ route('leads.edit', encrypt($lead->id)) }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-outline-success btn-sm px-3 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                            <i class="fas fa-plus me-1"></i> Add Course
                        </button>
                        @endcan
                        @can('Schedule Demo')
                        <a href="{{ route('schedules.index', ['lead_id' => $lead->id]) }}" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">
                            <i class="fas fa-calendar-check me-1"></i> Schedule Demo
                        </a>
                        @endcan
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Detail Boxes --}}
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">PHONE</label>
                            <div class="fw-bold">{{ $lead->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">EMAIL</label>
                            <div class="fw-bold">{{ $lead->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">SOURCE</label>
                            <div class="fw-bold">{{ $lead->source ? $lead->source->name : 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">LOCATION</label>
                            <div class="fw-bold">{{ $lead->location ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">SECONDARY</label>
                            <div class="fw-bold">{{ $lead->whatsapp ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">LEAD DATE</label>
                            <div class="fw-bold">
                                {{ $lead->created_at ? $lead->created_at->format('d M Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">ASSIGNMENT & FOLLOW-UP</label>
                            <div class="fw-bold">
                                {{ $lead->assignee ? $lead->assignee->name : 'Unassigned' }}
                            </div>
                            @php
                                $nextFollowUp = null;
                                if(isset($timeline)) {
                                    foreach($timeline as $item) {
                                        if($item['type'] === 'activity' && $item['data']->next_followup_date) {
                                            $nextFollowUp = $item['data']->next_followup_date;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            @if($nextFollowUp)
                                <div class="small text-info mt-1" style="font-size: 0.8rem;">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ \Carbon\Carbon::parse($nextFollowUp)->format('d M Y h:i A') }}
                                </div>
                            @else
                                <div class="small text-muted mt-1" style="font-size: 0.8rem;">
                                    No follow-up scheduled
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">LEAD TYPE</label>
                            <div class="fw-bold">{{ $lead->source && $lead->source->name == 'MyOperator' ? 'Inbound' : 'Outbound' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline Section --}}
        <div class="card timeline-card border-0 shadow-sm">
            <div class="card-header border-0 bg-white p-0">
                <ul class="nav nav-tabs nav-tabs-custom border-bottom px-4" id="timelineTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold py-3" id="call-history-tab" data-bs-toggle="tab"
                            data-bs-target="#call-history" type="button" role="tab">
                            <i class="fas fa-phone-alt me-2"></i> Timeline History
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-3" id="courses-tab" data-bs-toggle="tab"
                            data-bs-target="#courses-list" type="button" role="tab">
                            <i class="fas fa-book me-2"></i> Assigned Courses
                        </button>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-3" id="pipeline-tab" data-bs-toggle="tab"
                            data-bs-target="#pipeline-history" type="button" role="tab">
                            <i class="fas fa-exchange-alt me-2"></i> Pipeline History
                            @if($pipelineLogs->count() > 0)
                                <span class="badge bg-warning text-dark ms-1">{{ $pipelineLogs->count() }}</span>
                            @endif
                        </button>
                    </li> --}}
                    @can('Lead Registration View')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-3" id="registration-tab" data-bs-toggle="tab"
                                data-bs-target="#registration" type="button" role="tab">
                                <i class="fas fa-clipboard-list me-2"></i> Registration
                                <span id="registration-status-badge">
                                    @if (!$isConfirmed)
                                        <span class="badge bg-soft-danger text-danger ms-2" style="font-size: 0.6rem;">Locked</span>
                                    @else
                                        <span class="badge bg-soft-success text-success ms-2"
                                            style="font-size: 0.6rem;">Unlocked</span>
                                    @endif
                                </span>
                            </button>
                        </li>
                    @endcan
                    @can('Lead Payment View')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-3" id="payment-history-tab" data-bs-toggle="tab"
                                data-bs-target="#payment-history" type="button" role="tab">
                                <i class="fas fa-credit-card me-2"></i> Payment History
                            </button>
                        </li>
                    @endcan
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="timelineTabsContent">
                    <div class="tab-pane fade show active" id="call-history" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-primary"></i> Timeline <span
                                    class="text-muted small fw-normal ms-2">(Previous calls & activities)</span>
                            </h5>
                            <button class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#conversationModal">
                                <i class="fas fa-plus me-1"></i> Add Entry
                            </button>
                        </div>
                        
                        @php
                            $currentTimelineItem = null;
                            $previousTimelineItems = collect();
                            
                            if ($timeline && count($timeline) > 0) {
                                $currentTimelineItem = $timeline->first();
                                $previousTimelineItems = $timeline->slice(1);
                            }

                            // Find the ID of the latest logged activity in the timeline
                            $latestActivityId = null;
                            foreach($timeline as $t) {
                                if($t['type'] === 'activity') {
                                    $latestActivityId = $t['data']->id;
                                    break;
                                }
                            }
                        @endphp
                        
                        {{-- PREVIOUS SECTION --}}
                        <div class="timeline-section mb-5">
                            <div class="accordion-header-custom p-3 rounded-3 d-flex align-items-center justify-content-between mb-2"
                                style="cursor: pointer; background: #f1f3f5; transition: all 0.2s;"
                                data-bs-toggle="collapse" data-bs-target="#previousTimeline" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="text-uppercase fw-bold text-muted smaller me-2">PREVIOUS</span>
                                    <span class="text-muted small">({{ count($previousTimelineItems) }} records - already spoken)</span>
                                </div>
                                <i class="fas fa-chevron-down text-muted transition-icon"></i>
                            </div>

                            <div class="collapse" id="previousTimeline">
                                <div class="table-responsive mt-3">
                                    <table class="table timeline-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>TIME</th>
                                                <th>DIRECTION/TYPE</th>
                                                <th>RECORDING</th>
                                                <th>DURATION</th>
                                                <th>OWNED BY</th>
                                                <th>STATUS/TAG</th>
                                                <th>NOTES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($previousTimelineItems) > 0)
                                                @foreach($previousTimelineItems as $index => $item)
                                                    @include('leads.partials.timeline_row', ['item' => $item, 'latestActivityId' => $latestActivityId])
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted small">No previous call history found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- CURRENT SECTION --}}
                        @if($currentTimelineItem)
                        <div class="timeline-section mb-5">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-circle text-primary smaller me-2" style="font-size: 0.5rem;"></i>
                                <span class="text-uppercase fw-bold text-primary smaller me-2">CURRENT</span>
                                <span class="text-muted small">(Active conversation being handled)</span>
                            </div>
                            
                            <div class="table-responsive mt-3">
                                <table class="table timeline-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>TIME</th>
                                            <th>DIRECTION/TYPE</th>
                                            <th>RECORDING</th>
                                            <th>DURATION</th>
                                            <th>OWNED BY</th>
                                            <th>STATUS/TAG</th>
                                            <th>NOTES + ASSIGN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @include('leads.partials.timeline_row', ['item' => $currentTimelineItem, 'latestActivityId' => $latestActivityId, 'isCurrent' => true])
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Courses List Tab --}}
                    <div class="tab-pane fade" id="courses-list" role="tabpanel">
                        <div class="row g-3">
                            @forelse($lead->courses as $course)
                                <div class="col-md-6 col-lg-4 position-relative">
                                    <div class="card h-100 border-0 shadow-sm course-card">
                                        @if($course->image)
                                            <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 140px; object-fit: cover;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent('{{ $course->title }}') + '&background=f8f9fa&color=6c757d&size=256&font-size=0.33';">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                                                <i class="fas fa-book fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold mb-2 text-truncate" title="{{ $course->title }}">{{ $course->title }}</h6>
                                            
                                            <div class="d-flex flex-wrap gap-1 mb-2">
                                                @if($course->live_course_type)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"><i class="fas fa-video me-1"></i>{{ $course->live_course_type }}</span>
                                                @endif
                                                @if($course->course_type)
                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25"><i class="fas fa-tags me-1"></i>{{ $course->course_type }}</span>
                                                @endif
                                                @if($course->cour_language)
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25"><i class="fas fa-language me-1"></i>{{ $course->cour_language }}</span>
                                                @endif
                                            </div>

                                            <div class="d-flex justify-content-between align-items-end mt-3">
                                                <div>
                                                    <span class="text-muted small">Price</span><br>
                                                    <span class="fw-bold fs-6 text-success">₹{{ number_format($course->buy_price ?? $course->offer_price ?? $course->mrp_price, 2) }}</span>
                                                </div>
                                                <button class="btn btn-outline-danger btn-sm remove-course-btn" data-course-id="{{ $course->id }}" title="Remove Course">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <div class="text-muted mb-3"><i class="fas fa-folder-open fa-3x"></i></div>
                                    <h6 class="fw-bold text-muted">No courses attached to this lead.</h6>
                                    <p class="small text-muted">Click the "Add Course" button at the top to attach a course.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    {{-- Pipeline History Tab --}}
                    <div class="tab-pane fade" id="pipeline-history" role="tabpanel">
                        <h5 class="fw-bold mb-4"><i class="fas fa-exchange-alt me-2 text-warning"></i> Pipeline History <span class="text-muted small fw-normal ms-2">(Status & Tag changes)</span></h5>
                        @if($pipelineLogs->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>DATE</th>
                                            <th>CHANGED BY</th>
                                            <th>STATUS</th>
                                            <th>TAG</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pipelineLogs as $log)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $log->created_at->format('h:i A') }}</div>
                                                    <div class="text-muted small">{{ $log->created_at->format('d M Y') }}</div>
                                                </td>
                                                <td>
                                                    <span class="text-dark">{{ $log->changedBy ? trim($log->changedBy->first_name . ' ' . $log->changedBy->last_name) : 'System' }}</span>
                                                </td>
                                                <td>
                                                    @if($log->new_status_id)
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-2 py-1" style="font-size:0.65rem;">{{ $log->oldStatus?->name ?? '—' }}</span>
                                                            <i class="fas fa-arrow-right text-muted" style="font-size:0.7rem;"></i>
                                                            <span class="badge bg-soft-success text-success border border-success border-opacity-25 px-2 py-1" style="font-size:0.65rem;">{{ $log->newStatus?->name ?? '—' }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($log->new_tag_id || ($log->old_tag_id && !$log->new_tag_id))
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-2 py-1" style="font-size:0.65rem;">{{ $log->oldTag?->name ?? '—' }}</span>
                                                            <i class="fas fa-arrow-right text-muted" style="font-size:0.7rem;"></i>
                                                            <span class="badge bg-soft-warning text-warning border border-warning border-opacity-25 px-2 py-1" style="font-size:0.65rem;">{{ $log->newTag?->name ?? 'Removed' }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="text-muted mb-3"><i class="fas fa-exchange-alt fa-3x"></i></div>
                                <h6 class="fw-bold text-muted">No pipeline changes yet.</h6>
                                <p class="small text-muted">Changes to Status or Tag will be recorded here.</p>
                            </div>
                        @endif
                    </div>

                    @include('leads.partials.registration_tabs')
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Attach Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-course-form" action="{{ route('leads.attach-course', encrypt($lead->id)) }}" method="POST" onsubmit="return false;">
                    @csrf
                    <div class="modal-body p-4 bg-light">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Course <span class="text-danger">*</span></label>
                            <select name="course_id" class="form-select" required>
                                <option value="">-- Select a Course --</option>
                                @foreach($all_courses as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->title }} 
                                        @if($c->cour_language || $c->live_course_type)
                                            ({{ collect([$c->cour_language, $c->live_course_type])->filter()->join(' - ') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success fw-bold" id="add-course-submit">
                            <span class="spinner-border spinner-border-sm d-none me-2" id="course-loader"></span>
                            Attach Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Log Activity Modal -->
    <div class="modal fade" id="conversationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Complete Conversation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="log-activity-form" action="{{ route('leads.activity.store', encrypt($lead->id)) }}" method="POST" onsubmit="return false;">
                    @csrf
                    <div class="modal-body p-4 bg-light">
                        <div class="row g-3">
                            <input type="hidden" name="type" value="Call">
                            <div class="col-12">
                                <label class="form-label fw-bold">Conversation Note <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="note" rows="4" placeholder="Enter conversation details..." required></textarea>
                                <div class="invalid-feedback">Conversation note is strictly required.</div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Next Follow-up</label>
                                <input type="datetime-local" class="form-control" name="next_followup_date">
                            </div>
                            <hr class="my-4">
                            
                            <h6 class="fw-bold mb-2">Update Lead Pipeline</h6>

                            <div class="col-md-6">
                                <label class="form-label text-muted small">Update Status</label>
                                <select class="form-select" name="new_status_id" id="new_status_id">
                                    @foreach($statuses as $sts)
                                        <option value="{{ $sts->id }}" {{ $lead->lead_status_id == $sts->id ? 'selected' : '' }} data-name="{{ strtolower($sts->name) }}">{{ $sts->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Update Tag</label>
                                @php $selectedTagId = $lead->tags->first()?->id; @endphp
                                <select name="tag_id" id="new_tag_id" class="form-select">
                                    <option value="" data-name="">Select Tag</option>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ $selectedTagId == $tag->id ? 'selected' : '' }} data-name="{{ strtolower($tag->name) }}">{{ ucfirst($tag->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 d-none" id="assign_admission_officer_wrapper">
                                <label class="form-label text-muted small">Assign Admission Officer <span class="text-danger">*</span></label>
                                <select name="admission_officer_id" id="new_admission_officer_id" class="form-select">
                                    <option value="">Select Admission Officer</option>
                                    @if(isset($admissionOfficers))
                                        @foreach($admissionOfficers as $officer)
                                            <option value="{{ $officer->id }}" {{ $lead->assigned_to == $officer->id ? 'selected' : '' }}>{{ $officer->first_name }} {{ $officer->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold" id="log-activity-submit">
                            <span class="spinner-border spinner-border-sm d-none me-2" id="activity-loader"></span>
                            Complete Conversation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Activity Modal -->
    <div class="modal fade" id="editActivityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-activity-form" action="" method="POST" onsubmit="return false;">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4 bg-light">
                        <div class="row g-3">
                            <input type="hidden" name="type" id="edit_type" value="Call">

                            <div class="col-12">
                                <label class="form-label fw-bold">Conversation Note <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="note" id="edit_note" rows="4" required></textarea>
                                <div class="invalid-feedback">Conversation note is strictly required.</div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Next Follow-up</label>
                                <input type="datetime-local" class="form-control" name="next_followup_date" id="edit_next_followup_date">
                            </div>

                            <hr class="my-4">
                            
                            <h6 class="fw-bold mb-2">Update Lead Pipeline</h6>

                            <div class="col-md-6">
                                <label class="form-label text-muted small">Update Status</label>
                                <select class="form-select" name="new_status_id" id="edit_status_id">
                                    @foreach($statuses as $sts)
                                        <option value="{{ $sts->id }}" {{ $lead->lead_status_id == $sts->id ? 'selected' : '' }} data-name="{{ strtolower($sts->name) }}">{{ $sts->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Update Tag</label>
                                @php $selectedTagId = $lead->tags->first()?->id; @endphp
                                <select name="tag_id" id="edit_tag_id" class="form-select">
                                    <option value="" data-name="">Select Tag</option>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ $selectedTagId == $tag->id ? 'selected' : '' }} data-name="{{ strtolower($tag->name) }}">{{ ucfirst($tag->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 d-none" id="edit_assign_admission_officer_wrapper">
                                <label class="form-label text-muted small">Assign Admission Officer <span class="text-danger">*</span></label>
                                <select name="admission_officer_id" class="form-select" id="edit_admission_officer_id">
                                    <option value="">Select Admission Officer</option>
                                    @if(isset($admissionOfficers))
                                        @foreach($admissionOfficers as $officer)
                                            <option value="{{ $officer->id }}" {{ $lead->assigned_to == $officer->id ? 'selected' : '' }}>{{ $officer->first_name }} {{ $officer->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold" id="edit-activity-submit">
                            <span class="spinner-border spinner-border-sm d-none me-2" id="edit-activity-loader"></span>
                            Update Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
    <style>
        .timeline { position: relative; padding-left: 20px; }
        .timeline-item::before { content: ''; position: absolute; left: 7px; top: 15px; bottom: -30px; width: 2px; background: #e2e8f0; }
        .timeline-item:last-child::before { display: none; }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>

    <script>
    $(document).ready(function () {

        function toggleAdmissionOfficerDropdown(dropdownId, wrapperId, selectId) {
            var selectedOption = $('#' + dropdownId).find('option:selected');
            var statusName = selectedOption.data('name');
            if (statusName === 'converted') {
                $('#' + wrapperId).removeClass('d-none');
                $('#' + selectId).prop('required', true);
            } else {
                $('#' + wrapperId).addClass('d-none');
                $('#' + selectId).prop('required', false);
            }
        }

        $('#new_status_id').on('change', function() {
            toggleAdmissionOfficerDropdown('new_status_id', 'assign_admission_officer_wrapper', 'new_admission_officer_id');
        });

        $('#edit_status_id').on('change', function() {
            toggleAdmissionOfficerDropdown('edit_status_id', 'edit_assign_admission_officer_wrapper', 'edit_admission_officer_id');
        });

        // Trigger on load for pre-selected values
        toggleAdmissionOfficerDropdown('new_status_id', 'assign_admission_officer_wrapper', 'new_admission_officer_id');
        toggleAdmissionOfficerDropdown('edit_status_id', 'edit_assign_admission_officer_wrapper', 'edit_admission_officer_id');

        $('#log-activity-submit').on('click', function () {
            let $btn = $(this);
            let $loader = $btn.find('#activity-loader');
            let $form = $('#log-activity-form');

            $form.find('.invalid-feedback').text('').hide();
            $form.find('.form-control').removeClass('is-invalid');

            // Client-side validation for mandatory note
            if(!$form.find('textarea[name="note"]').val().trim()) {
                $form.find('textarea[name="note"]').addClass('is-invalid').siblings('.invalid-feedback').show();
                return;
            }

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                beforeSend: function () {
                    $loader.removeClass('d-none');
                    $btn.attr('disabled', true);
                },
                success: function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        setTimeout(function () {
                            if (res.redirect_url) {
                                window.location.href = res.redirect_url;
                            } else {
                                window.location.reload();
                            }
                        }, 1000);
                    } else {
                        toastr.error(res.message || 'Something went wrong.');
                        $btn.attr('disabled', false);
                    }
                },
                error: function (xhr) {
                    $btn.attr('disabled', false);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON?.errors || {};
                        $.each(errors, function (key, value) {
                            let $input = $form.find('[name="' + key + '"]');
                            $input.addClass('is-invalid');
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                complete: function () {
                    $loader.addClass('d-none');
                }
            });
        });

        $(document).on('submit', '.inline-activity-form', function(e) {
            e.preventDefault();
            let $form = $(this);
            let $btn = $form.find('button[type="submit"]');

            if (!this.checkValidity()) {
                e.stopPropagation();
                $form.addClass('was-validated');
                return;
            }

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                beforeSend: function () {
                    $btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
                },
                success: function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error(res.message || 'Something went wrong.');
                        $btn.attr('disabled', false).html('<i class="fas fa-save me-1"></i> Save Note');
                    }
                },
                error: function (xhr) {
                    $btn.attr('disabled', false).html('<i class="fas fa-save me-1"></i> Save Note');
                    toastr.error('Something went wrong. Please try again.');
                }
            });
        });
        
        $('.set-priority-btn').on('click', function (e) {
            e.preventDefault();
            let priority = $(this).data('priority');
            
            Swal.fire({
                title: 'Set Priority?',
                text: 'Are you sure you want to set the priority to ' + priority + '?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, set it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        url: "{{ route('leads.priority.update', encrypt($lead->id)) }}",
                        type: 'POST',
                        data: { priority: priority },
                        success: function (res) {
                            if (res.status) {
                                toastr.success(res.message);
                                setTimeout(function () { window.location.reload(); }, 1000);
                            } else {
                                toastr.error(res.message || 'Something went wrong.');
                            }
                        },
                        error: function (xhr) {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    });
                }
            });
        });
        $('.edit-activity-btn').on('click', function() {
            let id = $(this).data('id');
            let type = $(this).data('type');
            let outcome = $(this).data('outcome');
            let note = $(this).data('note');
            let nextAction = $(this).data('next-action');
            let nextFollowup = $(this).data('next-followup');

            $('#edit_type').val(type);
            $('#edit_outcome').val(outcome);
            $('#edit_note').val(note);
            $('#edit_next_action').val(nextAction);
            $('#edit_next_followup_date').val(nextFollowup);

            // Set action URL dynamically
            $('#edit-activity-form').attr('action', `/leads/activity/${id}`);

            $('#edit_status_id').trigger('change');

            $('#editActivityModal').modal('show');
        });

        $('#edit-activity-form').on('submit', function (e) {
            e.preventDefault();
            let $form = $(this);
            let $btn = $('#edit-activity-submit');
            let $loader = $('#edit-activity-loader');

            // Reset validation states
            $form.find('.is-invalid').removeClass('is-invalid');
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                $form.addClass('was-validated');
                return;
            }

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: $form.attr('action'),
                type: 'POST', // The method is POST, but the _method parameter makes it PUT for laravel
                data: $form.serialize(),
                beforeSend: function () {
                    $loader.removeClass('d-none');
                    $btn.attr('disabled', true);
                },
                success: function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        $('#editActivityModal').modal('hide');
                        setTimeout(function () {
                            if (res.redirect_url) {
                                window.location.href = res.redirect_url;
                            } else {
                                window.location.reload();
                            }
                        }, 1000);
                    } else {
                        toastr.error(res.message || 'Something went wrong.');
                        $btn.attr('disabled', false);
                    }
                },
                error: function (xhr) {
                    $btn.attr('disabled', false);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON?.errors || {};
                        $.each(errors, function (key, value) {
                            let $input = $form.find('[name="' + key + '"]');
                            $input.addClass('is-invalid');
                            toastr.error(value[0]);
                        });
                    } else if (xhr.status === 403) {
                        toastr.error('You do not have permission to edit activities.');
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                complete: function () {
                    $loader.addClass('d-none');
                }
            });
        });
        // Audio Playback Logic
        const audioPlayer = document.getElementById('globalAudioPlayer');
        let currentPlayingBtn = null;

        $('.play-btn').on('click', function () {
            const btn = $(this);
            const id = btn.data('id');
            const icon = btn.find('i');

            if (currentPlayingBtn && currentPlayingBtn[0] === btn[0]) {
                if (!audioPlayer.paused) {
                    audioPlayer.pause();
                    icon.removeClass('fa-pause').addClass('fa-play');
                    btn.removeClass('btn-info').addClass('btn-outline-info');
                } else {
                    audioPlayer.play();
                    icon.removeClass('fa-play').addClass('fa-pause');
                    btn.removeClass('btn-outline-info').addClass('btn-info');
                }
                return;
            }

            if (currentPlayingBtn) {
                currentPlayingBtn.find('i').removeClass('fa-pause').addClass('fa-play');
                currentPlayingBtn.removeClass('btn-info').addClass('btn-outline-info');
            }

            icon.removeClass('fa-play').addClass('fa-spinner fa-spin');
            btn.prop('disabled', true);

            $.get("{{ url('calls/get-recording') }}/" + id, function (response) {
                btn.prop('disabled', false);
                if (response.url) {
                    audioPlayer.src = response.url;
                    audioPlayer.play();

                    currentPlayingBtn = btn;
                    icon.removeClass('fa-spinner fa-spin').addClass('fa-pause');
                    btn.removeClass('btn-outline-info').addClass('btn-info');
                } else {
                    icon.removeClass('fa-spinner fa-spin').addClass('fa-play');
                    toastr.error('Recording link not available.');
                }
            }).fail(function () {
                btn.prop('disabled', false);
                icon.removeClass('fa-spinner fa-spin').addClass('fa-play');
                toastr.error('Failed to fetch recording.');
            });
        });

        if(audioPlayer) {
            audioPlayer.onended = function () {
                if (currentPlayingBtn) {
                    currentPlayingBtn.find('i').removeClass('fa-pause').addClass('fa-play');
                    currentPlayingBtn.removeClass('btn-info').addClass('btn-outline-info');
                    currentPlayingBtn = null;
                }
            };
        }
        // Add Course form
        $('#add-course-form').on('submit', function (e) {
            e.preventDefault();
            let $form = $(this);
            let $btn = $('#add-course-submit');
            let $loader = $('#course-loader');

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                beforeSend: function () {
                    $loader.removeClass('d-none');
                    $btn.attr('disabled', true);
                },
                success: function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        setTimeout(function () {
                            if (res.redirect_url) {
                                window.location.href = res.redirect_url + '#courses-list';
                            } else {
                                window.location.reload();
                            }
                        }, 1000);
                    } else {
                        toastr.error(res.message || 'Something went wrong.');
                        $btn.attr('disabled', false);
                    }
                },
                error: function (xhr) {
                    $btn.attr('disabled', false);
                    toastr.error('Something went wrong. Please try again.');
                },
                complete: function () {
                    $loader.addClass('d-none');
                }
            });
        });

        // Remove Course
        $('.remove-course-btn').on('click', function (e) {
            e.preventDefault();
            let courseId = $(this).data('course-id');
            let $btn = $(this);
            
            Swal.fire({
                title: 'Remove Course?',
                text: 'Are you sure you want to remove this course from the lead?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('leads.detach-course', ['lead' => encrypt($lead->id), 'course' => ':courseId']) }}".replace(':courseId', courseId);
                    
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        url: url,
                        type: 'DELETE',
                        success: function (res) {
                            if (res.status) {
                                toastr.success(res.message);
                                $btn.closest('.course-card').parent().fadeOut(400, function() {
                                    $(this).remove();
                                });
                            } else {
                                toastr.error(res.message || 'Something went wrong.');
                            }
                        },
                        error: function (xhr) {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    });
                }
            });
        });

        // Auto-open tab based on URL hash
        let hash = window.location.hash;
        if (hash) {
            let tabElement = document.querySelector('button[data-bs-target="' + hash + '"]');
            if (tabElement) {
                let tab = new bootstrap.Tab(tabElement);
                tab.show();
            }
        }

    });
    </script>
    @include('leads.partials.registration_js')
    <audio id="globalAudioPlayer" style="display: none;"></audio>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calls-show.css') }}">
@endpush
