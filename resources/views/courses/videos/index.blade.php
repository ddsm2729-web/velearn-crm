@extends('layouts.header')

@section('pageTitle')
    Course Videos | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">

        @can('Course List')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3">
                    Videos – <strong>{{ $course->title }}</strong>
                </h1>
                <div class="d-flex gap-2">
                    @can('Course List')
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">← Back</a>
                    @endcan
                </div>
            </div>

            <div class="row">
                @forelse($videos as $index => $video)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">

                                <h6 class="mb-2">
                                    {{ ($videos->currentPage() - 1) * $videos->perPage() + $index + 1 }}.
                                    {{ $video->title }}
                                </h6>

                                {{-- Video Player --}}
                                <video width="100%" height="200" controls>
                                    <source src="{{ asset($video->video) }}" type="video/mp4">
                                </video>

                                {{-- View Summary Button --}}
                                <div class="d-flex gap-2 mt-2">
                                    @if ($video->summaries->count())
                                        <button class="btn btn-sm btn-outline-primary view-summary-btn"
                                            data-title="{{ $video->title }}" data-summaries='@json($video->summaries)'>
                                            View Summary
                                        </button>
                                    @endif

                                    {{-- ✅ Edit Button --}}
                                    @can('Course Create')
                                        <a href="{{ route('course.videos.edit', encrypt($video->id)) }}"
                                            class="btn btn-sm btn-outline-warning">
                                            Edit
                                        </a>
                                    @endcan

                                    <button class="btn btn-sm btn-outline-info quiz-btn" 
                                        data-id="{{ encrypt($video->id) }}" 
                                        data-title="{{ $video->title }}">
                                        Quiz
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <div class="alert alert-warning p-2">
                            No videos uploaded for this course.
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $videos->links() }}
            </div>
        @endif
    </div>

    {{-- ================= Summary Modal ================= --}}
    <div class="modal fade" id="summaryModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="summaryModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <ul id="summaryList" class="ps-3"></ul>
                </div>
            </div>
        </div>
    </div>
    {{-- ================= Quiz Modal ================= --}}
    <div class="modal fade" id="quizModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Quiz: <span id="quiz-video-title"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Add form -->
                    <form id="quiz-form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label font-weight-bold">Question</label>
                                <input type="text" name="question" id="quiz_question" class="form-control" placeholder="Enter question" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Option 1</label>
                                <input type="text" name="option1" id="quiz_option1" class="form-control" placeholder="Option 1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Option 2</label>
                                <input type="text" name="option2" id="quiz_option2" class="form-control" placeholder="Option 2" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Option 3</label>
                                <input type="text" name="option3" id="quiz_option3" class="form-control" placeholder="Option 3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Correct Answer</label>
                                <select name="answer" id="quiz_answer" class="form-control" required>
                                    <option value="">Select Correct Option</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-theme w-100" id="save-quiz-btn">Add Question</button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <!-- List table -->
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Options</th>
                                    <th>Ans</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="quiz-list-body">
                                <!-- Dynamic content -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let currentVideoId = null;

            $('body').on('click', '.view-summary-btn', function() {
                let title = $(this).data('title');
                let summaries = $(this).data('summaries');

                $('#summaryModalTitle').text(title);
                $('#summaryList').html('');

                summaries.forEach(function(item) {
                    $('#summaryList').append(`<li class="p-2">${item.summary}</li>`);
                });

                $('#summaryModal').modal('show');
            });

            // Open Quiz Modal
            $('body').on('click', '.quiz-btn', function() {
                let id = $(this).data('id');
                let title = $(this).data('title');
                currentVideoId = id;
                $('#quiz-video-title').text(title);
                $('#quiz-form')[0].reset();
                loadQuizzes(id);
                $('#quizModal').modal('show');
            });

            // Load Quizzes
            function loadQuizzes(id) {
                $.ajax({
                    url: `{{ url('videos') }}/${id}/quizzes`,
                    method: 'GET',
                    success: function(res) {
                        if (res.status) {
                            let html = '';
                            res.quizzes.forEach((q, i) => {
                                html += `<tr>
                                    <td>${i+1}</td>
                                    <td>${q.question}</td>
                                    <td class="small">1: ${q.option1}<br>2: ${q.option2}<br>3: ${q.option3}</td>
                                    <td>${q.answer}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger delete-quiz" data-id="${q.id}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
                            });
                            $('#quiz-list-body').html(html || '<tr><td colspan="5" class="text-center">No quiz found</td></tr>');
                        }
                    }
                });
            }

            // Save Quiz
            $('#quiz-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $('#save-quiz-btn').prop('disabled', true).text('Saving...');

                $.ajax({
                    url: `{{ url('videos') }}/${currentVideoId}/quizzes`,
                    method: 'POST',
                    data: formData,
                    success: function(res) {
                        $('#save-quiz-btn').prop('disabled', false).text('Add Question');
                        if (res.status) {
                            toastr.success(res.message);
                            $('#quiz-form')[0].reset();
                            loadQuizzes(currentVideoId);
                        }
                    },
                    error: function(err) {
                        $('#save-quiz-btn').prop('disabled', false).text('Add Question');
                        toastr.error('Failed to add quiz question');
                    }
                });
            });

            // Delete Quiz
            $('body').on('click', '.delete-quiz', function() {
                if (!confirm('Are you sure to delete this question?')) return;
                let id = $(this).data('id');
                let row = $(this).closest('tr');

                $.ajax({
                    url: `{{ url('quizzes') }}/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if (res.status) {
                            toastr.success(res.message);
                            row.remove();
                        }
                    }
                });
            });

        });
    </script>
@endpush
