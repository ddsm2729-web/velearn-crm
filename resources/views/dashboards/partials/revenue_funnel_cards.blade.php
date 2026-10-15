{{-- ===== REVENUE TREND & LEAD FUNNEL ROW ===== --}}
<style>
.rf-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    padding: 1.5rem 1.65rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 20px rgba(0, 0, 0, 0.06);
    color: #0f172a;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform .2s ease, box-shadow .2s ease;
}
.rf-card:hover {
    box-shadow: 0 6px 24px rgba(79, 70, 229, 0.1);
}
.rf-badge-pill {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.22rem 0.65rem;
    border-radius: 99px;
}
.rf-badge-total {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 0.22rem 0.75rem;
    border-radius: 99px;
}
.rf-funnel-row {
    margin-bottom: 0.85rem;
}
.rf-funnel-label {
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
}
.rf-funnel-val {
    font-size: 0.82rem;
    font-weight: 700;
    color: #0f172a;
}
.rf-funnel-track {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    height: 8px;
    border-radius: 99px;
    overflow: hidden;
    margin-top: 0.35rem;
}
.rf-funnel-bar {
    height: 100%;
    border-radius: 99px;
    transition: width 0.8s ease-in-out;
}
.rf-kpi-sub {
    font-size: 0.72rem;
    color: #64748b;
    margin-bottom: 0.2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.rf-kpi-num {
    font-size: 1.25rem;
    font-weight: 800;
    line-height: 1.2;
}
.rf-day-bar {
    height: 3px;
    border-radius: 99px;
    background: linear-gradient(90deg, #4f46e5, #06b6d4);
    margin: 0 4px 6px 4px;
    opacity: 0.9;
}
.rf-day-label {
    font-size: 0.74rem;
    color: #64748b;
    font-weight: 600;
}
</style>

<div class="row g-3 mb-4">
    {{-- Left Card: Revenue Trend --}}
    <div class="col-xl-6">
        <div class="rf-card">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span style="font-size:1.15rem;">💵</span>
                        <h5 class="fw-bold mb-0" style="font-size:1.02rem; color: #0f172a;">Revenue Trend</h5>
                        <span class="rf-badge-pill">Last 7 days</span>
                    </div>
                    <span class="rf-badge-total">
                        {{ $revenueTrendTotalFormatted ?? '₹0' }} total
                    </span>
                </div>

                {{-- Chart Area --}}
                <div style="height: 165px; position: relative;" class="my-2">
                    <canvas id="sa-revenue-trend"></canvas>
                </div>

                {{-- 7 Days Indicator Strip --}}
                <div class="d-flex justify-content-between text-center pt-2 pb-1" style="border-top: 1px solid #f1f5f9;">
                    @foreach($revenueTrendDays ?? [] as $dayItem)
                    <div style="flex:1;">
                        <div class="rf-day-bar"></div>
                        <div class="rf-day-label">{{ $dayItem['day'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 3 KPI Columns --}}
            <div class="row pt-3 mt-2" style="border-top: 1px solid #f1f5f9;">
                <div class="col-4">
                    <div class="rf-kpi-sub">Recorded</div>
                    <div class="rf-kpi-num" style="color: #0284c7;">{{ $revenueRecordedFormatted ?? '₹0' }}</div>
                </div>
                <div class="col-4">
                    <div class="rf-kpi-sub">Live</div>
                    <div class="rf-kpi-num" style="color: #9333ea;">{{ $revenueLiveFormatted ?? '₹0' }}</div>
                </div>
                <div class="col-4">
                    <div class="rf-kpi-sub">Subs</div>
                    <div class="rf-kpi-num" style="color: #059669;">{{ $revenueSubsFormatted ?? '₹0' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Card: Lead -> Student Funnel --}}
    <div class="col-xl-6">
        <div class="rf-card">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span style="font-size:1.15rem;">📊</span>
                        <h5 class="fw-bold mb-0" style="font-size:1.02rem; color: #0f172a;">Lead → Student Funnel</h5>
                        <span class="rf-badge-pill">30 days</span>
                    </div>
                </div>

                {{-- 5 Funnel Stages --}}
                <div class="py-1">
                    {{-- 1. Leads --}}
                    <div class="rf-funnel-row">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="rf-funnel-label">Leads</span>
                            <span class="rf-funnel-val">{{ number_format($funnelLeads ?? 0) }} · {{ ($funnelLeads ?? 0) > 0 ? 100 : 0 }}%</span>
                        </div>
                        <div class="rf-funnel-track">
                            <div class="rf-funnel-bar" style="width: {{ ($funnelLeads ?? 0) > 0 ? 100 : 0 }}%; background: linear-gradient(90deg, #06b6d4, #0284c7);"></div>
                        </div>
                    </div>

                    {{-- 2. Contacted --}}
                    <div class="rf-funnel-row">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="rf-funnel-label">Contacted</span>
                            <span class="rf-funnel-val">{{ number_format($funnelContacted ?? 0) }} · {{ $funnelContactedPct ?? 0 }}%</span>
                        </div>
                        <div class="rf-funnel-track">
                            <div class="rf-funnel-bar" style="width: {{ $funnelContactedPct ?? 0 }}%; background: linear-gradient(90deg, #3b82f6, #4f46e5);"></div>
                        </div>
                    </div>

                    {{-- 3. Demo Booked --}}
                    <div class="rf-funnel-row">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="rf-funnel-label">Demo Booked</span>
                            <span class="rf-funnel-val">{{ number_format($funnelDemoBooked ?? 0) }} · {{ $funnelDemoBookedPct ?? 0 }}%</span>
                        </div>
                        <div class="rf-funnel-track">
                            <div class="rf-funnel-bar" style="width: {{ $funnelDemoBookedPct ?? 0 }}%; background: linear-gradient(90deg, #6366f1, #8b5cf6);"></div>
                        </div>
                    </div>

                    {{-- 4. Demo Attended --}}
                    <div class="rf-funnel-row">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="rf-funnel-label">Demo Attended</span>
                            <span class="rf-funnel-val">{{ number_format($funnelDemoAttended ?? 0) }} · {{ $funnelDemoAttendedPct ?? 0 }}%</span>
                        </div>
                        <div class="rf-funnel-track">
                            <div class="rf-funnel-bar" style="width: {{ $funnelDemoAttendedPct ?? 0 }}%; background: linear-gradient(90deg, #c026d3, #db2777);"></div>
                        </div>
                    </div>

                    {{-- 5. Enrolled --}}
                    <div class="rf-funnel-row mb-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="rf-funnel-label">Enrolled</span>
                            <span class="rf-funnel-val">{{ number_format($funnelEnrolled ?? 0) }} · {{ $funnelEnrolledPct ?? 0 }}%</span>
                        </div>
                        <div class="rf-funnel-track">
                            <div class="rf-funnel-bar" style="width: {{ $funnelEnrolledPct ?? 0 }}%; background: linear-gradient(90deg, #10b981, #059669);"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3 KPI Columns --}}
            <div class="row pt-3 mt-3" style="border-top: 1px solid #f1f5f9;">
                <div class="col-4">
                    <div class="rf-kpi-sub">Conv Rate</div>
                    <div class="rf-kpi-num" style="color: #059669;">{{ $funnelConvRate ?? 0 }}%</div>
                </div>
                <div class="col-4">
                    <div class="rf-kpi-sub">Cost/Lead</div>
                    <div class="rf-kpi-num" style="color: #0f172a;">₹{{ number_format($funnelCostPerLead ?? 0) }}</div>
                </div>
                <div class="col-4">
                    <div class="rf-kpi-sub">CAC</div>
                    <div class="rf-kpi-num" style="color: #0f172a;">₹{{ number_format($funnelCAC ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var revCanvas = document.getElementById('sa-revenue-trend');
    if (revCanvas && typeof Chart !== 'undefined') {
        var ctxRev = revCanvas.getContext('2d');
        var gradRev = ctxRev.createLinearGradient(0, 0, 0, 160);
        gradRev.addColorStop(0, 'rgba(79, 70, 229, 0.22)');
        gradRev.addColorStop(1, 'rgba(79, 70, 229, 0.01)');

        new Chart(revCanvas, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueTrendLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($revenueTrendAmounts ?? []) !!},
                    borderColor: '#4f46e5',
                    borderWidth: 2.5,
                    backgroundColor: gradRev,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#4f46e5',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 3.5,
                    pointHoverRadius: 5.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(ctx) {
                                var val = ctx.raw;
                                if (val >= 100000) return ' Revenue: ₹' + (val / 100000).toFixed(1) + 'L';
                                if (val >= 1000) return ' Revenue: ₹' + (val / 1000).toFixed(1) + 'k';
                                return ' Revenue: ₹' + val;
                            }
                        }
                    }
                },
                scales: {
                    x: { display: false },
                    y: {
                        display: true,
                        beginAtZero: true,
                        suggestedMax: 1000,
                        grid: {
                            color: 'rgba(226, 232, 240, 0.8)',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10 },
                            maxTicksLimit: 4,
                            callback: function(value) {
                                if (value >= 10000000) return '₹' + (value/10000000).toFixed(1) + 'Cr';
                                if (value >= 100000) return '₹' + (value/100000).toFixed(0) + 'L';
                                if (value >= 1000) return '₹' + (value/1000).toFixed(0) + 'k';
                                return '₹' + value;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
