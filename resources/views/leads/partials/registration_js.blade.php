<script>
    $(document).ready(function() {
        // --- Remember Tab Selection ---
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('activeLeadTab', $(e.target).attr('id'));
        });

        let activeTabId = localStorage.getItem('activeLeadTab');
        if (activeTabId) {
            let $tab = $('#' + activeTabId);
            if ($tab.length) {
                let tab = new bootstrap.Tab($tab[0]);
                tab.show();
            }
        }

        // --- Course and Batch Selection Logic ---
        $('.course-selection').on('change', function() {
            let courseId = $(this).val();
            let $batchSelect = $('.batch-selection');
            let $feesInput = $('input[name="course_fees"]');
            
            // Set default fees based on selected course
            if (courseId) {
                let defaultFees = $(this).find('option:selected').data('fees');
                $feesInput.val(defaultFees);
                $batchSelect.prop('disabled', false).html('<option value="">Loading batches...</option>');

                // Fetch batches for this course
                $.ajax({
                    url: `/courses/${courseId}/batches`, // Verify this endpoint exists
                    type: 'GET',
                    success: function(res) {
                        let options = '<option value="">Select Batch</option>';
                        if (res.status && res.data.length > 0) {
                            res.data.forEach(function(batch) {
                                let label = `${batch.batch_code} - ${batch.batch_name}`;
                                options += `<option value="${batch.id}" data-code="${batch.batch_code}" data-type="${batch.batch_type}">${label}</option>`;
                            });
                        } else {
                            options = '<option value="">No active batches found</option>';
                        }
                        $batchSelect.html(options);
                    },
                    error: function() {
                        $batchSelect.html('<option value="">Error loading batches</option>');
                    }
                });
            } else {
                $batchSelect.prop('disabled', true).html('<option value="">Select Batch</option>');
                $feesInput.val('');
            }
        });

        // Auto-select batch type when batch is chosen
        $('.batch-selection').on('change', function() {
            let type = $(this).find('option:selected').data('type');
            if(type) {
                $('select[name="batch_type"]').val(type);
            }
        });

        // --- Payment Mode Logic ---
        function updatePaymentTabs(mode) {
            const splitTab = $('#split-payment-tab');
            const singleTab = $('#single-payment-tab');

            if (mode === 'Full Payment') {
                singleTab.removeClass('disabled').prop('disabled', false).tab('show');
                splitTab.addClass('disabled').prop('disabled', true);
            } else if (mode === 'Split Payment') {
                splitTab.removeClass('disabled').prop('disabled', false).tab('show');
                singleTab.addClass('disabled').prop('disabled', true);
            }
        }

        $('#payment-mode-select').on('change', function() {
            let mode = $(this).val();
            updatePaymentTabs(mode);
            
            if (mode === 'Full Payment') {
                $('#single-amount').val($('input[name="course_fees"]').val());
            } else if (mode === 'Split Payment') {
                // Could auto-split the amount into 2 inputs here if desired
                let total = parseFloat($('input[name="course_fees"]').val()) || 0;
                if(total > 0) {
                    $('.split-amount').val(total / 2);
                }
            }
        });

        // Initialize tabs on page load if admission exists
        @if($admission)
            updatePaymentTabs('{{ $admission->payment_mode }}');
        @endif

        // Enable Edit Admission Details
        $('#edit-admission-btn').on('click', function(e) {
            e.preventDefault();
            $('#admission-readonly-view').addClass('d-none');
            $('#admission-details-form').removeClass('d-none');
            $(this).addClass('d-none');
            $('#fee-collection-locked').removeClass('d-none');
            $('#fee-collection-unlocked').addClass('d-none');
        });

        // Save Admission Details (Registration)
        $('#admission-details-form').on('submit', function(e) {
            e.preventDefault();
            let $btn = $('#save-admission-btn');
            
            // Collect form data
            let formData = {
                lead_id: {{ $lead->id }},
                course_id: $('select[name="course_id"]').val(),
                batch_id: $('select[name="batch_id"]').val(),
                batch_type: $('select[name="batch_type"]').val(),
                course_fees: $('input[name="course_fees"]').val(),
                payment_mode: $('select[name="payment_mode"]').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: `/admission`, // Warning: Make sure this route is available in your routes file
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                },
                success: function(res) {
                    if (res.status) {
                        toastr.success(res.message || 'Admission details saved successfully!');
                        
                        // Set the hidden admission ID so fee collection works
                        $('#admission_id_hidden').val(res.admission_id);

                        // Update Read-only view
                        $('#display-course').text($('select[name="course_id"] option:selected').text());
                        $('#display-batch-code').text($('select[name="batch_id"] option:selected').data('code') || '-');
                        $('#display-batch-type').text(formData.batch_type);
                        $('#display-fees').text('₹' + formData.course_fees);
                        $('#display-payment-mode').text(formData.payment_mode);
                        
                        // Switch views
                        $('#admission-details-form').addClass('d-none');
                        $('#admission-readonly-view').removeClass('d-none');
                        $('#edit-admission-btn').removeClass('d-none');

                        // Unlock Fee Collection
                        $('#fee-collection-locked').addClass('d-none');
                        $('#fee-collection-unlocked').removeClass('d-none');
                        
                        // Update Payment Tabs
                        updatePaymentTabs(formData.payment_mode);
                    } else {
                        toastr.error(res.message || 'Error saving admission details.');
                        $btn.prop('disabled', false).html('Save Details');
                    }
                },
                error: function(xhr) {
                    let msg = 'Something went wrong.';
                    if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    toastr.error(msg);
                    $btn.prop('disabled', false).html('Save Details');
                }
            });
        });

        // --- Fee Collection Logic ---

        // Add Split
        $('#add-split-payment-btn').on('click', function(e) {
            e.preventDefault();
            let count = $('.split-item').length + 1;
            let html = `
                <div class="p-3 rounded-4 mb-3 bg-light border border-secondary border-opacity-10 split-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="badge bg-primary bg-opacity-10 text-primary px-3">Payment ${count}</div>
                        <button type="button" class="btn btn-sm btn-link text-danger remove-split-btn p-0"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Amount (₹)</label>
                            <input type="number" class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 split-amount" placeholder="e.g. 17500">
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Date</label>
                            <input type="date" class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 split-date">
                        </div>
                    </div>
                </div>
            `;
            $('#split-payment-containers').append(html);
        });

        $(document).on('click', '.remove-split-btn', function() {
            $(this).closest('.split-item').remove();
            // Re-index
            $('.split-item').each(function(idx) {
                $(this).find('.badge').text('Payment ' + (idx + 1));
            });
        });

        // Save Single Payment
        $('#save-single-payment-btn').on('click', function(e) {
            e.preventDefault();
            let admissionId = $('#admission_id_hidden').val();
            if (!admissionId) {
                toastr.error('Please save admission details first.');
                return;
            }

            let amount = $('#single-amount').val();
            let date = $('#single-date').val();

            if (!amount || !date) {
                toastr.error('Please enter amount and date.');
                return;
            }

            let $btn = $(this);
            $.ajax({
                url: `/fee`, // Warning: Make sure this route is available in your routes file
                type: 'POST',
                data: {
                    admission_id: admissionId,
                    type: 'single',
                    amount: amount,
                    payment_date: date,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                },
                success: function(res) {
                    if(res.status) {
                        toastr.success(res.message || 'Payment recorded successfully!');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        toastr.error(res.message || 'Error recording payment.');
                        $btn.prop('disabled', false).html('Save');
                    }
                },
                error: function(xhr) {
                    let msg = 'Something went wrong.';
                    if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    toastr.error(msg);
                    $btn.prop('disabled', false).html('Save');
                }
            });
        });

        // Save Split Payments
        $('#save-split-payments-btn').on('click', function(e) {
            e.preventDefault();
            let admissionId = $('#admission_id_hidden').val();
            if (!admissionId) {
                toastr.error('Please save admission details first.');
                return;
            }

            let payments = [];
            let isValid = true;

            $('.split-item').each(function() {
                let amount = $(this).find('.split-amount').val();
                let date = $(this).find('.split-date').val();
                
                if(!amount || !date) {
                    isValid = false;
                } else {
                    payments.push({ amount: amount, payment_date: date });
                }
            });

            if (!isValid || payments.length === 0) {
                toastr.error('Please fill in all amount and date fields for split payments.');
                return;
            }

            let $btn = $(this);
            $.ajax({
                url: `/fee`, // Warning: Make sure this route is available in your routes file
                type: 'POST',
                data: {
                    admission_id: admissionId,
                    type: 'split',
                    payments: payments,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                },
                success: function(res) {
                    if(res.status) {
                        toastr.success(res.message || 'Split payments recorded successfully!');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        toastr.error(res.message || 'Error recording payments.');
                        $btn.prop('disabled', false).text('Save All');
                    }
                },
                error: function(xhr) {
                    let msg = 'Something went wrong.';
                    if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    toastr.error(msg);
                    $btn.prop('disabled', false).text('Save All');
                }
            });
        });

        // Generate Link Modal
        $(document).on('click', '.generate-link-btn', function() {
            let paymentId = $(this).data('id');
            $('#generate_link_payment_id').val(paymentId);
            let modal = new bootstrap.Modal(document.getElementById('generateLinkModal'));
            modal.show();
        });

        $('#confirm-generate-link-btn').on('click', function() {
            let paymentId = $('#generate_link_payment_id').val();
            let $btn = $(this);
            $.ajax({
                url: `/payments/${paymentId}/generate-link`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Generating...');
                },
                success: function(res) {
                    if (res.status) {
                        toastr.success(res.message);
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        toastr.error(res.message);
                        $btn.prop('disabled', false).html('Yes, Generate');
                    }
                },
                error: function(xhr) {
                    let msg = 'Something went wrong.';
                    if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    toastr.error(msg);
                    $btn.prop('disabled', false).html('Yes, Generate');
                }
            });
        });

        // Send Mail Modal
        $(document).on('click', '.send-mail-btn', function() {
            let paymentId = $(this).data('id');
            $('#send_mail_payment_id').val(paymentId);
            let modal = new bootstrap.Modal(document.getElementById('sendMailModal'));
            modal.show();
        });

        $('#confirm-send-mail-btn').on('click', function() {
            let paymentId = $('#send_mail_payment_id').val();
            let $btn = $(this);
            $.ajax({
                url: `/payments/${paymentId}/send-link`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');
                },
                success: function(res) {
                    if (res.status) {
                        toastr.success(res.message);
                        setTimeout(() => bootstrap.Modal.getInstance(document.getElementById('sendMailModal')).hide(), 1000);
                    } else {
                        toastr.error(res.message);
                        $btn.prop('disabled', false).html('Yes, Send Mail');
                    }
                },
                error: function(xhr) {
                    let msg = 'Something went wrong.';
                    if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    toastr.error(msg);
                    $btn.prop('disabled', false).html('Yes, Send Mail');
                }
            });
        });

    });
</script>
