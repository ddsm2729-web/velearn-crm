<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffSchedule;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StaffScheduleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Schedule List', only: ['index']),
            new Middleware('permission:Schedule Create', only: ['generate']),
            new Middleware('permission:Schedule Edit', only: ['updateStatus']),
            new Middleware('permission:Schedule Delete', only: ['destroy', 'clearAll']),
        ];
    }

    public function index(Request $request)
    {
        $staffs = Staff::with('role')
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['Admission Officer', 'Technical Staff']);
            })->get()->groupBy('role.name');

        // Identify staff who already have master schedules
        $assignedStaffIds = StaffSchedule::pluck('staff_id')
            ->unique()
            ->toArray();

        $selectedStaffId = $request->staff_id ?? (auth()->user()->hasRole('SuperAdmin') ? null : auth()->id());

        $schedules = [];
        $selectedStaff = null;
        $totalSlots = 0;
        $availableSlots = 0;
        $busySlots = 0;
        $offSlots = 0;

        if ($selectedStaffId) {
            $selectedStaff = Staff::find($selectedStaffId);
            $allSlots = StaffSchedule::where('staff_id', $selectedStaffId)
                ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
                ->orderBy('schedule_time')
                ->get();

            $schedules = $allSlots->groupBy('day');
            $totalSlots    = $allSlots->count();
            $availableSlots = $allSlots->where('status', 'available')->count();
            $busySlots      = $allSlots->where('status', 'busy')->count();
            $offSlots       = $allSlots->where('status', 'off')->count();
        }

        return view('staff_schedules.index', compact(
            'staffs', 'schedules', 'selectedStaffId', 'assignedStaffIds',
            'selectedStaff', 'totalSlots', 'availableSlots', 'busySlots', 'offSlots'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'days'     => 'required|array',
            'times'    => 'required|array',
        ]);

        $selectedDays  = $request->days;
        $selectedTimes = $request->times;
        $keepIds = [];

        foreach ($selectedDays as $day) {
            foreach ($selectedTimes as $time) {
                $schedule = StaffSchedule::firstOrCreate([
                    'staff_id'      => $request->staff_id,
                    'day'           => $day,
                    'schedule_time' => $time,
                ], [
                    'status' => 'available'
                ]);
                $keepIds[] = $schedule->id;
            }
        }

        // Remove any slots for this staff member that are no longer selected
        StaffSchedule::where('staff_id', $request->staff_id)
            ->whereNotIn('id', $keepIds)
            ->delete();

        return redirect()->route('staff.schedule.index', ['staff_id' => $request->staff_id])
            ->with('success', 'Master schedule updated successfully');
    }

    public function fetch($staff_id)
    {
        $schedules = StaffSchedule::where('staff_id', $staff_id)->get();

        return response()->json([
            'status' => true,
            'days'   => $schedules->pluck('day')->unique()->values(),
            'times'  => $schedules->pluck('schedule_time')->unique()->values()
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:staff_schedules,id',
            'status' => 'required|in:available,busy,off'
        ]);

        $schedule = StaffSchedule::findOrFail($request->id);
        $schedule->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => 'Slot status updated']);
    }

    public function destroy($id)
    {
        $schedule = StaffSchedule::findOrFail($id);
        $staffId  = $schedule->staff_id;
        $schedule->delete();

        if (request()->expectsJson()) {
            return response()->json(['status' => true, 'message' => 'Slot removed successfully']);
        }

        return redirect()->route('staff.schedule.index', ['staff_id' => $staffId])
            ->with('success', 'Slot removed successfully');
    }

    public function clearAll($staff_id)
    {
        StaffSchedule::where('staff_id', $staff_id)->delete();

        if (request()->expectsJson()) {
            return response()->json(['status' => true, 'message' => 'All schedules cleared']);
        }

        return redirect()->route('staff.schedule.index')
            ->with('success', 'All schedules cleared for this staff member');
    }
}
