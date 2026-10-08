<?php

namespace App\Http\Controllers;

use App\Models\LeadStatus;
use Illuminate\Http\Request;

class LeadStatusController extends Controller
{

    public function index()
    {
        $statuses = LeadStatus::with('roles')->get();
        return view('settings.lead-statuses.index', compact('statuses'));
    }

    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('settings.lead-statuses.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:lead_statuses,name',
            'color' => 'required|string',
            'role_ids' => 'nullable|array',
        ]);

        $status = LeadStatus::create([
            'name' => $request->name,
            'color' => $request->color,
        ]);
        
        $status->roles()->sync($request->role_ids ?? []);

        return response()->json(['status' => true, 'message' => 'Status created successfully']);
    }

    public function edit($id)
    {
        $status = LeadStatus::with('roles')->findOrFail($id);
        $roles = \Spatie\Permission\Models\Role::all();
        return view('settings.lead-statuses.edit', compact('status', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $status = LeadStatus::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:lead_statuses,name,' . $id,
            'color' => 'required|string',
            'role_ids' => 'nullable|array',
        ]);

        $status->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);
        
        $status->roles()->sync($request->role_ids ?? []);

        return response()->json(['status' => true, 'message' => 'Status updated successfully']);
    }

    public function destroy($id)
    {
        $status = LeadStatus::findOrFail($id);
        $status->delete();
        return response()->json(['status' => true, 'message' => 'Status deleted successfully']);
    }
}
