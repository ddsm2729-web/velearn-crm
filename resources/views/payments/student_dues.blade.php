@extends('layouts.header')

@section('pageTitle', 'Student Dues')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/payments-student_dues.css') }}?v={{ time() }}">

@endpush

@section('section')
    <div>
        {{-- â”€â”€ Page Header â”€â”€ --}}
        <div class="page-header">
            <div class="header-title">
                <h3>Student Dues</h3>
                <span class="header-subtitle">
                    <i class="fas fa-exclamation-triangle"></i>
                    Pending fee installments
                </span>
            </div>
        </div>

        {{-- â”€â”€ Stats Row â”€â”€ --}}
        @php
            $total = $payments->total() ?? 0;
            $totalAmt = $payments->sum('amount') ?? 0;
            $overdue = $payments->filter(fn($p) => $p->payment_date && $p->payment_date->isPast())->count();
        @endphp
        <div class="dues-stats-row">
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $total }}</div>
                    <div class="stat-label">Total Pending</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">₹{{ number_format($totalAmt) }}</div>
                    <div class="stat-label">Due Amount</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $overdue }}</div>
                    <div class="stat-label">Overdue</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $total - $overdue }}</div>
                    <div class="stat-label">On Schedule</div>
                </div>
            </div>
        </div>

        {{-- â”€â”€ Main Table Card â”€â”€ --}}
        <div class="payments-table-card">

            {{-- Toolbar --}}
            <div class="table-header">
                <h5>Pending Payments</h5>
                <form method="GET" action="{{ route('student-dues.index') }}" class="search-form">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" class="search-input"
                            placeholder="Search by name / phone..." id="dues-search-input">
                    </div>
                    <button type="submit" class="btn-search" id="dues-search-btn">
                        <i class="fas fa-search"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('student-dues.index') }}" class="btn btn-outline-danger" style="border-radius: 10px; padding: 7.5px 16px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table mb-0" id="dues-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course / Batch</th>
                            <th>Phone</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            @php
                                $contact = optional(optional($payment->admission)->contact);
                                $course = optional(optional($payment->admission)->course);
                                $batch = optional(optional($payment->admission)->batch);
                                $colors = ['#4f46e5', '#0891b2', '#059669', '#d97706', '#dc2626'];
                                $color = $colors[($payment->id ?? 0) % 5];
                                $isOverdue = $payment->payment_date && $payment->payment_date->isPast();
                                $linkAlreadySent = !empty($payment->payment_link_sent_at);
                            @endphp
                            <tr id="payment-row-{{ $payment->id }}">

                                {{-- Student --}}
                                <td>
                                    <div class="student-profile">
                                        <div class="avatar-circle" style="background: {{ $color }};">
                                            {{ strtoupper(substr($contact->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $contact->name ?? 'â€”' }}</div>
                                            <span class="text-muted smaller">{{ $contact->email ?? '' }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Course / Batch --}}
                                <td>
                                    <div class="fw-semibold small">{{ $course->title ?? 'â€”' }}</div>
                                    <span class="text-muted smaller">
                                        <i class="fas fa-layer-group" style="font-size:.65rem;"></i>
                                        {{ $batch->batch_name ?? 'No batch' }}
                                    </span>
                                </td>

                                {{-- Phone --}}
                                <td>
                                    <span style="font-size:.82rem; color: #374151;">
                                        {{ $contact->caller_number ?? $contact->phone ?? 'â€”' }}
                                    </span>
                                </td>

                                {{-- Due Date --}}
                                <td>
                                    <div class="fw-semibold small"
                                        style="color: {{ $isOverdue ? 'var(--sd-danger)' : 'var(--sd-text)' }}">
                                        {{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : 'â€”' }}
                                    </div>
                                    @if($isOverdue)
                                        <span class="badge bg-danger mt-1">
                                            <i class="fas fa-exclamation-circle"></i> Overdue
                                        </span>
                                    @endif
                                </td>

                                {{-- Amount --}}
                                <td>
                                    <div class="amount-text">₹{{ number_format($payment->amount) }}</div>
                                    <span class="smaller text-muted">#PAY-{{ $payment->id }}</span>
                                </td>

                                {{-- Status --}}
                                <td>
                                    <span class="status-pill pill-pending" id="status-badge-{{ $payment->id }}">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                    @if($linkAlreadySent)
                                        <div class="smaller text-muted mt-1" style="font-size:10px;">
                                            <i class="fas fa-link"></i> Link sent
                                            {{ $payment->payment_link_sent_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
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
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <h4>No pending dues!</h4>
                                        <p class="text-muted">All student payments are up to date.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($payments->hasPages())
                <div class="pagination-wrapper">
                    {{ $payments->links() }}
                </div>
            @endif

        </div>{{-- /.payments-table-card --}}
    </div>{{-- /page --}}

    {{-- Shared Razorpay modal (existing direct checkout) --}}
    @include('payments.partials.razorpay_modal')

    @push('script')
                        });
                    }
                };
            })();
        </script>
    @endpush
@endsection