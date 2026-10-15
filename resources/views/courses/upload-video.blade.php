@extends('layouts.header')

@section('pageTitle')
    {{ __('Upload Video & Summaries') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Upload Video & Summaries for: {{ $course->title }}</h1>
            @can('Course List')
                <a href="javascript:history.back()" class="btn btn-theme"><i class="fas fa-arrow-left me-2"></i> Back</a>
            @endcan
        </div>

        @can('Course Create')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form method="POST" action="{{ route('courses.video.store', $course->id) }}"
                                enctype="multipart/form-data" id="upload-video-form">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Video Title *</label>
                                    <input type="text" name="title" class="form-control" placeholder="Enter video title"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Select Video *</label>
                                    <input type="file" name="video" class="form-control" accept="video/*" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">English Subtitle (.vtt only)</label>
                                            <input type="file" name="subtitle_en" class="form-control" accept=".vtt">
                                            <div class="form-text small">Standard English caption file in WebVTT format.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tamil Subtitle (.vtt only)</label>
                                            <input type="file" name="subtitle_ta" class="form-control" accept=".vtt">
                                            <div class="form-text small">Tamil language caption file for localized content.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Video Script (Transcript)</label>
                                    <textarea name="script" class="form-control" rows="5" placeholder="Enter video script or transcript here..."></textarea>
                                    <div class="form-text small">Provide the full script or transcript for better accessibility and learning.</div>
                                </div>

                                <!-- ================= Summaries ================= -->
                                <div class="mb-3">
                                    <label class="form-label">Video Summaries *</label>
                                    <div id="summaries-wrapper">
                                        <div class="d-flex gap-2 mb-2 summary-item">
                                            <textarea type="text" name="summaries[0]" class="form-control" placeholder="Summary"></textarea>
                                            <button type="button" class="btn btn-danger remove-summary">X</button>
                                        </div>
                                    </div>
                                    <button type="button" id="add-summary" class="btn btn-sm btn-outline-primary">
                                        + Add Summary
                                    </button>
                                </div>

                                <!-- ================= Upload Progress ================= -->
                                <div class="mb-3">
                                    <div class="progress d-none" id="upload-progress">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;" id="progress-bar">0%
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-theme" id="upload-btn">
                                    <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                                    Upload
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection

@push('script')
    <script>
        let summaryIndex = 1;

        // Add Summary
        $('#add-summary').click(function() {
            $('#summaries-wrapper').append(`
        <div class="d-flex gap-2 mb-2 summary-item">
            <textarea type="text" name="summaries[${summaryIndex}]" class="form-control" placeholder="Summary"></textarea>
            <button type="button" class="btn btn-danger remove-summary">X</button>
        </div>
    `);
            summaryIndex++;
        });

        // Remove Summary
        $('body').on('click', '.remove-summary', function() {
            if ($('.summary-item').length > 1) {
                $(this).closest('.summary-item').remove();
            } else {
                alert('At least one summary is required.');
            }
        });

        // AJAX Upload with Progress
        $('#upload-video-form').on('submit', function(e) {
            e.preventDefault();

            let $btn = $('#upload-btn');
            let $spinner = $('#loader-spinner');

            // Disable button immediately to prevent multiple clicks
            $btn.prop('disabled', true);
            $spinner.removeClass('d-none');

            let form = $('#upload-video-form')[0];
            let formData = new FormData(form);

            $('#upload-progress').removeClass('d-none');
            $('#progress-bar').css('width', '0%').text('0%');

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            let percent = Math.round((e.loaded / e.total) * 100);
                            $('#progress-bar').css('width', percent + '%').text(percent + '%');
                        }
                    });
                    return xhr;
                },
                success: function(res) {
                    toastr.success(res.message);
                    $('#progress-bar').css('width', '100%').text('100%');
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    toastr.error('Upload failed.');
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            alert(value[0]);
                        });
                    }
                },
                complete: function() {
                    // Re-enable button if you want to allow retry after error
                    $btn.prop('disabled', false);
                    $spinner.addClass('d-none');
                }
            });
        });
    </script>
@endpush
