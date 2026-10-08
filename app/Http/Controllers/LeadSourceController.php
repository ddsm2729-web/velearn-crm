<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadSourceController extends Controller
{
    public function index()
    {
        $sources = \App\Models\LeadSource::with('roles')->get();
        return view('settings.lead-sources.index', compact('sources'));
    }

    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('settings.lead-sources.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:lead_sources,name',
            'role_ids' => 'nullable|array',
        ]);

        $source = \App\Models\LeadSource::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        $source->roles()->sync($request->role_ids ?? []);

        return response()->json(['status' => true, 'message' => 'Source created successfully']);
    }

    public function edit($id)
    {
        $source = \App\Models\LeadSource::with('roles')->findOrFail($id);
        $roles = \Spatie\Permission\Models\Role::all();
        return view('settings.lead-sources.edit', compact('source', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $source = \App\Models\LeadSource::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:lead_sources,name,' . $id,
            'role_ids' => 'nullable|array',
        ]);

        $source->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        $source->roles()->sync($request->role_ids ?? []);

        return response()->json(['status' => true, 'message' => 'Source updated successfully']);
    }

    public function destroy($id)
    {
        $source = \App\Models\LeadSource::findOrFail($id);
        $source->delete();
        return response()->json(['status' => true, 'message' => 'Source deleted successfully']);
    }
}
