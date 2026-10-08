<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BlogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Blog List', only: ['index']),
            new Middleware('permission:Blog Create', only: ['create', 'store']),
            new Middleware('permission:Blog Edit', only: ['edit', 'update']),
            new Middleware('permission:Blog Delete', only: ['destroy']),
            new Middleware('permission:Blog Status', only: ['updateStatus']),
        ];
    }

    /**
     * Display a listing of the blogs.
     */
    public function index(Request $request)
    {
        $blogs = Blog::with('info')
            ->when($request->search, function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created blog in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'published_date' => 'required|date',
            'descriptions'   => 'required|array|min:1',
            'descriptions.*' => 'required|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'status'         => 'required|in:draft,published',
        ]);

        DB::beginTransaction();

        try {
            // Upload image if provided
            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('blogs'), $imageName);
            }

            // Create blog
            $blog = Blog::create([
                'title'          => $request->title,
                'author'         => $request->author,
                'published_date' => $request->published_date,
                'image'          => $imageName,
                'status'         => $request->status,
            ]);

            // Create blog info (descriptions)
            foreach ($request->descriptions as $desc) {
                $blog->info()->create(['descriptions' => $desc]);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Blog created successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit($id)
    {
        $blog = Blog::with('info')->findOrFail(decrypt($id));
        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'published_date' => 'required|date',
            'descriptions'   => 'required|array|min:1',
            'descriptions.*' => 'required|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'status'         => 'required|in:draft,published',
        ]);

        DB::beginTransaction();

        try {
            $blog = Blog::with('info')->findOrFail(decrypt($id));
            
            $imageName = $blog->image;

            // Replace image if new file uploaded
            if ($request->hasFile('image')) {
                if ($blog->image && file_exists(public_path('blogs/' . $blog->image))) {
                    unlink(public_path('blogs/' . $blog->image));
                }
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('blogs'), $imageName);
            }

            // Update blog
            $blog->update([
                'title'          => $request->title,
                'author'         => $request->author,
                'published_date' => $request->published_date,
                'image'          => $imageName,
                'status'         => $request->status,
            ]);

            // Remove old descriptions and add new
            $blog->info()->delete();
            foreach ($request->descriptions as $desc) {
                $blog->info()->create(['descriptions' => $desc]);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Blog updated successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $blog = Blog::findOrFail(decrypt($id));

            if ($blog->image && file_exists(public_path('blogs/' . $blog->image))) {
                unlink(public_path('blogs/' . $blog->image));
            }

            $blog->delete(); // Related info deleted via cascade

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Blog deleted successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    /**
     * Toggle the status of the blog (draft/published).
     */
    public function updateStatus(Blog $blog)
    {
        $blog->status = $blog->status === 'published' ? 'draft' : 'published';
        $blog->save();

        return response()->json([
            'status'     => true,
            'message'    => 'Blog status updated successfully',
            'new_status' => $blog->status,
        ]);
    }

     /**
     * ✅ Get All blogs
     * API: GET /api/blogs
     */
    public function blogsInfo()
    {
        $blogs = Blog::with('info')
            ->where('status', 'published')
            ->latest()
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Blog list fetched successfully',
            'data'    => $blogs->map(function ($blog) {
                return [
                    'id'             => $blog->id,
                    'title'          => $blog->title,
                    'author'         => $blog->author,
                    'published_date' => $blog->published_date,
                    'image_url'      => $blog->image
                        ? asset('blogs/' . $blog->image)
                        : null,
                    'descriptions'   => $blog->info->pluck('descriptions'),
                ];
            })
        ], 200);
    }

    /**
     * ✅ Get Single blog Detail
     * API: GET /api/blog/{id}
     */
    public function blogDetail($id)
    {
        try {
            $blog = Blog::with('info')->find($id);

            if (!$blog) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Blog not found'
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Blog details fetched successfully',
                'data'    => [
                    'id'             => $blog->id,
                    'title'          => $blog->title,
                    'author'         => $blog->author,
                    'published_date' => $blog->published_date,
                    'status'         => $blog->status,
                    'image_url'      => $blog->image
                        ? asset('blogs/' . $blog->image)
                        : null,
                    'descriptions'   => $blog->info->map(function ($info) {
                        return [
                            'id'          => $info->id,
                            'description' => $info->descriptions
                        ];
                    }),
                ]
            ], 200);

        } catch (\Throwable $e) {

            Log::error('Blog detail API error', [
                'blog_id' => $id,
                'error'   => $e->getMessage()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while fetching blog details'
            ], 500);
        }
    }
}
