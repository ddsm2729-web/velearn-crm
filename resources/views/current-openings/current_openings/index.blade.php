@extends('layouts.header')

@section('pageTitle', 'Current Openings')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/current_openings-index.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endpush

@section('section')
<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong>Current Openings</strong> <span class="badge bg-secondary rounded-pill ms-2" style="font-size: 0.6em;">{{ $totalOpenings }}</span></h3>
        <p class="text-muted mb-0">Active vacancies being worked on</p>
    </div>
    <div class="col-auto ms-auto text-end mt-n1">
        @can('Current Openings Create')
        <a href="{{ route('current-openings.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
            <i class="fas fa-plus me-2"></i> Entry
        </a>
        @endcan
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-end flex-wrap gap-3" style="border-bottom: 1px solid #e2e8f0;">
        <div class="status-tabs-container">
            <button class="btn status-filter" data-status="Today's">Today's</button>
            <button class="btn status-filter active" data-status="Active">Active</button>
            <button class="btn status-filter" data-status="Hold">Hold</button>
            <button class="btn status-filter" data-status="Filled">Filled</button>
            <button class="btn status-filter" data-status="Closed">Closed</button>
            <button class="btn status-filter" data-status="Old">Old</button>
            <button class="btn status-filter" data-status="All">All</button>
        </div>
        
        <form id="filter-form" class="d-flex gap-2 pb-2">
            <input type="hidden" name="status" id="status-input" value="Active">
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search openings..." value="{{ request('search') }}">
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="row">
    <div class="col-12">
        <div class="card position-relative">
            <div id="table-loader" class="position-absolute w-100 h-100 d-none" style="background: rgba(255,255,255,0.7); z-index: 10; display: flex; align-items: center; justify-content: center;">
                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="text-uppercase text-muted" style="font-size: 0.75rem; background-color: #f8f9fa;">
                            <tr>
                                <th class="ps-4 py-3">ID</th>
                                <th class="py-3">COMPANY</th>
                                <th class="py-3">ROLE</th>
                                <th class="py-3">LOCATION</th>
                                <th class="py-3">CTC</th>
                                <th class="py-3">SLOTS</th>
                                <th class="py-3">EXP.</th>
                                <th class="py-3">MODE</th>
                                <th class="py-3">DATE</th>
                                <th class="py-3 text-center">STATUS</th>
                                @canany(['Current Openings Edit', 'Current Openings Delete'])
                                <th class="pe-4 py-3 text-end sticky-action">ACTIONS</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @include('current_openings._table_rows')
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end p-3" id="pagination-container">
                    {{ $openings->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let searchTimeout;

            function fetchOpenings(pageUrl) {
                $('#table-loader').removeClass('d-none');
                
                let page = 1;
                if (pageUrl) {
                    try {
                        let urlObj = new URL(pageUrl, window.location.origin);
                        page = urlObj.searchParams.get('page') || 1;
                    } catch (e) {
                        page = 1;
                    }
                }

                let formData = $('#filter-form').serialize() + '&page=' + page;
                
                $.ajax({
                    url: "{{ route('current-openings.index') }}",
                    type: "GET",
                    data: formData,
                    success: function(res) {
                        $('#table-body').html(res.html);
                        $('#pagination-container').html(res.pagination);
                    },
                    error: function(err) {
                        toastr.error('Error fetching data.');
                    },
                    complete: function() {
                        $('#table-loader').addClass('d-none');
                    }
                });
            }

            $('.status-filter').on('click', function() {
                $('.status-filter').removeClass('active');
                $(this).addClass('active');
                $('#status-input').val($(this).data('status'));
                fetchOpenings();
            });

            $('input[name="search"]').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    fetchOpenings();
                }, 500);
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                fetchOpenings();
            });

            $(document).on('click', '#pagination-container a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                fetchOpenings(url);
            });
            
            // Delete Action
            $(document).on('click', '.delete-opening', function() {
                let url = $(this).data('href');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(res) {
                                if (res.status) {
                                    toastr.success(res.message);
                                    fetchOpenings();
                                } else {
                                    toastr.error(res.message);
                                }
                            }
                        });
                    }
                });
            });

            // Send Mail Action
            $(document).on('click', '.send-mail-btn', function() {
                let url = $(this).data('href');
                Swal.fire({
                    title: 'Send Email?',
                    text: 'Send this opening details to snegaammu123@gmail.com?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Send',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return $.ajax({
                            url: url,
                            type: 'POST',
                            data: { _token: '{{ csrf_token() }}' },
                        }).catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error.responseJSON?.message || error.statusText}`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.value.status) {
                            toastr.success(result.value.message);
                        } else {
                            toastr.error(result.value.message);
                        }
                    }
                });
            });
        });
    </script>
@endpush
