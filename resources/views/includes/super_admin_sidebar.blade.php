<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">

        <a class="sidebar-brand" href="{{ route('/') }}">
        </a>

        <ul class="sidebar-nav">
            {{-- Main / Dashboard --}}
            <li class="sidebar-header">Main</li>
            <li class="sidebar-item @if (Request::is('/') || Request::is('*/dashboard')) active @endif">
                <a class="sidebar-link" href="{{ route('/') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#4F46E5"
                        viewBox="0 0 24 24">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z" />
                    </svg>
                    <span class="align-middle">Dashboard</span>
                </a>
            </li>

            {{-- Staff & Access --}}
            <li class="sidebar-header">Staff & Access</li>
            <li class="sidebar-item {{ Request::routeIs('staffs.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('staffs.index') ? route('staffs.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#059669"
                        viewBox="0 0 24 24">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3z" />
                        <path d="M8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3z" />
                        <path d="M8 13c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13z" />
                        <path
                            d="M16 13c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                    <span class="align-middle">Manage Staff</span>
                </a>
            </li>

            <li class="sidebar-item {{ Request::routeIs('calls.history') ? 'active' : '' }}">
                <a class="sidebar-link"
                    href="{{ Route::has('calls.history') ? route('calls.history') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#D97706"
                        viewBox="0 0 24 24">
                        <path
                            d="M20 15.5c-1.25 0-2.45-.2-3.57-.57a1.02 1.02 0 0 0-1.02.24l-2.2 2.2a15.045 15.045 0 0 1-6.59-6.59l2.2-2.21a.96.96 0 0 0 .25-1.02c-.37-1.12-.57-2.32-.57-3.57 0-.55-.45-1-1-1H3.5c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM19 12h2c0-4.97-4.03-9-9-9v2c3.87 0 7 3.13 7 7zm-4 0h2c0-2.76-2.24-5-5-5v2c1.66 0 3 1.34 3 3z" />
                    </svg>
                    <span class="align-middle">Call History</span>
                </a>
            </li>
            
            @can('Schedule List')
            <li class="sidebar-item {{ Request::routeIs('staff.schedule.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('staff.schedule.index') ? route('staff.schedule.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#0D9488" viewBox="0 0 24 24">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                    <span class="align-middle">Staff Schedule</span>
                </a>
            </li>
            @endcan

            @canany(['Role List', 'Permission List'])
            <li class="sidebar-item {{ Request::routeIs('roles.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('roles.index') ? route('roles.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#DC2626"
                        viewBox="0 0 24 24">
                        <path
                            d="M19.14,12.94c0.04-0.31,0.06-0.63,0.06-0.94s-0.02-0.63-0.06-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.62l-1.92-3.32 c-0.11-0.21-0.36-0.3-0.58-0.22l-2.39,0.96c-0.5-0.38-1.04-0.7-1.64-0.94l-0.36-2.54C14.38,2.18,14.19,2,13.96,2h-3.92 c-0.23,0-0.42,0.18-0.45,0.41l-0.36,2.54c-0.6,0.24-1.14,0.56-1.64,0.94L5.2,4.93C4.98,4.85,4.73,4.94,4.62,5.15L2.7,8.47 c-0.11,0.21-0.06,0.48,0.12,0.62l2.03,1.58C4.81,11.37,4.8,11.68,4.8,12s0.02,0.63,0.06,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.62l1.92,3.32c0.11,0.21,0.36,0.3,0.58,0.22l2.39-0.96c0.5,0.38,1.04,0.7,1.64,0.94l0.36,2.54 C9.62,21.82,9.81,22,10.04,22h3.92c0.23,0,0.42-0.18,0.45-0.41l0.36-2.54c0.6-0.24,1.14-0.56,1.64-0.94l2.39,0.96 c(0.22,0.09,0.47-0.01,0.58-0.22l1.92-3.32c0.11-0.21,0.06-0.48-0.12-0.62L19.14,12.94z M12,15.6c-1.99,0-3.6-1.61-3.6-3.6 s1.61-3.6,3.6-3.6s3.6,1.61,3.6,3.6S13.99,15.6,12,15.6z" />
                    </svg>
                    <span class="align-middle">Roles & Permissions</span>
                </a>
            </li>
            @endcanany

            {{-- CRM Settings --}}
            @canany(['Lead Tag List', 'Lead Source List', 'Lead Status List'])
            <li class="sidebar-header">CRM Settings</li>
            @can('Lead Tag List')
            <li class="sidebar-item {{ Request::routeIs('lead-tags.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('lead-tags.index') ? route('lead-tags.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#4B5563" viewBox="0 0 24 24">
                        <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.41l9 9c.36.36.86.59 1.41.59s1.05-.22 1.41-.59l7-7c.36-.36.59-.86.59-1.41s-.22-1.05-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
                    </svg>
                    <span class="align-middle">Lead Tags</span>
                </a>
            </li>
            @endcan
            @can('Lead Status List')
            <li class="sidebar-item {{ Request::routeIs('lead-statuses.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('lead-statuses.index') ? route('lead-statuses.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#4B5563" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                    <span class="align-middle">Lead Statuses</span>
                </a>
            </li>
            @endcan
            @can('Lead Source List')
            <li class="sidebar-item {{ Request::routeIs('lead-sources.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('lead-sources.index') ? route('lead-sources.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#6B7280" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                    <span class="align-middle">Lead Sources</span>
                </a>
            </li>
            @endcan
            @endcanany

            {{-- CRM & Contacts --}}
            @can('Manage Contacts')
            <li class="sidebar-header">CRM & Contacts</li>
            <li class="sidebar-item {{ Request::routeIs('contacts.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('contacts.info') ? route('contacts.info') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#0891B2"
                        viewBox="0 0 24 24">
                        <path
                            d="M19 2H9C7.9 2 7 2.9 7 4v16c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H9V4h10v16zM5 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h1V4z" />
                        <circle cx="12" cy="10" r="2" />
                        <path d="M12 13c-2.33 0-4 1.17-4 2v1h8v-1c0-0.83-1.67-2-4-2z" />
                    </svg>
                    <span class="align-middle">Contacts</span>
                </a>
            </li>
            @endcan

            @can('Lead List')
            <li class="sidebar-item {{ Request::routeIs('leads.*') ? 'active' : '' }}">
                <a class="sidebar-link d-flex justify-content-between align-items-center" href="{{ Route::has('leads.index') ? route('leads.index') : '#' }}">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#F59E0B" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span class="align-middle ms-2">Leads</span>
                    </div>
                    <span class="badge bg-primary rounded" style="font-size: 0.75rem;">
                        {{ \App\Models\Lead::count() }}
                    </span>
                </a>
            </li>
            @endcan

            {{-- Students --}}
            <li class="sidebar-header">Students</li>
            
            @can('Batch List')
            <li class="sidebar-item {{ Request::routeIs('batches.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('batches.index') ? route('batches.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#F59E0B" viewBox="0 0 24 24">
                        <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
                    </svg>
                    <span class="align-middle">Batch Management</span>
                </a>
            </li>
            @endcan
            
            @can('Batch Allocation')
            <li class="sidebar-item {{ Request::routeIs('batch-allocation.*') ? 'active' : '' }}">
                <a class="sidebar-link"
                    href="{{ Route::has('batch-allocation.index') ? route('batch-allocation.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#16A34A"
                        viewBox="0 0 24 24">
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                    <span class="align-middle">Batch Allocation</span>
                </a>
            </li>
            @endcan
            
            {{-- LMS & Academy --}}
            <li class="sidebar-header">LMS & Academy</li>
            <li class="sidebar-item {{ Request::is('courses*') ? 'active' : '' }}">
                <a data-bs-target="#courses_manage" data-bs-toggle="collapse"
                    class="sidebar-link {{ Request::is('courses*') ? '' : 'collapsed' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#9333EA"
                        viewBox="0 0 24 24">
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

            {{-- EVALUATIONS --}}
            <li class="sidebar-header">EVALUATIONS</li>
            <li class="sidebar-item {{ Request::routeIs('course-evaluation-questions.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('course-evaluation-questions.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#16A34A" viewBox="0 0 24 24">
                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                    </svg>
                    <span class="align-middle">Evaluation Questions</span>
                </a>
            </li>
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

            {{-- Marketing --}}
            <li class="sidebar-header">Marketing</li>
            <li class="sidebar-item {{ Request::routeIs('webinars.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('webinars.index') ? route('webinars.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#D97706"
                        viewBox="0 0 24 24">
                        <path
                            d="M17 10.5V6c0-1.1-.9-2-2-2H3C1.9 4 1 4.9 1 6v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4.5l4 4v-11l-4 4z" />
                    </svg>
                    <span class="align-middle">Webinars & Seminars</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('blogs-info.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('blogs-info.index') ? route('blogs-info.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#E11D48"
                        viewBox="0 0 24 24">
                        <path
                            d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14H7v-2h3v2zm7-4H7v-2h10v2zm0-4H7V7h10v2z" />
                    </svg>
                    <span class="align-middle">Blogs & News</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('ebooks.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ Route::has('ebooks.index') ? route('ebooks.index') : '#' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#2563EB"
                        viewBox="0 0 24 24">
                        <path
                            d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z" />
                    </svg>
                    <span class="align-middle">E-Books</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

