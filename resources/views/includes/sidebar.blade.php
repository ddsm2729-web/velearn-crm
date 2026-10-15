<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('/') }}">
        </a>

        <ul class="sidebar-nav">

            {{-- Dashboard --}}
            @can('Dashboard')
                <li class="sidebar-item @if (Request::is('/') || Request::is('*/dashboard')) active @endif">
                    <a class="sidebar-link" href="{{ route('/') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#4F46E5"
                            viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z" />
                        </svg>
                        <span class="align-middle">Dashboard</span>
                    </a>
                </li>
            @endcan

            @can('Staff List')
                <li class="sidebar-item @if (Request::is('staffs*')) active @endif">
                    <a class="sidebar-link" href="{{ route('staffs.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            fill="#059669" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3z" />
                            <path d="M8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3z" />
                            <path d="M8 13c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13z" />
                            <path
                                d="M16 13c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                        <span class="align-middle">Manage Staff</span>
                    </a>
                </li>
            @endcan

            @can('Manage Contacts')
                <li class="sidebar-item @if (Request::routeIs('contacts.*') || Route::currentRouteName() == 'contacts.info') active @endif">
                    <a class="sidebar-link" href="{{ route('contacts.info') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            fill="#D97706" viewBox="0 0 24 24">
                            <!-- Contact Book / Address Book Icon -->
                            <path
                                d="M19 2H9C7.9 2 7 2.9 7 4v16c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H9V4h10v16zM5 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h1V4z" />
                            <circle cx="12" cy="10" r="2" />
                            <path d="M12 13c-2.33 0-4 1.17-4 2v1h8v-1c0-0.83-1.67-2-4-2z" />
                        </svg>
                        <span class="align-middle">Contacts</span>
                    </a>
                </li>
            @endcan

            @canany(['Doubt Query Lead', 'Doubt Query Followup', 'Doubt Allocation', 'Certificates'])
                <li class="sidebar-header">Support Management</li>
                @can('Doubt Query Lead')
                    <li class="sidebar-item {{ Request::is('doubt-query-leads*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('doubt-query-leads.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#2563EB" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                            </svg>
                            <span class="align-middle">
                                @if (auth()->user()->hasRole('Support Executive'))
                                    Lead
                                @else
                                    Doubt Query Lead
                                @endif
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Doubt Allocation')
                    <li class="sidebar-item {{ Request::is('doubt-allocation*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('doubt-allocation.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#DC2626" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                            </svg>
                            <span class="align-middle">Doubt Allocation</span>
                        </a>
                    </li>
                @endcan

                @can('Certificates')
                    <li class="sidebar-item {{ Request::is('certificate-dispatch*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('certificates.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#0891B2" viewBox="0 0 24 24">
                                <path
                                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                            </svg>
                            <span class="align-middle">Certificates</span>
                        </a>
                    </li>
                @endcan
                @can('Doubt Query Followup')
                    <li class="sidebar-item {{ Request::is('doubt-query-followups*') ? 'active' : '' }}">
                        <a class="sidebar-link d-flex justify-content-between align-items-center"
                            href="{{ route('doubt-query-followups.index') }}">

                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    fill="#7C3AED" viewBox="0 0 24 24">

                                    <path
                                        d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm-8 8c0 1.57.46 3.03 1.24 4.26L6.7 14.8c-.45-.83-.7-1.79-.7-2.8 0-3.31 2.69-6 6-6V3l4 4-4 4V8c-3.31 0-6 2.69-6 6z" />
                                </svg>

                                <span class="align-middle ms-2">
                                    @if (auth()->user()->hasRole('Support Executive'))
                                        Follow-up
                                    @else
                                        Doubt Query Followup
                                    @endif
                                </span>
                            </div>

                            <div class="d-flex align-items-center gap-2">

                                <span class="badge bg-success">
                                    {{ $followupCount ?? 0 }}
                                </span>

                                @if (isset($missedFollowupCount) && $missedFollowupCount > 0)
                                    <span class="d-inline-flex align-items-center">
                                        <span
                                            style="
                                                display:inline-block;
                                                width:8px;
                                                height:8px;
                                                background-color:#ef4444;
                                                border-radius:50%;
                                                box-shadow:0 0 0 rgba(239,68,68,0.4);
                                                animation:pulse-red 2s infinite;
                                            "></span>
                                    </span>
                                @endif

                            </div>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- Calls & Leads --}}
            @canany(['Inbound Call List', 'Outbound Call List', 'Lead List', 'Follow-up List', 'Schedule Demo',
                'Schedule List', 'Demo Tracking'])
                <li class="sidebar-header">Calls & Leads</li>
                @can('Inbound Call List')
                    <li class="sidebar-item {{ Request::routeIs('calls.inbound') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('calls.inbound') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#EA580C" viewBox="0 0 24 24">
                                <path
                                    d="M20 15.5c-1.25 0-2.45-.2-3.57-.57a1.02 1.02 0 0 0-1.02.24l-2.2 2.2a15.045 15.045 0 0 1-6.59-6.59l2.2-2.21a.96.96 0 0 0 .25-1.02c-.37-1.12-.57-2.32-.57-3.57 0-.55-.45-1-1-1H3.5c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM19 12h2c0-4.97-4.03-9-9-9v2c3.87 0 7 3.13 7 7zm-4 0h2c0-2.76-2.24-5-5-5v2c1.66 0 3 1.34 3 3z" />
                            </svg>
                            <span class="align-middle">Inbound Calls</span>
                        </a>
                    </li>
                @endcan

                @can('Outbound Call List')
                    <li class="sidebar-item {{ Request::routeIs('calls.outbound') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('calls.outbound') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#16A34A" viewBox="0 0 24 24">
                                <path
                                    d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                            </svg>
                            <span class="align-middle">Outbound Calls</span>
                        </a>
                    </li>
                @endcan

                @can('Lead List')
                    <li class="sidebar-item {{ Request::routeIs('leads.*') ? 'active' : '' }}">

                        <a class="sidebar-link d-flex justify-content-between align-items-center"
                            href="{{ route('leads.index') }}">

                            {{-- Left Side --}}
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    fill="#E11D48" viewBox="0 0 24 24">

                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                                </svg>

                                <span class="align-middle ms-2">Leads</span>
                            </div>

                            {{-- Right Side Badge --}}
                            <span class="badge bg-primary">
                                {{ $leadCount ?? 0 }}
                            </span>

                        </a>
                    </li>
                @endcan

                @can('Follow-up List')
                    <li class="sidebar-item {{ Request::routeIs('followups.*') ? 'active' : '' }}">
                        <a class="sidebar-link d-flex justify-content-between align-items-center"
                            href="{{ route('followups.index') }}">

                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    fill="#0284C7" viewBox="0 0 24 24">

                                    <path
                                        d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm-8 8c0 1.57.46 3.03 1.24 4.26L6.7 14.8c-.45-.83-.7-1.79-.7-2.8 0-3.31 2.69-6 6-6V3l4 4-4 4V8c-3.31 0-6 2.69-6 6z" />
                                </svg>

                                <span class="align-middle ms-2">Follow-ups</span>
                            </div>

                            <div class="d-flex align-items-center gap-2">

                                <span class="badge bg-success">
                                    {{ $followupCount ?? 0 }}
                                </span>

                                @if (isset($missedFollowupCount) && $missedFollowupCount > 0)
                                    <span class="d-inline-flex align-items-center">
                                        <span
                                            style="
                                                display:inline-block;
                                                width:8px;
                                                height:8px;
                                                background-color:#ef4444;
                                                border-radius:50%;
                                                box-shadow:0 0 0 rgba(239,68,68,0.4);
                                                animation:pulse-red 2s infinite;
                                            "></span>
                                    </span>
                                @endif

                            </div>
                        </a>
                    </li>
                @endcan

                @can('Schedule Demo')
                    <li class="sidebar-item {{ Request::routeIs('schedules.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('schedules.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#9333EA" viewBox="0 0 24 24">
                                <path
                                    d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z" />
                            </svg>
                            <span class="align-middle">Schedule Demo</span>
                        </a>
                    </li>
                @endcan

                @can('Schedule List')
                    <li class="sidebar-item {{ Request::routeIs('staff.schedule.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('staff.schedule.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#CA8A04" viewBox="0 0 24 24">
                                <path
                                    d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                            </svg>
                            <span class="align-middle">Schedule Management</span>
                        </a>
                    </li>
                @endcan

                @can('Demo Tracking')
                    <li class="sidebar-item {{ Request::routeIs('demo.tracking') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('demo.tracking') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#4F46E5" viewBox="0 0 24 24">
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                            </svg>
                            <span class="align-middle">Demo Tracking</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            @canany(['Student List', 'Payment List', 'Batch List', 'Batch Allocation'])
                <li class="sidebar-header">Student Management</li>
                @can('Student List')
                    <li class="sidebar-item {{ Request::is('register-students*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('students.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#059669" viewBox="0 0 24 24">
                                <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z" />
                            </svg>
                            <span class="align-middle">Registered Students</span>
                        </a>
                    </li>
                @endcan

                @can('Payment List')
                    <li class="sidebar-item {{ Request::is('payments*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('payments.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#DC2626" viewBox="0 0 24 24">
                                <path
                                    d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" />
                            </svg>
                            <span class="align-middle">Payments</span>
                        </a>
                    </li>
                @endcan

                @can('Batch List')
                    <li class="sidebar-item {{ Request::routeIs('batches.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('batches.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#0891B2" viewBox="0 0 24 24">
                                <path
                                    d="M4 11h5V5H4v6zm0 7h5v-6H4v6zm6 0h5v-6h-5v6zm6 0h5v-6h-5v6zm-6-7h5V5h-5v6zm6-6v6h5V5h-5z" />
                            </svg>
                            <span class="align-middle">Batch Management</span>
                        </a>
                    </li>
                @endcan

                @can('Batch Allocation')
                    <li class="sidebar-item {{ Request::routeIs('batch-allocation.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('batch-allocation.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#7C3AED" viewBox="0 0 24 24">
                                <path
                                    d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                            </svg>
                            <span class="align-middle">Batch Allocation</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- Dues Management --}}
            @canany(['Student Due List', 'Due Followup', 'Completed Payments'])
                @php
                    $pendingDuesCount = \App\Models\Payment::where('status', 'Pending')->count();
                    $overdueFollowupsCount = \App\Models\Payment::where('status', 'Pending')
                        ->whereDate('payment_date', '<', now())
                        ->count();
                @endphp
                <li class="sidebar-header">Workspace</li>
                @can('Student Due List')
                    <li class="sidebar-item {{ Request::routeIs('student-dues.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('student-dues.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#D97706" viewBox="0 0 24 24">
                                <path
                                    d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                            </svg>
                            <span class="align-middle">Student Due List</span>
                            @if($pendingDuesCount > 0)
                                <span class="badge bg-danger float-end">{{ $pendingDuesCount }}</span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('Due Followup')
                    <li class="sidebar-item {{ Request::routeIs('due-followups.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('due-followups.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#E11D48" viewBox="0 0 24 24">
                                <path
                                    d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                            </svg>
                            <span class="align-middle">Follow-ups</span>
                            @if($overdueFollowupsCount > 0)
                                <span class="badge bg-danger float-end">{{ $overdueFollowupsCount }}</span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('Completed Payments')
                    <li class="sidebar-item {{ Request::routeIs('completed-payments.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('completed-payments.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#2563EB" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                            </svg>
                            <span class="align-middle">Completed Payments</span>
                        </a>
                    </li>
                @endcan
            @endcanany


            @canany(['Course List', 'Practice List'])
                <li class="sidebar-header">LMS & Academy</li>
                @can('Course List')
                    <li class="sidebar-item {{ Request::is('courses*') ? 'active' : '' }}">
                        <a data-bs-target="#courses_manage" data-bs-toggle="collapse"
                            class="sidebar-link {{ Request::is('courses*') ? '' : 'collapsed' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#16A34A" viewBox="0 0 24 24">
                                <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z" />
                                <path d="M11 12.85L3 9l8-3.85L19 9l-8 3.85z" />
                                <path d="M5 13.18V17c0 1.66 3.13 3 7 3s7-1.34 7-3v-3.82l-7 3.82-7-3.82z" />
                            </svg>
                            <span class="align-middle">Manage Courses</span>
                        </a>
                        <ul id="courses_manage"
                            class="sidebar-dropdown list-unstyled collapse {{ Request::is('courses*') ? 'show' : '' }}"
                            data-bs-parent="#sidebar">
                            <li
                                class="sidebar-item {{ Request::is('courses') || Request::is('courses/create') || Request::is('courses/*/edit') ? 'active' : '' }}">
                                <a class="sidebar-link" href="{{ route('courses.index') }}">Recorded (Free/Paid)</a>
                            </li>
                            <li class="sidebar-item {{ Request::routeIs('courses.live.*') ? 'active' : '' }}"><a
                                    class="sidebar-link" href="{{ route('courses.live.index') }}">Live Classes</a></li>
                            <li class="sidebar-item {{ Request::routeIs('courses.combo.*') ? 'active' : '' }}"><a
                                    class="sidebar-link" href="{{ route('courses.combo.index') }}">Combo Packages</a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('Practice List')
                    <li class="sidebar-item {{ Request::routeIs('practices.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('practices.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#EA580C" viewBox="0 0 24 24">
                                <path
                                    d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                            </svg>
                            <span class="align-middle">Manage Practices</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- Blogs --}}
            @canany(['Blog List', 'Webinar List', 'E-Book List'])
                <li class="sidebar-header">Marketing & Resources</li>
                @can('Blog List')
                    <li class="sidebar-item {{ Request::routeIs('blogs-info.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('blogs-info.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#E11D48" viewBox="0 0 24 24">
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14H7v-2h3v2zm7-4H7v-2h10v2zm0-4H7V7h10v2z" />
                            </svg>
                            <span class="align-middle">Blogs & NEWS</span>
                        </a>
                    </li>
                @endcan

                @can('Webinar List')
                    <li class="sidebar-item {{ Request::routeIs('webinars.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ Route::has('webinars.index') ? route('webinars.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#0284C7" viewBox="0 0 24 24">
                                <path
                                    d="M17 10.5V6c0-1.1-.9-2-2-2H3C1.9 4 1 4.9 1 6v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4.5l4 4v-11l-4 4z" />
                            </svg>
                            <span class="align-middle">Webinars List</span>
                        </a>
                    </li>
                @endcan

                @can('E-Book List')
                    <li class="sidebar-item {{ Request::routeIs('ebooks.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('ebooks.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#2563EB" viewBox="0 0 24 24">
                                <path
                                    d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z" />
                            </svg>
                            <span class="align-middle">E-Books</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- MANAGEMENT --}}
            @canany(['Batch System', 'Batch Progress', 'Batch Topic Logs', 'Student Monitoring', 'Staff Monitoring'])
                <li class="sidebar-header">MANAGEMENT</li>
                @can('Batch System')
                    <li class="sidebar-item {{ Request::routeIs('batch-system.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('batch-system.index') ? route('batch-system.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#4F46E5" viewBox="0 0 24 24">
                                <path
                                    d="M4 11h5V5H4v6zm0 7h5v-6H4v6zm6 0h5v-6h-5v6zm6 0h5v-6h-5v6zm-6-7h5V5h-5v6zm6-6v6h5V5h-5z" />
                            </svg>
                            <span class="align-middle">Batch System</span>
                        </a>
                    </li>
                @endcan

                @can('Batch Progress')
                    <li class="sidebar-item {{ Request::routeIs('tech.batch-progress.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('tech.batch-progress.index') ? route('tech.batch-progress.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#059669" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14H7v-2h3v2zm0-4H7v-2h3v2zm0-4H7V7h3v2zm6 8h-3v-2h3v2zm0-4h-3v-2h3v2zm0-4h-3V7h3v2z" />
                            </svg>
                            <span class="align-middle">Batch Progress</span>
                        </a>
                    </li>

                @endcan
                
                @can('Batch Topic Logs')
                    <li class="sidebar-item {{ Request::routeIs('tech.batch-logs.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ route('tech.batch-logs.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#2563EB" viewBox="0 0 24 24">
                                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                            </svg>
                            <span class="align-middle">Batch Topic Logs</span>
                        </a>
                    </li>
                @endcan

                @can('Staff Monitoring')
                    <li class="sidebar-item {{ Request::routeIs('tech.staff-monitoring.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('tech.staff-monitoring.index') ? route('tech.staff-monitoring.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#D97706" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9v-2h2v2zm0-4H9V7h2v5zm4 4h-2v-2h2v2zm0-4h-2V7h2v5z" />
                            </svg>
                            <span class="align-middle">Staff Monitoring</span>
                        </a>
                    </li>
                @endcan

                @can('Student Monitoring')
                    <li class="sidebar-item {{ Request::routeIs('tech.students.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('tech.students.index') ? route('tech.students.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#2563EB" viewBox="0 0 24 24">
                                <path
                                    d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                            </svg>
                            <span class="align-middle">Students</span>
                            <span class="badge bg-success float-end">{{ $studentCount ?? 0 }}</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- SESSIONS --}}
            @canany(['Demo Sessions', 'Doubt Sessions'])
                <li class="sidebar-header">SESSIONS</li>
                @php
                    $sessionStaff = \App\Models\Staff::where('auth_id', auth()->id())->first();
                    $sessionStaffId = $sessionStaff ? $sessionStaff->id : null;
                    $todayDate = now()->format('Y-m-d');
                    $demoCount = $sessionStaffId
                        ? \App\Models\SessionBooking::where('type', 'demo')
                            ->where('staff_id', $sessionStaffId)
                            ->where('date', $todayDate)
                            ->where('status', 'booked')
                            ->count()
                        : 0;
                    $doubtCount = $sessionStaffId
                        ? \App\Models\SessionBooking::where('type', 'doubt')
                            ->where('staff_id', $sessionStaffId)
                            ->where('date', $todayDate)
                            ->where('status', 'booked')
                            ->count()
                        : 0;
                @endphp
                @can('Demo Sessions')
                    <li class="sidebar-item {{ Request::routeIs('tech.demo-sessions.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('tech.demo-sessions.index') ? route('tech.demo-sessions.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#DC2626" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                                    fill="none" />
                                <path d="M10 8l6 4-6 4V8z" fill="currentColor" />
                            </svg>
                            <span class="align-middle">Demo Sessions</span>
                            @if ($demoCount > 0)
                                <span class="badge bg-danger float-end">{{ $demoCount }}</span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('Doubt Sessions')
                    <li class="sidebar-item {{ Request::routeIs('tech.doubt-sessions.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('tech.doubt-sessions.index') ? route('tech.doubt-sessions.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#0891B2" viewBox="0 0 24 24">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                            </svg>
                            <span class="align-middle">Doubt Sessions</span>
                            @if ($doubtCount > 0)
                                <span class="badge bg-warning float-end">{{ $doubtCount }}</span>
                            @endif
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- TECHNICAL HEAD SESSIONS --}}
            @canany(['Technical Head Demo Sessions', 'Technical Head Doubt Sessions'])
                <li class="sidebar-header">
                    @if (auth()->user()->hasRole('SuperAdmin'))
                        TECHNICAL HEAD SESSIONS
                    @else
                        SESSIONS
                    @endif
                </li>
                @php
                    $techHeadStaff = \App\Models\Staff::where('auth_id', auth()->id())->first();
                    $techHeadStaffId = $techHeadStaff ? $techHeadStaff->id : null;
                    $todayDate = now()->format('Y-m-d');
                    $techHeadDemoCount = $techHeadStaffId
                        ? \App\Models\SessionBooking::where('type', 'demo')
                            ->where('staff_id', $techHeadStaffId)
                            ->where('date', $todayDate)
                            ->where('status', 'booked')
                            ->count()
                        : 0;
                    $techHeadDoubtCount = $techHeadStaffId
                        ? \App\Models\SessionBooking::where('type', 'doubt')
                            ->where('staff_id', $techHeadStaffId)
                            ->where('date', $todayDate)
                            ->where('status', 'booked')
                            ->count()
                        : 0;
                @endphp
                @can('Technical Head Demo Sessions')
                    <li class="sidebar-item {{ Request::routeIs('tech-head.demo-sessions.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('tech-head.demo-sessions.index') ? route('tech-head.demo-sessions.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#7C3AED" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                                    fill="none" />
                                <path d="M10 8l6 4-6 4V8z" fill="currentColor" />
                            </svg>
                            <span class="align-middle">
                                @if (auth()->user()->hasRole('SuperAdmin'))
                                    Technical Head Demo Sessions
                                @else
                                    Demo Sessions
                                @endif
                            </span>
                            @if ($techHeadDemoCount > 0)
                                <span class="badge bg-danger float-end">{{ $techHeadDemoCount }}</span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('Technical Head Doubt Sessions')
                    <li class="sidebar-item {{ Request::routeIs('tech-head.doubt-sessions.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('tech-head.doubt-sessions.index') ? route('tech-head.doubt-sessions.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#EA580C" viewBox="0 0 24 24">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                            </svg>
                            <span class="align-middle">
                                @if (auth()->user()->hasRole('SuperAdmin'))
                                    Technical Head Doubt Sessions
                                @else
                                    Doubt Sessions
                                @endif
                            </span>
                            @if ($techHeadDoubtCount > 0)
                                <span class="badge bg-warning float-end">{{ $techHeadDoubtCount }}</span>
                            @endif
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- ACADEMICS --}}
            @canany(['Assignment List', 'Mini Project', 'Main Project'])
                <li class="sidebar-header">ACADEMICS</li>
                @can('Assignment List')
                    <li class="sidebar-item {{ Request::routeIs('assignments.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('assignments.index') ? route('assignments.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#16A34A" viewBox="0 0 24 24">
                                <path
                                    d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                            </svg>
                            <span class="align-middle">Assignment</span>
                        </a>
                    </li>
                @endcan

                @can('Mini Project List')
                    <li class="sidebar-item {{ Request::routeIs('mini_projects.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('mini_projects.index') ? route('mini_projects.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#E11D48" viewBox="0 0 24 24">
                                <path
                                    d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.1L7.6 4.8l5.6 5.6-1.4 1.4-5.6-5.6-2.9 2.9c-1.3 2.4-.9 5.4 1.1 7.4 1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l1.4-1.4c.4-.4.4-1 0-1.4z" />
                            </svg>
                            <span class="align-middle">Mini Project</span>
                        </a>
                    </li>
                @endcan

                @can('Main Project List')
                    <li class="sidebar-item {{ Request::routeIs('main_projects.*') ? 'active' : '' }}">
                        <a class="sidebar-link"
                            href="{{ Route::has('main_projects.index') ? route('main_projects.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#0284C7" viewBox="0 0 24 24">
                                <path
                                    d="M13.5.67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5.67zM11.71 19c-1.78 0-3.22-1.4-3.22-3.14 0-1.62 1.05-2.76 2.81-3.12 1.77-.36 3.6-1.21 4.62-2.58.39 1.29.59 2.65.59 4.04 0 2.65-2.15 4.8-4.8 4.8z" />
                            </svg>
                            <span class="align-middle">Main Project</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- EVALUATIONS --}}
            @canany(['Course Evaluation Question List', 'Course Evaluation Option List', 'Course Eligibility Test List', 'Placement Evaluation List', 'Placement Classes'])
                <li class="sidebar-header">EVALUATIONS</li>
                @can('Course Evaluation Question List')
                    <li class="sidebar-item {{ Request::routeIs('course-evaluation-questions.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('course-evaluation-questions.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#16A34A" viewBox="0 0 24 24">
                                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                            </svg>
                            <span class="align-middle">Evaluation Questions</span>
                        </a>
                    </li>
                @endcan
                @can('Placement Evaluation List')
                    <li class="sidebar-item {{ Request::routeIs('placement-evaluations.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('placement-evaluations.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#16A34A" viewBox="0 0 24 24">
                                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-1.218 19l-1.782-1.75 5.25-5.25-5.25-5.25 1.782-1.75 6.968 7-6.968 7z" />
                            </svg>
                            <span class="align-middle">Placement Eval Tests</span>
                        </a>
                    </li>
                @endcan
                @can('Placement Classes')
                    <li class="sidebar-item {{ Request::routeIs('placement-classes.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('placement-classes.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#16A34A" viewBox="0 0 24 24">
                                <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z" />
                            </svg>
                            <span class="align-middle">Placement Classes</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- TOOLS --}}
            @canany(['Meetings'])
                <li class="sidebar-header">TOOLS</li>
                @can('Meetings')
                    <li class="sidebar-item {{ Request::routeIs('meetings.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ Route::has('meetings.index') ? route('meetings.index') : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="#9333EA" viewBox="0 0 24 24">
                                <path
                                    d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z" />
                            </svg>
                            <span class="align-middle">Meetings</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- PLACEMENT & STUDENT COORDINATION --}}
            @canany(['Company Database List', 'Current Openings List', 'Interview Status', 'Placement Coordinator Followup', 'Applied Openings', 'Eligible Students', 'Student Coordinator Followup'])
                <li class="sidebar-header">PLACEMENT & COORDINATION</li>
                @can('Company Database List')
                    <li class="sidebar-item {{ Request::is('company-database*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('company-database.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#CA8A04" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9v-2h2v2zm0-4H9V7h2v5zm4 4h-2v-2h2v2zm0-4h-2V7h2v5z"/>
                            </svg>
                            <span class="align-middle">Company Database</span>
                        </a>
                    </li>
                @endcan

                @can('Current Openings List')
                    <li class="sidebar-item {{ Request::is('current-openings*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('current-openings.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#4F46E5" viewBox="0 0 24 24">
                                <path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/>
                            </svg>
                            <span class="align-middle">Current Openings</span>
                        </a>
                    </li>
                @endcan

                @can('Applied Openings')
                    <li class="sidebar-item {{ Request::is('applied-openings*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('applied-openings.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#059669" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                            </svg>
                            <span class="align-middle">Applied Openings</span>
                        </a>
                    </li>
                @endcan

                @can('Eligible Students')
                    <li class="sidebar-item {{ Request::is('eligible-students*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('eligible-students.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#DC2626" viewBox="0 0 24 24">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                            <span class="align-middle">Eligible Students</span>
                        </a>
                    </li>
                @endcan

                @can('Interview Status')
                    <li class="sidebar-item {{ Request::is('interview-status*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('interview-status.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#0891B2" viewBox="0 0 24 24">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                            </svg>
                            <span class="align-middle">Interview Status</span>
                        </a>
                    </li>
                @endcan

                @can('Placement Coordinator Followup')
                    <li class="sidebar-item {{ Request::is('placement-followups*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('placement-followups.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#7C3AED" viewBox="0 0 24 24">
                                <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm-8 8c0 1.57.46 3.03 1.24 4.26L6.7 14.8c-.45-.83-.7-1.79-.7-2.8 0-3.31 2.69-6 6-6V3l4 4-4 4V8c-3.31 0-6 2.69-6 6z"/>
                            </svg>
                            <span class="align-middle">Placement Followup</span>
                        </a>
                    </li>
                @endcan

                @can('Student Coordinator Followup')
                    <li class="sidebar-item {{ Request::is('student-followups*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('student-followups.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#D97706" viewBox="0 0 24 24">
                                <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm-8 8c0 1.57.46 3.03 1.24 4.26L6.7 14.8c-.45-.83-.7-1.79-.7-2.8 0-3.31 2.69-6 6-6V3l4 4-4 4V8c-3.31 0-6 2.69-6 6z"/>
                            </svg>
                            <span class="align-middle">Follow-ups / Leads</span>
                        </a>
                    </li>
                @endcan

            @endcanany

            {{-- Settings --}}
            @canany(['Role List', 'Permission List', 'Lead Tag List', 'Lead Source List'])
                <li class="sidebar-header">System Settings</li>
                <li class="sidebar-item @if (Request::is(['roles*', 'permissions*', 'lead-tags*', 'lead-sources*'])) active @endif">
                    <a data-bs-target="#setting_manage" data-bs-toggle="collapse"
                        class="sidebar-link {{ Request::is(['roles*', 'permissions*', 'lead-tags*', 'lead-sources*']) ? '' : 'collapsed' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            fill="#E11D48" viewBox="0 0 24 24">
                            <path
                                d="M19.14,12.94c0.04-0.31,0.06-0.63,0.06-0.94s-0.02-0.63-0.06-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.62l-1.92-3.32 c-0.11-0.21-0.36-0.3-0.58-0.22l-2.39,0.96c-0.5-0.38-1.04-0.7-1.64-0.94l-0.36-2.54C14.38,2.18,14.19,2,13.96,2h-3.92 c-0.23,0-0.42,0.18-0.45,0.41l-0.36,2.54c-0.6,0.24-1.14,0.56-1.64,0.94L5.2,4.93C4.98,4.85,4.73,4.94,4.62,5.15L2.7,8.47 c-0.11,0.21-0.06,0.48,0.12,0.62l2.03,1.58C4.81,11.37,4.8,11.68,4.8,12s0.02,0.63,0.06,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.62l1.92,3.32c0.11,0.21,0.36,0.3,0.58,0.22l2.39-0.96c0.5,0.38,1.04,0.7,1.64,0.94l0.36,2.54 C9.62,21.82,9.81,22,10.04,22h3.92c0.23,0,0.42-0.18,0.45-0.41l0.36-2.54c0.6-0.24,1.14-0.56,1.64-0.94l2.39,0.96 c(0.22,0.09,0.47-0.01,0.58-0.22l1.92-3.32c0.11-0.21,0.06-0.48-0.12-0.62L19.14,12.94z M12,15.6c-1.99,0-3.6-1.61-3.6-3.6 s1.61-3.6,3.6-3.6s3.6,1.61,3.6,3.6S13.99,15.6,12,15.6z" />
                        </svg>
                        <span class="align-middle">Management</span>
                    </a>
                    <ul id="setting_manage"
                        class="sidebar-dropdown list-unstyled collapse {{ Request::is(['roles*', 'permissions*', 'lead-tags*', 'lead-sources*']) ? 'show' : '' }}"
                        data-bs-parent="#sidebar">
                        @can('Role List')
                            <li class="sidebar-item {{ Request::routeIs('roles.*') ? 'active' : '' }}"><a
                                    class="sidebar-link" href="{{ route('roles.index') }}">Roles</a></li>
                        @endcan
                        @can('Permission List')
                            <li class="sidebar-item {{ Request::routeIs('permissions.*') ? 'active' : '' }}"><a
                                    class="sidebar-link" href="{{ route('permissions.index') }}">Permissions</a></li>
                        @endcan
                        @can('Lead Tag List')
                            <li class="sidebar-item {{ Request::routeIs('lead-tags.*') ? 'active' : '' }}"><a
                                    class="sidebar-link" href="{{ route('lead-tags.index') }}">Lead Tags</a></li>
                        @endcan
                        @can('Lead Source List')
                            <li class="sidebar-item {{ Request::routeIs('lead-sources.*') ? 'active' : '' }}"><a
                                    class="sidebar-link" href="{{ route('lead-sources.index') }}">Lead Sources</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany
        </ul>
    </div>
</nav>

