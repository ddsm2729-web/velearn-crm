<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadTagController extends Controller
{
    public function index()
    {
        $tags = \App\Models\LeadTag::with('roles')->get();
        return view('settings.lead-tags.index', compact('tags'));
    }

    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('settings.lead-tags.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:lead_tags,name',
            'role_ids' => 'nullable|array',
        ]);

        $tag = \App\Models\LeadTag::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        $tag->roles()->sync($request->role_ids ?? []);

        return response()->json(['status' => true, 'message' => 'Tag created successfully']);
    }

    public function edit($id)
    {
        $tag = \App\Models\LeadTag::with('roles')->findOrFail($id);
        $roles = \Spatie\Permission\Models\Role::all();
        return view('settings.lead-tags.edit', compact('tag', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $tag = \App\Models\LeadTag::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:lead_tags,name,' . $id,
            'role_ids' => 'nullable|array',
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        $tag->roles()->sync($request->role_ids ?? []);

        return response()->json(['status' => true, 'message' => 'Tag updated successfully']);
    }

    public function destroy($id)
    {
        $tag = \App\Models\LeadTag::findOrFail($id);
        $tag->delete();
        return response()->json(['status' => true, 'message' => 'Tag deleted successfully']);
    }
}
