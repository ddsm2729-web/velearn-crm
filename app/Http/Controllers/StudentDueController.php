<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentDueController extends Controller
{
    public function index()
    {
        return view('student-dues.index');
    }
}
