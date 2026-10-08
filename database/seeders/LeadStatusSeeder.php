<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadStatus;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LeadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Lead Status Permissions Exist & Assign to SuperAdmin and Operation Executive
        $permissions = [
            'Lead Status List',
            'Lead Status Create',
            'Lead Status Edit',
            'Lead Status Delete',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName],
                ['group_name' => 'Settings', 'guard_name' => 'web']
            );
        }

        $superAdmin = Role::where('name', 'SuperAdmin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        $operationExecutive = Role::where('name', 'Operation Executive')->first();
        if ($operationExecutive) {
            $operationExecutive->givePermissionTo($permissions);
        }

        // 2. Final Lead Statuses with Assigned Roles based on the Settings UI
        $statuses = [
            [
                'name' => 'Active',
                'color' => '#3b82f6',
                'roles' => ['SuperAdmin', 'Tele Caller'],
            ],
            [
                'name' => 'Converted',
                'color' => '#10b981',
                'roles' => ['SuperAdmin', 'Tele Caller', 'Admission Officer'],
            ],
            [
                'name' => 'Confirmed',
                'color' => '#8b5cf6',
                'roles' => ['SuperAdmin', 'Tele Caller', 'Admission Officer'],
            ],
            [
                'name' => 'Registered',
                'color' => '#06b6d4',
                'roles' => ['SuperAdmin', 'Tele Caller', 'Admission Officer'],
            ],
        ];

        $finalNames = collect($statuses)->pluck('name')->toArray();

        // 3. Remove any other statuses not in the final 4 list (if no leads or activities reference them)
        $statusesToDelete = LeadStatus::whereNotIn('name', $finalNames)->get();
        foreach ($statusesToDelete as $toDelete) {
            $hasLeads = Lead::where('lead_status_id', $toDelete->id)->exists();
            $hasActivities = LeadActivity::where('status_id', $toDelete->id)->exists();
            if (! $hasLeads && ! $hasActivities) {
                $toDelete->roles()->detach();
                $toDelete->delete();
            }
        }

        // 4. Create or Update Statuses and Assign Roles
        foreach ($statuses as $statusData) {
            $status = LeadStatus::updateOrCreate(
                ['name' => $statusData['name']],
                ['color' => $statusData['color']]
            );

            $roleIds = Role::whereIn('name', $statusData['roles'])->pluck('id');
            $status->roles()->sync($roleIds);
        }
    }
}

