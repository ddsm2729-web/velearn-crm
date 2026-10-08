<?php

namespace App\Http\Controllers;

use App\Models\PlacementClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\File;

class PlacementClassController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if ($request->is('api/*')) {
                    return $next($request);
                }

                return app(\Spatie\Permission\Middleware\PermissionMiddleware::class)->handle(
                    $request,
                    $next,
                    'Placement Classes|Placement Classes Create|Placement Classes Edit|Placement Classes Delete'
                );
            }, only: ['index', 'show']),
            new Middleware('permission:Placement Classes Create', only: ['create', 'store']),
            new Middleware('permission:Placement Classes Edit', only: ['edit', 'update']),
            new Middleware('permission:Placement Classes Delete', only: ['destroy']),
        ];
    }
    
    public function index(Request $request)
    {
        $query = PlacementClass::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('day', 'like', "%{$search}%");
            });
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            $classes = $query->with('questions.options')->orderBy('day')->get();
            return response()->json([
                'status' => 'success',
                'data' => $classes
            ]);
        }

        $classes = $query->orderBy('day')->paginate(10);
        return view('placement-classes.index', compact('classes'));
    }

    public function create()
    {
        return view('placement-classes.create');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'day' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'video' => 'required|file|mimes:mp4,mov,avi,wmv,webm,mkv|max:51200',
        ]);

        PlacementClass::create([
            'day' => $validated['day'],
            'title' => $validated['title'],
            'video_path' => $this->storeVideo($request->file('video')),
        ]);

        return $this->savedResponse($request, 'Placement Class created successfully.');
    }

    public function show(PlacementClass $placementClass)
    {
        return view('placement-classes.show', compact('placementClass'));
    }

    public function edit(PlacementClass $placementClass)
    {
        return view('placement-classes.edit', compact('placementClass'));
    }

    public function update(Request $request, PlacementClass $placementClass): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'day' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,webm,mkv|max:51200',
        ]);

        $data = [
            'day' => $validated['day'],
            'title' => $validated['title'],
        ];

        if ($request->hasFile('video')) {
            $this->deleteVideo($placementClass->video_path);
            $data['video_path'] = $this->storeVideo($request->file('video'));
            $data['video_url'] = null;
        }

        $placementClass->update($data);

        return $this->savedResponse($request, 'Placement Class updated successfully.');
    }

    public function destroy(PlacementClass $placementClass): RedirectResponse
    {
        $this->deleteVideo($placementClass->video_path);
        $placementClass->delete();

        return redirect()->route('placement-classes.index')
            ->with('success', 'Placement Class deleted successfully');
    }

    /**
     * Redirect to the list with a success flash; AJAX callers get the redirect URL as JSON.
     */
    private function savedResponse(Request $request, string $message): RedirectResponse|JsonResponse
    {
        session()->flash('success', $message);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'redirect' => route('placement-classes.index'),
            ]);
        }

        return redirect()->route('placement-classes.index');
    }

    /**
     * Move the uploaded video into public/videos/placement_classes and return its relative path.
     */
    private function storeVideo(UploadedFile $video): string
    {
        $videoName = time().'_'.uniqid().'.'.$video->getClientOriginalExtension();
        $video->move(public_path('videos/placement_classes'), $videoName);

        return 'videos/placement_classes/'.$videoName;
    }

    private function deleteVideo(?string $videoPath): void
    {
        if ($videoPath && File::exists(public_path($videoPath))) {
            File::delete(public_path($videoPath));
        }
    }
}
