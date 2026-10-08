<?php

namespace App\Http\Controllers;

use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\WebinarTitle;
use App\Models\WebinarDescription;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class WebinarController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Webinar List', only: ['index']),
            new Middleware('permission:Webinar Create', only: ['create', 'store']),
            new Middleware('permission:Webinar Edit', only: ['edit', 'update']),
            new Middleware('permission:Webinar Delete', only: ['destroy']),
            new Middleware('permission:Webinar Status', only: ['updateWebinarStatus']),
        ];
    }

    /**
     * Display a listing of webinars.
     */
    public function index(Request $request)
    {
        $webinars = Webinar::query();

        $search = $request->search ?? null;
        $status = $request->status ?? null;

        if (!empty($search)) {
            $webinars->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%")
                  ->orWhere('course_level', 'LIKE', "%{$search}%")
                  ->orWhere('professor', 'LIKE', "%{$search}%");
            });
        }

        if ($status !== null) {
            $webinars->where('status', $status);
        }

        $webinars = $webinars->latest()->paginate(10)->withQueryString();

        return view('webinars.index', compact('webinars'));
    }

    /**
     * Show the form for creating a new webinar.
     */
    public function create()
    {
        return view('webinars.create');
    }

    /**
     * Store a newly created webinar.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'                 => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'title'                 => 'required|string|max:255',
            'category'              => 'required',
            'course_level'          => 'required',
            'date'                  => 'required',
            'from_time'             => 'required',
            'to_time'               => 'required',
            'seats'                 => 'required|integer|min:1',
            'description'           => 'required',
            'professor'             => 'required',
            'professor_position'    => 'required',
            'professor_experience'  => 'required',
            'meeting_link'          => 'nullable',
            // 🔥 Relationship validation
            'sections'                      => 'required|array|min:1',
            'sections.*.title'              => 'required|string',
            'sections.*.descriptions'       => 'required|array|min:1',
            'sections.*.descriptions.*'     => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            /* ================= Image Upload ================= */
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/webinars'), $imageName);

            /* ================= Date Format ================= */
            $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');

            /* ================= Zoom Meeting Integration ================= */
            $zoomLink = $request->meeting_link;
            $zoomMeetingId = null;

            try {
                $zoomService = new \App\Services\ZoomService();
                $startTimeString = $date . 'T' . $request->from_time . ':00';
                
                $fromTime = Carbon::parse($request->from_time);
                $toTime = Carbon::parse($request->to_time);
                $duration = $toTime->diffInMinutes($fromTime) ?: 60;

                $zoomMeeting = $zoomService->createMeeting(ucwords($request->title), $startTimeString, $duration);
                if ($zoomMeeting && isset($zoomMeeting['join_url'])) {
                    $zoomLink = $zoomMeeting['join_url'];
                    $zoomMeetingId = $zoomMeeting['id'] ?? null;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Zoom meeting creation failed for Webinar: ' . $e->getMessage());
            }

            /* ================= Create Webinar ================= */
                $webinar = Webinar::create([
                'image'                 => $imageName,
                'title'                 => ucwords($request->title),
                'category'              => $request->category,
                'course_level'          => $request->course_level,
                'date'                  => $date,
                'from_time'             => $request->from_time,
                'to_time'               => $request->to_time,
                'meeting_link'          => $zoomLink,
                'zoom_meeting_id'       => $zoomMeetingId,
                'seats'                 => $request->seats,
                'description'           => $request->description,
                'professor'             => ucwords($request->professor),
                'professor_position'    => ucwords($request->professor_position),
                'professor_experience'  => $request->professor_experience,
                'status'                => 1,
            ]);

            /* ================= Titles & Descriptions ================= */
            foreach ($request->sections as $section) {

                // Create Title
                $title = $webinar->titles()->create([
                    'title' => $section['title'],
                ]);

                // Create Multiple Descriptions
                foreach ($section['descriptions'] as $desc) {
                    $title->descriptions()->create([
                        'webinar_id' => $webinar->id,
                        'description' => $desc,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Webinar created successfully'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Show the form for editing the webinar.
     */
    public function edit(string $id)
    {
        $webinar = Webinar::findOrFail(decrypt($id));
        return view('webinars.edit', compact('webinar'));
    }

    /**
     * Update the webinar.
     */
    public function update(Request $request, string $id)
    {
        $webinar = Webinar::with('titles.descriptions')
            ->findOrFail(decrypt($id));

        /* ================= VALIDATION ================= */
        $validator = Validator::make($request->all(), [
            'image'                 => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'title'                 => 'required|string|max:255',
            'category'              => 'required|string',
            'course_level'          => 'required|string',
            'date'                  => 'required|date',
            'from_time'             => 'required',
            'to_time'               => 'required',
            'seats'                 => 'required|integer|min:1',
            'description'           => 'required|string',
            'professor'             => 'required|string',
            'professor_position'    => 'required|string',
            'professor_experience'  => 'required|string',
            'meeting_link'          => 'nullable',

            // 🔥 Relationship validation
            'sections'                              => 'required|array|min:1',
            'sections.*.title'                      => 'required|string',
            'sections.*.descriptions'               => 'required|array|min:1',
            'sections.*.descriptions.*.description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            /* ================= IMAGE UPDATE ================= */
            if ($request->hasFile('image')) {

                if ($webinar->image && file_exists(public_path('uploads/webinars/' . $webinar->image))) {
                    unlink(public_path('uploads/webinars/' . $webinar->image));
                }

                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/webinars'), $imageName);

                $webinar->image = $imageName;
            }

            /* ================= Zoom Meeting Update ================= */
            $zoomLink = $request->meeting_link;
            $zoomMeetingId = $webinar->zoom_meeting_id;
            $formattedDate = Carbon::parse($request->date)->format('Y-m-d');

            try {
                $zoomService = new \App\Services\ZoomService();
                $startTimeString = $formattedDate . 'T' . $request->from_time . ':00';
                
                $fromTime = Carbon::parse($request->from_time);
                $toTime = Carbon::parse($request->to_time);
                $duration = $toTime->diffInMinutes($fromTime) ?: 60;

                if ($zoomMeetingId && !empty($webinar->meeting_link)) {
                    $zoomService->updateMeeting($zoomMeetingId, ucwords($request->title), $startTimeString, $duration);
                    // Keep existing link
                    $zoomLink = $webinar->meeting_link;
                } else {
                    $zoomMeeting = $zoomService->createMeeting(ucwords($request->title), $startTimeString, $duration);
                    if ($zoomMeeting && isset($zoomMeeting['join_url'])) {
                        $zoomLink = $zoomMeeting['join_url'];
                        $zoomMeetingId = $zoomMeeting['id'] ?? null;
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Zoom meeting update/creation failed for Webinar: ' . $e->getMessage());
            }

            /* ================= WEBINAR UPDATE ================= */
            $webinar->update([
                'title'                 => ucwords($request->title),
                'category'              => $request->category,
                'course_level'          => $request->course_level,
                'date'                  => $formattedDate,
                'from_time'             => $request->from_time,
                'to_time'               => $request->to_time,
                'meeting_link'          => $zoomLink,
                'zoom_meeting_id'       => $zoomMeetingId,
                'seats'                 => $request->seats,
                'description'           => $request->description,
                'professor'             => ucwords($request->professor),
                'professor_position'    => ucwords($request->professor_position),
                'professor_experience'  => $request->professor_experience,
            ]);

            /* ================= TITLES & DESCRIPTIONS SYNC ================= */

            $submittedTitleIds = [];

            foreach ($request->sections as $section) {

                /** ---------- TITLE ---------- */
                if (!empty($section['id'])) {
                    $title = WebinarTitle::where('webinar_id', $webinar->id)
                        ->findOrFail($section['id']);

                    $title->update([
                        'title' => $section['title']
                    ]);
                } else {
                    $title = $webinar->titles()->create([
                        'title' => $section['title']
                    ]);
                }

                $submittedTitleIds[] = $title->id;

                /** ---------- DESCRIPTIONS ---------- */
                $submittedDescIds = [];

                foreach ($section['descriptions'] as $desc) {

                    if (!empty($desc['id'])) {
                        $description = WebinarDescription::where('webinar_title_id', $title->id)
                            ->findOrFail($desc['id']);

                        $description->update([
                            'description' => $desc['description']
                        ]);

                        $submittedDescIds[] = $description->id;
                    } else {
                        $newDesc = $title->descriptions()->create([
                            'webinar_id' => $webinar->id,
                            'description' => $desc['description']
                        ]);

                        $submittedDescIds[] = $newDesc->id;
                    }
                }

                // ❌ Delete removed descriptions
                $title->descriptions()
                    ->whereNotIn('id', $submittedDescIds)
                    ->delete();
            }

            // ❌ Delete removed titles (cascade deletes descriptions)
            $webinar->titles()
                ->whereNotIn('id', $submittedTitleIds)
                ->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Webinar has been updated successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error($e);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Delete the webinar.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $webinar = Webinar::findOrFail(decrypt($id));

            if ($webinar->image && file_exists(public_path('uploads/webinars/' . $webinar->image))) {
                unlink(public_path('uploads/webinars/' . $webinar->image));
            }

            if ($webinar->zoom_meeting_id) {
                try {
                    $zoomService = new \App\Services\ZoomService();
                    $zoomService->deleteMeeting($webinar->zoom_meeting_id);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Zoom meeting deletion failed for Webinar: ' . $e->getMessage());
                }
            }

            $webinar->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Webinar has been deleted successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update webinar status (Active / Inactive)
     */
    public function updateWebinarStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $webinarId = decrypt($request->webinarId);
            $status = $request->status;

            $webinar = Webinar::findOrFail($webinarId);
            $webinar->update(['status' => $status]);

            DB::commit();

            return [
                'status' => true,
                'message' => 'Webinar status has been changed successfully.'
            ];

        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

     /**
     * ✅ Get All Webinars
     * API: GET /api/webinars
     */
    public function webinarInfo(Request $request)
    {
        $now = Carbon::now();
        $category = $request->category ?? null;
    
        $webinars = Webinar::select(
                'id',
                'title',
                'category',
                'course_level',
                'date',
                'from_time',
                'to_time',
                'meeting_link',
                'seats',
                'image',
                'status'
            )
            ->where('status', 1);

        if (!empty($category)) {
            $webinars->where('category', $category);
        }

        $webinars = $webinars->where(function ($q) use ($now) {
    
                // 🔴 LIVE webinars (currently running)
                $q->where(function ($live) use ($now) {
                    $live->whereDate('date', $now->toDateString())
                         ->whereTime('from_time', '<=', $now->toTimeString())
                         ->whereTime('to_time', '>=', $now->toTimeString());
                })
    
                // 🟢 OR UPCOMING webinars
                ->orWhere(function ($upcoming) use ($now) {
                    $upcoming->whereDate('date', '>', $now->toDateString())
                             ->orWhere(function ($todayUpcoming) use ($now) {
                                 $todayUpcoming->whereDate('date', $now->toDateString())
                                                ->whereTime('from_time', '>', $now->toTimeString());
                             });
                });
            })
            ->orderBy('date')
            ->orderBy('from_time')
            ->get();
    
        Log::info('Live & Upcoming webinars fetched', [
            'count' => $webinars->count()
        ]);
    
        return response()->json([
            'status'  => true,
            'message' => 'Live & Upcoming webinars fetched successfully',
            'data'    => $webinars
        ], 200);
    }

    /**
     * ✅ Get Single Webinar Detail
     * API: GET /api/webinars/{id}
     */
    public function webinarDetail($id)
    {
        try {
            $webinar = Webinar::with([
                'titles.descriptions'
            ])->find($id);
    
            if (!$webinar) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Webinar not found'
                ], 404);
            }
    
            // 🔹 Format date & time
            $dateFormatted = Carbon::parse($webinar->date)->format('d M Y'); // 02 Feb 2026
            
            // From time (15:00:00 → 03:00 PM)
            $fromTime = Carbon::createFromFormat('H:i:s', $webinar->from_time)
                ->format('h:i A');
            
            // To time
            $toTime = Carbon::createFromFormat('H:i:s', $webinar->to_time)
                ->format('h:i A');
    
            Log::info('Webinar details fetched', [
                'webinar_id' => $id
            ]);
    
            return response()->json([
                'status'  => true,
                'message' => 'Webinar details fetched successfully',
                'data'    => [
                    'id'                   => $webinar->id,
                    'title'                => $webinar->title,
                    'category'             => $webinar->category,
                    'course_level'         => $webinar->course_level,
    
                    // ✅ formatted date & time
                    'date'                 => $dateFormatted,
                    'from_time'            => $fromTime,
                    'to_time'              => $toTime,
    
                    'seats'                => $webinar->seats,
                    'description'          => $webinar->description,
                    'meeting_link'         => $webinar->meeting_link,
                    'professor'            => $webinar->professor,
                    'professor_position'   => $webinar->professor_position,
                    'professor_experience' => $webinar->professor_experience,
                    'requirements'         => $webinar->requirements,
    
                    'image_url' => $webinar->image
                        ? asset('uploads/webinars/' . $webinar->image)
                        : null,
    
                    // 🔹 Titles with descriptions
                    'titles' => $webinar->titles->map(function ($title) {
                        return [
                            'id'    => $title->id,
                            'title' => $title->title,
                            'descriptions' => $title->descriptions->map(function ($desc) {
                                return [
                                    'id'          => $desc->id,
                                    'description' => $desc->description
                                ];
                            })
                        ];
                    })
                ]
            ], 200);
    
        } catch (\Throwable $e) {
    
            Log::error('Webinar detail API error', [
                'webinar_id' => $id,
                'error'      => $e->getMessage()
            ]);
    
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while fetching webinar details'
            ], 500);
        }
    }

    /**
     * 🔴 Live Webinars
     */
    public function live()
    {
        $now = Carbon::now();

        $webinars = Webinar::select(
                'id',
                'title',
                'course_level',
                'date',
                'from_time',
                'to_time',
                'seats',
                'image',
                'status'
            )
            ->where('status', 1)
            ->whereDate('date', $now->toDateString())
            ->whereTime('from_time', '<=', $now->toTimeString())
            ->whereTime('to_time', '>=', $now->toTimeString())
            ->orderBy('from_time')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Live webinars fetched successfully',
            'data'    => $webinars
        ], 200);
    }

    /**
     * 🟢 Upcoming Webinars
     */
    public function upcoming()
    {
        $now = Carbon::now();

        $webinars = Webinar::select(
                'id',
                'title',
                'course_level',
                'date',
                'from_time',
                'to_time',
                'seats',
                'image',
                'status'
            )
            ->where('status', 1)
            ->where(function ($q) use ($now) {
                $q->whereDate('date', '>', $now->toDateString())
                  ->orWhere(function ($q) use ($now) {
                      $q->whereDate('date', $now->toDateString())
                        ->whereTime('from_time', '>', $now->toTimeString());
                  });
            })
            ->orderBy('date')
            ->orderBy('from_time')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Upcoming webinars fetched successfully',
            'data'    => $webinars
        ], 200);
    }

    /**
     * ⚪ Past Webinars
     */
    public function past()
    {
        $now = Carbon::now();

        $webinars = Webinar::select(
                'id',
                'title',
                'course_level',
                'date',
                'from_time',
                'to_time',
                'seats',
                'image',
                'status'
            )
            ->where('status', 1)
            ->where(function ($q) use ($now) {
                $q->whereDate('date', '<', $now->toDateString())
                  ->orWhere(function ($q) use ($now) {
                      $q->whereDate('date', $now->toDateString())
                        ->whereTime('to_time', '<', $now->toTimeString());
                  });
            })
            ->orderByDesc('date')
            ->orderByDesc('from_time')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Past webinars fetched successfully',
            'data'    => $webinars
        ], 200);
    }
}