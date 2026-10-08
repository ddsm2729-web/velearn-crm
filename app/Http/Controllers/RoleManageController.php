<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class RoleManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::whereNotIn('name', ['SuperAdmin'])->orderBy('id','desc')->get();
        return view('roles-permission.role-list', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles-permission.role-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'role_name' => 'required|string|regex:/^[a-zA-Z\s]+$/|unique:roles,name',
        ];

        $messages = [
            'role_name.required' => '* Role Name is required.',
            'role_name.string'   => '* Role Name must be a string.',
            'role_name.unique'   => '* Role with this name already exists.',
            'role_name.regex'    => '* Role Name must only contain alphabetic characters and spaces.',
        ];

        $request->validate($rules, $messages);

        try {
            $roleName = ucwords(trim($request->role_name));

            $role = Role::create(['name' => $roleName]);

            if ($role) {
                return response()->json([
                    'status' => true,
                    'message' => 'Role has been created successfully.'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to create Role, please try again.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage() // ✅ fixed missing parentheses
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $roleId = decrypt($id);
        $role = Role::findOrFail($roleId);
        $roleName = $role->name;
        $assignedPermission = $role->permissions->pluck('name')->toArray();
        $permissions = Permission::all()->groupBy('group_name');

        return view('roles-permission.permission-assign', compact('roleName','roleId','assignedPermission','permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roleId = decrypt($id);
        $role = Role::findOrFail($roleId);
        return view('roles-permission.role-edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $roleId = decrypt($id);

            $rules = [
                'role_name' => 'required|string|regex:/^[a-zA-Z\s]+$/|unique:roles,name,' . $roleId,
            ];

            $messages = [
                'role_name.required' => '* Role Name is required.',
                'role_name.string'   => '* Role Name must be a string.',
                'role_name.unique'   => '* A Role with this name already exists.',
                'role_name.regex'    => '* Role Name must only contain alphabetic characters and spaces.',
            ];

            $request->validate($rules, $messages);

            $role = Role::findOrFail($roleId);

            $roleName = ucwords(trim($request->role_name));
            $role->update(['name' => $roleName]);

            return response()->json([
                'status' => true,
                'message' => 'Role has been updated successfully.'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
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
            $roleId = decrypt($id);
            $role = Role::findOrFail($roleId);
            if ( $role ){
                if($role->delete()) {
                    return response()->json(['status' => true, 'message' => 'Role deleted successfully.']);
                }
                return response()->json(['status' => false, 'message' => 'Failed to delete Role, Try again.']);
            }
            return response()->json(['status' => false, 'message' => 'Role with this name, Not found.']);
        } 
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Store a assigned permission to specific role.
     */
    public function assignPermissionsToRole (Request $request)
    {
        $request->validate([
            'permission_assign' => 'required|array',
            'permission_assign.*' => 'integer|exists:permissions,id',
        ],[
            'permission_assign.required'    =>  '* Choose the permissions you wish to assign to the specific role.'
        ]);
    
        $role = Role::findOrFail($request->role_id);
    
        $permissions = Permission::whereIn('id', $request->permission_assign)->get();
        $role->syncPermissions($permissions);
    
        return response()->json([
            'status'    =>  true,
            'message'   =>  'Permissions assigned successfully to the role.'
        ]);
    }
}
