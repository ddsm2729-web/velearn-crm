@can('Lead Registration View')
    <div class="tab-pane fade" id="registration" role="tabpanel">
        <div class="p-4" id="registration-content-wrapper">
            <div id="registration-locked-view" class="{{ $isConfirmed ? 'd-none' : '' }}">
                <div
                    class="rounded-4 p-5 text-center border border-secondary border-opacity-10 shadow-sm bg-white">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-4x text-muted opacity-25"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">Registration Locked</h3>
                    <p class="text-muted small mb-0">Tag the lead as <span
                            class="text-success fw-bold">Converted</span> or <span class="text-success fw-bold">Confirmed</span> in the Call History to unlock
                        registration</p>

                    @if ($lead->location)
                        <div class="mt-4 pt-4 border-top border-light">
                            <span class="text-muted smaller text-uppercase fw-bold d-block mb-1">Lead
                                Location</span>
                            <div class="h5 mb-0 text-dark">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                {{ $lead->location }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div id="registration-unlocked-view" class="{{ !$isConfirmed ? 'd-none' : '' }}">
                <!-- Admission Details Card -->
                <div
                    class="card border border-secondary border-opacity-10 shadow-sm rounded-4 mb-4 bg-white">
                    <div class="card-body p-4">
                        <input type="hidden" id="admission_id_hidden" value="{{ $admission->id ?? '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title text-dark mb-0 fw-bold">Admission Details</h5>
                            <button
                                class="btn btn-sm btn-outline-secondary border-0 text-muted {{ $admission && $admission->payments->count() == 0 ? '' : 'd-none' }}"
                                id="edit-admission-btn">
                                <i class="fas fa-edit me-1"></i> Edit
                            </button>
                        </div>

                        <form id="admission-details-form" class="{{ $admission ? 'd-none' : '' }}">
                            <div class="row g-3">
                                @php
                                    $defaultCourseId = ($admission && $admission->course_id) ? $admission->course_id : ($lead->courses->count() == 1 ? $lead->courses->first()->id : null);
                                    $defaultBatches = $defaultCourseId ? \App\Models\Batch::where('course_id', $defaultCourseId)->where(function($q) {
                                        $q->where('batch_status', '!=', 'Completed')->orWhereNull('batch_status');
                                    })->get() : collect();
                                @endphp
                                <div class="col-md-4">
                                    <label
                                        class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Course</label>
                                    <select
                                        class="form-select bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 course-selection"
                                        name="course_id" required>
                                        <option value="">Select Course</option>
                                        @foreach ($lead->courses as $course)
                                            <option value="{{ $course->id }}"
                                                data-fees="{{ $course->buy_price ?? $course->offer_price ?? $course->mrp_price }}" {{ ($admission && $admission->course_id == $course->id) || (!$admission && $lead->courses->count() == 1) ? 'selected' : '' }}>
                                                {{ $course->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label
                                        class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Batch
                                        Code</label>
                                    <select
                                        class="form-select bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 batch-selection"
                                        name="batch_id" required {{ $defaultCourseId ? '' : 'disabled' }}
                                        data-selected="{{ $admission->batch_id ?? '' }}">
                                        <option value="">Select Batch</option>
                                        @foreach ($defaultBatches as $batch)
                                            <option value="{{ $batch->id }}"
                                                data-code="{{ $batch->batch_code }}"
                                                data-type="{{ $batch->batch_type }}"
                                                {{ ($admission && $admission->batch_id == $batch->id) ? 'selected' : '' }}>
                                                {{ $batch->batch_code }} - {{ $batch->batch_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label
                                        class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Batch
                                        Type</label>
                                    <select
                                        class="form-select bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25"
                                        name="batch_type" required>
                                        <option value="">Select Type</option>
                                        <option value="Weekday Morning" {{ $admission && $admission->batch_type == 'Weekday Morning' ? 'selected' : '' }}>
                                            Weekday Morning</option>
                                        <option value="Weekday Evening" {{ $admission && $admission->batch_type == 'Weekday Evening' ? 'selected' : '' }}>
                                            Weekday Evening</option>
                                        <option value="Weekend" {{ $admission && $admission->batch_type == 'Weekend' ? 'selected' : '' }}>
                                            Weekend</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label
                                        class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Course
                                        Fees (₹)</label>
                                    <input type="number"
                                        class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25"
                                        name="course_fees" placeholder="e.g. 35000" required
                                        value="{{ $admission->course_fees ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label
                                        class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Payment
                                        Mode</label>
                                    <select
                                        class="form-select bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25"
                                        name="payment_mode" id="payment-mode-select" required>
                                        <option value="">Select Mode</option>
                                        <option value="Full Payment" {{ $admission && $admission->payment_mode == 'Full Payment' ? 'selected' : '' }}>
                                            Full Payment</option>
                                        <option value="Split Payment" {{ $admission && $admission->payment_mode == 'Split Payment' ? 'selected' : '' }}>
                                            Split Payment</option>
                                    </select>
                                </div>
                                <div class="col-12 text-end mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4"
                                        id="save-admission-btn">
                                        Save Details
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Read-only View (Hidden by default) -->
                        <div id="admission-readonly-view" class="{{ $admission ? '' : 'd-none' }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div
                                        class="p-3 rounded-3 bg-light border border-secondary border-opacity-10">
                                        <span
                                            class="text-muted smaller text-uppercase d-block mb-1">Course</span>
                                        <span class="text-dark fw-bold"
                                            id="display-course">{{ $admission->course->title ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div
                                        class="p-3 rounded-3 bg-light border border-secondary border-opacity-10">
                                        <span class="text-muted smaller text-uppercase d-block mb-1">Batch
                                            Code</span>
                                        <span class="text-dark fw-bold"
                                            id="display-batch-code">{{ $admission->batch->batch_code ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div
                                        class="p-3 rounded-3 bg-light border border-secondary border-opacity-10">
                                        <span class="text-muted smaller text-uppercase d-block mb-1">Batch
                                            Type</span>
                                        <span class="text-dark fw-bold"
                                            id="display-batch-type">{{ $admission->batch_type ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div
                                        class="p-3 rounded-3 bg-light border border-secondary border-opacity-10">
                                        <span class="text-muted smaller text-uppercase d-block mb-1">Course
                                            Fees</span>
                                        <span class="text-success fw-bold"
                                            id="display-fees">₹{{ number_format($admission->course_fees ?? 0) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div
                                        class="p-3 rounded-3 bg-light border border-secondary border-opacity-10">
                                        <span class="text-muted smaller text-uppercase d-block mb-1">Payment
                                            Mode</span>
                                        <span class="text-dark fw-bold"
                                            id="display-payment-mode">{{ $admission->payment_mode ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fee Collection Card -->
                <div class="card border border-secondary border-opacity-10 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-4">
                        <h5 class="card-title text-dark mb-4 fw-bold">Fee Collection</h5>

                        <!-- Locked State -->
                        <div id="fee-collection-locked"
                            class="text-center py-4 border border-secondary border-opacity-10 border-dashed rounded-4 bg-light {{ $admission ? 'd-none' : '' }}">
                            <p class="text-muted mb-0 small">Save admission details above to unlock fee
                                collection</p>
                        </div>

                        <!-- Unlocked State -->
                        <div id="fee-collection-unlocked" class="{{ $admission ? '' : 'd-none' }}">
                            <!-- Payment Tabs -->
                            <ul class="nav nav-pills nav-justified bg-light rounded-pill p-1 mb-4"
                                id="payment-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill py-2 active"
                                        id="single-payment-tab" data-bs-toggle="pill"
                                        data-bs-target="#single-payment" type="button">
                                        <i class="fas fa-money-check-alt me-2"></i> Single Payment
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill py-2" id="split-payment-tab"
                                        data-bs-toggle="pill" data-bs-target="#split-payment" type="button">
                                        <i class="fas fa-layer-group me-2"></i> Split Payment
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="payment-tabs-content">
                                @php
                                    $payments = $admission ? $admission->payments : collect();
                                    $hasPayments = $payments->count() > 0;
                                    $singlePayment = $hasPayments ? $payments->first() : null;
                                @endphp
                                <!-- Single Payment Pane -->
                                <div class="tab-pane fade show active" id="single-payment" role="tabpanel">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label
                                                class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Amount
                                                (₹)</label>
                                            <input type="number" id="single-amount"
                                                class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25"
                                                placeholder="e.g. 35000"
                                                value="{{ $singlePayment ? $singlePayment->amount : '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label
                                                class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Payment
                                                Date</label>
                                            <input type="date" id="single-date"
                                                class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25"
                                                value="{{ $singlePayment ? \Carbon\Carbon::parse($singlePayment->payment_date)->format('Y-m-d') : '' }}">
                                        </div>
                                        <div class="col-12 text-end mt-4">
                                            <button class="btn btn-primary rounded-pill px-5"
                                                id="save-single-payment-btn">{{ $hasPayments ? 'Update' : 'Save' }}</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Split Payment Pane -->
                                <div class="tab-pane fade" id="split-payment" role="tabpanel">
                                    <div id="split-payment-containers">
                                        @if ($hasPayments && $admission->payment_mode == 'Split Payment')
                                            @foreach ($payments as $index => $payment)
                                                <div
                                                    class="p-3 rounded-4 mb-3 bg-light border border-secondary border-opacity-10 split-item">
                                                    <div
                                                        class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3">
                                                        Payment {{ $index + 1 }}</div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label
                                                                class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Amount
                                                                (₹)
                                                            </label>
                                                            <input type="number"
                                                                class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 split-amount"
                                                                placeholder="e.g. 17500"
                                                                value="{{ $payment->amount }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label
                                                                class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Date</label>
                                                            <input type="date"
                                                                class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 split-date"
                                                                value="{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div
                                                class="p-3 rounded-4 mb-3 bg-light border border-secondary border-opacity-10 split-item">
                                                <div
                                                    class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3">
                                                    Payment 1</div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label
                                                            class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Amount
                                                            (₹)</label>
                                                        <input type="number"
                                                            class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 split-amount"
                                                            placeholder="e.g. 17500">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Date</label>
                                                        <input type="date"
                                                            class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 split-date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="p-3 rounded-4 mb-3 bg-light border border-secondary border-opacity-10 split-item">
                                                <div
                                                    class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3">
                                                    Payment 2</div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label
                                                            class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Amount
                                                            (₹)</label>
                                                        <input type="number"
                                                            class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 split-amount"
                                                            placeholder="e.g. 17500">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Date</label>
                                                        <input type="date"
                                                            class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 split-date">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button
                                        class="btn btn-outline-secondary btn-sm w-100 rounded-3 border-dashed py-3 mt-2"
                                        id="add-split-payment-btn" style="border: 1px dashed #dee2e6;">
                                        <i class="fas fa-plus me-2"></i> Add Another Split
                                    </button>
                                    <div class="text-end mt-4">
                                        <button class="btn btn-primary rounded-pill px-5"
                                            id="save-split-payments-btn">{{ $hasPayments && $admission->payment_mode == 'Split Payment' ? 'Update All' : 'Save All' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan

@can('Lead Payment View')
    <div class="tab-pane fade" id="payment-history" role="tabpanel">
        @if ($admission && $admission->payments->count() > 0)
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 small fw-bold text-uppercase text-muted">Date</th>
                                <th class="border-0 small fw-bold text-uppercase text-muted">Amount</th>
                                <th class="border-0 small fw-bold text-uppercase text-muted">Status</th>
                                <th class="border-0 small fw-bold text-uppercase text-muted text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admission->payments as $payment)
                                <tr>
                                    <td class="small">
                                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                                    </td>
                                    <td class="fw-bold text-dark">₹{{ number_format($payment->amount) }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-soft-{{ $payment->status == 'Paid' ? 'success' : 'warning' }} text-{{ $payment->status == 'Paid' ? 'success' : 'warning' }} rounded-pill px-3">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if(strtolower($payment->status) !== 'paid')
                                            @if(!$payment->payment_link_url)
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill generate-link-btn" data-id="{{ $payment->id }}">
                                                    <i class="fas fa-link me-1"></i> Generate Link
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill send-mail-btn" data-id="{{ $payment->id }}">
                                                    <i class="fas fa-envelope me-1"></i> Send Mail
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i> Completed</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-receipt fa-3x text-muted opacity-25"></i>
                </div>
                <h5 class="text-muted fw-bold">No Lead Payment History</h5>
                <p class="text-muted small">There are no payment records associated with this lead yet.</p>
            </div>
        @endif
    </div>
@endcan

<!-- Modals for Payment Links -->
<div class="modal fade" id="generateLinkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Generate Payment Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to generate a Razorpay payment link for this payment?
                <input type="hidden" id="generate_link_payment_id">
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="confirm-generate-link-btn">Yes, Generate</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sendMailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Send Payment Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to email the payment link to the student?
                <input type="hidden" id="send_mail_payment_id">
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success rounded-pill px-4" id="confirm-send-mail-btn">Yes, Send Mail</button>
            </div>
        </div>
    </div>
</div>
