@extends('layouts.header')

@section('pageTitle')
    Manage Practices | {{ config('app.name') }}
@endsection

@section('section')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">
                Manage Practice Questions
            </h1>
            <small class="text-muted">Manage all practice questions</small>
        </div>
        <div>
            <button type="button" class="btn btn-theme" id="btn-add-modal">
                <i class="fa fa-plus me-1"></i> Add Question
            </button>
        </div>
    </div>

    <div class="row">
        {{-- ===== LIST ===== --}}
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <h5 class="mb-0">Practice Questions</h5>
                    <span class="badge bg-primary fs-6" id="practice-count">{{ $practices->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" id="practice-table">
                            <thead class="table-light text-nowrap">
                                <tr>
                                    <th>#</th>
                                    <th>Language</th>
                                    <th>Level</th>
                                    <th>Main Title</th>
                                    <th>Question</th>
                                    <th>Input</th>
                                    <th>Actual Output</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="practice-list-body">
                                @forelse($practices as $i => $practice)
                                    <tr id="practice-row-{{ $practice->id }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td class="text-capitalize">{{ $practice->language }}</td>
                                        <td>
                                            @php
                                                $levelBadge = [
                                                    'Easy' => 'bg-success',
                                                    'Medium' => 'bg-warning',
                                                    'Hard' => 'bg-danger'
                                                ][$practice->level] ?? 'bg-secondary';
                                            @endphp
                                            <span class="badge {{ $levelBadge }}">{{ $practice->level ?: '—' }}</span>
                                        </td>
                                        <td style="max-width:150px;white-space:pre-wrap">{{ $practice->main_title }}</td>
                                        <td style="max-width:200px;">
                                            <div class="line-clamp-2">{{ $practice->question }}</div>
                                        </td>
                                        <td style="max-width:150px;white-space:pre-wrap">{{ $practice->input ?: '—' }}</td>
                                        <td style="max-width:150px;white-space:pre-wrap">{{ $practice->actual_output ?: '—' }}</td>
                                        <td class="text-center">
                                            <span class="status-toggle cursor-pointer"
                                                  data-id="{{ $practice->id }}"
                                                  data-status="{{ $practice->status }}"
                                                  title="Toggle Status">
                                                <img src="{{ asset($practice->status ? 'assets/images/icon-toggle-on.png' : 'assets/images/icon-toggle-off.png') }}"
                                                     id="toggle-img-{{ $practice->id }}">
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info edit-practice"
                                                    data-id="{{ $practice->id }}" 
                                                    data-practice="{{ base64_encode(json_encode($practice)) }}" 
                                                    title="Edit">
                                                <i class="fa fa-edit text-white"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-practice"
                                                    data-id="{{ $practice->id }}" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty-row">
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No practice questions added yet.
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

<!-- Add/Edit Modal -->
<div class="modal fade" id="practiceModal" tabindex="-1" aria-labelledby="practiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-theme text-white">
                <h5 class="modal-title" id="form-title">Add Practice Question</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="practice-form">
                    @csrf
                    <input type="hidden" name="practice_id" id="practice_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Language <span class="text-danger">*</span></label>
                        <select name="language" id="practice_language" class="form-control" required>
                            <option value="">Select Language</option>
                            <option value="javascript">JavaScript</option>
                            <option value="python">Python</option>
                            <option value="java">Java</option>
                            <option value="cpp">C++</option>
                            <option value="typescript">TypeScript</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Level <span class="text-danger">*</span></label>
                        <select name="level" id="practice_level" class="form-control" required>
                            <option value="">Select Level</option>
                            <option value="Easy">Easy</option>
                            <option value="Medium">Medium</option>
                            <option value="Hard">Hard</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Main Title <span class="text-danger">*</span></label>
                        <input type="text" name="main_title" id="practice_main_title" class="form-control" 
                            placeholder="Enter main title (e.g. Chapter 1)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Question <span class="text-danger">*</span></label>
                        <textarea name="question" id="practice_question" class="form-control" rows="4"
                            placeholder="Enter practice question..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Input</label>
                        <textarea name="input" id="practice_input" class="form-control" rows="3"
                            placeholder="Sample input (optional)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Actual Output</label>
                        <textarea name="actual_output" id="practice_actual_output" class="form-control" rows="3"
                            placeholder="Expected output (optional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="practice-form" class="btn btn-theme" id="save-practice-btn">
                    <i class="fa fa-plus me-1"></i> <span id="save-btn-text">Add Practice Question</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/courses-practices-index.css') }}">
@endpush
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

    const storeUrl = '{{ route("practices.store") }}';
    const destroyBase = '{{ url("practices") }}';
    const updateBase = '{{ url("practices") }}';
    const statusUrl = '{{ route("practices.update-status") }}';

    /* === helpers === */
    function updateCount() {
        const rows = $('#practice-list-body tr:not(#empty-row)').length;
        $('#practice-count').text(rows);
    }

    function appendRow(p, idx) {
        $('#empty-row').remove();
        const row = `
            <tr id="practice-row-${p.id}">
                <td>${idx}</td>
                <td class="text-capitalize">${p.language || '—'}</td>
                <td>
                    <span class="badge ${p.level === 'Easy' ? 'bg-success' : (p.level === 'Hard' ? 'bg-danger' : 'bg-warning')}">
                        ${p.level || '—'}
                    </span>
                </td>
                <td style="max-width:150px;white-space:pre-wrap">${p.main_title || '—'}</td>
                <td style="max-width:200px;">
                    <div class="line-clamp-2">${p.question}</div>
                </td>
                <td style="max-width:150px;white-space:pre-wrap">${p.input || '—'}</td>
                <td style="max-width:150px;white-space:pre-wrap">${p.actual_output || '—'}</td>
                <td class="text-center">
                    <span class="status-toggle cursor-pointer"
                          data-id="${p.id}" data-status="1" title="Toggle Status">
                        <img src="{{ asset('assets/images/icon-toggle-on.png') }}" id="toggle-img-${p.id}">
                    </span>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-info edit-practice"
                            data-id="${p.id}" 
                            data-practice="${btoa(unescape(encodeURIComponent(JSON.stringify(p))))}"
                            title="Edit">
                        <i class="fa fa-edit text-white"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-practice"
                            data-id="${p.id}" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        $('#practice-list-body').append(row);
        updateCount();
    }

    /* === ADD / EDIT PRACTICE === */
    $('#practice-form').on('submit', function (e) {
        e.preventDefault();
        const btn = $('#save-practice-btn');
        const isEdit = $('#practice_id').val() !== '';
        const actionUrl = isEdit ? `${updateBase}/${$('#practice_id').val()}` : storeUrl;
        const methodType = isEdit ? 'PUT' : 'POST';
        const originalText = $('#save-btn-text').text();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> <span id="save-btn-text">Saving...</span>');

        $.ajax({
            url: actionUrl,
            method: methodType,
            data: $(this).serialize(),
            success: function (res) {
                btn.prop('disabled', false).html(`<i class="fa ${isEdit ? 'fa-save' : 'fa-plus'} me-1"></i> <span id="save-btn-text">${originalText}</span>`);
                if (res.status) {
                    toastr.success(res.message);
                    resetForm();
                    
                    if (isEdit) {
                        const p = res.practice;
                        const row = $(`#practice-row-${p.id}`);
                        // Update row data
                        row.find('.text-capitalize').text(p.language || '—');
                        const levelBadge = p.level === 'Easy' ? 'bg-success' : (p.level === 'Hard' ? 'bg-danger' : 'bg-warning');
                        row.find('td:eq(2) .badge').attr('class', `badge ${levelBadge}`).text(p.level || '—');
                        row.find('td:eq(3)').text(p.main_title || '—');
                        row.find('td:eq(4) .line-clamp-2').text(p.question);
                        row.find('td:eq(5)').text(p.input || '—');
                        row.find('td:eq(6)').text(p.actual_output || '—');
                        
                        const encodedStr = btoa(unescape(encodeURIComponent(JSON.stringify(p))));
                        row.find('.edit-practice').data('practice', encodedStr)
                                                 .attr('data-practice', encodedStr);
                    } else {
                        const idx = $('#practice-list-body tr:not(#empty-row)').length + 1;
                        appendRow(res.practice, idx);
                    }
                    $('#practiceModal').modal('hide');
                } else {
                    toastr.error(res.message || 'Failed to save practice');
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html(`<i class="fa ${isEdit ? 'fa-save' : 'fa-plus'} me-1"></i> <span id="save-btn-text">${originalText}</span>`);
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    $.each(errors, function (k, v) { toastr.error(v[0]); });
                } else {
                    toastr.error('Failed to save practice question');
                }
            }
        });
    });

    $('#btn-add-modal').on('click', function() {
        resetForm();
        $('#practiceModal').modal('show');
    });

    /* === PREPARE EDIT STATUS === */
    $('body').on('click', '.edit-practice', function() {
        const btn = $(this);
        const encodedStr = btn.data('practice');
        
        try {
            const data = JSON.parse(decodeURIComponent(escape(atob(encodedStr))));
            $('#form-title').text('Edit Practice Question');
            $('#save-btn-text').text('Update Practice Question');
            $('#save-practice-btn').find('i').removeClass('fa-plus').addClass('fa-save');
            
            $('#practice_id').val(data.id);
            $('#practice_language').val(data.language);
            $('#practice_level').val(data.level);
            $('#practice_main_title').val(data.main_title);
            $('#practice_question').val(data.question);
            $('#practice_input').val(data.input);
            $('#practice_actual_output').val(data.actual_output);
            
            $('#practiceModal').modal('show');
        } catch(e) {
            console.error('Error parsing practice data', e);
        }
    });

    function resetForm() {
        $('#practice-form')[0].reset();
        $('#practice_id').val('');
        $('#form-title').text('Add Practice Question');
        $('#save-btn-text').text('Add Practice Question');
        $('#save-practice-btn').find('i').removeClass('fa-save').addClass('fa-plus');
    }

    /* === TOGGLE STATUS === */
    $('body').on('click', '.status-toggle', function () {
        const btn   = $(this);
        const id    = btn.data('id');
        const current = parseInt(btn.data('status'));
        const newStatus = current === 1 ? 0 : 1;
        const msg = newStatus === 1 ? 'activate' : 'deactivate';

        Swal.fire({
            title: 'Are you sure?',
            text: `You want to ${msg} this practice question?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Yes, ${msg} it!`
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: statusUrl,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: newStatus
                    },
                    success: function (res) {
                        if (res.status) {
                            btn.data('status', newStatus);
                            const img = newStatus
                                ? '{{ asset("assets/images/icon-toggle-on.png") }}'
                                : '{{ asset("assets/images/icon-toggle-off.png") }}';
                            $(`#toggle-img-${id}`).attr('src', img);
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message || 'Status update failed');
                        }
                    },
                    error: function () {
                        toastr.error('Something went wrong');
                    }
                });
            }
        });
    });

    /* === DELETE === */
    $('body').on('click', '.delete-practice', function () {
        const id  = $(this).data('id');
        const row = $(`#practice-row-${id}`);

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${destroyBase}/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        if (res.status) {
                            row.remove();
                            updateCount();
                            if ($('#practice-list-body tr').length === 0) {
                                $('#practice-list-body').html(
                                    `<tr id="empty-row">
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No practice questions added yet.
                                        </td>
                                    </tr>`
                                );
                            }
                            toastr.success(res.message);
                        }
                    },
                    error: function () {
                        toastr.error('Failed to delete practice question');
                    }
                });
            }
        });
    });

});
</script>
@endpush
