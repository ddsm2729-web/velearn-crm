@extends('layouts.header')
@section('pageTitle')
    {{ __('Staffs List') }} | {{ config('app.name') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
    
@endpush

@section('section')
    <div class="container-fluid p-0">

        <!-- ── Page Banner / Header ── -->
        <div class="staff-page-header">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h1 class="staff-title">
                        <div class="staff-title-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        Staff Management
                    </h1>
                    <div class="staff-subtitle">
                        Manage staff profiles, permissions, roles, and account statuses
                    </div>
                </div>

                @can('Staff Create')
                    <div>
                        <a href="{{ route('staffs.create') }}" class="btn-add-staff">
                            <i class="fas fa-user-plus"></i> Add New Staff
                        </a>
                    </div>
                @endcan
            </div>
        </div>

        <!-- ── Top Metrics Row ── -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="staff-stat-card">
                    <div class="stat-icon-wrapper stat-icon-indigo">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total Staff</div>
                        <div class="stat-number">{{ $stats['total'] ?? count($staffs) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="staff-stat-card">
                    <div class="stat-icon-wrapper stat-icon-emerald">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <div class="stat-label">Active Staff</div>
                        <div class="stat-number">{{ $stats['active'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="staff-stat-card">
                    <div class="stat-icon-wrapper stat-icon-rose">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <div>
                        <div class="stat-label">Inactive Staff</div>
                        <div class="stat-number">{{ $stats['inactive'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="staff-stat-card">
                    <div class="stat-icon-wrapper stat-icon-sky">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <div class="stat-label">System Roles</div>
                        <div class="stat-number">{{ $stats['roles'] ?? count($roles) }}</div>
                    </div>
                </div>
            </div>
        </div>

        @can('Staff List')
            <!-- ── Search & Filter Card ── -->
            <div class="staff-filter-card">
                <form method="GET" action="{{ route('staffs.index') }}" class="row g-3 align-items-end">
                    <!-- Search Field -->
                    <div class="col-12 col-md-5 col-lg-5">
                        <label class="filter-field-label">Search Staff Member</label>
                        <div class="custom-search-group">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="search" class="custom-search-input"
                                value="{{ request('search') }}" placeholder="Search by name, email or phone..." autocomplete="off">
                        </div>
                    </div>

                    <!-- Role Filter -->
                    <div class="col-12 col-md-4 col-lg-4">
                        <label class="filter-field-label">Filter by Role</label>
                        <select name="role_id" id="role_id" class="select2 form-control w-100">
                            <option value="" {{ request('role_id') ? '' : 'selected' }}>All Staff Roles</option>
                            @foreach ($roles as $rl)
                                <option value="{{ $rl->id }}"
                                    {{ request('role_id') == $rl->id ? 'selected' : '' }}>
                                    {{ $rl->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-apply-filter flex-grow-1">
                                <i class="fas fa-filter me-1"></i> Apply Filter
                            </button>
                            <a href="{{ route('staffs.index') }}" class="btn-reset-filter" title="Reset Filters">
                                <i class="fas fa-redo-alt"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ── Staff List Table Card ── -->
            <div class="staff-table-card">
                <div class="table-responsive">
                    <table class="table staff-table align-middle" id="stickyTableWrapper">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 70px;">#</th>
                                <th>Staff Member</th>
                                <th>Contact Information</th>
                                <th>Gender</th>
                                <th>Status</th>
                                @canany(['Staff Edit', 'Staff Delete'])
                                    <th class="text-end pe-4">Actions</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $avatarGradients = [
                                    'linear-gradient(135deg, #6366f1, #4f46e5)',
                                    'linear-gradient(135deg, #3b82f6, #1d4ed8)',
                                    'linear-gradient(135deg, #10b981, #059669)',
                                    'linear-gradient(135deg, #f59e0b, #d97706)',
                                    'linear-gradient(135deg, #ec4899, #db2777)',
                                    'linear-gradient(135deg, #8b5cf6, #7c3aed)',
                                    'linear-gradient(135deg, #06b6d4, #0891b2)',
                                ];
                            @endphp

                            @if (count($staffs) > 0)
                                @foreach ($staffs as $key => $ms)
                                    @php
                                        $initials = strtoupper(substr($ms->first_name, 0, 1) . substr($ms->last_name, 0, 1));
                                        $gradIndex = abs(crc32($ms->first_name . $ms->id)) % count($avatarGradients);
                                        $gradient = $avatarGradients[$gradIndex];
                                    @endphp
                                    <tr>
                                        <!-- Index -->
                                        <td class="ps-4 fw-bold text-muted">
                                            {{ ($staffs->currentPage() - 1) * $staffs->perPage() + $key + 1 }}
                                        </td>

                                        <!-- Staff Information -->
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="staff-avatar" style="background: {{ $gradient }};">
                                                    {{ $initials }}
                                                </div>
                                                <div>
                                                    <div class="staff-name">
                                                        {{ $ms->first_name . ' ' . $ms->last_name }}
                                                    </div>
                                                    <div class="staff-role-badge">
                                                        <i class="fas fa-user-tag me-1" style="font-size: 10px;"></i>{{ $ms->role->name ?? 'Unassigned' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Contact Details -->
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @if($ms->primary_phone)
                                                    <div class="staff-contact-item">
                                                        <i class="fas fa-phone-alt"></i>
                                                        <span class="fw-semibold">{{ $ms->primary_phone }}</span>
                                                    </div>
                                                @endif
                                                @if($ms->email)
                                                    <div class="staff-contact-item">
                                                        <i class="far fa-envelope text-primary"></i>
                                                        <span class="text-secondary">{{ $ms->email }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Gender -->
                                        <td>
                                            <span class="text-secondary small fw-medium">
                                                <i class="fas fa-venus-mars me-1 text-muted"></i>{{ getGenderTitle((int) $ms->gender) }}
                                            </span>
                                        </td>

                                        <!-- Status with Toggle -->
                                        <td>
                                            <div class="status-badge-container">
                                                @if($ms->status == 1)
                                                    <span class="status-pill status-pill-active">
                                                        <span class="pulse-dot pulse-dot-active"></span> Active
                                                    </span>
                                                @else
                                                    <span class="status-pill status-pill-inactive">
                                                        <span class="pulse-dot pulse-dot-inactive"></span> Inactive
                                                    </span>
                                                @endif

                                                @can('Staff Status')
                                                    <span data-href="{{ route('staffs.status') }}"
                                                        data-authid="{{ encrypt($ms->auth_id) }}"
                                                        data-id="{{ encrypt($ms->id) }}" 
                                                        data-change-status="{{ $ms->status == 1 ? 0 : 1 }}"
                                                        class="status-staff ms-2 cursor-pointer"
                                                        title="{{ $ms->status == 1 ? 'Click to Deactivate' : 'Click to Activate' }} Staff">
                                                        <i class="fas {{ $ms->status == 1 ? 'fa-toggle-on text-success' : 'fa-toggle-off text-muted opacity-50' }} fa-xl"></i>
                                                    </span>
                                                @endcan
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        @canany(['Staff Edit', 'Staff Delete'])
                                            <td class="text-end pe-4">
                                                <div class="action-btn-group">
                                                    @can('Staff Edit')
                                                        <a href="{{ route('staffs.edit', encrypt($ms->id)) }}"
                                                            class="btn-action-icon btn-action-edit" title="Edit Staff Details">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                    @endcan

                                                    @can('Staff Delete')
                                                        <button type="button" 
                                                            class="btn-action-icon btn-action-delete delete-staff" 
                                                            data-href="{{ route('staffs.destroy', encrypt($ms->id)) }}"
                                                            data-authid="{{ encrypt($ms->auth_id) }}"
                                                            title="Delete Staff Member">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        @endcanany
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <div style="width: 70px; height: 70px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                                <i class="fas fa-users-slash text-muted" style="font-size: 28px;"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">No Staff Members Found</h6>
                                            <p class="text-muted small mb-3">No records matched your search or filter criteria.</p>
                                            <a href="{{ route('staffs.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                                                Clear Search Filters
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if($staffs->hasPages())
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top" style="background: #f8fafc; border-radius: 0 0 16px 16px;">
                        <div class="small text-muted fw-medium">
                            Showing <strong>{{ $staffs->firstItem() }}</strong> to <strong>{{ $staffs->lastItem() }}</strong> of <strong>{{ $staffs->total() }}</strong> staff members
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0 gap-1" style="gap: 4px;">
                                {{-- Previous --}}
                                @if ($staffs->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link" style="border-radius: 8px; border: 1px solid #e2e8f0; color: #94a3b8; padding: 6px 12px; font-size: 13px;">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $staffs->previousPageUrl() }}" style="border-radius: 8px; border: 1px solid #e2e8f0; color: #475569; padding: 6px 12px; font-size: 13px; text-decoration: none;">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Page Numbers --}}
                                @foreach ($staffs->getUrlRange(1, $staffs->lastPage()) as $page => $url)
                                    @if ($page == $staffs->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link" style="border-radius: 8px; background: #4f46e5; border-color: #4f46e5; color: #fff; padding: 6px 11px; font-size: 13px; font-weight: 600;">
                                                {{ $page }}
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}" style="border-radius: 8px; border: 1px solid #e2e8f0; color: #475569; padding: 6px 11px; font-size: 13px; text-decoration: none;">
                                                {{ $page }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next --}}
                                @if ($staffs->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $staffs->nextPageUrl() }}" style="border-radius: 8px; border: 1px solid #e2e8f0; color: #475569; padding: 6px 12px; font-size: 13px; text-decoration: none;">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link" style="border-radius: 8px; border: 1px solid #e2e8f0; color: #94a3b8; padding: 6px 12px; font-size: 13px;">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
        @endcan
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/staff.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('body').find('.select2').select2({
                minimumResultsForSearch: 5
            });
        });
    </script>
@endpush
