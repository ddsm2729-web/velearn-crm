<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display the Certificate Dispatch page.
     */
    public function index()
    {
        // Mock data to match the screenshot perfectly
        $certificates = [
            [
                'name' => 'Anitha Murugan',
                'course' => 'Graphic Design — Advanced',
                'date' => 'Mar 30, 2026',
                'grade' => 'Grade A',
                'status' => 'READY',
            ],
            [
                'name' => 'Suresh Babu',
                'course' => 'Mechanical Engineering Fundamentals',
                'date' => 'Apr 02, 2026',
                'grade' => 'Grade A+',
                'status' => 'READY',
            ],
            [
                'name' => 'Ravi Shankar',
                'course' => 'AutoCAD 2D & 3D',
                'date' => 'Apr 10, 2026',
                'grade' => 'Grade A',
                'status' => 'ISSUED',
            ],
            [
                'name' => 'Lakshmi Priya',
                'course' => 'Civil Engineering Drafting',
                'date' => 'Apr 15, 2026',
                'grade' => 'Grade B+',
                'status' => 'READY',
            ],
            [
                'name' => 'Karthik Raja',
                'course' => 'AutoCAD Professional',
                'date' => 'Apr 20, 2026',
                'grade' => 'Grade B',
                'status' => 'PENDING',
            ],
        ];

        $courses = \App\Models\Course::where('status', 1)->get(['id', 'title']);

        return view('certificates.index', compact('certificates', 'courses'));
    }
}
