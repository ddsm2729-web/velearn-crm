<?php

use App\Models\State;
use App\Models\StaffSchedule;

if (!function_exists('getStateName')) {
    /**
     * Get state name by ID.
     */
    function getStateName($stateId)
    {
        if (!empty($stateId) && is_numeric($stateId)) {
            $stateData = State::select('state_name')->where('id', $stateId)->first();
            return $stateData ? $stateData->state_name : '';
        }
        return '';
    }
}

if (!function_exists('getGenderTitle')) {
    /**
     * Get human-readable gender title.
     */
    function getGenderTitle($gender)
    {
        $genders = [
            1 => 'Male',
            2 => 'Female',
            3 => 'Transgender',
            'male' => 'Male',
            'female' => 'Female',
            'transgender' => 'Transgender',
        ];

        $key = is_numeric($gender) ? (int) $gender : strtolower((string) $gender);

        return $genders[$key] ?? '--';
    }
}

if (!function_exists('patientType')) {
    /**
     * Get patient/user type description (Free or Paid).
     */
    function patientType($type)
    {
        if (is_numeric($type)) {
            $typeInt = (int) $type;
            if ($typeInt === 1) {
                return 'Free';
            }
            if ($typeInt === 2) {
                return 'Paid';
            }
        }
        return '--';
    }
}

if (!function_exists('patientStatus')) {
    /**
     * Get patient/user status description.
     */
    function patientStatus($status)
    {
        if (is_numeric($status)) {
            $statusInt = (int) $status;
            if ($statusInt === 0) {
                return 'Deactive';
            }
            if ($statusInt === 1) {
                return 'Active';
            }
        }
        return 'Suspended';
    }
}

if (!function_exists('hasSchedule')) {
    /**
     * Check if a staff member has an assigned schedule.
     */
    function hasSchedule($staffId)
    {
        if (empty($staffId) || !is_numeric($staffId)) {
            return false;
        }

        return StaffSchedule::where('staff_id', $staffId)->exists();
    }
}