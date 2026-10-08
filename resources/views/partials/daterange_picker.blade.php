<div class="daterange-picker-container ms-auto">
    <form method="GET" action="{{ url()->current() }}" id="daterangeForm" class="d-flex align-items-center m-0">
        <!-- Preserve other query parameters -->
        @foreach(request()->except(['start_date', 'end_date']) as $key => $value)
            @if(!is_array($value))
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach
        
        <input type="hidden" name="start_date" id="start_date" value="{{ $startDate ?? request('start_date') }}">
        <input type="hidden" name="end_date" id="end_date" value="{{ $endDate ?? request('end_date') }}">
        
        <button type="button" id="reportrange" class="btn btn-white border text-secondary fw-semibold shadow-sm rounded d-flex align-items-center gap-2" style="font-size: 13px; padding: 6px 12px; background: #fff;">
            <i class="far fa-calendar-alt text-muted"></i>
            <span id="reportrange-text">Select Date</span>
        </button>
        
        @if(request('start_date') || request('end_date'))
            <a href="{{ url()->current() }}" class="btn btn-white border shadow-sm rounded ms-2 text-danger d-flex align-items-center justify-content-center" style="padding: 6px 12px; background: #fff;" title="Clear Filter">
                <i class="fas fa-times"></i>
            </a>
        @endif
    </form>
</div>

<!-- Push Scripts and Styles -->
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />
@endpush

@push('script')
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            var start = $('#start_date').val() ? moment($('#start_date').val()) : null;
            var end = $('#end_date').val() ? moment($('#end_date').val()) : null;

            function cb(start, end, label) {
                if (label && label !== 'Custom Range') {
                    $('#reportrange-text').text(label);
                } else if (start && end) {
                    $('#reportrange-text').text(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                } else {
                    $('#reportrange-text').text('All Time');
                }
            }

            // Figure out initial label based on start and end
            var initialLabel = 'All Time';
            if (start && end) {
                var s = start.format('YYYY-MM-DD');
                var e = end.format('YYYY-MM-DD');
                if (s === moment().format('YYYY-MM-DD') && e === moment().format('YYYY-MM-DD')) initialLabel = 'Today';
                else if (s === moment().subtract(1, 'days').format('YYYY-MM-DD') && e === moment().subtract(1, 'days').format('YYYY-MM-DD')) initialLabel = 'Yesterday';
                else if (s === moment().subtract(6, 'days').format('YYYY-MM-DD') && e === moment().format('YYYY-MM-DD')) initialLabel = 'Last 7 Days';
                else if (s === moment().subtract(13, 'days').format('YYYY-MM-DD') && e === moment().format('YYYY-MM-DD')) initialLabel = 'Last 14 Days';
                else if (s === moment().subtract(29, 'days').format('YYYY-MM-DD') && e === moment().format('YYYY-MM-DD')) initialLabel = 'Last 30 days';
                else if (s === moment().startOf('isoWeek').format('YYYY-MM-DD') && e === moment().endOf('isoWeek').format('YYYY-MM-DD')) initialLabel = 'This week';
                else if (s === moment().startOf('month').format('YYYY-MM-DD') && e === moment().endOf('month').format('YYYY-MM-DD')) initialLabel = 'This month';
                else if (s === moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD') && e === moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD')) initialLabel = 'Last month';
                else initialLabel = start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY');
            }

            $('#reportrange').daterangepicker({
                startDate: start || moment(),
                endDate: end || moment(),
                autoUpdateInput: false,
                alwaysShowCalendars: true,
                showCustomRangeLabel: false,
                ranges: {
                   'Today': [moment(), moment()],
                   'Last 7 days': [moment().subtract(6, 'days'), moment()],
                   'Last 14 days': [moment().subtract(13, 'days'), moment()],
                   'Last 30 days': [moment().subtract(29, 'days'), moment()],
                   'This week': [moment().startOf('isoWeek'), moment().endOf('isoWeek')],
                   'This month': [moment().startOf('month'), moment().endOf('month')],
                   'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                   'All time': [moment('2020-01-01'), moment().add(10, 'years')]
                },
                locale: {
                    cancelLabel: 'Cancel',
                    applyLabel: 'Apply',
                    format: 'MMM D, YYYY',
                    customRangeLabel: 'Custom Range'
                }
            }, function(newStart, newEnd, label) {
                if(label === 'All time') {
                    $('#start_date').val('');
                    $('#end_date').val('');
                } else {
                    $('#start_date').val(newStart.format('YYYY-MM-DD'));
                    $('#end_date').val(newEnd.format('YYYY-MM-DD'));
                }
                
                $('#daterangeForm').submit();
            });
            
            $('#reportrange-text').text(initialLabel);
            
            // Format the drp-selected on show and apply
            $('#reportrange').on('show.daterangepicker', function(ev, picker) {
                // Remove 'Custom Range' if it appears
                picker.container.find('.ranges ul li:last-child').hide();
            });
        });
    </script>
@endpush