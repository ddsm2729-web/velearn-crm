@extends('layouts.master')

@section('pageTitle', 'Student Management')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/tech_students.css') }}">
@endpush

@section('content')
    <div class="tech-student-wrapper">
        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">Student <span>Management</span></h1>

            <div class="header-actions">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="search-input" placeholder="Search students, batches...">
                </div>
            </div>
        </div>

        <div class="main-card">
            <div class="filter-tabs">
                <button class="filter-tab active filter-btn" data-filter="all">
                    All Students
                </button>
                @foreach($courses as $course)
                    <button class="filter-tab filter-btn" data-filter="course-{{ $course->id }}">
                        {{ $course->title }}
                    </button>
                @endforeach
            </div>

            <div class="table-responsive-custom">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>STUDENT</th>
                            <th>BATCH CODE</th>
                            <th>COURSE</th>
                            <th>JOINED</th>
                            <th>ATTEND.</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="student-table-body">
                        @include('tech.students.partials.list')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Reapply frontend filters after ajax
            function applyCurrentFilter() {
                const activeBtn = document.querySelector('.filter-tab.active');
                if (activeBtn) {
                    const filter = activeBtn.getAttribute('data-filter');
                    filterTable(filter);
                }
            }

            // Frontend course filtering
            function filterTable(filter) {
                const studentRows = document.querySelectorAll('.student-row');
                let visibleCount = 0;
                studentRows.forEach(row => {
                    if (filter === 'all' || row.classList.contains(filter)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const emptyRow = document.getElementById('empty-filter-msg');
                if (emptyRow) {
                    if (visibleCount === 0 && studentRows.length > 0) {
                        emptyRow.style.display = '';
                    } else {
                        emptyRow.style.display = 'none';
                    }
                }
            }

            // Click listener for filter tabs
            const filterBtns = document.querySelectorAll('.filter-btn');
            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    filterTable(btn.getAttribute('data-filter'));
                });
            });

            // AJAX Search
            const searchInput = document.getElementById('search-input');
            const tableBody = document.getElementById('student-table-body');
            let timeout = null;

            searchInput.addEventListener('keyup', function(e) {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const searchVal = e.target.value;
                    
                    fetch('{{ route("tech.students.index") }}?search=' + encodeURIComponent(searchVal), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableBody.innerHTML = html;
                        applyCurrentFilter(); // Re-apply course filter to new rows
                    });
                }, 400); // 400ms debounce
            });

        });
    </script>
@endpush