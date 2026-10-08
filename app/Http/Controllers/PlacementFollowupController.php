<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlacementFollowupController extends Controller
{
    public function index()
    {
        return view('placement-followups.index');
    }
}
