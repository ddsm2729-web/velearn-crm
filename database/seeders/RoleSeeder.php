<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'SuperAdmin',
            'Tele Caller',
            'Admission Officer',
            'Support Executive',
            'Technical Staff',
            'Technical Head',
            'Learning Coordinator',
            'Dues collector',
            'Digital Marketing',
            'Operation Executive',
            'Placement Coordinator',
            'Student Coordinator',
            'Student',
        ];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
        }

        // Define Permissions
        $permissions = [
            ['name' => 'Dashboard', 'group_name' => 'Dashboard'],
            ['name' => 'Role List', 'group_name' => 'Roles'],
            ['name' => 'Role Create', 'group_name' => 'Roles'],
            ['name' => 'Role Edit', 'group_name' => 'Roles'],
            ['name' => 'Role Delete', 'group_name' => 'Roles'],
            ['name' => 'Role Assign', 'group_name' => 'Roles'],
            ['name' => 'Permission List', 'group_name' => 'Permissions'],
            ['name' => 'Permission Create', 'group_name' => 'Permissions'],
            ['name' => 'Permission Edit', 'group_name' => 'Permissions'],
            ['name' => 'Permission Delete', 'group_name' => 'Permissions'],
            ['name' => 'Blog List', 'group_name' => 'Blogs'],
            ['name' => 'Blog Create', 'group_name' => 'Blogs'],
            ['name' => 'Blog Edit', 'group_name' => 'Blogs'],
            ['name' => 'Blog Delete', 'group_name' => 'Blogs'],
            ['name' => 'Blog Status', 'group_name' => 'Blogs'],
            ['name' => 'Webinar List', 'group_name' => 'Webinars'],
            ['name' => 'Webinar Create', 'group_name' => 'Webinars'],
            ['name' => 'Webinar Edit', 'group_name' => 'Webinars'],
            ['name' => 'Webinar Delete', 'group_name' => 'Webinars'],
            ['name' => 'Webinar Status', 'group_name' => 'Webinars'],
            ['name' => 'E-Book List', 'group_name' => 'E-Books'],
            ['name' => 'E-Book Create', 'group_name' => 'E-Books'],
            ['name' => 'E-Book Edit', 'group_name' => 'E-Books'],
            ['name' => 'E-Book Delete', 'group_name' => 'E-Books'],
            ['name' => 'E-Book Status', 'group_name' => 'E-Books'],
            ['name' => 'Staff List', 'group_name' => 'Staff'],
            ['name' => 'Staff Create', 'group_name' => 'Staff'],
            ['name' => 'Staff Edit', 'group_name' => 'Staff'],
            ['name' => 'Staff Delete', 'group_name' => 'Staff'],
            ['name' => 'Staff Status', 'group_name' => 'Staff'],
            ['name' => 'Technical Staff Monitoring', 'group_name' => 'Staff'],
            ['name' => 'Course List', 'group_name' => 'Courses'],
            ['name' => 'Course View', 'group_name' => 'Courses'],
            ['name' => 'Course Create', 'group_name' => 'Courses'],
            ['name' => 'Course Edit', 'group_name' => 'Courses'],
            ['name' => 'Course Delete', 'group_name' => 'Courses'],
            ['name' => 'Course Status', 'group_name' => 'Courses'],
            ['name' => 'Practice List', 'group_name' => 'Practices'],
            ['name' => 'Practice Create', 'group_name' => 'Practices'],
            ['name' => 'Practice Edit', 'group_name' => 'Practices'],
            ['name' => 'Practice Delete', 'group_name' => 'Practices'],
            ['name' => 'Practice Status', 'group_name' => 'Practices'],
            ['name' => 'Manage Contacts', 'group_name' => 'Contacts'],
            ['name' => 'Contact Create', 'group_name' => 'Contacts'],
            ['name' => 'Contact Edit', 'group_name' => 'Contacts'],
            ['name' => 'Contact Delete', 'group_name' => 'Contacts'],
            ['name' => 'Contact Export', 'group_name' => 'Contacts'],
            ['name' => 'Inbound Call List', 'group_name' => 'Calls'],
            ['name' => 'Outbound Call List', 'group_name' => 'Calls'],
            ['name' => 'Lead List', 'group_name' => 'Leads'],
            ['name' => 'Lead Create', 'group_name' => 'Leads'],
            ['name' => 'Lead Edit', 'group_name' => 'Leads'],
            ['name' => 'Lead Delete', 'group_name' => 'Leads'],
            ['name' => 'Lead Show', 'group_name' => 'Leads'],
            ['name' => 'Lead Activity Edit', 'group_name' => 'Leads'],
            ['name' => 'Lead Registration View', 'group_name' => 'Leads'],
            ['name' => 'Lead Payment View', 'group_name' => 'Leads'],
            ['name' => 'Follow-up List', 'group_name' => 'Follow-ups'],
            ['name' => 'Schedule Demo', 'group_name' => 'Demos'],
            ['name' => 'Demo Tracking', 'group_name' => 'Demos'],
            ['name' => 'Student List', 'group_name' => 'Students'],
            ['name' => 'Batch List', 'group_name' => 'Batches'],
            ['name' => 'Batch Progress', 'group_name' => 'Batches'],
            ['name' => 'Batch Topic Logs', 'group_name' => 'Batches'],
            ['name' => 'Batch Create', 'group_name' => 'Batches'],
            ['name' => 'Batch Edit', 'group_name' => 'Batches'],
            ['name' => 'Batch Delete', 'group_name' => 'Batches'],
            ['name' => 'Batch Allocation', 'group_name' => 'Batch Allocation'],
            ['name' => 'Batch Allocation View', 'group_name' => 'Batch Allocation'],
            ['name' => 'Batch Allocation Assign', 'group_name' => 'Batch Allocation'],
            ['name' => 'Batch Allocation Remove', 'group_name' => 'Batch Allocation'],
            ['name' => 'Batch Allocation Export', 'group_name' => 'Batch Allocation'],
            ['name' => 'view setting', 'group_name' => 'Settings'],
            ['name' => 'edit setting', 'group_name' => 'Settings'],
            ['name' => 'Schedule List', 'group_name' => 'Schedule'],
            ['name' => 'Schedule Create', 'group_name' => 'Schedule'],
            ['name' => 'Schedule Edit', 'group_name' => 'Schedule'],
            ['name' => 'Schedule Delete', 'group_name' => 'Schedule'],
            ['name' => 'Payment List', 'group_name' => 'Payments'],
            ['name' => 'Registration List', 'group_name' => 'Registrations'],
            ['name' => 'Doubt Query Lead', 'group_name' => 'Support'],
            ['name' => 'Doubt Query Followup', 'group_name' => 'Support'],
            ['name' => 'Doubt Allocation', 'group_name' => 'Support'],
            ['name' => 'Certificates', 'group_name' => 'Support'],
            ['name' => 'Lead Tag List', 'group_name' => 'Settings'],
            ['name' => 'Lead Tag Create', 'group_name' => 'Settings'],
            ['name' => 'Lead Tag Edit', 'group_name' => 'Settings'],
            ['name' => 'Lead Tag Delete', 'group_name' => 'Settings'],
            ['name' => 'Lead Status List', 'group_name' => 'Settings'],
            ['name' => 'Lead Status Create', 'group_name' => 'Settings'],
            ['name' => 'Lead Status Edit', 'group_name' => 'Settings'],
            ['name' => 'Lead Status Delete', 'group_name' => 'Settings'],
            ['name' => 'Lead Source List', 'group_name' => 'Settings'],
            ['name' => 'Lead Source Create', 'group_name' => 'Settings'],
            ['name' => 'Lead Source Edit', 'group_name' => 'Settings'],
            ['name' => 'Lead Source Delete', 'group_name' => 'Settings'],
            ['name' => 'Student Due List', 'group_name' => 'Payments'],
            ['name' => 'Due Followup', 'group_name' => 'Payments'],
            ['name' => 'Completed Payments', 'group_name' => 'Payments'],
            ['name' => 'Batch System', 'group_name' => 'Management'],
            ['name' => 'Students', 'group_name' => 'Management'],
            ['name' => 'Staff Monitoring', 'group_name' => 'Management'],
            ['name' => 'Student Monitoring', 'group_name' => 'Management'],
            ['name' => 'Demo Sessions', 'group_name' => 'Sessions'],
            ['name' => 'Technical Head Demo Sessions', 'group_name' => 'Sessions'],
            ['name' => 'Doubt Sessions', 'group_name' => 'Sessions'],
            ['name' => 'Placement Classes', 'group_name' => 'Placement'],
            ['name' => 'Placement Classes Create', 'group_name' => 'Placement'],
            ['name' => 'Placement Classes Edit', 'group_name' => 'Placement'],
            ['name' => 'Placement Classes Delete', 'group_name' => 'Placement'],
            ['name' => 'Technical Head Doubt Sessions', 'group_name' => 'Sessions'],
            ['name' => 'Assignment List', 'group_name' => 'Academics'],
            ['name' => 'Assignment Create', 'group_name' => 'Academics'],
            ['name' => 'Assignment Edit', 'group_name' => 'Academics'],
            ['name' => 'Assignment Delete', 'group_name' => 'Academics'],
            ['name' => 'Assignment Evaluate', 'group_name' => 'Academics'],
            ['name' => 'Assignment Complete', 'group_name' => 'Academics'],
            ['name' => 'Assignment Status', 'group_name' => 'Academics'],
            ['name' => 'Mini Project List', 'group_name' => 'Academics'],
            ['name' => 'Mini Project Create', 'group_name' => 'Academics'],
            ['name' => 'Mini Project Edit', 'group_name' => 'Academics'],
            ['name' => 'Mini Project Delete', 'group_name' => 'Academics'],
            ['name' => 'Mini Project Evaluate', 'group_name' => 'Academics'],
            ['name' => 'Mini Project Complete', 'group_name' => 'Academics'],
            ['name' => 'Mini Project Status', 'group_name' => 'Academics'],
            ['name' => 'Main Project List', 'group_name' => 'Academics'],
            ['name' => 'Main Project Create', 'group_name' => 'Academics'],
            ['name' => 'Main Project Edit', 'group_name' => 'Academics'],
            ['name' => 'Main Project Delete', 'group_name' => 'Academics'],
            ['name' => 'Main Project Evaluate', 'group_name' => 'Academics'],
            ['name' => 'Main Project Complete', 'group_name' => 'Academics'],
            ['name' => 'Main Project Status', 'group_name' => 'Academics'],
            ['name' => 'Meetings', 'group_name' => 'Tools'],
            ['name' => 'Company Database List', 'group_name' => 'Placement'],
            ['name' => 'Company Database Create', 'group_name' => 'Placement'],
            ['name' => 'Company Database Edit', 'group_name' => 'Placement'],
            ['name' => 'Company Database Delete', 'group_name' => 'Placement'],
            ['name' => 'Current Openings List', 'group_name' => 'Placement'],
            ['name' => 'Current Openings Create', 'group_name' => 'Placement'],
            ['name' => 'Current Openings Edit', 'group_name' => 'Placement'],
            ['name' => 'Current Openings Delete', 'group_name' => 'Placement'],
            ['name' => 'Interview Status', 'group_name' => 'Placement'],
            ['name' => 'Placement Coordinator Followup', 'group_name' => 'Placement'],
            ['name' => 'Applied Openings', 'group_name' => 'Placement'],
            ['name' => 'Eligible Students', 'group_name' => 'Placement'],
            ['name' => 'Student Coordinator Followup', 'group_name' => 'Placement'],
            
                        
            // Placement Evaluation
            ['name' => 'Placement Evaluation List', 'group_name' => 'Placement'],
            ['name' => 'Placement Evaluation Create', 'group_name' => 'Placement'],
            ['name' => 'Placement Evaluation Edit', 'group_name' => 'Placement'],
            ['name' => 'Placement Evaluation Delete', 'group_name' => 'Placement'],
            // Course Evaluation
            ['name' => 'Course Evaluation List', 'group_name' => 'Courses'],
            ['name' => 'Course Evaluation Create', 'group_name' => 'Courses'],
            ['name' => 'Course Evaluation Edit', 'group_name' => 'Courses'],
            ['name' => 'Course Evaluation Delete', 'group_name' => 'Courses'],
            // Course Eligibility
            ['name' => 'Course Eligibility List', 'group_name' => 'Courses'],
            ['name' => 'Course Eligibility Create', 'group_name' => 'Courses'],
            ['name' => 'Course Eligibility Edit', 'group_name' => 'Courses'],
            ['name' => 'Course Eligibility Delete', 'group_name' => 'Courses'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                ['group_name' => $permission['group_name'], 'guard_name' => 'web']
            );
        }

        // Assign 'Dashboard' permission to all roles
        $dashboardPermission = Permission::where('name', 'Dashboard')->first();
        $allRoles = Role::all();
        foreach ($allRoles as $role) {
            $role->givePermissionTo($dashboardPermission);
        }

        // Tele Caller Permissions
        $teleCaller = Role::where('name', 'Tele Caller')->first();
        if ($teleCaller) {
            $teleCaller->syncPermissions([
                'Dashboard',
                'Inbound Call List',
                'Outbound Call List',
                'Follow-up List',
                'Schedule Demo',
                'Demo Tracking',
                'Student List',
                'Batch List',
                'Lead Create',
                'Lead Edit',
                'Lead Activity Edit',
                'Lead Show',
            ]);
        }

        // Admission Officer Permissions
        $admissionOfficer = Role::where('name', 'Admission Officer')->first();
        if ($admissionOfficer) {
            $admissionOfficer->syncPermissions([
                'Dashboard',
                'Lead List',
                'Follow-up List',
                'Payment List',
                'Batch Allocation',
                'Lead Registration View',
                'Lead Payment View',
                'Lead Show',
            ]);
        }

        // Support Executive Permissions
        $supportExecutive = Role::where('name', 'Support Executive')->first();
        if ($supportExecutive) {
            $supportExecutive->syncPermissions([
                'Dashboard',
                'Doubt Query Lead',
                'Doubt Query Followup',
                'Doubt Allocation',
                'Certificates',
                'Batch Allocation',
                'Batch Allocation View',
                'Batch Allocation Assign',
           
            ]);
        }

        // Dues Collector Permissions
        $duesCollector = Role::where('name', 'Dues collector')->first();
        if ($duesCollector) {
            $duesCollector->syncPermissions([
                'Dashboard',
                'Student Due List',
                'Due Followup',
                'Completed Payments',
            ]);
        }

        // Technical Staff Permissions
        $technicalStaff = Role::where('name', 'Technical Staff')->first();
        if ($technicalStaff) {
            $technicalStaff->syncPermissions([
                'Dashboard',
                'Batch System',
                'Batch Topic Logs',
                'Students',
                'Student Monitoring',
                'Demo Sessions',
                'Doubt Sessions',
                'Assignment List',
                'Assignment Evaluate',
                'Assignment Complete',
                'Mini Project List',
                'Mini Project Evaluate',
                'Mini Project Complete',
                'Main Project List',
                'Main Project Evaluate',
                'Main Project Complete',
                'Meetings',
                'Placement Classes',
                'Placement Classes Create',
                'Placement Classes Edit',
                'Placement Classes Delete',
                'Placement Evaluation List',
                'Placement Evaluation Create',
                'Placement Evaluation Edit',
                'Placement Evaluation Delete',
            ]);
        }

        // Placement Coordinator Permissions
        $placementCoordinator = Role::where('name', 'Placement Coordinator')->first();
        if ($placementCoordinator) {
            $placementCoordinator->syncPermissions([
                'Dashboard',
                'Company Database List',
                'Company Database Create',
                'Company Database Edit',
                'Company Database Delete',
                'Current Openings List',
                'Current Openings Create',
                'Current Openings Edit',
                'Current Openings Delete',
                'Interview Status',
                'Placement Coordinator Followup',
            ]);
        }

        // Student Coordinator Permissions
        $studentCoordinator = Role::where('name', 'Student Coordinator')->first();
        if ($studentCoordinator) {
            $studentCoordinator->syncPermissions([
                'Dashboard',
                'Current Openings List',
                'Applied Openings',
                'Eligible Students',
                'Interview Status',
                'Student Coordinator Followup',
            ]);
        }

        // Technical Head Permissions
        $technicalHead = Role::where('name', 'Technical Head')->first();
        if ($technicalHead) {
            $technicalHead->syncPermissions([
                'Dashboard',
                'Course List',
                'Course View',
                'Course Create',
                'Course Edit',
                'Course Delete',
                'Course Status',
                'Batch System',
                'Batch Progress',
                'Batch Topic Logs',
                'Staff Monitoring',
                'Student Monitoring',
                'Technical Head Demo Sessions',
                'Technical Head Doubt Sessions',
                'Assignment List',
                'Assignment Create',
                'Assignment Edit',
                'Assignment Delete',
                'Assignment Evaluate',
                'Assignment Complete',
                'Mini Project List',
                'Mini Project Create',
                'Mini Project Edit',
                'Mini Project Delete',
                'Mini Project Evaluate',
                'Mini Project Complete',
                'Main Project List',
                'Main Project Create',
                'Main Project Edit',
                'Main Project Delete',
                'Main Project Evaluate',
                'Main Project Complete',
                'Placement Classes',
                'Placement Classes Create',
                'Placement Classes Edit',
                'Placement Classes Delete',
                'Placement Evaluation List',
                'Placement Evaluation Create',
                'Placement Evaluation Edit',
                'Placement Evaluation Delete',
                'Meetings',
            ]);
        }

        // Learning Coordinator Permissions
        $learningCoordinator = Role::where('name', 'Learning Coordinator')->first();
        if ($learningCoordinator) {
            $learningCoordinator->syncPermissions([
                'Dashboard',
                'Assignment List',
                'Assignment Status',
                'Mini Project List',
                'Mini Project Status',
                'Main Project List',
                'Main Project Status',
            ]);
        }

        // Assign all permissions to SuperAdmin
        $superAdmin = Role::where('name', 'SuperAdmin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions(Permission::all());
        }

        $operationExecutive = Role::where('name', 'Operation Executive')->first();
        if ($operationExecutive) {
            $operationExecutive->syncPermissions(Permission::all());
        }
    }
}

