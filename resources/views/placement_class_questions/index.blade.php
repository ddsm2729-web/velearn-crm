@extends('layouts.header')
@section('pageTitle', 'Placement Class Questions')
@section('section')
<div class='row mb-2 mb-xl-3'>
    <div class='col-auto d-none d-sm-block'>
        <h3><strong>Placement Class Questions</strong></h3>
    </div>
    <div class='col-auto ms-auto text-end mt-n1 d-flex gap-2'>
        <a href='{{ route("placement-classes.index") }}' class='btn btn-secondary d-flex align-items-center shadow-sm'>
            <i class='fas fa-arrow-left me-2'></i> Back
        </a>
        <a href='{{ route("placement-class-questions.create", ["class_id" => request("class_id")]) }}' class='btn btn-primary d-flex align-items-center shadow-sm'>
            <i class='fas fa-plus me-2'></i> Create
        </a>
    </div>
</div>
<div class='row'>
    <div class='col-12'>
        <div class='card'>
            <div class='card-body p-0'>
                <div class='table-responsive'>
                    <table class='table table-hover mb-0'>
                        <thead class='text-uppercase text-muted' style='font-size: 0.75rem; background-color: #f8f9fa;'>
                            <tr>
                                <th class='ps-4 py-3'>ID</th>
                                <th>DAY</th>
                                <th>QUESTION</th>
                                <th>MARKS</th>
                                <th class='pe-4 py-3 text-end'>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                            <tr>
                                <td class='ps-4'>{{ $loop->iteration }}</td>
                                <td>Day {{ $question->placementClass->day ?? 'N/A' }}</td><td>{{ $question->question }}</td><td>{{ $question->marks }}</td>
                                <td class='pe-4 text-end'>
                                    <a href='{{ route("placement-class-questions.edit", $question->id) }}' class='btn btn-sm btn-outline-primary'>Edit</a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-form-id="delete-form-{{ $question->id }}">Delete</button>
                                    <form id="delete-form-{{ $question->id }}" action='{{ route("placement-class-questions.destroy", $question->id) }}' method='POST' class='d-inline' style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($questions->isEmpty())
                            <tr><td colspan='10' class='text-center py-4'>No records found.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        // Delete Modal Logic
        let deleteFormId = null;
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            deleteFormId = button.data('form-id');
        });
        $('#confirmDeleteBtn').on('click', function () {
            if(deleteFormId) {
                $('#' + deleteFormId).submit();
            }
        });
    });
</script>
@endpush


