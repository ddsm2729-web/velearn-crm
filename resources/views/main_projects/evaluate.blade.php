@extends('layouts.header')

@section('pageTitle', 'Evaluate Submission')

@section('section')
<div class="container-fluid p-0">
    <div class="mb-4">
        <h3 class="fw-bold text-success">Evaluate <span class="text-dark">Submission</span></h3>
    </div>
    
    <div class="row">
        <!-- Submission Details Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Submission Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase">Student</label>
                        <div class="fw-bold">{{ $submission->student->name ?? 'N/A' }}</div>
                        <div class="text-muted small">{{ $submission->student->email ?? '' }}</div>
                    </div>
                    <hr class="text-muted opacity-25">
                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase">Assignment</label>
                        <div class="fw-bold">{{ $submission->mainProject->title ?? 'N/A' }}</div>
                        <div class="text-muted small">{{ $submission->mainProject->module ?? '' }}</div>
                    </div>
                    <hr class="text-muted opacity-25">
                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase">Submitted On</label>
                        <div class="fw-bold">{{ $submission->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    @if($submission->file_path)
                        <hr class="text-muted opacity-25">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Submitted File</label>
                            <div class="mt-2">
                                <a href="{{ strpos($submission->file_path, 'assignment_submissions') === 0 ? asset($submission->file_path) : asset('storage/' . $submission->file_path) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
                                    <i class="fas fa-file-download"></i> View Submitted File
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Evaluation Form Card -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Grading & Score Breakdown</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('main_projects.storeEvaluation', $submission) }}" method="POST" id="evaluationForm">
                        @csrf
                        
                        <!-- Criteria Breakdown -->
                        <h6 class="fw-bold text-primary mb-3">Criteria Grading</h6>
                        
                        @php
                            $criteria = $submission->mainProject->criteria ?? collect();
                        @endphp

                        @if($criteria->count() > 0)
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Evaluation Criterion</th>
                                            <th style="width: 150px;">Max Score</th>
                                            <th style="width: 180px;">Obtained Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($criteria as $index => $criterion)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $criterion->criterion }}</div>
                                                    <input type="hidden" name="breakdowns[{{ $index }}][criterion]" value="{{ $criterion->criterion }}">
                                                </td>
                                                <td>
                                                    <span class="fw-bold">{{ (int)$criterion->maximum_mark }}</span>
                                                    <input type="hidden" name="breakdowns[{{ $index }}][max_score]" value="{{ (int)$criterion->maximum_mark }}" class="max-score-input">
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           name="breakdowns[{{ $index }}][obtained_score]" 
                                                           class="form-control obtained-score-input" 
                                                           min="0" 
                                                           max="{{ (int)$criterion->maximum_mark }}" 
                                                           step="1" 
                                                           value="{{ $submission->scoreBreakdowns->where('criterion', $criterion->criterion)->first()->obtained_score ?? 0 }}"
                                                           required>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-4">
                                No specific evaluation criteria are defined for this assignment. Defaulting to general score.
                            </div>
                            <!-- Default general criterion if none exists -->
                            <input type="hidden" name="breakdowns[0][criterion]" value="General Submission">
                            <input type="hidden" name="breakdowns[0][max_score]" value="100" class="max-score-input">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Obtained Score (Out of 100)</label>
                                <input type="number" 
                                       name="breakdowns[0][obtained_score]" 
                                       class="form-control obtained-score-input" 
                                       min="0" 
                                       max="100" 
                                       value="{{ $submission->score ?? 0 }}" 
                                       required>
                            </div>
                        @endif

                        <!-- Summary Score Display -->
                        <div class="bg-light p-3 rounded mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase">Total Score</span>
                            </div>
                            <div class="fs-4 fw-bold text-dark">
                                <span id="totalObtained">0</span> / <span id="totalMax">0</span> (<span id="percentage">0%</span>)
                            </div>
                        </div>

                        <!-- Grade Selection -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Grade</label>
                            <select name="grade" class="form-select" required>
                                <option value="">Select Grade</option>
                                <option value="A+" {{ ($submission->grade == 'A+') ? 'selected' : '' }}>A+ (Excellent)</option>
                                <option value="A" {{ ($submission->grade == 'A') ? 'selected' : '' }}>A (Very Good)</option>
                                <option value="B" {{ ($submission->grade == 'B') ? 'selected' : '' }}>B (Good)</option>
                                <option value="C" {{ ($submission->grade == 'C') ? 'selected' : '' }}>C (Satisfactory)</option>
                                <option value="D" {{ ($submission->grade == 'D') ? 'selected' : '' }}>D (Pass)</option>
                                <option value="F" {{ ($submission->grade == 'F') ? 'selected' : '' }}>F (Fail)</option>
                            </select>
                        </div>

                        <!-- Feedback Comments -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Feedback Comments</label>
                            <textarea name="feedback" class="form-control" rows="4" placeholder="Enter structured feedback for the student...">{{ $submission->feedback }}</textarea>
                        </div>

                        <!-- Allow Reupload Setting -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Allow Reupload After Evaluation</label>
                            <div class="d-flex gap-3 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reupload_approved" id="reupload_disable" value="0" {{ ($submission->reupload_approved == 0) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark fw-semibold" for="reupload_disable">
                                        <i class="fas fa-ban text-danger me-1"></i> Disable Reupload
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reupload_approved" id="reupload_enable" value="1" {{ ($submission->reupload_approved == 1) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark fw-semibold" for="reupload_enable">
                                        <i class="fas fa-check-circle text-success me-1"></i> Enable Reupload
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">If enabled, the student will be permitted to upload a new attempt for this assignment.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Submit Evaluation</button>
                            <a href="{{ route('main_projects.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        function calculateScores() {
            let totalObtained = 0;
            let totalMax = 0;

            $('.max-score-input').each(function() {
                totalMax += parseInt($(this).val()) || 0;
            });

            $('.obtained-score-input').each(function() {
                totalObtained += parseInt($(this).val()) || 0;
            });

            $('#totalObtained').text(totalObtained);
            $('#totalMax').text(totalMax);

            let percent = totalMax > 0 ? Math.round((totalObtained / totalMax) * 100) : 0;
            $('#percentage').text(percent + '%');
        }

        $('.obtained-score-input').on('input change', function() {
            calculateScores();
        });

        // Run initially
        calculateScores();
    });
</script>
@endpush
