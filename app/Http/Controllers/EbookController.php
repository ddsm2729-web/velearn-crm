<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ebook;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EbookController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:E-Book List', only: ['index']),
            new Middleware('permission:E-Book Create', only: ['store']),
            new Middleware('permission:E-Book Edit', only: ['update']),
            new Middleware('permission:E-Book Delete', only: ['destroy']),
            new Middleware('permission:E-Book Status', only: ['toggleStatus']),
        ];
    }

    public function index()
    {
        $ebooks = Ebook::latest()->paginate(12);
        return view('ebooks.index', compact('ebooks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'file' => 'required|mimes:pdf|max:10000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
        ]);

        $ebook = new Ebook();
        $ebook->title = $request->title;
        $ebook->author = $request->author;
        $ebook->is_free = $request->price > 0 ? false : true;
        $ebook->price = $request->price ?? 0;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ebooks'), $fileName);
            $ebook->file_path = 'uploads/ebooks/' . $fileName;
        }

        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbName = time() . '_' . $thumb->getClientOriginalName();
            $thumb->move(public_path('uploads/ebooks/thumbnails'), $thumbName);
            $ebook->thumbnail = 'uploads/ebooks/thumbnails/' . $thumbName;
        }

        $ebook->save();

        return back()->with('success', 'E-book uploaded successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'file' => 'nullable|mimes:pdf|max:10000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
        ]);

        $ebook = Ebook::findOrFail($id);
        $ebook->title = $request->title;
        $ebook->author = $request->author;
        $ebook->is_free = $request->price > 0 ? false : true;
        $ebook->price = $request->price ?? 0;

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($ebook->file_path && file_exists(public_path($ebook->file_path))) {
                unlink(public_path($ebook->file_path));
            }
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ebooks'), $fileName);
            $ebook->file_path = 'uploads/ebooks/' . $fileName;
        }

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($ebook->thumbnail && file_exists(public_path($ebook->thumbnail))) {
                unlink(public_path($ebook->thumbnail));
            }
            $thumb = $request->file('thumbnail');
            $thumbName = time() . '_' . $thumb->getClientOriginalName();
            $thumb->move(public_path('uploads/ebooks/thumbnails'), $thumbName);
            $ebook->thumbnail = 'uploads/ebooks/thumbnails/' . $thumbName;
        }

        $ebook->save();

        return back()->with('success', 'E-book updated successfully');
    }

    public function destroy($id)
    {
        $ebook = Ebook::findOrFail($id);
        
        // Delete files
        if ($ebook->file_path && file_exists(public_path($ebook->file_path))) {
            unlink(public_path($ebook->file_path));
        }
        if ($ebook->thumbnail && file_exists(public_path($ebook->thumbnail))) {
            unlink(public_path($ebook->thumbnail));
        }

        $ebook->delete();

        return response()->json(['status' => true, 'message' => 'E-book deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $ebook = Ebook::findOrFail($id);
        $ebook->status = !$ebook->status;
        $ebook->save();

        return response()->json([
            'status' => true, 
            'new_status' => $ebook->status,
            'message' => 'Status updated successfully'
        ]);
    }
}
