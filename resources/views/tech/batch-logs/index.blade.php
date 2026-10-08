@extends('layouts.master')

@section('pageTitle', 'Batch Topic Logs')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800">Batch Topic Logs</h1>
            <a href="{{ route('tech.batch-logs.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Log
            </a>
        </div>
    </div>



    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Batch</th>
                            <th>Module/Topic</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->date ? \Carbon\Carbon::parse($log->date)->format('d M Y') : $log->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="fw-bold">{{ $log->batch->batch_name ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $log->curriculum->title ?? 'N/A' }}</td>
                                <td>{{ $log->description ?: '-' }}</td>
                                <td>
                                    @if($log->status === 'Completed' || ($log->status === null && $log->is_completed))
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($log->status === 'In Progress')
                                        <span class="badge bg-warning text-dark">In Progress</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $log->status ?: 'Pending' }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('tech.batch-logs.edit', $log->id) }}" class="btn btn-sm btn-light">
                                        <i class="fas fa-edit text-muted"></i>
                                    </a>
                                    <form action="{{ route('tech.batch-logs.destroy', $log->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this log?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No batch topic logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
