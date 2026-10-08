<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::latest()->get();
        return view('roles-permission.permission-list', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles-permission.permission-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'permission_name' => 'required|unique:permissions,name|regex:/^[a-zA-Z\s]+$/',
            'group_name' => 'required|string',
        ];

        $messages = [
            'permission_name.required' => '* Permission Name is required.',
            'permission_name.unique' => '* Permission with this name already exists.',
            'permission_name.regex' => '* Permission Name must only contain alphabetic characters.',
            'group_name.required' => '* Guard Name is required.',
        ];

        $request->validate($rules, $messages);

        try {
            $permissionName = ucwords($request->permission_name);

            $permission = Permission::create([
                'name' => $permissionName,
                'group_name' => $request->group_name,
                'guard_name' => 'web',
            ]);

            // ✅ Assign permission to SuperAdmin automatically
            $superAdmin = Role::where('name', 'SuperAdmin')->first();

            if ($superAdmin) {
                $superAdmin->givePermissionTo($permission);
            }

            return response()->json(['status' => true, 'message' => 'Permission created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permissionId = decrypt($id);
        $permission = Permission::findOrFail($permissionId);
        return view('roles-permission.permission-edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permissionId = decrypt($id);

        $rules = [
            'permission_name' => 'required|regex:/^[a-zA-Z\s]+$/|unique:permissions,name,' . $permissionId
        ];

        $messages = [
            'permission_name.required' => '* Permission Name should be required.',
            'permission_name.unique'   => '* Permission with this name already exists.',
            'permission_name.regex'    => '* Permission Name must only contain alphabetic characters.',
        ];

        $request->validate($rules, $messages);

        try {
            $permission = Permission::findOrFail($permissionId);

            // Update permission name
            $permission->update([
                'name' => $request->permission_name
            ]);

            // Get SuperAdmin role
            $superAdmin = Role::where('name', 'SuperAdmin')->first();

            if ($superAdmin) {
                // Assign permission only if not already assigned
                if (!$superAdmin->hasPermissionTo($permission->name)) {
                    $superAdmin->givePermissionTo($permission);
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Permission has been updated and assigned successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $permissionId = decrypt($id);
            $permission = Permission::findOrFail($permissionId);
            if ( $permission ){
                if($permission->delete()) {
                    return response()->json(['status' => true, 'message' => 'Permission deleted successfully.']);
                }
                return response()->json(['status' => false, 'message' => 'Failed to delete Permission, Try again.']);
            }
            return response()->json(['status' => false, 'message' => 'Permission with this name, Not found.']);
        } 
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
