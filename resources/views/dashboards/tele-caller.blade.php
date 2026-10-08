@extends('layouts.master')

@section('pageTitle', 'Tele Caller Dashboard')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-end mb-3">
        @include('partials.daterange_picker')
    </div>


    {{-- HERO --}}
    <div class="tc-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div style="position:relative;z-index:1;">
                <div class="hero-avatar"><i class="fas fa-headset"></i></div>
                <h2 class="text-white">Good {{ now()->format('H') < 12 ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ auth()->user()->first_name ?? auth()->user()->name }}! 👋</h2>
                <p>Here's your performance overview · {{ now()->format('l, d M Y') }}</p>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-start" style="position:relative;z-index:1;">
                <span class="hero-chip"><i class="far fa-calendar-alt"></i> {{ now()->format('d M Y') }}</span>
                <span class="hero-chip"><span class="live-pulse"></span> Live</span>
            </div>
        </div>
    </div>

    {{-- KPI ROW --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl">
            <div class="kpi h-100">
                <div class="kpi-top-bar" style="background:linear-gradient(90deg,#6366f1,#8b5cf6);">

</div>
                <div class="kpi-icon" style="background:#eef2ff;color:#6366f1;"><i class="fas fa-users"></i></div>
                <div class="kpi-label">Assigned Leads</div>
                <div class="kpi-num" data-count="{{ $assignedLeadsCount }}">{{ $assignedLeadsCount }}</div>
                <div class="kpi-sub badge-up"><i class="fas fa-arrow-up me-1"></i>+{{ $assignedLeadsToday }} today</div>
                <div class="prog"><div class="prog-fill" style="background:linear-gradient(90deg,#6366f1,#8b5cf6);width:{{ $assignedLeadsCount > 0 ? min(100, $assignedLeadsToday / max($assignedLeadsCount,1) * 100) : 0 }}%;"></div></div>
            </div>
        </div>

        <div class="col-6 col-xl">
            <div class="kpi h-100">
                <div class="kpi-top-bar" style="background:linear-gradient(90deg,#f59e0b,#fbbf24);"></div>
                <div class="kpi-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-sync-alt"></i></div>
                <div class="kpi-label">Follow-ups</div>
                <div class="kpi-num" data-count="{{ $followupsCount }}">{{ $followupsCount }}</div>
                <div class="kpi-sub {{ $pendingFollowups > 0 ? 'badge-down' : 'badge-up' }}">
                    <i class="fas fa-{{ $pendingFollowups > 0 ? 'exclamation-circle' : 'check-circle' }} me-1"></i>{{ $pendingFollowups }} overdue
                </div>
                <div class="prog"><div class="prog-fill" style="background:linear-gradient(90deg,#f59e0b,#fbbf24);width:{{ $followupsCount > 0 ? min(100,($followupsCount - $pendingFollowups)/max($followupsCount,1)*100) : 0 }}%;"></div></div>
            </div>
        </div>

        <div class="col-6 col-xl">
            <div class="kpi h-100">
                <div class="kpi-top-bar" style="background:linear-gradient(90deg,#06b6d4,#67e8f9);"></div>
                <div class="kpi-icon" style="background:#cffafe;color:#0891b2;"><i class="fas fa-bullseye"></i></div>
                <div class="kpi-label">Demos Today</div>
                <div class="kpi-num" data-count="{{ $demosToday }}">{{ $demosToday }}</div>
                <div class="kpi-sub" style="color:var(--tc-muted);">🌅 {{ $demosMorning }} &nbsp;🌆 {{ $demosEvening }}</div>
                <div class="prog"><div class="prog-fill" style="background:linear-gradient(90deg,#06b6d4,#67e8f9);width:{{ $demosCount > 0 ? min(100,$demosToday/max($demosCount,1)*100) : 0 }}%;"></div></div>
            </div>
        </div>

        <div class="col-6 col-xl">
            <div class="kpi h-100">
                <div class="kpi-top-bar" style="background:linear-gradient(90deg,#10b981,#34d399);"></div>
                <div class="kpi-icon" style="background:#d1fae5;color:#059669;"><i class="fas fa-chart-line"></i></div>
                <div class="kpi-label">Conv. Rate</div>
                <div class="kpi-num">{{ $conversionRate }}<span style="font-size:1rem;font-weight:600;">%</span></div>
                <div class="kpi-sub badge-up"><i class="fas fa-trophy me-1"></i>{{ $registeredCount }} registered</div>
                <div class="prog"><div class="prog-fill" style="background:linear-gradient(90deg,#10b981,#34d399);width:{{ min(100,$conversionRate) }}%;"></div></div>
            </div>
        </div>

        <div class="col-6 col-xl">
            <div class="kpi h-100">
                <div class="kpi-top-bar" style="background:linear-gradient(90deg,#ef4444,#f87171);"></div>
                <div class="kpi-icon" style="background:#fee2e2;color:#ef4444;"><i class="fas fa-graduation-cap"></i></div>
                <div class="kpi-label">Registered</div>
                <div class="kpi-num" data-count="{{ $registeredCount }}">{{ $registeredCount }}</div>
                <div class="kpi-sub badge-up"><i class="fas fa-arrow-up me-1"></i>+{{ $registeredThisWeek }} this week</div>
                <div class="prog"><div class="prog-fill" style="background:linear-gradient(90deg,#ef4444,#f87171);width:{{ $assignedLeadsCount > 0 ? min(100,$registeredCount/max($assignedLeadsCount,1)*100) : 0 }}%;"></div></div>
            </div>
        </div>

    </div>

    {{-- CALL COUNTS ROW --}}
    <div class="row g-3 mb-4">
        <!-- Total Inbound -->
        <div class="col-6 col-xl">
            <div class="kpi h-100">
                <div class="kpi-top-bar" style="background:linear-gradient(90deg,#6366f1,#8b5cf6);"></div>
                <div class="kpi-icon" style="background:#eef2ff;color:#6366f1;"><i class="fas fa-phone-alt"></i></div>
                <div class="kpi-label">Total Inbound Leads</div>
                <div class="kpi-num" data-count="{{ $totalInbound }}">{{ $totalInbound }}</div>
                <div class="kpi-sub badge-up"><i class="fas fa-arrow-up me-1"></i>+{{ $todayInbound }} today</div>
            </div>
        </div>
        <!-- Total Outbound -->
        <div class="col-6 col-xl">
            <div class="kpi h-100">
                <div class="kpi-top-bar" style="background:linear-gradient(90deg,#f59e0b,#fbbf24);"></div>
                <div class="kpi-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-phone-volume"></i></div>
                <div class="kpi-label">Total Outbound Leads</div>
                <div class="kpi-num" data-count="{{ $totalOutbound }}">{{ $totalOutbound }}</div>
                <div class="kpi-sub badge-up"><i class="fas fa-arrow-up me-1"></i>+{{ $todayOutbound }} today</div>
            </div>
        </div>
        <!-- Today Inbound -->
        <div class="col-6 col-xl">
            <div class="kpi h-100">
                <div class="kpi-top-bar" style="background:linear-gradient(90deg,#06b6d4,#67e8f9);"></div>
                <div class="kpi-icon" style="background:#cffafe;color:#0891b2;"><i class="fas fa-phone-alt"></i></div>
                <div class="kpi-label">Today Inbound Leads</div>
                <div class="kpi-num" data-count="{{ $todayInbound }}">{{ $todayInbound }}</div>
            </div>
        </div>
        <!-- Today Outbound -->
        <div class="col-6 col-xl">
            <div class="kpi h-100">
                <div class="kpi-top-bar" style="background:linear-gradient(90deg,#10b981,#34d399);"></div>
                <div class="kpi-icon" style="background:#d1fae5;color:#059669;"><i class="fas fa-phone-volume"></i></div>
                <div class="kpi-label">Today Outbound Leads</div>
                <div class="kpi-num" data-count="{{ $todayOutbound }}">{{ $todayOutbound }}</div>
            </div>
        </div>
    </div>

    {{-- MIDDLE ROW --}}
    <div class="row g-3 mb-3">

        {{-- Lead Quality Donut --}}
        <div class="col-xl-4">
            <div class="sc h-100">
                <div class="sc-head">
                    <span class="sc-title"><span class="dot" style="background:#6366f1;"></span>Lead Quality</span>
                    <span class="sc-badge" style="background:#eef2ff;color:#6366f1;">This Month</span>
                </div>
                <div class="px-4 pb-4">
                    <div class="donut-wrap">
                        <canvas id="tc-donut"></canvas>
                        <div class="donut-center">
                            <div class="donut-val">{{ $assignedLeadsCount }}</div>
                            <div class="donut-lbl">Total</div>
                        </div>
                    </div>
                    @php $total = max(array_sum(array_column($tagBreakdown,'count')),1); @endphp
                    @if(count($tagBreakdown) > 0)
                        @foreach($tagBreakdown as $tag)
                        <div class="leg-row">
                            <div class="d-flex align-items-center gap-2">
                                <span class="leg-dot" style="background:{{ $tag['color'] }};"></span>
                                <span class="leg-name">{{ $tag['label'] }}</span>
                            </div>
                            <div>
                                <span class="leg-val">{{ $tag['count'] }}</span>
                                <span class="leg-pct">{{ round($tag['count']/$total*100) }}%</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="leg-row">
                            <div class="d-flex align-items-center gap-2">
                                <span class="leg-dot" style="background:#94a3b8;"></span>
                                <span class="leg-name">New Lead</span>
                            </div>
                            <div><span class="leg-val">{{ $assignedLeadsCount }}</span><span class="leg-pct">100%</span></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Leads --}}
        <div class="col-xl-4">
            <div class="sc h-100">
                <div class="sc-head">
                    <span class="sc-title"><span class="dot" style="background:#6366f1;"></span>Leads</span>
                    <span class="sc-badge" style="background:#eef2ff;color:#6366f1;">{{ $todayLeads->count() }}</span>
                </div>
                <div class="px-4 pb-1">
                    @forelse($todayLeads as $lead)
                    @php
                        $nm  = $lead->name ?? 'Unknown';
                        $ini = strtoupper(substr($nm,0,1).(substr(strstr($nm,' ')??' ',1,1)));
                        $avColors = ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b'];
                        $avBg     = $avColors[$loop->index % count($avColors)];
                        $tl       = strtolower($lead->lead_tag ?? '');
                        $tc       = match($tl){ 'hot'=>'t-hot','warm'=>'t-warm','cold'=>'t-cold','contacted'=>'t-contacted','mismatch'=>'t-mismatch',default=>'t-new' };
                    @endphp
                    <div class="lead-row">
                        <div class="av" style="background:{{ $avBg }};">{{ $ini }}</div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="ln">{{ $nm }}</div>
                            <div class="lph">{{ $lead->phone ?? '' }}</div>
                        </div>
                        <span class="tag-pill {{ $tc }}">{{ strtoupper($lead->lead_tag ?? 'New') }}</span>
                        <span class="ltime">{{ \Carbon\Carbon::parse($lead->created_at)->format('h:i A') }}</span>
                    </div>
                    @empty
                    <div class="empty-box"><i class="fas fa-inbox"></i><p>No leads assigned today</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Demos --}}
        <div class="col-xl-4">
            <div class="sc h-100">
                <div class="sc-head">
                    <span class="sc-title"><span class="dot" style="background:#06b6d4;"></span>Demos</span>
                    <span class="sc-badge" style="background:#cffafe;color:#0891b2;">{{ $todayDemos->count() }}</span>
                </div>
                <div class="px-4 pb-1">
                    @forelse($todayDemos as $demo)
                    @php
                        $nm2 = $demo->contact->name ?? 'Unknown';
                        $in2 = strtoupper(substr($nm2,0,1).(substr(strstr($nm2,' ')??' ',1,1)));
                        $av2 = ['#06b6d4','#6366f1','#10b981','#8b5cf6','#f59e0b'];
                        $bg2 = $av2[$loop->index % count($av2)];
                    @endphp
                    <div class="lead-row">
                        <div class="av" style="background:{{ $bg2 }};">{{ $in2 }}</div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="ln">{{ $nm2 }}</div>
                            <div class="lph">{{ $demo->contact->caller_number ?? '' }}</div>
                        </div>
                        <span class="demo-time"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($demo->demo_time)->format('h:i A') }}</span>
                    </div>
                    @empty
                    <div class="empty-box"><i class="fas fa-calendar-times"></i><p>No demos scheduled today</p></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- FOLLOWUPS TABLE --}}
    <div class="sc">
        <div class="sc-head">
            <span class="sc-title"><i class="fas fa-bell me-2" style="color:#f59e0b;font-size:.85rem;"></i>Follow-ups</span>
            <a href="{{ route('followups.index') }}" class="btn-view">View All <i class="fas fa-arrow-right ms-1" style="font-size:.65rem;"></i></a>
        </div>
        <div class="table-responsive">
            <table class="table tc-tbl mb-0">
                <thead>
                    <tr>
                        <th>Contact</th>
                        <th>Phone</th>
                        <th>Tag</th>
                        <th>Follow-up Time</th>
                        <th>Last Activity</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayFollowups as $followup)
                    @php
                        $fn3  = $followup->contact->name ?? '—';
                        $ini3 = strtoupper(substr($fn3,0,1));
                        $rowC = ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444'];
                        $rc   = $rowC[$loop->index % count($rowC)];
                        $tl3  = strtolower($followup->lead_tag ?? '');
                        $tc3  = match($tl3){ 'hot'=>'t-hot','warm'=>'t-warm','cold'=>'t-cold','contacted'=>'t-contacted','mismatch'=>'t-mismatch',default=>'t-new' };
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="av" style="background:{{ $rc }};width:30px;height:30px;font-size:.68rem;">{{ $ini3 }}</div>
                                <span class="cn">{{ $fn3 }}</span>
                            </div>
                        </td>
                        <td style="color:var(--tc-muted);">{{ $followup->contact->caller_number ?? '—' }}</td>
                        <td><span class="tag-pill {{ $tc3 }}">{{ strtoupper($followup->lead_tag ?? 'New') }}</span></td>
                        <td>
                            @if($followup->followup_time)
                                <span style="color:#0891b2;font-weight:600;font-size:.8rem;"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($followup->followup_time)->format('h:i A') }}</span>
                            @else <span style="color:var(--tc-muted);">—</span> @endif
                        </td>
                        <td style="color:var(--tc-muted);">{{ $followup->updated_at->diffForHumans() }}</td>
                        <td class="text-end">
                            <a href="{{ route('calls.profile', $followup->contact->id ?? 0) }}" class="btn-view">
                                <i class="fas fa-eye me-1" style="font-size:.65rem;"></i>View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-box"><i class="fas fa-check-circle" style="color:#10b981;opacity:1;"></i><p>No follow-ups for today. Great job!</p></div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('script')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    /* ===== TAG BREAKDOWN from PHP ===== */
    var tagBreakdown = @json($tagBreakdown);
    var grandTotal   = tagBreakdown.reduce(function(s,t){ return s + t.count; }, 0);

    var chartData   = tagBreakdown.length ? tagBreakdown.map(function(t){ return t.count; }) : [1];
    var chartColors = tagBreakdown.length ? tagBreakdown.map(function(t){ return t.color;  }) : ['#e2e8f0'];
    var chartLabels = tagBreakdown.length ? tagBreakdown.map(function(t){ return t.label;  }) : ['No Data'];

    /* ===== DONUT ===== */
    var canvas = document.getElementById('tc-donut');
    if (canvas) {
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: chartColors,
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '76%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                var pct = grandTotal > 0 ? Math.round(ctx.raw / grandTotal * 100) : 0;
                                return ' ' + ctx.label + ': ' + ctx.raw + ' (' + pct + '%)';
                            }
                        }
                    }
                },
                animation: { animateRotate: true, duration: 800 }
            }
        });
    }

    /* ===== PROGRESS BARS ===== */
    document.querySelectorAll('.prog-fill').forEach(function(el) {
        var w = el.style.width;
        el.style.width = '0';
        setTimeout(function(){ el.style.width = w; }, 150);
    });

})();
</script>
@endpush
