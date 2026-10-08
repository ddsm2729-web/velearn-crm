@extends('layouts.header')

@section('pageTitle')
    Edit Video | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Edit Video</h1>
            @can('Course List')
                <a href="{{ route('courses.videos.index', encrypt($video->course_id)) }}" class="btn btn-theme">Back</a>
            @endcan
        </div>

        @can('Course Create')
            <div class="card">
                <div class="card-body">

                    <form method="POST" action="{{ route('course.videos.update', encrypt($video->id)) }}"
                        enctype="multipart/form-data" id="upload-video-form">
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label">Video Title *</label>
                            <input type="text" name="title" class="form-control" value="{{ $video->title }}" required>
                        </div>

                        {{-- Current Video --}}
                        <div class="mb-3">
                            <label class="form-label">Current Video</label><br>
                            <video width="400" controls>
                                <source src="{{ asset($video->video) }}" type="video/mp4">
                            </video>
                        </div>

                        {{-- Replace Video --}}
                        <div class="mb-4">
                            <label class="form-label font-weight-bold">Replace Video (optional)</label>
                            <input type="file" name="video" class="form-control" accept="video/*">
                        </div>

                        <hr>

                        {{-- Subtitles --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">English Subtitle (.vtt)</label>
                                @if($video->subtitle_en)
                                    <div class="mb-2">
                                        <span class="badge bg-success">Current: {{ basename($video->subtitle_en) }}</span>
                                    </div>
                                @endif
                                <input type="file" name="subtitle_en" class="form-control" accept=".vtt">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Tamil Subtitle (.vtt)</label>
                                @if($video->subtitle_ta)
                                    <div class="mb-2">
                                        <span class="badge bg-success">Current: {{ basename($video->subtitle_ta) }}</span>
                                    </div>
                                @endif
                                <input type="file" name="subtitle_ta" class="form-control" accept=".vtt">
                            </div>
                        </div>

                        <hr>

                        {{-- Video Script --}}
                        <div class="mb-4">
                            <label class="form-label font-weight-bold">Video Script (Transcript)</label>
                            <textarea name="script" class="form-control" rows="6" placeholder="Update video script or transcript here...">{{ $video->script }}</textarea>
                            <div class="form-text small">Detailed transcript for accessibility and lesson notes.</div>
                        </div>

                        <hr>

                        {{-- Summaries --}}
                        <div class="mb-3">
                            <label class="form-label">Summaries *</label>

                            <div id="summaries-wrapper">
                                @foreach ($video->summaries as $summary)
                                    <div class="d-flex gap-2 mb-2 summary-item">
                                        <textarea name="summaries[]" class="form-control" required>{{ $summary->summary }}</textarea>
                                        <button type="button" class="btn btn-danger remove-summary">X</button>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" id="add-summary" class="btn btn-sm btn-outline-primary mt-2">
                                + Add Summary
                            </button>
                        </div>

                        {{-- Upload Progress --}}
                        <div class="mb-3">
                            <div class="progress d-none" id="upload-progress">
                                <div class="progress-bar" role="progressbar" style="width:0%" id="progress-bar">0%</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-theme" id="upload-btn">
                            <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                            Update Video
                        </button>

                    </form>

                </div>
            </div>
        @endcan
    </div>
@endsection

@push('script')
    <script>
        /* -------------------- SUMMARY ADD / REMOVE -------------------- */
        $('#add-summary').on('click', function() {
            $('#summaries-wrapper').append(`
        <div class="d-flex gap-2 mb-2 summary-item">
            <textarea name="summaries[]" class="form-control" required></textarea>
            <button type="button" class="btn btn-danger remove-summary">X</button>
        </div>
    `);
        });

        $('body').on('click', '.remove-summary', function() {
            if ($('.summary-item').length > 1) {
                $(this).closest('.summary-item').remove();
            } else {
                alert('At least one summary is required.');
            }
        });

        /* -------------------- AJAX UPDATE WITH PROGRESS -------------------- */
        $('#upload-video-form').on('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            let $btn = $('#upload-btn');
            let $spinner = $('#loader-spinner');

            // Prevent double submit
            $btn.prop('disabled', true);
            $spinner.removeClass('d-none');

            $('#upload-progress').removeClass('d-none');
            $('#progress-bar').css('width', '0%').text('0%');

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST', // PUT via _method
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
                    toastr.success(res.message ?? 'Video updated successfully');
                    $('#progress-bar').css('width', '100%').text('100%');

                    setTimeout(() => {
                        window.location.href =
                            "{{ route('courses.videos.index', encrypt($video->course_id)) }}";
                    }, 800);
                },
                error: function(xhr) {
                    toastr.error('Update failed');
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function(_, msg) {
                            toastr.error(msg[0]);
                        });
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $spinner.addClass('d-none');
                }
            });
        });
    </script>
@endpush
