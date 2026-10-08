@extends('layouts.master')

@section('pageTitle', 'Edit Batch Topic Log')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800">Edit Topic Log</h1>
            <a href="{{ route('tech.batch-logs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Logs
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('tech.batch-logs.update', $batch_log->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $batch_log->date ?? $batch_log->created_at->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Course</label>
                        <select class="form-select course-select" onchange="updateBatchesAndTopics(this, 'edit')">
                            <option value="">-- Select Course --</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}" {{ ($batch_log->batch && $batch_log->batch->course_id == $c->id) ? 'selected' : '' }}>{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Batch</label>
                        <select name="batch_id" class="form-select batch-select" id="batch_select_edit" required>
                            <option value="">-- Select Batch --</option>
                            @if($batch_log->batch && $batch_log->batch->course)
                                @foreach($batch_log->batch->course->batches as $b)
                                    <option value="{{ $b->id }}" {{ $batch_log->batch_id == $b->id ? 'selected' : '' }}>{{ $b->batch_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Module/Topic</label>
                        <select name="course_curriculum_id" class="form-select topic-select" id="topic_select_edit" required>
                            <option value="">-- Select Topic --</option>
                            @if($batch_log->batch && $batch_log->batch->course)
                                @foreach($batch_log->batch->course->curricula as $topic)
                                    <option value="{{ $topic->id }}" {{ $batch_log->course_curriculum_id == $topic->id ? 'selected' : '' }}>{{ $topic->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ $batch_log->description }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Note (Optional)</label>
                        <textarea name="note" class="form-control" rows="2">{{ $batch_log->note }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Video Recording (Optional)</label>
                        <input type="file" name="video" id="video_input" class="form-control" accept="video/mp4,video/x-m4v,video/*">
                        @if($batch_log->video)
                            <small class="text-muted d-block mt-1">
                                Current video: <a href="{{ asset($batch_log->video) }}" target="_blank" class="text-primary text-decoration-underline">View Video</a>
                            </small>
                        @endif
                        
                        <!-- Progress Bar Container -->
                        <div id="upload_progress_container" class="mt-3 d-none">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold">Uploading Video...</small>
                                <small id="upload_percentage" class="fw-bold text-primary">0%</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div id="upload_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Progress Status</label>
                        <select name="status" class="form-select">
                            <option value="Pending" {{ ($batch_log->status === 'Pending' || ($batch_log->status === null && !$batch_log->is_completed)) ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ $batch_log->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ ($batch_log->status === 'Completed' || ($batch_log->status === null && $batch_log->is_completed)) ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-4 text-end">
                    <a href="{{ route('tech.batch-logs.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" id="submit_btn" class="btn btn-primary">Update Log</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Prepare courses data
    const coursesData = {};
    @foreach($courses as $c)
        coursesData[{{ $c->id }}] = {
            batches: [
                @foreach($c->batches as $b)
                    { id: {{ $b->id }}, name: "{{ addslashes($b->batch_name) }}" },
                @endforeach
            ],
            topics: [
                @foreach($c->curricula as $topic)
                    { id: {{ $topic->id }}, title: "{{ addslashes($topic->title) }}" },
                @endforeach
            ]
        };
    @endforeach

    function updateBatchesAndTopics(selectElement, targetId) {
        const courseId = selectElement.value;
        const batchSelect = document.getElementById('batch_select_' + targetId);
        const topicSelect = document.getElementById('topic_select_' + targetId);
        
        // Clear current options
        batchSelect.innerHTML = '<option value="">-- Select Batch --</option>';
        topicSelect.innerHTML = '<option value="">-- Select Topic --</option>';
        
        if (courseId && coursesData[courseId]) {
            const data = coursesData[courseId];
            
            // Populate Batches
            data.batches.forEach(b => {
                const option = document.createElement('option');
                option.value = b.id;
                option.textContent = b.name;
                batchSelect.appendChild(option);
            });
            
            // Populate Topics
            data.topics.forEach(t => {
                const option = document.createElement('option');
                option.value = t.id;
                option.textContent = t.title;
                topicSelect.appendChild(option);
            });
        }
    }

    // Form submission with progress tracking
    document.querySelector('form').addEventListener('submit', function(e) {
        const videoInput = document.getElementById('video_input');
        if (videoInput.files.length > 0) {
            e.preventDefault(); // Prevent standard submission only if file is attached
            
            const form = this;
            const submitBtn = document.getElementById('submit_btn');
            const progressContainer = document.getElementById('upload_progress_container');
            const progressBar = document.getElementById('upload_progress_bar');
            const percentageText = document.getElementById('upload_percentage');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            progressContainer.classList.remove('d-none');
            
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            
            xhr.open(form.method, form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            // Upload progress event
            xhr.upload.addEventListener('progress', function(event) {
                if (event.lengthComputable) {
                    const percentComplete = Math.round((event.loaded / event.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressBar.setAttribute('aria-valuenow', percentComplete);
                    percentageText.textContent = percentComplete + '%';
                    
                    if (percentComplete === 100) {
                        percentageText.textContent = 'Processing...';
                    }
                }
            });
            
            // Request completion
            xhr.addEventListener('load', function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    // Success, redirect to index
                    window.location.href = "{{ route('tech.batch-logs.index') }}";
                } else {
                    // Error occurred
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Update Log';
                    alert('An error occurred during upload. Please try again.');
                }
            });
            
            xhr.addEventListener('error', function() {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Log';
                alert('Network error occurred. Please try again.');
            });
            
            xhr.send(formData);
        }
    });
</script>
@endsection
