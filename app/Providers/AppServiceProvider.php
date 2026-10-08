<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrap();

        view()->composer(['includes.sidebar', 'includes.super_admin_sidebar'], function ($view) {
            if (auth()->check()) {
                $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
                $staffId = $staff ? $staff->id : null;
                $isSuperAdmin = auth()->user()->hasRole('SuperAdmin');

                // Lead Count
                $leadQuery = \App\Models\Lead::query();
                if (!$isSuperAdmin && $staffId) {
                    $handledLeadIds = \App\Models\CallLog::where('staff_id', $staffId)->pluck('lead_id')->merge(
                        \App\Models\LeadActivity::where('staff_id', $staffId)->pluck('lead_id')
                    )->unique();
                    $leadQuery->where(function($q) use ($staffId, $handledLeadIds) {
                        $q->where('assigned_to', $staffId)->orWhereIn('id', $handledLeadIds);
                    });
                }
                $view->with('leadCount', $leadQuery->count());

                // Followup Count (upcoming/today)
                $followupQuery = \App\Models\Lead::whereHas('latestActivity', function($q) use ($staffId, $isSuperAdmin) {
                    $q->whereDate('next_followup_date', '>=', now()->toDateString());
                    if (!$isSuperAdmin && $staffId) {
                        $q->where('staff_id', $staffId);
                    }
                });
                $view->with('followupCount', $followupQuery->count());

                // Missed Followup Count
                $missedQuery = \App\Models\Lead::whereHas('latestActivity', function($q) use ($staffId, $isSuperAdmin) {
                    $q->whereDate('next_followup_date', '<', now()->toDateString());
                    if (!$isSuperAdmin && $staffId) {
                        $q->where('staff_id', $staffId);
                    }
                });
                $view->with('missedFollowupCount', $missedQuery->count());

                // Student Count
                // Depending on the exact logic for backend
                $studentQuery = \App\Models\Admission::whereHas('course', function($q) {
                    $q->where('course_type', 'live')->where('status', '1');
                })->whereIn('status', ['Active', 'ongoing', 'Ongoing', 'pending_fee', 'Completed']);

                if (auth()->user()->hasRole('Technical Staff')) {
                    if ($staffId) {
                        $studentQuery->whereHas('batch', function($q) use ($staffId) {
                            $q->where('instructor_id', $staffId);
                        });
                    } else {
                        $studentQuery->where('id', 0);
                    }
                }
                
                $studentCount = $studentQuery->count();
                
                $view->with('studentCount', $studentCount);
            }
        });
    }
}
