@extends('layouts.master')

@section('pageTitle', 'Follow-ups')

@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calls-followups.css') }}">
    @endpush

    <div class="leads-container p-4">
        <div class="leads-header mb-4">
            <h1 class="fw-bold mb-1">Follow-ups</h1>
            <p class="text-muted small">Your assigned follow-up leads</p>
        </div>

        <div class="search-filter-row d-flex gap-3 mb-4 align-items-center flex-wrap">
            <div class="search-box position-relative" style="width: 300px;">
                <i class="fas fa-search position-absolute text-primary"
                    style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
                <input type="text" id="searchLeads" class="form-control rounded-3" placeholder="Search follow-up leads...">
            </div>

            <select id="tagFilter" class="form-select filter-select rounded-3 w-auto">
                <option value="All Tags">All Tags</option>
                <option value="hot">hot</option>
                <option value="warm">warm</option>
                <option value="cold">cold</option>
                <option value="contacted">contacted</option>
                <option value="booked">booked</option>
                <option value="attended">attended</option>
                <option value="converted">converted</option>
                <option value="lost">lost</option>
            </select>

            <select id="typeFilter" class="form-select filter-select rounded-3 w-auto">
                <option value="All Types">All Types</option>
                <option value="Inbound">Inbound</option>
                <option value="Outbound">Outbound</option>
            </select>
        </div>

        <div id="leadsContainer">
            @include('doubt-query-leads.partials.followup_rows', ['calls' => $calls])
        </div>

        <div class="mt-4 d-flex justify-content-center" id="paginationContainer">
            {{ $calls->links() }}
        </div>
    </div>

    @push('script')
        <script>
            $(function () {
                function filterLeads() {
                    var search = $('#searchLeads').val();
                    var tag = $('#tagFilter').val();
                    var type = $('#typeFilter').val();

                    console.log("Filtering with:", { search, tag, type });

                    $.ajax({
                        url: "{{ route('followups.index') }}",
                        type: 'GET',
                        data: {
                            search: search,
                            tag: tag,
                            type: type
                        },
                        cache: false,
                        success: function (response) {
                            if (response.status) {
                                $('#leadsContainer').html(response.data);
                                $('#paginationContainer').html(response.pagination);
                            }
                        },
                        error: function (xhr) {
                            console.error("Filter failed", xhr);
                        }
                    });
                }

                $('#searchLeads').on('keyup', filterLeads);
                $('#tagFilter, #typeFilter').on('change', filterLeads);

                // Handle lead card click
                $(document).on('click', '.lead-card', function () {
                    window.location.href = $(this).data('url');
                });
            });
        </script>
    @endpush
@endsection