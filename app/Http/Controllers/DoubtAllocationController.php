<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoubtAllocationController extends Controller
{
    public function index()
    {
        return view('doubt-allocation.index');
    }
}
