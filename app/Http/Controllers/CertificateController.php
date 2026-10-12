<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Course;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display the Certificate Dispatch page.
     */
    public function index(Request $request)
    {
        $courseId = $request->get('course_id');

        $query = Admission::with(['contact', 'course'])
            ->whereHas('contact')
            ->whereHas('course');

        if ($courseId && $courseId !== 'all') {
            $query->where('course_id', $courseId);
        }

        $admissions = $query->latest()->get();

        // Map admission status to certificate dispatch status
        $certificates = $admissions->map(function ($admission) {
            $statusMap = [
                'completed'   => 'ISSUED',
                'issued'      => 'ISSUED',
                'active'      => 'READY',
                'ongoing'     => 'READY',
                'pending_fee' => 'PENDING',
                'pending'     => 'PENDING',
            ];
            $certStatus = $statusMap[strtolower($admission->status)] ?? 'PENDING';

            return [
                'id'     => $admission->id,
                'name'   => $admission->contact->name ?? '—',
                'course' => $admission->course->title ?? '—',
                'date'   => $admission->created_at->format('M d, Y'),
                'grade'  => '—',
                'status' => $certStatus,
            ];
        });

        $courses = Course::where('status', 1)->get(['id', 'title']);

        return view('certificates.index', compact('certificates', 'courses', 'courseId'));
    }
}
