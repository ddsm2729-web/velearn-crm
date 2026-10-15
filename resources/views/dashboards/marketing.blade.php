@extends('layouts.master')

@section('pageTitle', 'Digital Marketing Dashboard')

@push('css')
<style>
    :root {
        --sa-indigo:  #4f46e5;
        --sa-emerald: #10b981;
        --sa-amber:   #f59e0b;
        --sa-rose:    #ef4444;
        --sa-violet:  #8b5cf6;
        --sa-cyan:    #06b6d4;
        --sa-dark:    #0f172a;
        --sa-muted:   #64748b;
        --sa-border:  #e2e8f0;
        --sa-bg:      #f1f5f9;
        --sa-card:    #ffffff;
        --sa-radius:  .875rem;
        --sa-shadow:  0 1px 3px rgba(0,0,0,.05), 0 4px 20px rgba(0,0,0,.07);
        --sa-shadow-hover: 0 8px 28px rgba(79,70,229,.15);
    }
    body { background: var(--sa-bg) !important; }

    /* === HERO === */
    .sa-hero {
        background: linear-gradient(130deg, #1e1b4b 0%, #4338ca 50%, #0891b2 100%);
        border-radius: 1.25rem; padding: 2.25rem 2.5rem;
        color:#fff; position:relative; overflow:hidden; margin-bottom:1.75rem;
    }
    .sa-hero::before{content:'';position:absolute;top:-60px;right:-60px;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,.06);}
    .sa-hero::after {content:'';position:absolute;bottom:-80px;right:100px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.04);}
    .sa-hero h2  {font-size:1.6rem;font-weight:800;margin-bottom:.3rem;}
    .sa-hero p   {opacity:.75;font-size:.875rem;margin:0;}
    .hero-chip   {background:rgba(255,255,255,.16);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.26);border-radius:2rem;padding:.4rem 1rem;font-size:.78rem;color:#fff;display:inline-flex;align-items:center;gap:.4rem;}
    .live-dot    {width:7px;height:7px;border-radius:50%;background:#4ade80;display:inline-block;animation:pulse-l 1.5s infinite;}
    @keyframes pulse-l{0%,100%{opacity:1;}50%{opacity:.4;}}

    /* === STAT CARD === */
    .sc{background:var(--sa-card);border-radius:var(--sa-radius);box-shadow:var(--sa-shadow);border:1px solid var(--sa-border);transition:transform .2s,box-shadow .2s;position:relative;overflow:hidden;}
    .sc:hover{transform:translateY(-3px);box-shadow:var(--sa-shadow-hover);}
    .sc-bar{height:3px;border-radius:var(--sa-radius) var(--sa-radius) 0 0;}
    .sc-body{padding:1.4rem 1.5rem;}
    .sc-label{font-size:.68rem;text-transform:uppercase;letter-spacing:.09em;color:var(--sa-muted);font-weight:700;margin-bottom:.35rem;}
    .sc-val  {font-size:2rem;font-weight:800;color:var(--sa-dark);line-height:1.1;margin-bottom:.3rem;}
    .sc-sub  {font-size:.76rem;color:var(--sa-muted);}
    .sc-icon {width:44px;height:44px;border-radius:.75rem;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
    .clr-up  {color:#10b981;font-weight:600;}
    .clr-dn  {color:#ef4444;font-weight:600;}

    /* === SECTION HEADING === */
    .sec-title{font-size:.95rem;font-weight:700;color:var(--sa-dark);display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;}
    .sec-title .tbar{width:3px;height:1.1rem;border-radius:99px;display:inline-block;}

    /* === PANEL CARD === */
    .panel{background:var(--sa-card);border-radius:var(--sa-radius);box-shadow:var(--sa-shadow);border:1px solid var(--sa-border);overflow:hidden;}
    .panel-head{padding:1.1rem 1.5rem;border-bottom:1px solid var(--sa-border);display:flex;align-items:center;justify-content:space-between;}
    .panel-title{font-size:.875rem;font-weight:700;color:var(--sa-dark);margin:0;}
    .panel-badge{font-size:.7rem;font-weight:700;padding:.25rem .75rem;border-radius:99px;}

    /* === TABLE === */
    .sa-tbl thead th{background:#f8fafc;font-size:.67rem;text-transform:uppercase;letter-spacing:.08em;color:var(--sa-muted);font-weight:700;border-bottom:2px solid var(--sa-border);padding:.8rem 1.1rem;}
    .sa-tbl tbody td{padding:.85rem 1.1rem;vertical-align:middle;border-bottom:1px solid var(--sa-border);font-size:.82rem;color:var(--sa-dark);}
    .sa-tbl tbody tr:last-child td{border-bottom:none;}
    .sa-tbl tbody tr:hover{background:#fafbfd;}

    /* === PILL TAGS === */
    .tp{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;padding:.2rem .65rem;border-radius:99px;}
    .tp-hot   {background:#fee2e2;color:#ef4444;}
    .tp-warm  {background:#fef3c7;color:#d97706;}
    .tp-cold  {background:#eef2ff;color:#6366f1;}
    .tp-new   {background:#f1f5f9;color:#64748b;}
    .tp-in    {background:#d1fae5;color:#059669;}
    .tp-out   {background:#cffafe;color:#0891b2;}
    .tp-miss  {background:#fee2e2;color:#ef4444;}

    /* === AVATAR === */
    .av{width:34px;height:34px;border-radius:50%;font-size:.72rem;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;}

    /* === CALL DONUT === */
    .call-ring-wrap{position:relative;height:170px;margin:1rem 0;}
    .call-center{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;}
    .call-center-val{font-size:1.7rem;font-weight:800;color:var(--sa-dark);line-height:1;}
    .call-center-lbl{font-size:.63rem;color:var(--sa-muted);text-transform:uppercase;letter-spacing:.06em;}

    /* === STAFF MINI CARD === */
    .staff-card{background:#f8fafc;border:1px solid var(--sa-border);border-radius:.75rem;padding:1rem;display:flex;align-items:center;gap:.85rem;transition:all .18s;}
    .staff-card:hover{background:#eef2ff;border-color:#c7d2fe;}
    .staff-av{width:42px;height:42px;border-radius:.75rem;font-size:.9rem;font-weight:800;color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;}

    /* === PROG BAR === */
    .prog{height:5px;border-radius:99px;background:#e2e8f0;overflow:hidden;margin-top:.6rem;}
    .prog-fill{height:100%;border-radius:99px;transition:width 1s cubic-bezier(.4,0,.2,1);}

    /* === ACTION STRIP === */
    .action-strip{background:var(--sa-card);border:1px solid var(--sa-border);border-radius:var(--sa-radius);padding:1rem 1.5rem;display:flex;flex-wrap:wrap;gap:.6rem;align-items:center;}
    .act-btn{font-size:.78rem;font-weight:600;padding:.4rem 1rem;border-radius:99px;border:1px solid var(--sa-border);background:#fff;color:var(--sa-dark);text-decoration:none;transition:all .18s;display:inline-flex;align-items:center;gap:.4rem;}
    .act-btn:hover{background:var(--sa-indigo);color:#fff;border-color:var(--sa-indigo);}
    .act-btn-primary{background:var(--sa-indigo);color:#fff;border-color:var(--sa-indigo);}
    .act-btn-primary:hover{background:#4338ca;color:#fff;}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-end mb-3">
        @include('partials.daterange_picker')
    </div>

    {{-- HERO --}}
    <div class="sa-hero mb-4">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div style="position:relative;z-index:1;">
                <div class="mb-2"><i class="fas fa-shield-alt fa-lg" style="opacity:.8;"></i></div>
                <h2 class="text-white">Welcome back, {{ auth()->user()->name ?? 'Admin' }} 👋</h2>
                <p>Institute overview · {{ now()->format('l, d M Y') }} · All data is live from the database.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-start" style="position:relative;z-index:1;">
                <span class="hero-chip"><i class="far fa-calendar-alt"></i> {{ now()->format('d M Y') }}</span>
                <span class="hero-chip"><span class="live-dot"></span> Live Data</span>
                <span class="hero-chip"><i class="fas fa-users"></i> {{ $activeStaff ?? 0 }} Staff Active</span>
            </div>
        </div>
    </div>

    {{-- ===== KPI ROW 1: LEADS & CALLS ===== --}}
    <div class="sec-title"><span class="tbar" style="background:var(--sa-indigo);"></span>Leads & Call Activity</div>
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="sc h-100">
                <div class="sc-bar" style="background:linear-gradient(90deg,#4f46e5,#8b5cf6);"></div>
                <div class="sc-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sc-label">Total Leads</div>
                        <div class="sc-val">{{ $totalLeads ?? 0 }}</div>
                        <div class="sc-sub clr-up"><i class="fas fa-arrow-up me-1"></i>+{{ $leadsToday ?? 0 }} today</div>
                    </div>
                    <div class="sc-icon" style="background:#eef2ff;color:#4f46e5;"><i class="fas fa-users"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc h-100">
                <div class="sc-bar" style="background:linear-gradient(90deg,#10b981,#34d399);"></div>
                <div class="sc-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sc-label">Total Calls</div>
                        <div class="sc-val">{{ $totalCalls ?? 0 }}</div>
                        <div class="sc-sub" style="color:var(--sa-muted);">+{{ $callsToday ?? 0 }} today</div>
                    </div>
                    <div class="sc-icon" style="background:#d1fae5;color:#10b981;"><i class="fas fa-phone-alt"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc h-100">
                <div class="sc-bar" style="background:linear-gradient(90deg,#f59e0b,#fbbf24);"></div>
                <div class="sc-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sc-label">Follow-ups</div>
                        <div class="sc-val">{{ $pendingFollowups ?? 0 }}</div>
                        <div class="sc-sub {{ ($overdueFollowups ?? 0) > 0 ? 'clr-dn' : 'clr-up' }}">{{ $overdueFollowups ?? 0 }} overdue</div>
                    </div>
                    <div class="sc-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-sync-alt"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc h-100">
                <div class="sc-bar" style="background:linear-gradient(90deg,#ef4444,#f87171);"></div>
                <div class="sc-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sc-label">Demos Today</div>
                        <div class="sc-val">{{ $demosToday ?? 0 }}</div>
                        <div class="sc-sub clr-up">{{ $demosThisWeek ?? 0 }} this week</div>
                    </div>
                    <div class="sc-icon" style="background:#fee2e2;color:#ef4444;"><i class="fas fa-bullseye"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== KPI ROW 2: STUDENTS & INSTITUTE ===== --}}
    <div class="sec-title"><span class="tbar" style="background:var(--sa-emerald);"></span>Institute Overview</div>
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="sc h-100">
                <div class="sc-bar" style="background:linear-gradient(90deg,#10b981,#34d399);"></div>
                <div class="sc-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sc-label">Active Students</div>
                        <div class="sc-val">{{ $activeStudents ?? 0 }}</div>
                        <div class="sc-sub clr-up">+{{ $admissionsToday ?? 0 }} joined today</div>
                    </div>
                    <div class="sc-icon" style="background:#d1fae5;color:#10b981;"><i class="fas fa-user-graduate"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc h-100">
                <div class="sc-bar" style="background:linear-gradient(90deg,#06b6d4,#67e8f9);"></div>
                <div class="sc-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sc-label">Live Courses</div>
                        <div class="sc-val">{{ $liveCourses ?? 0 }}</div>
                        <div class="sc-sub" style="color:var(--sa-muted);">{{ $activeBatches ?? 0 }} active batches</div>
                    </div>
                    <div class="sc-icon" style="background:#cffafe;color:#0891b2;"><i class="fas fa-book-open"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc h-100">
                <div class="sc-bar" style="background:linear-gradient(90deg,#8b5cf6,#a78bfa);"></div>
                <div class="sc-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sc-label">Converted</div>
                        <div class="sc-val">{{ $convertedLeads ?? 0 }}</div>
                        <div class="sc-sub clr-up">{{ $conversionRate ?? 0 }}% conv. rate</div>
                    </div>
                    <div class="sc-icon" style="background:#ede9fe;color:#8b5cf6;"><i class="fas fa-trophy"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc h-100">
                <div class="sc-bar" style="background:linear-gradient(90deg,#ef4444,#f87171);"></div>
                <div class="sc-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sc-label">Open Tickets</div>
                        <div class="sc-val">{{ $openTickets ?? 0 }}</div>
                        <div class="sc-sub {{ ($highPriority ?? 0) > 0 ? 'clr-dn' : 'clr-up' }}">{{ $highPriority ?? 0 }} high priority</div>
                    </div>
                    <div class="sc-icon" style="background:#fee2e2;color:#ef4444;"><i class="fas fa-headset"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MIDDLE ROW: CHARTS + RECENT LEADS ===== --}}
    <div class="row g-3 mb-3">

        {{-- Call Breakdown Donut --}}
        <div class="col-xl-3">
            <div class="panel h-100">
                <div class="panel-head">
                    <span class="panel-title">Call Breakdown</span>
                    <span class="panel-badge" style="background:#eef2ff;color:#4f46e5;">{{ $totalCalls ?? 0 }} total</span>
                </div>
                <div class="px-4 pb-4">
                    <div class="call-ring-wrap">
                        <canvas id="sa-call-donut"></canvas>
                        <div class="call-center">
                            <div class="call-center-val">{{ $totalCalls ?? 0 }}</div>
                            <div class="call-center-lbl">Calls</div>
                        </div>
                    </div>
                    @php
                        $inboundCallsVal = $inboundCalls ?? 0;
                        $outboundCallsVal = $outboundCalls ?? 0;
                        $missedCallsVal = $missedCalls ?? 0;
                        $callTotal = max($inboundCallsVal + $outboundCallsVal + $missedCallsVal, 1);
                    @endphp
                    <div style="border-top:1px solid var(--sa-border); padding-top:.75rem;">
                        <div class="d-flex align-items-center justify-content-between py-1">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width:9px;height:9px;border-radius:50%;background:#10b981;display:inline-block;"></span>
                                <span style="font-size:.78rem;color:var(--sa-dark);">Inbound</span>
                            </div>
                            <div>
                                <span style="font-size:.82rem;font-weight:700;color:var(--sa-dark);">{{ $inboundCallsVal }}</span>
                                <span style="font-size:.7rem;color:var(--sa-muted);margin-left:.3rem;">{{ round($inboundCallsVal / $callTotal * 100) }}%</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-1">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width:9px;height:9px;border-radius:50%;background:#4f46e5;display:inline-block;"></span>
                                <span style="font-size:.78rem;color:var(--sa-dark);">Outbound</span>
                            </div>
                            <div>
                                <span style="font-size:.82rem;font-weight:700;color:var(--sa-dark);">{{ $outboundCallsVal }}</span>
                                <span style="font-size:.7rem;color:var(--sa-muted);margin-left:.3rem;">{{ round($outboundCallsVal / $callTotal * 100) }}%</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-1">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width:9px;height:9px;border-radius:50%;background:#ef4444;display:inline-block;"></span>
                                <span style="font-size:.78rem;color:var(--sa-dark);">Missed</span>
                            </div>
                            <div>
                                <span style="font-size:.82rem;font-weight:700;color:var(--sa-dark);">{{ $missedCallsVal }}</span>
                                <span style="font-size:.7rem;color:var(--sa-muted);margin-left:.3rem;">{{ round($missedCallsVal / $callTotal * 100) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lead Quality Donut --}}
        <div class="col-xl-3">
            <div class="panel h-100">
                <div class="panel-head">
                    <span class="panel-title">Lead Quality</span>
                    <span class="panel-badge" style="background:#d1fae5;color:#059669;">{{ $totalLeads ?? 0 }} leads</span>
                </div>
                <div class="px-4 pb-4">
                    <div class="call-ring-wrap">
                        <canvas id="sa-lead-donut"></canvas>
                        <div class="call-center">
                            <div class="call-center-val">{{ $hotLeads ?? 0 }}</div>
                            <div class="call-center-lbl">Hot Leads</div>
                        </div>
                    </div>
                    @php
                        $hotVal = $hotLeads ?? 0;
                        $warmVal = $warmLeads ?? 0;
                        $coldVal = $coldLeads ?? 0;
                        $contactedVal = $contactedLeads ?? 0;
                        $newVal = $newLeads ?? 0;
                        $lt = max($hotVal + $warmVal + $coldVal + $contactedVal + $newVal, 1);
                    @endphp
                    <div style="border-top:1px solid var(--sa-border); padding-top:.75rem;">
                        @foreach([['Hot', $hotVal, '#ef4444'], ['Warm', $warmVal, '#f59e0b'], ['Cold', $coldVal, '#6366f1'], ['Contacted', $contactedVal, '#10b981'], ['New', $newVal, '#94a3b8']] as [$lname, $lcount, $lcolor])
                        @if($lcount > 0)
                        <div class="d-flex align-items-center justify-content-between py-1">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width:9px;height:9px;border-radius:50%;background:{{ $lcolor }};display:inline-block;"></span>
                                <span style="font-size:.78rem;color:var(--sa-dark);">{{ $lname }}</span>
                            </div>
                            <div>
                                <span style="font-size:.82rem;font-weight:700;color:var(--sa-dark);">{{ $lcount }}</span>
                                <span style="font-size:.7rem;color:var(--sa-muted);margin-left:.3rem;">{{ round($lcount / $lt * 100) }}%</span>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Leads --}}
        <div class="col-xl-6">
            <div class="panel h-100">
                <div class="panel-head">
                    <span class="panel-title">Recent Leads</span>
                    <a href="{{ Route::has('calls.inbound') ? route('calls.inbound') : '#' }}" class="panel-badge" style="background:#eef2ff;color:#4f46e5;text-decoration:none;">View All →</a>
                </div>
                <div class="table-responsive">
                    <table class="table sa-tbl mb-0">
                        <thead>
                            <tr>
                                <th>Contact</th>
                                <th>Direction</th>
                                <th>Tag</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeads as $lead)
                            @php
                                $rn = $lead->contact->name ?? 'Unknown';
                                $ri = strtoupper(substr($rn,0,1));
                                $rcolors = ['#4f46e5','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444'];
                                $rc = $rcolors[$loop->index % count($rcolors)];
                                $dir = strtolower($lead->direction ?? '');
                                $dirClass = match($dir){ 'inbound'=>'tp-in','outgoing','outbound'=>'tp-out','missed'=>'tp-miss',default=>'tp-new' };
                                $tl = strtolower($lead->lead_tag ?? '');
                                $tc = match($tl){ 'hot'=>'tp-hot','warm'=>'tp-warm','cold'=>'tp-cold',default=>'tp-new' };
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="av" style="background:{{ $rc }};">{{ $ri }}</div>
                                        <div>
                                            <div style="font-weight:600;">{{ $rn }}</div>
                                            <div style="font-size:.72rem;color:var(--sa-muted);">{{ $lead->contact->caller_number ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="tp {{ $dirClass }}">{{ ucfirst($lead->direction ?? 'N/A') }}</span></td>
                                <td><span class="tp {{ $tc }}">{{ strtoupper($lead->lead_tag ?? 'New') }}</span></td>
                                <td style="color:var(--sa-muted);">{{ \Carbon\Carbon::parse($lead->created_at)->format('d M, h:i A') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4" style="color:var(--sa-muted);font-size:.82rem;">No leads yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ROW: TOP COURSES + STAFF PERFORMANCE ===== --}}
    <div class="row g-3 mb-3">
        {{-- Top Courses --}}
        <div class="col-xl-7">
            <div class="panel h-100">
                <div class="panel-head">
                    <span class="panel-title"><i class="fas fa-award me-2" style="color:#f59e0b;"></i>Top Courses by Enrolment</span>
                    <a href="{{ Route::has('courses.index') ? route('courses.index') : '#' }}" class="panel-badge" style="background:#fef3c7;color:#d97706;text-decoration:none;">View All →</a>
                </div>
                <div class="p-4">
                    @forelse($topCourses as $course)
                    @php
                        $ccolors = ['#4f46e5','#e83e8c','#20c997','#fd7e14','#0d6efd'];
                        $cc = $ccolors[$loop->index % count($ccolors)];
                        $maxEnrol = $topCourses->max('admissions_count') ?: 1;
                        $pct = round($course->admissions_count / $maxEnrol * 100);
                        $courseTitle = $course->full_title ?? $course->title ?? 'N/A';
                    @endphp
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:32px;height:32px;border-radius:.5rem;background:{{ $cc }};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.78rem;flex-shrink:0;">{{ $loop->iteration }}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size:.84rem;font-weight:600;color:var(--sa-dark);">{{ \Illuminate\Support\Str::limit($courseTitle, 45) }}</span>
                                <span style="font-size:.78rem;font-weight:700;color:{{ $cc }};">{{ $course->admissions_count }}</span>
                            </div>
                            <div class="prog"><div class="prog-fill" style="background:{{ $cc }};width:{{ $pct }}%;"></div></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4" style="color:var(--sa-muted);font-size:.82rem;">No course data found.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Staff Performance --}}
        <div class="col-xl-5">
            <div class="panel h-100">
                <div class="panel-head">
                    <span class="panel-title"><i class="fas fa-chart-bar me-2" style="color:#8b5cf6;"></i>Staff Call Performance</span>
                </div>
                <div class="p-4">
                    @forelse($staffPerformance as $sp)
                    @php
                        $spcolors = ['#4f46e5','#10b981','#f59e0b','#ef4444','#8b5cf6'];
                        $spc = $spcolors[$loop->index % count($spcolors)];
                        $initSP = strtoupper(substr($sp->first_name ?? '',0,1).substr($sp->last_name ?? '',0,1));
                        if(empty($initSP)) { $initSP = 'ST'; }
                        $maxCalls = $staffPerformance->max('call_logs_count') ?: 1;
                        $spPct = round($sp->call_logs_count / $maxCalls * 100);
                    @endphp
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="staff-av" style="background:{{ $spc }};">{{ $initSP }}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div>
                                    <div style="font-size:.83rem;font-weight:600;color:var(--sa-dark);">{{ $sp->first_name }} {{ $sp->last_name }}</div>
                                    <div style="font-size:.7rem;color:var(--sa-muted);">{{ $sp->role->name ?? ($sp->user?->roles?->first()?->name ?? 'Staff') }}</div>
                                </div>
                                <span style="font-size:.82rem;font-weight:700;color:{{ $spc }};">{{ $sp->call_logs_count }} calls</span>
                            </div>
                            <div class="prog"><div class="prog-fill" style="background:{{ $spc }};width:{{ $spPct }}%;"></div></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4" style="color:var(--sa-muted);font-size:.82rem;">No staff data found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ===== REVENUE TREND & LEAD FUNNEL ===== --}}
    @include('dashboards.partials.revenue_funnel_cards')

    {{-- ===== QUICK ACTIONS ===== --}}
    <div class="action-strip mb-3">
        <span style="font-size:.78rem;font-weight:700;color:var(--sa-muted);">Quick Actions:</span>
        <a href="{{ Route::has('calls.inbound') ? route('calls.inbound') : '#' }}" class="act-btn"><i class="fas fa-phone-alt"></i> Inbound Calls</a>
        <a href="{{ Route::has('followups.index') ? route('followups.index') : '#' }}" class="act-btn"><i class="fas fa-sync-alt"></i> Follow-ups</a>
        <a href="{{ Route::has('staffs.index') ? route('staffs.index') : '#' }}" class="act-btn"><i class="fas fa-users"></i> Manage Staff</a>
        <a href="{{ Route::has('courses.index') ? route('courses.index') : '#' }}" class="act-btn"><i class="fas fa-book-open"></i> Courses</a>
        <a href="{{ Route::has('admin.staff-monitoring.index') ? route('admin.staff-monitoring.index') : (Route::has('tech.staff-monitoring.index') ? route('tech.staff-monitoring.index') : '#') }}" class="act-btn act-btn-primary"><i class="fas fa-desktop"></i> Staff Monitoring</a>
    </div>

</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function(){
    /* Call Donut */
    var callCanvas = document.getElementById('sa-call-donut');
    if(callCanvas){
        new Chart(callCanvas, {
            type:'doughnut',
            data:{
                labels:['Inbound','Outbound','Missed'],
                datasets:[{
                    data:[{{ $inboundCalls ?? 0 }},{{ $outboundCalls ?? 0 }},{{ $missedCalls ?? 0 }}],
                    backgroundColor:['#10b981','#4f46e5','#ef4444'],
                    borderWidth:3, borderColor:'#ffffff', hoverOffset:6
                }]
            },
            options:{
                responsive:true, maintainAspectRatio:false, cutout:'76%',
                plugins:{ legend:{display:false}, tooltip:{callbacks:{label:function(ctx){ return ' '+ctx.label+': '+ctx.raw; }}} },
                animation:{ animateRotate:true, duration:800 }
            }
        });
    }

    /* Lead Donut */
    var leadCanvas = document.getElementById('sa-lead-donut');
    if(leadCanvas){
        var ldata = [{{ $hotLeads ?? 0 }},{{ $warmLeads ?? 0 }},{{ $coldLeads ?? 0 }},{{ $contactedLeads ?? 0 }},{{ $newLeads ?? 0 }}];
        var ltotal = ldata.reduce(function(a,b){return a+b;},0);
        new Chart(leadCanvas, {
            type:'doughnut',
            data:{
                labels:['Hot','Warm','Cold','Contacted','New'],
                datasets:[{
                    data: ltotal > 0 ? ldata : [1],
                    backgroundColor: ltotal > 0 ? ['#ef4444','#f59e0b','#6366f1','#10b981','#94a3b8'] : ['#e2e8f0'],
                    borderWidth:3, borderColor:'#ffffff', hoverOffset:6
                }]
            },
            options:{
                responsive:true, maintainAspectRatio:false, cutout:'76%',
                plugins:{ legend:{display:false}, tooltip:{callbacks:{label:function(ctx){ return ' '+ctx.label+': '+ctx.raw; }}} },
                animation:{ animateRotate:true, duration:800 }
            }
        });
    }

    /* Progress bars animate in */
    document.querySelectorAll('.prog-fill').forEach(function(el){
        var w = el.style.width;
        el.style.width = '0';
        setTimeout(function(){ el.style.width = w; }, 150);
    });

    /* Feather icons */
    if(typeof feather !== 'undefined'){ feather.replace(); }
})();
</script>
@endpush
