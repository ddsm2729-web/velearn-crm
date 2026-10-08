@extends('layouts.header')
@section('pageTitle')
    {{ __('Unassigned Calls History') }} | {{ config('app.name') }}
@endsection

@push('css')
    <style>
        .staff-header {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
        }
        .staff-avatar {
            width: 64px;
            height: 64px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            margin-right: 1.5rem;
        }
        .table-card {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
    </style>
@endpush

@section('section')
    <div class="container-fluid p-0">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('calls.history') }}" class="btn btn-link text-decoration-none p-0 text-muted">
                <i class="align-middle" data-feather="arrow-left"></i> Back to Call History
            </a>
        </div>

        <!-- Staff Header -->
        <div class="staff-header">
            <div class="staff-avatar bg-primary">S</div>
            <div>
                <h3 class="mb-1 fw-bold">System / Unassigned</h3>
                <p class="text-muted mb-0">
                    <span class="badge bg-primary bg-opacity-10 text-primary">SYSTEM</span>
                </p>
            </div>
            <div class="ms-auto text-end">
                <h4 class="mb-0 fw-bold">{{ $callLogs->total() }}</h4>
                <p class="text-muted mb-0 small text-uppercase">Total Calls</p>
            </div>
        </div>

        <!-- Call Logs Table -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th>CONTACT</th>
                            <th>PHONE</th>
                            <th>SOURCE</th>
                            <th>DIRECTION</th>
                            <th>DURATION</th>
                            <th>OUTCOME</th>
                            <th>TIME</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($callLogs as $call)
                        <tr>
                            <td>{{ $call->contact ? $call->contact->first_name . ' ' . $call->contact->last_name : ($call->lead->name ?? 'Unknown') }}</td>
                            <td><i class="align-middle me-1 text-muted" data-feather="phone"></i> {{ $call->caller_number }}</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success">MyOperator</span></td>
                            <td>
                                @if($call->direction == 'incoming')
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="align-middle" data-feather="arrow-down-left"></i> Incoming</span>
                                @else
                                <span class="badge bg-primary bg-opacity-10 text-primary"><i class="align-middle" data-feather="arrow-up-right"></i> Outgoing</span>
                                @endif
                            </td>
                            <td>{{ gmdate("i:s", $call->call_duration) }}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ strtoupper($call->lead_tag ?? $call->status ?? 'NEW LEAD') }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($call->call_start)->format('M d, h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No unassigned call logs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($callLogs->hasPages())
            <div class="p-3 border-top">
                {{ $callLogs->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
