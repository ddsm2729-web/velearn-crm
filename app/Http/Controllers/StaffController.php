<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\State;
use Illuminate\Http\Request;
use App\Models\Staff;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StaffController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Staff List', only: ['index']),
            new Middleware('permission:Staff Create', only: ['create', 'store']),
            new Middleware('permission:Staff Edit', only: ['edit', 'update']),
            new Middleware('permission:Staff Delete', only: ['destroy']),
            new Middleware('permission:Staff Status', only: ['updateStaffStatus']),
        ];
    }

    /**
     * Display a listing of the staff.
     */
    public function index(Request $request)
    {
        $staffs = Staff::with(['role:id,name']);

        $roles = Role::select('id','name')
            ->whereNotIn('name',['SuperAdmin'])
            ->orderBy('name','ASC')
            ->get();

        $search = $request->search ?? null;
        $roleId = $request->role_id ?? null;

        if (!empty($search)) {
            $staffs->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$search}%"])
                  ->orWhere('primary_phone', 'LIKE', "%{$search}%")
                  ->orWhere('secondary_phone', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($roleId)) {
            $staffs->where('role_id', $roleId);
        }

        $staffs = $staffs->latest('id')->paginate(10)->withQueryString();

        $stats = [
            'total'    => Staff::count(),
            'active'   => Staff::where('status', 1)->count(),
            'inactive' => Staff::where('status', 0)->count(),
            'roles'    => $roles->count(),
        ];

        return view('staffs.index', compact('staffs', 'roles', 'stats'));
    }

    /**
     * Show the form for creating a new staff.
     */
    public function create()
    {
        $roles = Role::select('id','name')->whereNotIn('name',['SuperAdmin'])->orderBy('name','ASC')->get();
        $states = State::select('id','state_name')->orderBy('state_name','ASC')->get();

        return view('staffs.create', compact('roles','states'));
    }

    /**
     * Store a newly created staff.
     */
    public function store(Request $request)
    {
        $rules = [
            'role_id'           => 'required',
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|unique:staff,email',
            'gender'            => 'required',
            'primary_phone'     => 'required',
            'password'          => 'required|min:6|confirmed',
        ];

        $messages = [
            'role_id.required'         => '* Please select the Role of Staff',
            'first_name.required'      => '* First Name should not be blank',
            'last_name.required'       => '* Last Name should not be blank',
            'email.required'           => '* Please select the Staff Email',
            'gender.required'          => '* Please select the Staff Gender',
            'primary_phone.required' => '* Primary Contact should not be blank',
            'password.required'        => '* Password should not be blank',
            'password.min'             => '* Password should be at least 6 characters',
            'password.confirmed'       => '* Password and Confirm Password do not match',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            // 🔴 LOG VALIDATION ERRORS
            Log::warning('Staff creation validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->except(['password', 'password_confirmation']),
            ]);

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $role = Role::findOrFail($request->role_id);

            $authUser = User::create([
                'name'       => ucwords($request->first_name).' '.ucwords($request->last_name),
                'email'      => $request->email ?? null,
                'password'   => bcrypt($request->password),
                'role_id'    => $request->role_id,
            ])->assignRole($role->name);

            $staff = Staff::create([
                'auth_id'         => $authUser->id,
                'first_name'      => trim(ucwords($request->first_name)),
                'last_name'       => trim(ucwords($request->last_name)),
                'email'           => $request->email ?? null,
                'primary_phone'   => $request->primary_phone,
                'secondary_phone' => $request->secondary_phone ?? null,
                'gender'          => $request->gender,
                'role_id'         => $request->role_id,
            ]);

            DB::commit();

            Log::info('Staff created successfully', [
                'staff_id' => $staff->id,
                'auth_id'  => $authUser->id,
            ]);

            return response()->json(['status' => true, 'message' => 'Staff has been created successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();

            // 🔥 LOG EXCEPTION ERROR
            Log::error('Staff creation failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->except(['password', 'password_confirmation']),
            ]);

            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing a staff.
     */
    public function edit(string $id)
    {
        $staff = Staff::findOrFail(decrypt($id));
        $roles = Role::select('id','name')->whereNotIn('name',['SuperAdmin'])->orderBy('name','ASC')->get();
        $states = State::select('id','state_name')->orderBy('state_name','ASC')->get();

        return view('staffs.edit', compact('staff','roles','states'));
    }

    /**
     * Update a staff.
     */
    public function update(Request $request, string $id)
    {
        $staff = Staff::findOrFail(decrypt($id));

        $rules = [
            'role_id'         => 'required',
            'first_name'      => 'required',
            'last_name'       => 'required',
            'email'           => 'nullable|unique:staff,email,'.$staff->id,
            'gender'          => 'required',
            'primary_phone' => 'required',
        ];

        $messages = [
            'role_id.required'         => '* Please select the Role of Staff',
            'first_name.required'      => '* First Name should not be blank',
            'last_name.required'       => '* Last Name should not be blank',
            'gender.required'          => '* Please select the Staff Gender',
            'primary_phone.required' => '* Primary Contact should not be blank',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $role = Role::findOrFail($request->role_id);

            $authUser = User::findOrFail($staff->auth_id);
            $authUser->update([
                'name'     => ucwords($request->first_name).' '.ucwords($request->last_name),
                'email'    => $request->email ?? null,
                'role_id'  => $request->role_id,
            ]);
            $authUser->syncRoles($role->name);

            $staff->update([
                'first_name'      => trim(ucwords($request->first_name)),
                'last_name'       => trim(ucwords($request->last_name)),
                'email'           => $request->email ?? null,
                'primary_phone'   => $request->primary_phone,
                'secondary_phone' => $request->secondary_phone ?? null,
                'gender'          => $request->gender,
                'role_id'         => $request->role_id,
            ]);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Staff has been updated successfully.']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete a staff.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $staff = Staff::findOrFail(decrypt($id));
            $authUser = User::findOrFail(decrypt($request->authId));

            $staff->delete();
            $authUser->delete();

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Staff has been deleted successfully.']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update the status of a staff.
     */
    public function updateStaffStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $authId = decrypt($request->authId);
            $staffId = decrypt($request->staffsId);
            $status = $request->status;

            $staff = Staff::where(['auth_id' => $authId, 'id' => $staffId])->firstOrFail();
            $staff->update(['status' => $status]);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Staff status has been changed successfully.']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
