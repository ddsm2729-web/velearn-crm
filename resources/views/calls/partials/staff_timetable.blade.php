@if($selectedStaff)
<div class="card shadow-sm rounded-3 border-0 overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
        <div class="staff-avatar me-2" style="width: 40px; height: 40px; font-size: 16px;">
            {{ strtoupper(substr($selectedStaff->name, 0, 1)) }}
        </div>
        <div>
            <h5 class="mb-0 fw-bold">{{ $selectedStaff->name }}'s Timetable</h5>
            <small class="text-muted">Upcoming 7 Days Availability</small>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center" style="min-width: 800px;">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 text-muted small fw-bold" style="width: 100px;">TIME</th>
                        @foreach(array_keys($timetable) as $date)
                            <th class="py-3">
                                <div class="small fw-bold text-primary">{{ date('D', strtotime($date)) }}</div>
                                <div class="h5 mb-0">{{ date('d M', strtotime($date)) }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $timeKey => $timeLabel)
                        <tr>
                            <td class="align-middle fw-bold small text-muted">{{ $timeLabel }}</td>
                            @foreach($timetable as $date => $slots)
                                <td class="p-1 align-middle" style="height: 60px; min-width: 120px;">
                                    @if($slots[$timeKey])
                                        <div class="p-2 bg-primary text-white rounded-3 small shadow-sm cursor-pointer hover-lift" 
                                             onclick="window.location.href='{{ route('calls.profile', $slots[$timeKey]->id) }}'"
                                             title="{{ $slots[$timeKey]->contact->name }}">
                                            <div class="fw-bold text-truncate">{{ $slots[$timeKey]->contact->name }}</div>
                                            <div style="font-size: 10px; opacity: 0.9;">Booked</div>
                                        </div>
                                    @else
                                        <div class="w-100 h-100 bg-light opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-plus text-muted opacity-25" style="font-size: 10px;"></i>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
