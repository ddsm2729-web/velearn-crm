{{--
Shared Razorpay Checkout Modal Partial
@include('payments.partials.razorpay_modal')

Requires: .collect-payment-btn elements with data attributes:
- data-payment-id : local Payment model ID
- data-amount : formatted amount string (e.g. "20,000.00")
- data-student : student name
- data-course : course name
--}}

@push('css')

@endpush

<div class="rzp-modal-overlay" id="rzpModalOverlay">
    <div class="rzp-modal">
        <div class="rzp-modal-header">
            <div class="rzp-logo"><i class="fas fa-shield-alt"></i></div>
            <div>
                <h5>Secure Payment</h5>
                <p>Powered by Razorpay · 256-bit SSL</p>
            </div>
        </div>
        <div class="rzp-modal-body">
            <div class="rzp-info-row">
                <span class="label"><i class="fas fa-user me-1"></i> Student</span>
                <span class="value" id="rzpStudentName">—</span>
            </div>
            <div class="rzp-info-row">
                <span class="label"><i class="fas fa-book me-1"></i> Course</span>
                <span class="value" id="rzpCourseName">—</span>
            </div>
            <div class="rzp-amount-badge">
                <div class="amount-label">Payment Amount</div>
                <div class="amount-value" id="rzpAmount">₹0</div>
            </div>
            <button class="btn-rzp-pay" id="rzpPayBtn">
                <i class="fas fa-lock"></i>
                <span id="rzpPayBtnText">Pay Securely via Razorpay</span>
            </button>
            <button class="btn-rzp-cancel" id="rzpCancelBtn">Cancel</button>
            <div class="rzp-secure-note">
                <i class="fas fa-shield-alt" style="color:#10b981"></i>
                All transactions are 100% secure and encrypted
            </div>
        </div>
    </div>
</div>

@push('script')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        (function () {
            'use strict';
            const overlay = document.getElementById('rzpModalOverlay');
            const cancelBtn = document.getElementById('rzpCancelBtn');
            const payBtn = document.getElementById('rzpPayBtn');
            const payBtnText = document.getElementById('rzpPayBtnText');
            let currentPaymentId = null;

            document.querySelectorAll('.collect-payment-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    currentPaymentId = this.dataset.paymentId;
                    document.getElementById('rzpStudentName').textContent = this.dataset.student;
                    document.getElementById('rzpCourseName').textContent = this.dataset.course;
                    document.getElementById('rzpAmount').textContent = '₹' + this.dataset.amount;
                    overlay.classList.add('show');
                });
            });

            cancelBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal(); });

            function closeModal() {
                overlay.classList.remove('show');
                currentPaymentId = null;
                setPayBtnState(false);
            }

            payBtn.addEventListener('click', function () {
                if (!currentPaymentId) return;
                setPayBtnState(true);

                fetch('{{ route("payments.create-order") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ payment_id: currentPaymentId }),
                })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) { showToast('error', data.message || 'Could not create order.'); setPayBtnState(false); return; }

                        closeModal();
                        const rzp = new Razorpay({
                            key: data.key_id,
                            amount: data.amount,
                            currency: data.currency || 'INR',
                            order_id: data.order_id,
                            name: 'VeLearn',
                            description: data.description || 'Course Fee Payment',
                            prefill: { name: data.student_name },
                            theme: { color: '#1a56db' },
                            handler: function (response) {
                                verifyPayment(response.razorpay_order_id, response.razorpay_payment_id, response.razorpay_signature, data.payment_id);
                            },
                            modal: { ondismiss: function () { setPayBtnState(false); showToast('warning', 'Payment cancelled.'); } }
                        });
                        rzp.open();
                    })
                    .catch(() => { showToast('error', 'Network error. Please try again.'); setPayBtnState(false); });
            });

            function verifyPayment(orderId, paymentId, signature, localPaymentId) {
                fetch('{{ route("payments.verify") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ razorpay_order_id: orderId, razorpay_payment_id: paymentId, razorpay_signature: signature, payment_id: localPaymentId }),
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', '✅ Payment collected successfully!');
                            updatePaymentRow(localPaymentId);
                        } else {
                            showToast('error', data.message || 'Payment verification failed.');
                        }
                    })
                    .catch(() => { showToast('error', 'Could not verify payment. Check payment records.'); });
            }

            function updatePaymentRow(paymentId) {
                const badge = document.getElementById('status-badge-' + paymentId);
                if (badge) { badge.className = 'status-pill pill-paid'; badge.innerHTML = '<i class="fas fa-check-circle"></i> Paid'; }
                const btn = document.querySelector('.collect-payment-btn[data-payment-id="' + paymentId + '"]');
                if (btn) { btn.closest('td').innerHTML = '<span class="badge bg-success">Collected</span>'; }
            }

            function setPayBtnState(loading) {
                payBtn.disabled = loading;
                payBtnText.textContent = loading ? 'Creating order…' : 'Pay Securely via Razorpay';
            }

            function showToast(type, message) {
                if (typeof toastr !== 'undefined') { toastr[type](message); } else { alert(message); }
            }
        })();
    </script>
@endpush