@extends('layouts.header')

@section('pageTitle', 'Payment Tracker')

@section('section')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payments <span class="text-muted small fw-normal ms-2">/ Payment Tracker</span></h1>
    </div>

    <!-- Metrics Cards -->
    <div class="row g-3 mb-4" id="metricsContainer">
        <!-- Collected Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-left: 4px solid #10b981 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <small class="text-success text-uppercase fw-bold mb-0">Collected</small>
                        <div class="icon-circle bg-white text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">₹{{ number_format($collected) }}</h4>
                </div>
            </div>
        </div>

        <!-- Pending Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-left: 4px solid #f59e0b !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <small class="text-warning text-uppercase fw-bold mb-0">Pending</small>
                        <div class="icon-circle bg-white text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">₹{{ number_format($pending) }}</h4>
                </div>
            </div>
        </div>

        <!-- Failed Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-left: 4px solid #ef4444 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <small class="text-danger text-uppercase fw-bold mb-0">Failed</small>
                        <div class="icon-circle bg-white text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">₹{{ number_format($failed) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('payments.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold">Search Student</label>
                        <input type="text" class="form-control form-control-sm" name="search" placeholder="Name or phone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-bold">Status</label>
                        <select class="form-select form-select-sm" name="status">
                            <option value="">All Statuses</option>
                            <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold">Course</label>
                        <select class="form-select form-select-sm" name="course_id">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted fw-bold">Date Range</label>
                        <div class="input-group input-group-sm">
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                            <span class="input-group-text bg-light border-start-0 border-end-0 text-muted">to</span>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <!-- Buttons removed for AJAX auto-filter -->
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card border-0 shadow-sm rounded-4" id="tableContainer">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h5 class="fw-bold mb-0"><i class="fas fa-list-alt text-primary me-2"></i> Recent Transactions</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 mt-3">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 px-4 border-0">Student</th>
                            <th class="text-uppercase text-muted small fw-bold py-3 border-0">Amount</th>
                            <th class="text-uppercase text-muted small fw-bold py-3 border-0">Date</th>
                            <th class="text-uppercase text-muted small fw-bold py-3 border-0">Method</th>
                            <th class="text-uppercase text-muted small fw-bold py-3 border-0">Status</th>
                            <th class="text-uppercase text-muted small fw-bold py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    @php
                                        $name = $payment->admission->contact->name ?? $payment->admission->lead->name ?? 'Student';
                                        $initials = collect(explode(' ', $name))->map(function($segment) { return strtoupper(substr($segment, 0, 1)); })->take(2)->implode('');
                                        $colors = ['#4f46e5', '#db2777', '#ea580c', '#16a34a', '#0284c7'];
                                        $bgColor = $colors[strlen($name) % count($colors)];
                                    @endphp
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: {{ $bgColor }}; font-weight: 600;">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $name }}</h6>
                                        <small class="text-muted">{{ $payment->admission->course->title ?? 'Course' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold text-dark py-3">
                                ₹{{ number_format($payment->amount) }}
                            </td>
                            <td class="py-3 text-muted">
                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M') }}
                            </td>
                            <td class="py-3 text-muted">
                                {{ strtoupper($payment->payment_method ?? 'UPI') }}
                            </td>
                            <td class="py-3">
                                @if(strtolower($payment->status) === 'paid')
                                    <span class="badge bg-soft-success text-success rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fas fa-circle me-1" style="font-size: 8px;"></i> Paid
                                    </span>
                                @elseif(strtolower($payment->status) === 'pending' || strtolower($payment->status) === 'unpaid')
                                    <span class="badge bg-soft-warning text-warning rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fas fa-circle me-1" style="font-size: 8px;"></i> Pending
                                    </span>
                                @else
                                    <span class="badge bg-soft-danger text-danger rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fas fa-circle me-1" style="font-size: 8px;"></i> {{ ucfirst($payment->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                @if(strtolower($payment->status) === 'paid')
                                    <a href="{{ route('payments.invoice', $payment->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2" target="_blank" title="Download Invoice">
                                        <i class="fas fa-file-invoice me-1"></i> Receipt
                                    </a>
                                @else
                                    @if($payment->payment_link_url && $payment->payment_link_expires_at && \Carbon\Carbon::parse($payment->payment_link_expires_at)->isFuture())
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ $payment->payment_link_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Open Link">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-warning rounded-pill px-3 send-link-btn" data-id="{{ $payment->id }}" title="Send Link via Email">
                                                <i class="fas fa-paper-plane me-1"></i> Send
                                            </button>
                                        </div>
                                    @else
                                        <button class="btn btn-sm btn-outline-info rounded-pill px-3 generate-link-btn" data-id="{{ $payment->id }}">
                                            <i class="fas fa-link me-1"></i> {{ $payment->payment_link_url ? 'Regenerate' : 'Generate' }}
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                <h5>No Payments Found</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-end">
                    {{ $payments->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-success { background-color: rgba(16, 185, 129, 0.1) !important; }
    .text-success { color: #10b981 !important; }
    .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1) !important; }
    .text-warning { color: #f59e0b !important; }
    .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1) !important; }
    .text-danger { color: #ef4444 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
</style>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        attachTableListeners();

        let filterTimeout;
        const filterForm = document.getElementById('filterForm');
        const filterInputs = filterForm.querySelectorAll('input, select');
        
        function applyFilters() {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData).toString();
            const url = filterForm.action + '?' + params;

            document.getElementById('metricsContainer').style.opacity = '0.5';
            document.getElementById('tableContainer').style.opacity = '0.5';

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newMetrics = doc.getElementById('metricsContainer');
                    const newTable = doc.getElementById('tableContainer');
                    
                    if (newMetrics && newTable) {
                        document.getElementById('metricsContainer').innerHTML = newMetrics.innerHTML;
                        document.getElementById('tableContainer').innerHTML = newTable.innerHTML;
                        attachTableListeners();
                    }
                    
                    document.getElementById('metricsContainer').style.opacity = '1';
                    document.getElementById('tableContainer').style.opacity = '1';
                    window.history.pushState({}, '', url);
                });
        }

        filterInputs.forEach(input => {
            if(input.tagName === 'SELECT' || input.type === 'date') {
                input.addEventListener('change', applyFilters);
            } else {
                input.addEventListener('keyup', () => {
                    clearTimeout(filterTimeout);
                    filterTimeout = setTimeout(applyFilters, 500);
                });
            }
        });
    });

    function attachTableListeners() {
        const generateBtns = document.querySelectorAll('.generate-link-btn');
        generateBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const paymentId = this.getAttribute('data-id');
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> ...';
                this.disabled = true;

                fetch(`/payments/${paymentId}/generate-link`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        toastr.success(data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        toastr.error(data.message || 'Error generating link');
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An error occurred');
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
            });
        });

        const sendBtns = document.querySelectorAll('.send-link-btn');
        sendBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const paymentId = this.getAttribute('data-id');
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> ...';
                this.disabled = true;

                fetch(`/payments/${paymentId}/send-link`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        toastr.success(data.message);
                        this.innerHTML = '<i class="fas fa-check me-1"></i> Sent';
                    } else {
                        toastr.error(data.message || 'Error sending link');
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An error occurred');
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
            });
        });
    }
</script>
@endpush
@endsection