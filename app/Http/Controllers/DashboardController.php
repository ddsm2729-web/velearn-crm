<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display Super Admin Dashboard.
     */
    public function admin(Request $request): View
    {
        $data = $this->getSuperAdminDashboardData($request);
        return view('dashboards.admin', $data);
    }

    /**
     * Shared data generator for Super Admin & Digital Marketing Dashboards.
     */
    protected function getSuperAdminDashboardData(Request $request): array
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $dateFilter = function ($query, $column = 'created_at') use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereDate($column, '>=', $startDate)
                      ->whereDate($column, '<=', $endDate);
            } elseif ($startDate) {
                $query->whereDate($column, '>=', $startDate);
            } elseif ($endDate) {
                $query->whereDate($column, '<=', $endDate);
            }
        };

        $today = now()->toDateString();
        $startOfWeek = now()->startOfWeek()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();

        // ---- STAFF ----
        $activeStaffs = \App\Models\Staff::with(['role', 'user.roles'])
            ->where(function ($q) {
                $q->where('status', 1)->orWhere('status', 'active');
            })
            ->whereDoesntHave('role', function ($q) {
                $q->whereRaw('LOWER(name) = ?', ['superadmin']);
            })
            ->whereDoesntHave('user.roles', function ($q) {
                $q->whereRaw('LOWER(name) = ?', ['superadmin']);
            })
            ->get();
        $totalStaff = \App\Models\Staff::whereDoesntHave('role', function ($q) {
                $q->whereRaw('LOWER(name) = ?', ['superadmin']);
            })
            ->whereDoesntHave('user.roles', function ($q) {
                $q->whereRaw('LOWER(name) = ?', ['superadmin']);
            })
            ->count();
        $activeStaff = $activeStaffs->count();

        // ---- CONTACTS ----
        $totalContacts = \App\Models\Contact::where($dateFilter)->count();
        $contactsToday = \App\Models\Contact::whereDate('created_at', $today)->count();

        // ---- CALL LOGS (latest per contact = unique leads) ----
        $leadBase = \App\Models\CallLog::where($dateFilter)->whereIn('id', function ($q) {
            $q->selectRaw('MAX(id)')->from('call_logs')->groupBy('contact_id');
        });
        $totalLeads = (clone $leadBase)->count();
        $leadsToday = (clone $leadBase)->whereDate('created_at', $today)->count();
        $leadsThisWeek = (clone $leadBase)->whereDate('created_at', '>=', $startOfWeek)->count();
        $leadsMonth = (clone $leadBase)->whereDate('created_at', '>=', $startOfMonth)->count();

        // Calls breakdown
        $totalCalls = \App\Models\CallLog::where($dateFilter)->count();
        $inboundCalls = \App\Models\CallLog::where($dateFilter)->where('direction', 'Inbound')->count();
        $outboundCalls = \App\Models\CallLog::where($dateFilter)->whereIn('direction', ['Outbound', 'Outgoing'])->count();
        $missedCalls = \App\Models\CallLog::where($dateFilter)->where('direction', 'Missed')->count();
        $callsToday = \App\Models\CallLog::whereDate('created_at', $today)->count();

        // Lead tag breakdown
        $hotLeads = (clone $leadBase)->whereRaw('LOWER(lead_tag) = ?', ['hot'])->count();
        $warmLeads = (clone $leadBase)->whereRaw('LOWER(lead_tag) = ?', ['warm'])->count();
        $coldLeads = (clone $leadBase)->whereRaw('LOWER(lead_tag) = ?', ['cold'])->count();
        $contactedLeads = (clone $leadBase)->whereRaw('LOWER(lead_tag) = ?', ['contacted'])->count();
        $newLeads = (clone $leadBase)->whereRaw('LOWER(lead_tag) IN (?, ?)', ['new lead', 'new'])->count();

        // Follow-ups
        $pendingFollowups = (clone $leadBase)->whereNotNull('followup_date')->where('followup_status', '!=', 'Completed')->count();
        $followupsToday = (clone $leadBase)->where('followup_date', $today)->count();
        $overdueFollowups = (clone $leadBase)->where('followup_date', '<', $today)->where('followup_status', '!=', 'Completed')->count();

        // Demos
        $demosToday = (clone $leadBase)->where('demo_date', $today)->count();
        $demosThisWeek = (clone $leadBase)->where('demo_date', '>=', $startOfWeek)->count();
        $convertedLeads = (clone $leadBase)->whereRaw('LOWER(demo_status) = ?', ['converted'])->count();

        // ---- COURSES ----
        $totalCourses = \App\Models\Course::where($dateFilter)->count();
        $activeCourses = \App\Models\Course::where('status', '1')->count();
        $liveCourses = \App\Models\Course::where('course_type', 'live')->where('status', '1')->count();

        // ---- BATCHES ----
        $totalBatches = \App\Models\Batch::where($dateFilter)->count();
        $activeBatches = \App\Models\Batch::where('start_date', '<=', $today)->count();

        // ---- ADMISSIONS ----
        $totalAdmissions = \App\Models\Admission::where($dateFilter)->count();
        $activeStudents = \App\Models\Admission::whereIn('status', ['Active', 'Ongoing', 'ongoing'])->count();
        $admissionsToday = \App\Models\Admission::whereDate('created_at', $today)->count();
        $admissionsMonth = \App\Models\Admission::whereDate('created_at', '>=', $startOfMonth)->count();

        // ---- SUPPORT TICKETS ----
        $openTickets = \App\Models\SupportTicket::whereIn('status', ['New', 'Opened', 'Pending', 'In Review'])->where($dateFilter)->count();
        $resolvedToday = \App\Models\SupportTicket::where('status', 'Resolved')->whereDate('updated_at', $today)->count();
        $highPriority = 0;

        // ---- TOP COURSES (by admissions count) ----
        $topCourses = \App\Models\Course::select(
            'courses.*',
            \Illuminate\Support\Facades\DB::raw('(SELECT COUNT(*) FROM admissions WHERE admissions.course_id = courses.id) as admissions_count')
        )
            ->orderByDesc('admissions_count')
            ->take(5)->get();

        // ---- RECENT LEADS ----
        $recentLeads = \App\Models\CallLog::with('contact')
            ->whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')->from('call_logs')->groupBy('contact_id');
            })
            ->whereNotNull('contact_id')
            ->latest()->take(8)->get();

        // ---- STAFF PERFORMANCE (call counts per staff) ----
        $staffPerformance = \App\Models\Staff::with(['role', 'user.roles'])
            ->whereDoesntHave('role', function ($q) {
                $q->whereRaw('LOWER(name) = ?', ['superadmin']);
            })
            ->whereDoesntHave('user.roles', function ($q) {
                $q->whereRaw('LOWER(name) = ?', ['superadmin']);
            })
            ->withCount('callLogs')
            ->orderBy('call_logs_count', 'desc')
            ->take(5)->get();

        // Conversion rate
        $conversionRate = $totalLeads > 0 ? round($convertedLeads / $totalLeads * 100, 1) : 0;

        // Combo Courses Revenue
        $doubleComboCourses = \App\Models\Course::where('combo_type', 'Double')->where($dateFilter)->get();
        $tripleComboCourses = \App\Models\Course::where('combo_type', 'Triple')->where($dateFilter)->get();

        $doubleComboRevenue = 0;
        $doubleComboEnrollments = 0;
        foreach ($doubleComboCourses as $course) {
            $enrollments = \App\Models\Admission::where('course_id', $course->id)->where($dateFilter)->count();
            $doubleComboEnrollments += $enrollments;
            $doubleComboRevenue += \App\Models\Payment::whereHas('admission', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->where($dateFilter)->sum('amount');
        }

        $tripleComboRevenue = 0;
        $tripleComboEnrollments = 0;
        foreach ($tripleComboCourses as $course) {
            $enrollments = \App\Models\Admission::where('course_id', $course->id)->where($dateFilter)->count();
            $tripleComboEnrollments += $enrollments;
            $tripleComboRevenue += \App\Models\Payment::whereHas('admission', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->where($dateFilter)->sum('amount');
        }

        // Live Courses Revenue
        $liveRevenueCourses = \App\Models\Course::where('course_type', 'live')->where($dateFilter)->get();

        $liveTotalRevenue = 0;
        $liveTotalEnrollments = 0;
        foreach ($liveRevenueCourses as $course) {
            $enrollments = \App\Models\Admission::where('course_id', $course->id)->where($dateFilter)->count();
            $liveTotalEnrollments += $enrollments;
            $liveTotalRevenue += \App\Models\Payment::whereHas('admission', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->where($dateFilter)->sum('amount');
        }

        // ---- MARKETING SPECIFIC METRICS ----
        $leadSources = \App\Models\LeadSource::withCount('leads')->orderByDesc('leads_count')->get();
        $activeCampaigns = max($leadSources->where('leads_count', '>', 0)->count(), 8);
        $ctr = '4.6%';
        $cpl = '₹145';

        // ---- REVENUE TREND (Last 7 Days) ----
        $formatRevenue = function($amount) {
            if ($amount <= 0) {
                return '₹0';
            }
            if ($amount >= 10000000) {
                return '₹' . round($amount / 10000000, 1) . 'Cr';
            } elseif ($amount >= 100000) {
                return '₹' . round($amount / 100000, 1) . 'L';
            } elseif ($amount >= 1000) {
                return '₹' . round($amount / 1000, 1) . 'k';
            }
            return '₹' . number_format($amount);
        };

        $revenueTrendDays = [];
        $revenueTrendAmounts = [];
        $revenueTrendLabels = [];
        $totalTrendAmount = 0;

        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $dateString = $d->toDateString();
            $dayLabel = ($i === 0) ? 'Today' : $d->format('D');
            
            $dayPay = (float)\App\Models\Payment::where(function($q) use ($dateString) {
                $q->whereDate('payment_date', $dateString)
                  ->orWhere(function($sq) use ($dateString) {
                      $sq->whereNull('payment_date')->whereDate('created_at', $dateString);
                  });
            })->where(function($q) {
                $q->whereNull('status')->orWhereIn('status', ['success', 'captured', 'paid', 'Completed', '1']);
            })->sum('amount');

            if ($dayPay == 0) {
                $dayPay = (float)\App\Models\Admission::whereDate('created_at', $dateString)
                    ->whereNotIn('status', ['Cancelled', 'cancelled', 'Refunded', 'refunded'])
                    ->sum('course_fees');
            }
            
            $revenueTrendDays[] = [
                'day' => $dayLabel,
                'date' => $dateString,
                'amount' => $dayPay,
            ];
            $revenueTrendAmounts[] = $dayPay;
            $revenueTrendLabels[] = $dayLabel;
            $totalTrendAmount += $dayPay;
        }

        $recordedRev = (float)\App\Models\Payment::whereHas('admission.course', function($q) {
            $q->whereIn('course_type', ['paid', 'combo', 'recorded']);
        })->sum('amount');
        if ($recordedRev == 0) {
            $recordedRev = (float)\App\Models\Admission::whereHas('course', function($q) {
                $q->whereIn('course_type', ['paid', 'combo', 'recorded']);
            })->whereNotIn('status', ['Cancelled', 'cancelled', 'Refunded', 'refunded'])
              ->sum('course_fees');
        }

        $liveRev = (float)\App\Models\Payment::whereHas('admission.course', function($q) {
            $q->where('course_type', 'live');
        })->sum('amount');
        if ($liveRev == 0) {
            $liveRev = (float)\App\Models\Admission::whereHas('course', function($q) {
                $q->where('course_type', 'live');
            })->whereNotIn('status', ['Cancelled', 'cancelled', 'Refunded', 'refunded'])
              ->sum('course_fees');
        }

        $subsRev = (float)\App\Models\Payment::whereHas('admission.course', function($q) {
            $q->where('combo_type', 'Triple');
        })->sum('amount');
        if ($subsRev == 0) {
            $subsRev = (float)\App\Models\Admission::whereHas('course', function($q) {
                $q->where('combo_type', 'Triple');
            })->whereNotIn('status', ['Cancelled', 'cancelled', 'Refunded', 'refunded'])
              ->sum('course_fees');
        }

        $revenueTrendTotalFormatted = $formatRevenue($totalTrendAmount);
        $revenueRecordedFormatted = $formatRevenue($recordedRev);
        $revenueLiveFormatted = $formatRevenue($liveRev);
        $revenueSubsFormatted = $formatRevenue($subsRev);

        // ---- LEAD -> STUDENT FUNNEL (30 Days) ----
        $thirtyDaysAgo = now()->subDays(30);

        // 1. Leads: Unique leads from call logs, leads table, or contacts table in the last 30 days
        $funnelLeads = \App\Models\CallLog::where('created_at', '>=', $thirtyDaysAgo)
            ->whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')->from('call_logs')->groupBy('contact_id');
            })->count();
        if ($funnelLeads === 0) {
            $funnelLeads = \App\Models\Lead::where('created_at', '>=', $thirtyDaysAgo)->count();
        }
        if ($funnelLeads === 0) {
            $funnelLeads = \App\Models\Contact::where('created_at', '>=', $thirtyDaysAgo)->count();
        }

        // 2. Contacted: Unique leads called or tagged as contacted
        $funnelContacted = \App\Models\CallLog::where('created_at', '>=', $thirtyDaysAgo)
            ->where(function($q) {
                $q->whereIn('direction', ['Inbound', 'Outbound', 'Outgoing'])
                  ->orWhereIn('lead_tag', ['contacted', 'Contacted', 'Demo booked', 'Demo Attended', 'Converted']);
            })
            ->distinct('contact_id')->count('contact_id');
        if ($funnelContacted === 0 && $funnelLeads > 0) {
            $funnelContacted = \App\Models\Lead::where('created_at', '>=', $thirtyDaysAgo)
                ->whereHas('activities')->count();
        }

        // 3. Demo Booked: Unique leads with a scheduled demo
        $funnelDemoBooked = \App\Models\CallLog::where('created_at', '>=', $thirtyDaysAgo)
            ->where(function($q) {
                $q->whereNotNull('demo_date')
                  ->orWhereIn('lead_tag', ['Demo booked', 'Demo Attended', 'Converted']);
            })
            ->distinct('contact_id')->count('contact_id');
        if ($funnelDemoBooked === 0 && class_exists(\App\Models\SessionBooking::class)) {
            $funnelDemoBooked = \App\Models\SessionBooking::where('created_at', '>=', $thirtyDaysAgo)->count();
        }

        // 4. Demo Attended: Unique leads who attended the demo
        $funnelDemoAttended = \App\Models\CallLog::where('created_at', '>=', $thirtyDaysAgo)
            ->where(function($q) {
                $q->whereIn('lead_tag', ['Demo Attended', 'Converted'])
                  ->orWhere(function($sub) {
                      $sub->whereNotNull('demo_date')
                          ->whereNotNull('demo_status')
                          ->whereNotIn('demo_status', ['cancelled', 'no show', 'Missed', 'Pending', 'Demo Missed', 'Lost']);
                  });
            })
            ->distinct('contact_id')->count('contact_id');

        // 5. Enrolled: Unique enrolled admissions in the last 30 days
        $funnelEnrolled = \App\Models\Admission::where('created_at', '>=', $thirtyDaysAgo)
            ->whereNotIn('status', ['Cancelled', 'cancelled', 'Refunded', 'refunded'])
            ->count();
        if ($funnelEnrolled === 0) {
            $funnelEnrolled = \App\Models\CallLog::where('created_at', '>=', $thirtyDaysAgo)
                ->where('lead_tag', 'Converted')
                ->distinct('contact_id')->count('contact_id');
        }

        // Percentages & Conversion Rate
        $funnelContactedPct = $funnelLeads > 0 ? min(100, round(($funnelContacted / $funnelLeads) * 100)) : 0;
        $funnelDemoBookedPct = $funnelLeads > 0 ? min(100, round(($funnelDemoBooked / $funnelLeads) * 100)) : 0;
        $funnelDemoAttendedPct = $funnelLeads > 0 ? min(100, round(($funnelDemoAttended / $funnelLeads) * 100)) : 0;
        $funnelEnrolledPct = $funnelLeads > 0 ? min(100, round(($funnelEnrolled / $funnelLeads) * 100)) : 0;

        $funnelConvRate = $funnelLeads > 0 ? round(($funnelEnrolled / $funnelLeads) * 100, 1) : 0;
        $funnelCostPerLead = 0;
        $funnelCAC = ($funnelEnrolled > 0 && $funnelCostPerLead > 0) ? round(($funnelLeads * $funnelCostPerLead) / $funnelEnrolled) : 0;

        return compact(
            'activeStaffs', 'totalStaff', 'activeStaff',
            'totalContacts', 'contactsToday',
            'totalLeads', 'leadsToday', 'leadsThisWeek', 'leadsMonth',
            'totalCalls', 'inboundCalls', 'outboundCalls', 'missedCalls', 'callsToday',
            'hotLeads', 'warmLeads', 'coldLeads', 'contactedLeads', 'newLeads',
            'pendingFollowups', 'followupsToday', 'overdueFollowups',
            'demosToday', 'demosThisWeek', 'convertedLeads', 'conversionRate',
            'totalCourses', 'activeCourses', 'liveCourses',
            'totalBatches', 'activeBatches',
            'totalAdmissions', 'activeStudents', 'admissionsToday', 'admissionsMonth',
            'openTickets', 'resolvedToday', 'highPriority',
            'topCourses', 'recentLeads', 'staffPerformance',
            'doubleComboRevenue', 'doubleComboEnrollments',
            'tripleComboRevenue', 'tripleComboEnrollments',
            'liveTotalRevenue', 'liveTotalEnrollments',
            'startDate', 'endDate',
            'leadSources', 'activeCampaigns', 'ctr', 'cpl',
            'revenueTrendDays', 'revenueTrendAmounts', 'revenueTrendLabels',
            'revenueTrendTotalFormatted', 'revenueRecordedFormatted', 'revenueLiveFormatted', 'revenueSubsFormatted',
            'funnelLeads', 'funnelContacted', 'funnelContactedPct',
            'funnelDemoBooked', 'funnelDemoBookedPct', 'funnelDemoAttended', 'funnelDemoAttendedPct',
            'funnelEnrolled', 'funnelEnrolledPct', 'funnelConvRate', 'funnelCostPerLead', 'funnelCAC'
        );
    }

    /**
     * Display Tele Caller Dashboard.
     */
    public function teleCaller(Request $request): View
    {
        $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
        $staffId = $staff ? $staff->id : 0;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Base Query for Leads (Currently assigned OR worked on by this staff)
        $handledLeadIds = \App\Models\CallLog::where('staff_id', $staffId)->pluck('lead_id')->merge(
            \App\Models\LeadActivity::where('staff_id', $staffId)->pluck('lead_id')
        )->unique();
        
        $leadsQuery = \App\Models\Lead::where(function($q) use ($staffId, $handledLeadIds) {
            $q->where('assigned_to', $staffId)
              ->orWhereIn('id', $handledLeadIds);
        });
        
        if ($startDate && $endDate) {
            $leadsQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $assignedLeadsCount = (clone $leadsQuery)->count();
        $assignedLeadsToday = (clone $leadsQuery)->whereDate('created_at', today())->count();

        // Follow-ups
        $followupsQuery = \App\Models\Lead::whereHas('latestActivity', function($q) use ($staffId) {
            $q->whereNotNull('next_followup_date')
              ->where('staff_id', $staffId);
        });

        if ($startDate && $endDate) {
            $followupsQuery->whereHas('latestActivity', function($q) use ($startDate, $endDate) {
                $q->whereBetween('next_followup_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            });
        }

        $followupsCount = (clone $followupsQuery)->count();
        $pendingFollowups = (clone $followupsQuery)->whereHas('latestActivity', function($q) {
            $q->whereDate('next_followup_date', '<', today()->toDateString());
        })->count();

        // Demos
        $demosQuery = \App\Models\CallLog::whereNotNull('demo_date')
            ->where('assigned_staff_id', $staffId)
            ->where(function($q) use ($staffId) {
                $q->whereHas('lead', function($sq) use ($staffId) {
                    $sq->where('assigned_to', $staffId);
                })->orWhereNull('lead_id');
            });

        if ($startDate && $endDate) {
            $demosQuery->whereBetween('demo_date', [$startDate, $endDate]);
        }

        $demosCount = (clone $demosQuery)->count();
        $todayDemosQuery = (clone $demosQuery)->where('demo_date', today()->toDateString());
        $demosToday = (clone $todayDemosQuery)->count();
        $demosMorning = (clone $todayDemosQuery)->whereTime('demo_time', '<', '12:00:00')->count();
        $demosEvening = (clone $todayDemosQuery)->whereTime('demo_time', '>=', '12:00:00')->count();

        // Conversion
        $registeredCount = (clone $leadsQuery)->whereHas('status', function($q) { $q->where('name', 'Registered'); })->count();
        $registeredThisWeek = (clone $leadsQuery)->whereHas('status', function($q) { $q->where('name', 'Registered'); })
            ->where('created_at', '>=', now()->startOfWeek())->count();
        
        $conversionRate = $assignedLeadsCount > 0 ? round(($registeredCount / $assignedLeadsCount) * 100, 1) : 0;

        // Tag Breakdown (Donut chart)
        $tags = \App\Models\LeadTag::all();
        $tagBreakdown = [];
        $colors = ['#6366f1', '#f59e0b', '#06b6d4', '#10b981', '#ef4444', '#8b5cf6'];
        $i = 0;
        foreach ($tags as $tag) {
            $count = (clone $leadsQuery)->whereHas('tags', function($q) use ($tag) {
                $q->where('lead_tags.id', $tag->id);
            })->count();
            
            if ($count > 0) {
                $tagBreakdown[] = [
                    'label' => $tag->name,
                    'count' => $count,
                    'color' => $colors[$i % count($colors)]
                ];
                $i++;
            }
        }

        // Today's Lists
        $todayLeads = (clone $leadsQuery)->whereDate('created_at', today())->with(['tags'])->take(10)->get();
        $todayDemos = (clone $todayDemosQuery)->with('contact')->take(10)->get();
        $todayFollowups = (clone $followupsQuery)->whereHas('latestActivity', function($q) {
            $q->whereDate('next_followup_date', today()->toDateString());
        })->with(['contact', 'latestActivity', 'tags'])->take(10)->get()->map(function($lead) {
            $act = $lead->latestActivity;
            return (object)[
                'contact' => $lead->contact,
                'lead_tag' => $lead->tags->first()->name ?? 'New',
                'followup_time' => $act ? \Carbon\Carbon::parse($act->next_followup_date)->format('h:i A') : null,
                'updated_at' => $act ? $act->updated_at : $lead->updated_at
            ];
        });

        // Inbound/Outbound Leads Count (Based on lead_source_id)
        $totalInbound = (clone $leadsQuery)->where('lead_source_id', 1)->count();
        $totalOutbound = (clone $leadsQuery)->where(function($q) {
            $q->where('lead_source_id', '!=', 1)->orWhereNull('lead_source_id');
        })->count();
        
        $todayInbound = (clone $leadsQuery)->where('lead_source_id', 1)->whereDate('created_at', today())->count();
        $todayOutbound = (clone $leadsQuery)->where(function($q) {
            $q->where('lead_source_id', '!=', 1)->orWhereNull('lead_source_id');
        })->whereDate('created_at', today())->count();

        return view('dashboards.tele-caller', compact(
            'assignedLeadsCount', 'assignedLeadsToday', 
            'followupsCount', 'pendingFollowups', 
            'demosCount', 'demosToday', 'demosMorning', 'demosEvening', 
            'registeredCount', 'registeredThisWeek', 'conversionRate',
            'tagBreakdown', 'todayLeads', 'todayDemos', 'todayFollowups',
            'totalInbound', 'totalOutbound', 'todayInbound', 'todayOutbound'
        ));
    }

    /**
     * Display Admission Officer Dashboard.
     */
    public function admissionOfficer(\Illuminate\Http\Request $request): View
    {
        $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
        $staffId = $staff ? $staff->id : 0;

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        // Total Leads
        $leadsQuery = \App\Models\Lead::where(function($q) use ($staffId) {
            $handledLeadIds = \App\Models\CallLog::where('staff_id', $staffId)->pluck('lead_id')->merge(
                \App\Models\LeadActivity::where('staff_id', $staffId)->pluck('lead_id')
            )->unique();
            
            $q->where('assigned_to', $staffId)
              ->orWhereIn('id', $handledLeadIds);
        });
        if ($startDate && $endDate) {
            $leadsQuery->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        $totalLeads = (clone $leadsQuery)->count();
        $leadsThisWeek = (clone $leadsQuery)->where('updated_at', '>=', now()->startOfWeek())->count();

        // Followups
        $followupsQuery = \App\Models\Lead::whereHas('latestActivity', function($q) use ($staffId) {
            $q->whereNotNull('next_followup_date')
              ->where('staff_id', $staffId);
        });
        
        if ($startDate && $endDate) {
            $followupsQuery->whereHas('latestActivity', function($q) use ($startDate, $endDate) {
                $q->whereBetween('next_followup_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            });
        }
        $followupsToday = (clone $followupsQuery)->whereHas('latestActivity', function($q) {
            $q->whereDate('next_followup_date', today()->toDateString());
        })->count();
        $overdueFollowups = (clone $followupsQuery)->whereHas('latestActivity', function($q) {
            $q->whereDate('next_followup_date', '<', today()->toDateString());
        })->count();

        // Revenue (tied to the staff's leads)
        $leadIds = (clone $leadsQuery)->pluck('id');
        $paymentsQuery = \App\Models\Payment::whereHas('admission', function($q) use ($leadIds) {
            $q->whereIn('lead_id', $leadIds);
        });
        
        if ($startDate && $endDate) {
            $paymentsQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        $revenueCollected = (clone $paymentsQuery)->where('status', 'Paid')->sum('amount');
        $pendingRevenue = (clone $paymentsQuery)->where('status', 'Pending')->sum('amount');

        // Registered Students
        $registeredCount = (clone $leadsQuery)->whereHas('status', function($q) { $q->where('name', 'Registered'); })->count();
        $registeredThisWeek = (clone $leadsQuery)->whereHas('status', function($q) { $q->where('name', 'Registered'); })
            ->where('created_at', '>=', now()->startOfWeek())->count();

        // Recent Leads
        $recentLeads = (clone $leadsQuery)->with(['status', 'courses'])->orderBy('created_at', 'desc')->take(5)->get();

        // Pipeline (Filter by leads assigned to staff)
        $statuses = \App\Models\LeadStatus::all();
        $pipeline = [];
        foreach ($statuses as $status) {
            $pipeline[] = [
                'label' => $status->name,
                'color' => $status->color ?? '#3b82f6',
                'count' => (clone $leadsQuery)->where('lead_status_id', $status->id)->count(),
            ];
        }

        return view('dashboards.admission-officer', compact(
            'totalLeads', 'leadsThisWeek', 'followupsToday', 'overdueFollowups',
            'revenueCollected', 'pendingRevenue', 'registeredCount', 'registeredThisWeek',
            'recentLeads', 'pipeline', 'startDate', 'endDate'
        ));
    }

    /**
     * Display Support Executive Dashboard.
     */
    public function supportExecutive(\Illuminate\Http\Request $request)
    {

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $dateFilter = function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
            }
        };

        $today = now()->toDateString();
        $yesterday = \Carbon\Carbon::parse($startDate ?? now())->subDay()->toDateString();

        // Technical Queries Stats
        $techToday = \App\Models\SupportTicket::where('type', 'technical')->where($dateFilter)->count();
        $techYesterday = \App\Models\SupportTicket::where('type', 'technical')->whereDate('created_at', $yesterday)->count();
        $techDiff = $techToday - $techYesterday;
        $techTrend = $techDiff >= 0 ? 'up' : 'down';
        $techDiffStr = ($techDiff > 0 ? '+' : '') . $techDiff;

        // Non-Technical Queries Stats
        $nonTechToday = \App\Models\SupportTicket::where('type', 'non-technical')->where($dateFilter)->count();
        $nonTechYesterday = \App\Models\SupportTicket::where('type', 'non-technical')->whereDate('created_at', $yesterday)->count();
        $nonTechDiff = $nonTechToday - $nonTechYesterday;
        $nonTechTrend = $nonTechDiff >= 0 ? 'up' : 'down';
        $nonTechDiffStr = ($nonTechDiff > 0 ? '+' : '') . $nonTechDiff;

        // Pending Queries Stats
        $pendingTickets = \App\Models\SupportTicket::whereIn('status', ['New', 'Opened', 'Pending', 'In Review'])->get();
        $pendingTotal = $pendingTickets->count();
        $pendingOverdue = $pendingTickets->where('created_at', '<', now()->startOfDay())->count();

        // Solved Queries Stats
        $solvedToday = \App\Models\SupportTicket::where('status', 'Resolved')->where(function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereDate('updated_at', '>=', $startDate)
                  ->whereDate('updated_at', '<=', $endDate);
            }
        })->count();
        $solvedStatus = $solvedToday >= 10 ? 'Strong day' : ($solvedToday > 0 ? 'Good day' : 'Quiet day');

        $stats = [
            'technical' => ['count' => $techToday, 'diff' => $techDiffStr, 'trend' => $techTrend],
            'non_technical' => ['count' => $nonTechToday, 'diff' => $nonTechDiffStr, 'trend' => $nonTechTrend],
            'pending' => ['count' => $pendingTotal, 'overdue' => $pendingOverdue, 'tech' => 0, 'non_tech' => 0],
            'solved' => ['count' => $solvedToday, 'status' => $solvedStatus, 'tech' => 0, 'non_tech' => 0]
        ];

        $colors = ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#06b6d4', '#ef4444', '#ec4899', '#6366f1'];

        // Technical Queries List
        $technicalQueriesRaw = \App\Models\SupportTicket::with('user')
            ->where('type', 'technical')
            ->where($dateFilter)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $technicalQueries = [];
        foreach ($technicalQueriesRaw as $index => $ticket) {
            $name = $ticket->user->name ?? 'Unknown';
            $words = explode(' ', $name);
            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            $technicalQueries[] = [
                'name' => $name,
                'topic' => \Illuminate\Support\Str::limit($ticket->title ?? $ticket->subject, 50),
                'time' => $ticket->created_at->format('g:i A'),
                'initials' => $initials,
                'color' => $colors[$index % count($colors)]
            ];
        }

        // Non-Technical Queries List
        $nonTechnicalQueriesRaw = \App\Models\SupportTicket::with('user')
            ->where('type', 'non-technical')
            ->where($dateFilter)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $nonTechnicalQueries = [];
        foreach ($nonTechnicalQueriesRaw as $index => $ticket) {
            $name = $ticket->user->name ?? 'Unknown';
            $words = explode(' ', $name);
            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            $nonTechnicalQueries[] = [
                'name' => $name,
                'topic' => \Illuminate\Support\Str::limit($ticket->description ?? $ticket->subject, 80),
                'initials' => $initials,
                'color' => $colors[($index + 3) % count($colors)]
            ];
        }

        return view('dashboards.support', compact('stats', 'technicalQueries', 'nonTechnicalQueries'));
    }

    /**
     * Display Technical Staff Dashboard.
     */
    public function technicalStaff(): View
    {
        return view('dashboards.tech');
    }

    /**
     * Display Technical Head Dashboard.
     */
    public function technicalHead(\Illuminate\Http\Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $dateFilter = function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
            }
        };

        $sessionDateFilter = function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereDate('date', '>=', $startDate)
                  ->whereDate('date', '<=', $endDate);
            }
        };

        $fsStudents = \App\Models\Admission::whereHas('course', function($q) { $q->where('title', 'like', '%Full Stack%'); })->where($dateFilter)->count();
        $uiuxStudents = \App\Models\Admission::whereHas('course', function($q) { $q->where('title', 'like', '%UI%UX%'); })->where($dateFilter)->count();
        $dmStudents = \App\Models\Admission::whereHas('course', function($q) { $q->where('title', 'like', '%Digital Marketing%'); })->where($dateFilter)->count();
        $dsStudents = \App\Models\Admission::whereHas('course', function($q) { $q->where('title', 'like', '%Data Science%'); })->where($dateFilter)->count();
        $totalDemos = \App\Models\SessionBooking::where('type', 'demo')->where($sessionDateFilter)->count();

        $totalStudents = [
            'full_stack' => ['count' => $fsStudents, 'batches' => 0, 'subtext' => '', 'color' => '#f59e0b', 'icon' => 'fas fa-bolt'],
            'ui_ux' => ['count' => $uiuxStudents, 'batches' => 0, 'subtext' => '', 'color' => '#ec4899', 'icon' => 'fas fa-palette'],
            'digital_mktg' => ['count' => $dmStudents, 'batches' => 0, 'subtext' => '', 'color' => '#f59e0b', 'icon' => 'fas fa-bullhorn'],
            'data_science' => ['count' => $dsStudents, 'batches' => 0, 'subtext' => '', 'color' => '#10b981', 'icon' => 'fas fa-chart-bar'],
            'total_demos' => ['count' => $totalDemos, 'subtext' => 'today', 'color' => '#ef4444', 'icon' => 'fas fa-bullseye'],
        ];

        $fsBatches = \App\Models\Batch::whereHas('course', function($q) { $q->where('title', 'like', '%Full Stack%'); })->count();
        $uiuxBatches = \App\Models\Batch::whereHas('course', function($q) { $q->where('title', 'like', '%UI%UX%'); })->count();
        $dmBatches = \App\Models\Batch::whereHas('course', function($q) { $q->where('title', 'like', '%Digital Marketing%'); })->count();
        $dsBatches = \App\Models\Batch::whereHas('course', function($q) { $q->where('title', 'like', '%Data Science%'); })->count();
        $totalDoubts = \App\Models\SessionBooking::where('type', 'doubt')->where($sessionDateFilter)->count();

        $totalBatches = [
            'fs_batches' => ['count' => $fsBatches, 'subtext' => $fsBatches . ' batches', 'color' => '#f59e0b'],
            'uiux_batches' => ['count' => $uiuxBatches, 'subtext' => $uiuxBatches . ' batches', 'color' => '#ec4899'],
            'dm_batches' => ['count' => $dmBatches, 'subtext' => $dmBatches . ' batches', 'color' => '#f59e0b'],
            'ds_batches' => ['count' => $dsBatches, 'subtext' => $dsBatches . ' batches', 'color' => '#10b981'],
            'total_doubts' => ['count' => $totalDoubts, 'subtext' => 'today', 'color' => '#a855f7', 'icon' => 'fas fa-comment-dots'],
        ];

        // -------------------------------------------------------------
        // 100% DYNAMIC STAFF ACTIVITIES & NOTIFICATIONS ENGINE
        // -------------------------------------------------------------
        $getColor = function($str) {
            $palette = ['#6366f1', '#ec4899', '#10b981', '#f59e0b', '#3b82f6', '#8b5cf6', '#06b6d4', '#14b8a6'];
            return $palette[abs(crc32($str ?? 'VE')) % count($palette)];
        };

        $getInitials = function($name) {
            $name = trim($name ?: 'Staff');
            $parts = preg_split('/\s+/', $name);
            return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
        };

        $formatTimeAgo = function($date) {
            if (!$date) return 'Recently';
            $carbon = \Carbon\Carbon::parse($date);
            $diffMinutes = (int) $carbon->diffInMinutes();
            if ($diffMinutes < 1) return 'Just now';
            if ($diffMinutes < 60) return $diffMinutes . ' min ago';
            $diffHours = (int) $carbon->diffInHours();
            if ($diffHours < 24) return $diffHours . ' hr ago';
            $diffDays = (int) $carbon->diffInDays();
            if ($diffDays < 7) return $diffDays . ' day' . ($diffDays > 1 ? 's' : '') . ' ago';
            return $carbon->format('d M');
        };

        $todayDate = today()->toDateString();
        $targetDateStart = $startDate ?: $todayDate;
        $targetDateEnd = $endDate ?: $todayDate;

        // ==========================================
        // 1. STAFF ACTIVITIES (TODAY ONLY, 100% DYNAMIC)
        // ==========================================
        $staffActivities = collect();

        // 1a. Session Bookings (Demos & Doubts for Today)
        $sessions = \App\Models\SessionBooking::with(['staff', 'admission.contact', 'admission.course', 'admission.batch'])
            ->where(function($q) use ($targetDateStart, $targetDateEnd) {
                $q->whereBetween('date', [$targetDateStart, $targetDateEnd])
                  ->orWhere(function($sub) use ($targetDateStart, $targetDateEnd) {
                      $sub->whereDate('created_at', '>=', $targetDateStart)
                          ->whereDate('created_at', '<=', $targetDateEnd);
                  });
            })
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->take(10)
            ->get();

        foreach ($sessions as $sb) {
            $staffName = $sb->staff ? $sb->staff->name : ($sb->user ? $sb->user->name : 'Technical Staff');
            $studentName = $sb->admission && $sb->admission->contact ? $sb->admission->contact->name : 'Student';
            $courseName = $sb->admission && $sb->admission->course ? $sb->admission->course->title : '';
            $role = $sb->staff && $sb->staff->designation ? $sb->staff->designation : ($courseName ? $courseName . ' Trainer' : 'Senior Trainer');
            $isDemo = strtolower($sb->type) === 'demo';
            $sessionTimestamp = $sb->created_at ? $sb->created_at->timestamp : strtotime($sb->date . ' ' . ($sb->time ?: '00:00:00'));

            $staffActivities->push([
                'initials' => $getInitials($staffName),
                'color' => $getColor($staffName),
                'name' => $staffName,
                'role' => $role,
                'action_prefix' => $isDemo ? 'Conducted demo session' : 'Cleared a technical doubt',
                'action_highlight' => $isDemo ? $studentName : ($studentName . ($sb->topic ? " — {$sb->topic}" : '')),
                'badge_text' => $isDemo ? 'Demo' : 'Doubt',
                'badge_icon' => $isDemo ? '🎯' : '💬',
                'badge_color' => $isDemo ? '#f97316' : '#06b6d4',
                'badge_bg' => $isDemo ? 'rgba(249, 115, 22, 0.12)' : 'rgba(6, 182, 212, 0.12)',
                'time_ago' => $formatTimeAgo($sb->created_at ?: ($sb->date . ' ' . $sb->time)),
                'timestamp' => $sessionTimestamp,
            ]);
        }

        // 1b. Batch Attendance for Today
        $attendances = \App\Models\BatchAttendance::with(['batch.course', 'batch.instructor'])
            ->select('batch_id', 'date', 'created_at', \DB::raw('count(*) as total_marked'), \DB::raw("sum(case when status = 'Present' then 1 else 0 end) as present_count"))
            ->where(function($q) use ($targetDateStart, $targetDateEnd) {
                $q->whereBetween('date', [$targetDateStart, $targetDateEnd])
                  ->orWhere(function($sub) use ($targetDateStart, $targetDateEnd) {
                      $sub->whereDate('created_at', '>=', $targetDateStart)
                          ->whereDate('created_at', '<=', $targetDateEnd);
                  });
            })
            ->groupBy('batch_id', 'date', 'created_at')
            ->latest('created_at')
            ->take(10)
            ->get();

        foreach ($attendances as $att) {
            $batch = $att->batch ?: \App\Models\Batch::with(['instructor', 'course'])->find($att->batch_id);
            $staffName = $batch && $batch->instructor ? $batch->instructor->name : 'Staff Instructor';
            $role = $batch && $batch->course ? $batch->course->title . ' Trainer' : 'Senior Trainer';
            $batchCode = $batch ? $batch->batch_code : 'Batch';
            $ratio = "{$att->present_count}/{$att->total_marked} present";
            $attTimestamp = $att->created_at ? $att->created_at->timestamp : strtotime($att->date);

            $staffActivities->push([
                'initials' => $getInitials($staffName),
                'color' => $getColor($staffName),
                'name' => $staffName,
                'role' => $role,
                'action_prefix' => 'Marked attendance',
                'action_highlight' => "{$batchCode} · {$ratio}",
                'badge_text' => 'Attendance',
                'badge_icon' => '🗓️',
                'badge_color' => '#3b82f6',
                'badge_bg' => 'rgba(59, 130, 246, 0.12)',
                'time_ago' => $formatTimeAgo($att->created_at ?: $att->date),
                'timestamp' => $attTimestamp,
            ]);
        }

        // 1c. Batch Schedules (Live classes for Today)
        $schedules = \App\Models\BatchSchedule::with(['batch.course', 'batch.instructor'])
            ->where(function($q) use ($targetDateStart, $targetDateEnd) {
                $q->whereBetween('date', [$targetDateStart, $targetDateEnd])
                  ->orWhere(function($sub) use ($targetDateStart, $targetDateEnd) {
                      $sub->whereDate('created_at', '>=', $targetDateStart)
                          ->whereDate('created_at', '<=', $targetDateEnd);
                  });
            })
            ->latest('created_at')
            ->take(10)
            ->get();

        foreach ($schedules as $sched) {
            $staffName = $sched->batch && $sched->batch->instructor ? $sched->batch->instructor->name : 'Staff Instructor';
            $role = $sched->batch && $sched->batch->course ? $sched->batch->course->title . ' Trainer' : 'Live Class Trainer';
            $batchCode = $sched->batch ? $sched->batch->batch_code : 'Batch';
            $track = $sched->batch && $sched->batch->batch_track ? $sched->batch->batch_track : 'Live Class';
            $schedTimestamp = $sched->created_at ? $sched->created_at->timestamp : strtotime($sched->date . ' ' . ($sched->start_time ?: '00:00:00'));

            $staffActivities->push([
                'initials' => $getInitials($staffName),
                'color' => $getColor($staffName),
                'name' => $staffName,
                'role' => $role,
                'action_prefix' => 'Conducted live class',
                'action_highlight' => "{$batchCode} · {$track}",
                'badge_text' => 'Live Class',
                'badge_icon' => '🎥',
                'badge_color' => '#a855f7',
                'badge_bg' => 'rgba(168, 85, 247, 0.12)',
                'time_ago' => $formatTimeAgo($sched->created_at ?: ($sched->date . ' ' . $sched->start_time)),
                'timestamp' => $schedTimestamp,
            ]);
        }

        // 1d. Staff Meetings for Today
        $meetings = \App\Models\StaffMeeting::with('staff')
            ->where(function($q) use ($targetDateStart, $targetDateEnd) {
                $q->whereBetween('date', [$targetDateStart, $targetDateEnd])
                  ->orWhere(function($sub) use ($targetDateStart, $targetDateEnd) {
                      $sub->whereDate('created_at', '>=', $targetDateStart)
                          ->whereDate('created_at', '<=', $targetDateEnd);
                  });
            })
            ->latest('created_at')
            ->take(5)
            ->get();

        foreach ($meetings as $sm) {
            $staffName = $sm->staff ? $sm->staff->name : 'Technical Staff';
            $timeFormatted = $sm->time ? \Carbon\Carbon::parse($sm->time)->format('h:i A') : '';
            $smTimestamp = $sm->created_at ? $sm->created_at->timestamp : strtotime($sm->date . ' ' . ($sm->time ?: '00:00:00'));

            $staffActivities->push([
                'initials' => $getInitials($staffName),
                'color' => $getColor($staffName),
                'name' => $staffName,
                'role' => 'Technical Team',
                'action_prefix' => 'Conducted team meeting',
                'action_highlight' => $sm->title . ($timeFormatted ? " · {$timeFormatted}" : ''),
                'badge_text' => 'Meeting',
                'badge_icon' => '📊',
                'badge_color' => '#10b981',
                'badge_bg' => 'rgba(16, 185, 129, 0.12)',
                'time_ago' => $formatTimeAgo($sm->created_at ?: ($sm->date . ' ' . $sm->time)),
                'timestamp' => $smTimestamp,
            ]);
        }

        // 1e. Batch Topic Logs for Today
        if (class_exists(\App\Models\BatchTopicLog::class)) {
            $topicLogs = \App\Models\BatchTopicLog::with(['batch.course', 'batch.instructor'])
                ->whereDate('created_at', '>=', $targetDateStart)
                ->whereDate('created_at', '<=', $targetDateEnd)
                ->latest('created_at')
                ->take(5)
                ->get();
            foreach ($topicLogs as $log) {
                $staffName = $log->batch && $log->batch->instructor ? $log->batch->instructor->name : 'Trainer';
                $role = $log->batch && $log->batch->course ? $log->batch->course->title . ' Trainer' : 'DS Trainer';
                $staffActivities->push([
                    'initials' => $getInitials($staffName),
                    'color' => $getColor($staffName),
                    'name' => $staffName,
                    'role' => $role,
                    'action_prefix' => 'Updated syllabus module',
                    'action_highlight' => ($log->batch ? $log->batch->batch_code : 'Batch') . ' · ' . ($log->topic_name ?: 'Module'),
                    'badge_text' => 'Syllabus',
                    'badge_icon' => '📚',
                    'badge_color' => '#8b5cf6',
                    'badge_bg' => 'rgba(139, 92, 246, 0.12)',
                    'time_ago' => $formatTimeAgo($log->created_at),
                    'timestamp' => $log->created_at ? $log->created_at->timestamp : 0,
                ]);
            }
        }

        // 1f. Project Submissions & Reviews for Today
        if (class_exists(\App\Models\MiniProjectSubmission::class)) {
            $miniSubs = \App\Models\MiniProjectSubmission::with(['project.course', 'student'])
                ->whereDate('created_at', '>=', $targetDateStart)
                ->whereDate('created_at', '<=', $targetDateEnd)
                ->latest('created_at')
                ->take(5)
                ->get();
            foreach ($miniSubs as $sub) {
                $studentName = $sub->student->name ?? 'Student';
                $projTitle = $sub->project->title ?? 'Mini Project';
                $staffActivities->push([
                    'initials' => $getInitials($studentName),
                    'color' => $getColor($studentName),
                    'name' => $studentName,
                    'role' => 'Student Submission',
                    'action_prefix' => 'Submitted mini project',
                    'action_highlight' => "{$studentName} — {$projTitle}",
                    'badge_text' => 'Review',
                    'badge_icon' => '✅',
                    'badge_color' => '#10b981',
                    'badge_bg' => 'rgba(16, 185, 129, 0.12)',
                    'time_ago' => $formatTimeAgo($sub->created_at),
                    'timestamp' => $sub->created_at ? $sub->created_at->timestamp : 0,
                ]);
            }
        }

        $staffActivities = $staffActivities->sortByDesc('timestamp')->values();

        // ==========================================
        // 2. TECHNICAL HEAD NOTIFICATIONS (TODAY ONLY, 100% DYNAMIC)
        // ==========================================
        $techHeadNotifications = collect();

        // 2a. Batches Created Today
        $batches = \App\Models\Batch::with(['course', 'instructor'])
            ->whereDate('created_at', '>=', $targetDateStart)
            ->whereDate('created_at', '<=', $targetDateEnd)
            ->latest('created_at')
            ->take(5)
            ->get();
        foreach ($batches as $b) {
            $techHeadNotifications->push([
                'icon' => 'fas fa-box-open',
                'icon_bg' => '#f59e0b',
                'title' => 'New batch created',
                'subtitle' => "{$b->batch_code} · {$b->batch_name} — " . ($b->batch_type ?: 'Regular'),
                'category' => 'New Batch',
                'category_color' => '#2563eb',
                'status' => $b->status === 'active' ? 'DONE' : 'PENDING',
                'time_ago' => $formatTimeAgo($b->created_at),
                'timestamp' => $b->created_at ? $b->created_at->timestamp : 0,
            ]);
        }

        // 2b. Courses Created/Updated Today
        $courses = \App\Models\Course::whereDate('created_at', '>=', $targetDateStart)
            ->whereDate('created_at', '<=', $targetDateEnd)
            ->latest('created_at')
            ->take(5)
            ->get();
        foreach ($courses as $c) {
            $isApproved = ($c->status == '1' || $c->status === 'active');
            $techHeadNotifications->push([
                'icon' => $isApproved ? 'fas fa-check-circle' : 'fas fa-layer-group',
                'icon_bg' => $isApproved ? '#10b981' : '#8b5cf6',
                'title' => $isApproved ? 'Course approved & active' : 'Course pending approval',
                'subtitle' => "{$c->title} · {$c->course_code}",
                'category' => $isApproved ? 'Approved' : 'New Course',
                'category_color' => $isApproved ? '#16a34a' : '#9333ea',
                'status' => $isApproved ? 'DONE' : 'PENDING',
                'time_ago' => $formatTimeAgo($c->created_at),
                'timestamp' => $c->created_at ? $c->created_at->timestamp : 0,
            ]);
        }

        // 2c. Student Admissions Today
        $admissions = \App\Models\Admission::with(['contact', 'course', 'batch'])
            ->whereDate('created_at', '>=', $targetDateStart)
            ->whereDate('created_at', '<=', $targetDateEnd)
            ->latest('created_at')
            ->take(5)
            ->get();
        foreach ($admissions as $adm) {
            $cName = $adm->contact ? $adm->contact->name : 'Student';
            $target = $adm->batch ? $adm->batch->batch_code : ($adm->course ? $adm->course->title : 'Batch');
            $techHeadNotifications->push([
                'icon' => 'fas fa-user-friends',
                'icon_bg' => '#a855f7',
                'title' => 'Student enrolment confirmed',
                'subtitle' => "{$cName} enrolled in {$target}",
                'category' => 'Enrolments',
                'category_color' => '#db2777',
                'status' => 'INFO',
                'time_ago' => $formatTimeAgo($adm->created_at),
                'timestamp' => $adm->created_at ? $adm->created_at->timestamp : 0,
            ]);
        }

        // 2d. Support Tickets Today
        if (class_exists(\App\Models\SupportTicket::class)) {
            $tickets = \App\Models\SupportTicket::whereDate('created_at', '>=', $targetDateStart)
                ->whereDate('created_at', '<=', $targetDateEnd)
                ->latest('created_at')
                ->take(5)
                ->get();
            foreach ($tickets as $st) {
                $isResolved = strtolower($st->status) === 'resolved';
                $techHeadNotifications->push([
                    'icon' => 'fas fa-headset',
                    'icon_bg' => '#0ea5e9',
                    'title' => 'Support Ticket: ' . ($st->subject ?: 'Technical Assistance'),
                    'subtitle' => "Ticket #{$st->id} · Status: {$st->status}",
                    'category' => 'Support',
                    'category_color' => '#0284c7',
                    'status' => $isResolved ? 'DONE' : 'PENDING',
                    'time_ago' => $formatTimeAgo($st->created_at),
                    'timestamp' => $st->created_at ? $st->created_at->timestamp : 0,
                ]);
            }
        }

        // 2e. Session Bookings Today
        foreach ($sessions->take(5) as $sb) {
            $cName = $sb->admission && $sb->admission->contact ? $sb->admission->contact->name : 'Student';
            $cCourse = $sb->admission && $sb->admission->course ? $sb->admission->course->title : 'Course';
            $isDemo = strtolower($sb->type) === 'demo';
            $techHeadNotifications->push([
                'icon' => $isDemo ? 'fas fa-bullseye' : 'fas fa-comment-dots',
                'icon_bg' => $isDemo ? '#ef4444' : '#06b6d4',
                'title' => ucfirst($sb->type) . ' session booked',
                'subtitle' => "{$cName} for {$cCourse} at {$sb->time}",
                'category' => $isDemo ? 'Demo Slots' : 'Doubts',
                'category_color' => $isDemo ? '#0284c7' : '#0891b2',
                'status' => 'INFO',
                'time_ago' => $formatTimeAgo($sb->created_at ?: ($sb->date . ' ' . $sb->time)),
                'timestamp' => $sb->created_at ? $sb->created_at->timestamp : strtotime($sb->date . ' ' . ($sb->time ?: '00:00:00')),
            ]);
        }

        // 2f. Live Class Schedules Today
        foreach ($schedules->take(5) as $sched) {
            $batchCode = $sched->batch ? $sched->batch->batch_code : 'Batch';
            $techHeadNotifications->push([
                'icon' => 'fas fa-calendar-alt',
                'icon_bg' => '#f59e0b',
                'title' => 'Live class scheduled',
                'subtitle' => "{$batchCode} on " . \Carbon\Carbon::parse($sched->date)->format('d M') . " ({$sched->start_time} - {$sched->end_time})",
                'category' => 'Live Class',
                'category_color' => '#d97706',
                'status' => \Carbon\Carbon::parse($sched->date)->isPast() ? 'DONE' : 'PENDING',
                'time_ago' => $formatTimeAgo($sched->created_at ?: ($sched->date . ' ' . $sched->start_time)),
                'timestamp' => $sched->created_at ? $sched->created_at->timestamp : strtotime($sched->date . ' ' . ($sched->start_time ?: '00:00:00')),
            ]);
        }

        $techHeadNotifications = $techHeadNotifications->sortByDesc('timestamp')->values();

        $todayActivities = $staffActivities;

        $colors = ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#06b6d4', '#ef4444', '#ec4899', '#6366f1'];
        
        $todayDemosRaw = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')
            ->whereDate('date', today())
            ->get();
            
        $todayDemos = [];
        foreach($todayDemosRaw as $i => $demo) {
            $name = $demo->admission->contact->name ?? 'Unknown';
            $todayDemos[] = [
                'name' => $name,
                'phone' => $demo->admission->contact->phone ?? ($demo->admission->contact->caller_number ?? 'N/A'),
                'course' => $demo->admission->course->title ?? 'N/A',
                'time' => $demo->time,
                'initials' => strtoupper(substr($name, 0, 1) . (strpos($name, ' ') !== false ? $name[strpos($name, ' ') + 1] : '')),
                'color' => $colors[$i % count($colors)]
            ];
        }

        $todayDoubtsRaw = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')
            ->whereDate('date', today())
            ->get();
            
        $todayDoubts = [];
        foreach($todayDoubtsRaw as $i => $doubt) {
            $name = $doubt->admission->contact->name ?? 'Unknown';
            $todayDoubts[] = [
                'name' => $name,
                'phone' => $doubt->admission->contact->phone ?? ($doubt->admission->contact->caller_number ?? 'N/A'),
                'course' => $doubt->admission->course->title ?? 'N/A',
                'topic' => $doubt->topic ?? 'N/A',
                'time' => $doubt->time,
                'initials' => strtoupper(substr($name, 0, 1) . (strpos($name, ' ') !== false ? $name[strpos($name, ' ') + 1] : '')),
                'color' => $colors[$i % count($colors)]
            ];
        }

        return view('dashboards.tech-head', compact(
            'totalStudents', 'totalBatches', 
            'todayActivities', 'staffActivities', 'techHeadNotifications', 
            'todayDemos', 'todayDoubts'
        ));
    }

    /**
     * Display Dues Collector Dashboard.
     */
    public function duesCollector(\Illuminate\Http\Request $request)
    {

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        
        $dateFilter = function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
            }
        };

        $paymentDateFilter = function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereDate('payment_date', '>=', $startDate)
                  ->whereDate('payment_date', '<=', $endDate);
            }
        };

        $today        = now()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();

        // â”€â”€ Admissions with pending/due payments â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $admissionsWithDues = \App\Models\Admission::with(['contact', 'course', 'payments'])
            ->whereHas('payments', fn($q) => $q->where('status', '!=', 'Paid')->where($paymentDateFilter))
            ->get();

        $totalDueStudents = $admissionsWithDues->count();

        // Overdue = payment_date has passed and still not Paid
        $overdueCount = \App\Models\Payment::where('status', '!=', 'Paid')
            ->where('payment_date', '<', $today)
            ->where($paymentDateFilter)
            ->count();

        // Total pending due amount
        $totalDueAmount = \App\Models\Payment::where('status', '!=', 'Paid')->where($paymentDateFilter)->sum('amount');

        // Total collected (Paid) all time
        $totalCollected = \App\Models\Payment::where('status', 'Paid')->where($paymentDateFilter)->sum('amount');

        // New admissions this month
        $newThisMonth = \App\Models\Admission::where($dateFilter)->count();

        $stats = [
            'total_due_students' => ['count' => $totalDueStudents, 'new' => $newThisMonth],
            'overdue_students'   => ['count' => $overdueCount],
            'total_due_amount'   => ['amount' => number_format($totalDueAmount), 'diff' => 0],
            'total_collected'    => ['amount' => number_format($totalCollected)],
        ];

        // â”€â”€ Today's dues â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $colors = ['#3b82f6','#10b981','#8b5cf6','#06b6d4','#ec4899','#f59e0b','#ef4444'];
        $todayDuePayments = \App\Models\Payment::with(['admission.contact', 'admission.course'])
            ->where('payment_date', $today)
            ->where('status', '!=', 'Paid')
            ->limit(10)->get();

        $todayDues = $todayDuePayments->map(function ($p, $i) use ($colors) {
            $name = $p->admission->contact->name ?? 'Unknown';
            $phone = $p->admission->contact->caller_number ?? 'â€”';
            return [
                'contact_id' => $p->admission->contact_id,
                'lead_id'    => $p->admission->lead_id,
                'name'       => $name,
                'phone'      => $phone,
                'date'       => \Carbon\Carbon::parse($p->payment_date)->format('d M'),
                'status'     => $p->status,
                'amount'     => number_format($p->amount),
                'initials'   => strtoupper(substr($name, 0, 1) . (strpos($name, ' ') !== false ? $name[strpos($name, ' ') + 1] : '')),
                'color'      => $colors[$i % count($colors)],
            ];
        })->values()->toArray();

        // â”€â”€ Today's reminders (overdue + today) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $reminderPayments = \App\Models\Payment::with(['admission.contact'])
            ->where('status', '!=', 'Paid')
            ->where('payment_date', '<=', $today)
            ->orderBy('payment_date')
            ->limit(10)->get();

        $todayReminders = $reminderPayments->map(function ($p, $i) use ($colors, $today) {
            $name = $p->admission->contact->name ?? 'Unknown';
            $isOverdue = $p->payment_date < $today;
            return [
                'contact_id' => $p->admission->contact_id,
                'name'       => $name,
                'phone'      => $p->admission->contact->caller_number ?? 'â€”',
                'date'       => \Carbon\Carbon::parse($p->payment_date)->format('d M'),
                'status'     => $isOverdue ? 'Overdue' : 'Due Today',
                'amount'     => number_format($p->amount),
                'initials'   => strtoupper(substr($name, 0, 1) . (strpos($name, ' ') !== false ? $name[strpos($name, ' ') + 1] : '')),
                'color'      => $isOverdue ? '#ef4444' : $colors[$i % count($colors)],
            ];
        })->values()->toArray();

        // â”€â”€ Chart: last 7 days collection â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $chartLabels[] = $day->format('D');
            $chartValues[] = (float) \App\Models\Payment::where('status', 'Paid')
                ->whereDate('updated_at', $day->toDateString())
                ->sum('amount');
        }
        $weekTotal = array_sum($chartValues);
        $chartData = [
            'labels'     => $chartLabels,
            'values'     => $chartValues,
            'total_week' => number_format($weekTotal),
            'avg_day'    => number_format($weekTotal > 0 ? $weekTotal / 7 : 0),
        ];

        return view('dashboards.dues', compact('stats', 'todayDues', 'todayReminders', 'chartData', 'startDate', 'endDate'));
    }

    /**
     * Display Digital Marketing Dashboard.
     */
    public function digitalMarketing(Request $request): View
    {
        $data = $this->getSuperAdminDashboardData($request);
        return view('dashboards.marketing', $data);
    }

    /**
     * Display Student Coordinator Dashboard.
     */
    public function studentCoordinator(): View
    {
        return view('dashboards.student-coordinator');
    }

    /**
     * Display Operation Executive Dashboard.
     */
    public function operationExecutive(Request $request): View
    {
        $data = $this->getSuperAdminDashboardData($request);
        return view('dashboards.operation', $data);
    }

    /**
     * Display Placement Coordinator Dashboard.
     */
    public function placementCoordinator(\Illuminate\Http\Request $request): View
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $dateFilter = function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
            }
        };

        $totalCompanies = \App\Models\Company::where($dateFilter)->count();
        $activeOpenings = \App\Models\CurrentOpening::where('opening_status', 'Open')->where($dateFilter)->count() ?? 0;
        $filledOpenings = \App\Models\CurrentOpening::where('opening_status', 'Closed')->where($dateFilter)->count() ?? 0;
        $todaysVacanciesCount = \App\Models\CurrentOpening::where($dateFilter)->count();
        
        $stats = [
            ['title' => 'Total Companies', 'value' => $totalCompanies, 'desc' => 'in database', 'icon' => 'blue'],
            ['title' => 'Active Openings', 'value' => $activeOpenings, 'desc' => 'live & accepting profiles', 'icon' => 'green'],
            ['title' => 'Filled Openings', 'value' => $filledOpenings, 'desc' => 'closed by placement', 'icon' => 'orange'],
            ['title' => 'Today\'s Vacancies', 'value' => $todaysVacanciesCount, 'desc' => 'added today', 'icon' => 'purple'],
            ['title' => 'Today\'s Interviews', 'value' => rand(2, 8), 'desc' => 'scheduled today', 'icon' => 'blue'],
            ['title' => 'Selected Total', 'value' => rand(10, 45), 'desc' => 'offers in hand', 'icon' => 'green'],
            ['title' => 'On-Hold', 'value' => rand(3, 12), 'desc' => 'awaiting HR result', 'icon' => 'purple'],
            ['title' => 'Pending Leads', 'value' => rand(1, 6), 'desc' => 'assigned to self', 'icon' => 'red'],
        ];

        // Fetch actual vacancies from DB
        $vacancies = \App\Models\CurrentOpening::with('company')
            ->where($dateFilter)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        // Empty arrays for modules that don't exist yet
        $interviews = [];
        $followups = [];
        
        // Let's fetch some students as a placeholder from Admissions
        $admissions = \App\Models\Admission::with(['contact', 'course', 'batch'])->limit(10)->get();
        $studentStatus = [];
        foreach($admissions as $admission) {
            if($admission->contact) {
                $studentStatus[] = [
                    'name' => $admission->contact->name ?? 'Unknown',
                    'phone' => $admission->contact->phone ?? 'N/A',
                    'course' => $admission->course->title ?? 'N/A',
                    'batch' => $admission->batch_type ?? 'N/A',
                    'code' => $admission->batch->batch_code ?? 'N/A',
                    'company' => '-',
                    'location' => $admission->contact->city ?? '-',
                    'count' => '-',
                    'date' => $admission->created_at->format('d M, Y')
                ];
            }
        }

        return view('dashboards.placement-coordinator', compact('stats', 'vacancies', 'interviews', 'followups', 'studentStatus'));
    }

    public function interviewStatus(Request $request)
    {
        return app(InterviewStatusController::class)->index($request);
    }

    public function placementFollowups(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $query = \App\Models\Company::whereHas('activities', function($q) use ($startDate, $endDate) {
            $q->whereNotNull('next_followup_date');
            
            if ($startDate && $endDate) {
                $q->whereDate('next_followup_date', '>=', $startDate)
                  ->whereDate('next_followup_date', '<=', $endDate);
            }
        })->with(['activities' => function($q) {
            $q->whereNotNull('next_followup_date')->orderBy('next_followup_date', 'desc');
        }]);

        // Optional filter for "Today", "Upcoming", "Overdue" via query param
        $filter = $request->get('filter', 'today');
        if ($filter === 'today') {
            $query->whereHas('activities', function($q) {
                $q->whereDate('next_followup_date', today()->toDateString());
            });
        } elseif ($filter === 'overdue') {
            $query->whereHas('activities', function($q) {
                $q->whereDate('next_followup_date', '<', today()->toDateString());
            });
        } elseif ($filter === 'upcoming') {
            $query->whereHas('activities', function($q) {
                $q->whereDate('next_followup_date', '>', today()->toDateString());
            });
        }

        $companies = $query->paginate(15);

        // Stats for the top bar
        $todayCount = \App\Models\CompanyActivity::whereNotNull('next_followup_date')
            ->whereDate('next_followup_date', today()->toDateString())->count();
            
        $overdueCount = \App\Models\CompanyActivity::whereNotNull('next_followup_date')
            ->whereDate('next_followup_date', '<', today()->toDateString())->count();
            
        $upcomingCount = \App\Models\CompanyActivity::whereNotNull('next_followup_date')
            ->whereDate('next_followup_date', '>', today()->toDateString())->count();

        return view('dashboards.placement_followups', compact('companies', 'todayCount', 'overdueCount', 'upcomingCount', 'filter', 'startDate', 'endDate'));
    }

    /**
     * Display Learning Coordinator Dashboard.
     */
    public function learningCoordinator(Request $request): View
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $dateFilter = function ($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
            }
        };

        // Prefer live courses; fallback to any active course if none marked live
        $liveCourses = \App\Models\Course::where('course_type', 'live')
            ->where('status', '1')
            ->orderBy('title')
            ->get();

        if ($liveCourses->isEmpty()) {
            $liveCourses = \App\Models\Course::where('course_type', 'live')->orderBy('title')->get();
        }

        if ($liveCourses->isEmpty()) {
            $liveCourses = \App\Models\Course::where('status', '1')->orderBy('title')->get();
        }

        $totals = [
            'assignment_total' => \App\Models\Assignment::where($dateFilter)->count(),
            'assignment_active' => \App\Models\Assignment::where('status', 'active')->where($dateFilter)->count(),
            'assignment_pending' => \App\Models\Assignment::where(function ($q) {
                $q->where('status', 'pending')->orWhereNull('status')->orWhere('status', '');
            })->where($dateFilter)->count(),

            'mini_total' => \App\Models\MiniProject::where($dateFilter)->count(),
            'mini_active' => \App\Models\MiniProject::where('status', 'active')->where($dateFilter)->count(),
            'mini_pending' => \App\Models\MiniProject::where(function ($q) {
                $q->where('status', 'pending')->orWhereNull('status')->orWhere('status', '');
            })->where($dateFilter)->count(),

            'main_total' => \App\Models\MainProject::where($dateFilter)->count(),
            'main_active' => \App\Models\MainProject::where('status', 'active')->where($dateFilter)->count(),
            'main_pending' => \App\Models\MainProject::where(function ($q) {
                $q->where('status', 'pending')->orWhereNull('status')->orWhere('status', '');
            })->where($dateFilter)->count(),
        ];

        $totalPending = $totals['assignment_pending'] + $totals['mini_pending'] + $totals['main_pending'];
        $totalActive = $totals['assignment_active'] + $totals['mini_active'] + $totals['main_active'];
        $totalItems = $totalPending + $totalActive;
        $approvalRate = $totalItems > 0 ? round(($totalActive / $totalItems) * 100) : 100;

        $totals['total_pending'] = $totalPending;
        $totals['total_active'] = $totalActive;
        $totals['total_items'] = $totalItems;
        $totals['approval_rate'] = $approvalRate;

        // Fetch recent pending items awaiting LC approval
        $pendingAssignments = \App\Models\Assignment::where(function ($q) {
            $q->where('status', 'pending')->orWhereNull('status')->orWhere('status', '');
        })->where($dateFilter)->with(['course', 'batch'])->latest()->take(10)->get();

        $pendingMiniProjects = \App\Models\MiniProject::where(function ($q) {
            $q->where('status', 'pending')->orWhereNull('status')->orWhere('status', '');
        })->where($dateFilter)->with(['course', 'batch'])->latest()->take(10)->get();

        $pendingMainProjects = \App\Models\MainProject::where(function ($q) {
            $q->where('status', 'pending')->orWhereNull('status')->orWhere('status', '');
        })->where($dateFilter)->with(['course', 'batch'])->latest()->take(10)->get();

        // Fetch recent records across all statuses to populate activity stream
        $recentAssignments = \App\Models\Assignment::with(['course', 'batch'])->latest()->take(5)->get()->map(function ($item) {
            $item->record_type = 'Assignment';
            $item->type_badge_class = 'danger';
            $item->manage_url = route('assignments.index');

            return $item;
        });

        $recentMiniProjects = \App\Models\MiniProject::with(['course', 'batch'])->latest()->take(5)->get()->map(function ($item) {
            $item->record_type = 'Mini Project';
            $item->type_badge_class = 'success';
            $item->manage_url = route('mini_projects.index');

            return $item;
        });

        $recentMainProjects = \App\Models\MainProject::with(['course', 'batch'])->latest()->take(5)->get()->map(function ($item) {
            $item->record_type = 'Main Project';
            $item->type_badge_class = 'primary';
            $item->manage_url = route('main_projects.index');

            return $item;
        });

        $recentRecords = $recentAssignments->concat($recentMiniProjects)->concat($recentMainProjects)
            ->sortByDesc('created_at')->values()->take(8);

        $coursesData = [];
        foreach ($liveCourses as $course) {
            $assignments = \App\Models\Assignment::where('course_id', $course->id)->where($dateFilter)->get();
            $miniProjects = \App\Models\MiniProject::where('course_id', $course->id)->where($dateFilter)->get();
            $mainProjects = \App\Models\MainProject::where('course_id', $course->id)->where($dateFilter)->get();

            $assignCount = $assignments->count();
            $assignActive = $assignments->where('status', 'active')->count();
            $assignPending = $assignments->where(function ($assign) {
                return $assign->status === 'pending' || empty($assign->status);
            })->count();

            $miniCount = $miniProjects->count();
            $miniActive = $miniProjects->where('status', 'active')->count();
            $miniPending = $miniProjects->where(function ($project) {
                return $project->status === 'pending' || empty($project->status);
            })->count();

            $mainCount = $mainProjects->count();
            $mainActive = $mainProjects->where('status', 'active')->count();
            $mainPending = $mainProjects->where(function ($project) {
                return $project->status === 'pending' || empty($project->status);
            })->count();

            $coursesData[] = [
                'course' => $course,
                'assignment_count' => $assignCount,
                'assignment_active' => $assignActive,
                'assignment_pending' => $assignPending,
                'mini_count' => $miniCount,
                'mini_active' => $miniActive,
                'mini_pending' => $miniPending,
                'main_count' => $mainCount,
                'main_active' => $mainActive,
                'main_pending' => $mainPending,
            ];
        }

        return view('dashboards.learning', compact(
            'coursesData',
            'totals',
            'pendingAssignments',
            'pendingMiniProjects',
            'pendingMainProjects',
            'recentRecords',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display Student Dashboard.
     */
    public function student(): View
    {
        return view('dashboards.student');
    }
}


