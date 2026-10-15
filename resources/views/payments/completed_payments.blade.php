@extends('layouts.header')

@section('pageTitle', 'Completed Payments')

@push('css')
<style>
    .payments-page {
        padding: 24px;
        background-color: #f8fafc;
        min-height: calc(100vh - 60px);
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    
    .header-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .header-title p {
        color: #64748b;
        font-size: 0.875rem;
        margin: 0;
    }
    
    .btn-export {
        background: white;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 9999px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }
    
    .btn-export:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }
    
    .payments-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        background-color: #ffffff;
    }
    
    .table-header h5 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #334155;
    }
    
    .search-input {
        border-radius: 9999px;
        padding: 8px 16px;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
        transition: border-color 0.2s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .table th {
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        letter-spacing: 0.05em;
        padding: 12px 24px;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .table td {
        padding: 16px 24px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    
    .student-profile {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    
    .amount-text {
        font-weight: 700;
        font-size: 1.05rem;
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: #dcfce7;
        color: #166534;
    }
    
    .btn-premium {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    
    .btn-success-soft {
        background-color: #f0fdf4;
        color: #16a34a;
    }
    
    .btn-success-soft:hover {
        background-color: #dcfce7;
        color: #15803d;
    }
    
    .btn-blue-soft {
        background-color: #eff6ff;
        color: #2563eb;
    }
    
    .btn-blue-soft:hover {
        background-color: #dbeafe;
        color: #1d4ed8;
    }
    
    .empty-state {
        text-align: center;
        padding: 48px 24px;
    }
    
    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }
    
    .empty-state h4 {
        color: #475569;
        font-weight: 600;
    }
    
    .method-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
    }
</style>
@endpush

@section('section')
<div class="payments-page">
    <div class="page-header">
        <div class="header-title">
            <h3>Completed Payments</h3>
            <p><i class="fas fa-check-double text-success me-1"></i> Successfully collected fee records</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-white border shadow-sm rounded-pill px-4 btn-sm fw-bold">
                <i class="fas fa-download me-2"></i>Export
            </button>
        </div>
    </div>

    <div class="payments-table-card">
        <div class="table-header">
            <h5>Paid Transactions</h5>
            <form method="GET" action="{{ route('completed-payments.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control form-control-sm rounded-pill px-3"
                    placeholder="Search by name..." style="width:250px;">
                <button class="btn btn-primary btn-sm rounded-pill px-3">Search</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Amount</th>
                        <th>Paid On</th>
                        <th>Method</th>
                        <th>Razorpay Payment ID</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        @php
                            $contact = optional(optional($payment->admission)->contact);
                            $course  = optional(optional($payment->admission)->course);
                            $colors  = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'];
                            $color   = $colors[($payment->id ?? 0) % 5];
                        @endphp
                        <tr>
                            <td>
                                <div class="student-profile">
                                    <div class="avatar-circle" style="background: {{ $color }}">
                                        {{ strtoupper(substr($contact->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $contact->name ?? 'â€”' }}</div>
                                        <div class="text-muted smaller">{{ $contact->phone ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="small fw-semibold">{{ $course->title ?? 'â€”' }}</td>
                            <td>
                                <div class="amount-text text-success">₹{{ number_format($payment->amount) }}</div>
                                <div class="smaller text-muted">#PAY-{{ $payment->id }}</div>
                            </td>
                            <td class="small fw-semibold">
                                {{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : 'â€”' }}
                            </td>
                            <td>
                                @if($payment->payment_method)
                                    <span class="method-badge">
                                        {{ strtoupper($payment->payment_method) }}
                                    </span>
                                @else
                                    <span class="text-muted small">â€”</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->razorpay_payment_id)
                                    <code class="small text-muted" style="word-break:break-all;">
                                        {{ $payment->razorpay_payment_id }}
                                    </code>
                                @else
                                    <span class="text-muted small">Manual / Offline</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge">
                                    <i class="fas fa-check-circle"></i> Paid
                                </span>
                            </td>
                            <td class="text-end" style="white-space: nowrap;">
                                <a href="{{ route('payments.invoice', $payment->id) }}" class="btn-premium btn-success-soft" target="_blank" title="Download Invoice">
                                    <i class="fas fa-file-invoice"></i> Receipt
                                </a>
                                {{-- <a href="{{ route('batch-allocation.index') }}" class="btn-premium btn-blue-soft">
                                    <i class="fas fa-user-plus"></i> Allocate
                                </a> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-receipt"></i>
                                    <h4>No completed payments yet</h4>
                                    <p class="text-muted">Paid transactions will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="p-4 border-top">{{ $payments->links() }}</div>
        @endif
    </div>
</div>
@endsection

