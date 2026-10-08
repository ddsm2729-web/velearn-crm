<!-- Allocate Modal -->
<div class="modal fade" id="allocateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-dark fw-bold">Allocate Student to Batch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="selected-batch-info p-3 rounded mb-4">
                    <span id="modalBatchInfo" class="text-muted small"></span>
                    <input type="hidden" id="currentBatchId">
                </div>

                <div class="search-container mb-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="studentSearchInput" class="form-control bg-light border-0 text-dark" placeholder="Search confirmed students by name or phone...">
                    </div>
                </div>

                <div class="student-list" id="studentSearchResults" style="max-height: 300px; overflow-y: auto;">
                    <p class="text-muted small text-center my-3">Type to search for students...</p>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="confirmAllocationBtn" disabled>Confirm Allocation</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allocateModal = document.getElementById('allocateModal');
        let selectedContactId = null;
        allocateModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            // If button is provided and has attributes, use them. Otherwise fall back to PHP context.
            const batchInfo = (button && button.getAttribute('data-batch-info')) 
                ? button.getAttribute('data-batch-info') 
                : '{{ isset($batch) ? ($batch->batch_code . " - " . $batch->course->title) : "" }}';
            
            const batchId = (button && button.getAttribute('data-batch-id')) 
                ? button.getAttribute('data-batch-id') 
                : '{{ $batch->id ?? "" }}';
            
            const modalBatchInfo = allocateModal.querySelector('#modalBatchInfo');
            const currentBatchId = allocateModal.querySelector('#currentBatchId');
            
            modalBatchInfo.textContent = batchInfo;
            currentBatchId.value = batchId;
            
            // Reset modal state
            document.getElementById('studentSearchInput').value = '';
            document.getElementById('studentSearchResults').innerHTML = '<p class="text-muted small text-center my-3">Type to search for students...</p>';
            selectedContactId = null;
            document.getElementById('confirmAllocationBtn').disabled = true;
        });

        // Search logic
        let searchTimeout;
        document.getElementById('studentSearchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            const batchId = document.getElementById('currentBatchId').value;
            const resultsContainer = document.getElementById('studentSearchResults');

            selectedContactId = null;
            document.getElementById('confirmAllocationBtn').disabled = true;

            if (query.length < 2) {
                resultsContainer.innerHTML = '<p class="text-muted small text-center my-3">Type to search for students...</p>';
                return;
            }

            resultsContainer.innerHTML = '<div class="text-center my-3"><i class="fas fa-spinner fa-spin text-primary"></i></div>';

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('batch-allocation.search') }}?q=${encodeURIComponent(query)}&batch_id=${batchId}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsContainer.innerHTML = '';
                        if (data.length === 0) {
                            resultsContainer.innerHTML = '<p class="text-muted small text-center my-3">No confirmed students found (or they are already in this batch).</p>';
                            return;
                        }

                        data.forEach(student => {
                            const item = document.createElement('div');
                            const isAssigned = student.is_already_assigned;
                            
                            item.className = `student-item d-flex align-items-center justify-content-between p-3 rounded mb-2 border ${isAssigned ? 'bg-light opacity-75' : 'border-light'}`;
                            item.setAttribute('data-contact-id', student.id);
                            
                            item.innerHTML = `
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3 ${isAssigned ? 'bg-secondary' : 'bg-primary'}">${student.avatar_initials}</div>
                                    <div>
                                        <h6 class="text-dark mb-0">${student.name}</h6>
                                        <p class="text-muted small mb-0">${student.phone}</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    ${isAssigned ? 
                                        `<span class="badge bg-danger-soft text-danger rounded-pill me-2">Already Assigned: ${student.assigned_batch || 'N/A'}</span>` : 
                                        `<i class="fas fa-check text-primary d-none check-icon"></i>`
                                    }
                                </div>
                            `;

                            if (!isAssigned) {
                                item.addEventListener('click', function() {
                                    // Deselect others
                                    document.querySelectorAll('.student-item').forEach(el => {
                                        el.classList.remove('selected', 'border-primary');
                                        const icon = el.querySelector('.check-icon');
                                        if (icon) icon.classList.add('d-none');
                                    });
                                    // Select this one
                                    item.classList.add('selected', 'border-primary');
                                    item.querySelector('.check-icon').classList.remove('d-none');
                                    selectedContactId = student.id;
                                    document.getElementById('confirmAllocationBtn').disabled = false;
                                });
                            } else {
                                item.style.cursor = 'not-allowed';
                            }

                            resultsContainer.appendChild(item);
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        resultsContainer.innerHTML = '<p class="text-danger small text-center my-3">Error fetching students.</p>';
                    });
            }, 500);
        });

        // Allocation logic
        document.getElementById('confirmAllocationBtn').addEventListener('click', function() {
            if (!selectedContactId) return;
            const batchId = document.getElementById('currentBatchId').value;
            const btn = this;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Allocating...';
            btn.disabled = true;

            fetch(`{{ route('batch-allocation.allocate') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    contact_id: selectedContactId,
                    batch_id: batchId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    toastr.success(data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(data.message || 'Error allocating student');
                    btn.innerHTML = 'Confirm Allocation';
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                toastr.error('Network error allocating student.');
                btn.innerHTML = 'Confirm Allocation';
                btn.disabled = false;
            });
        });
    });
</script>

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/batches-partials-allocate_modal.css') }}">
@endpush
