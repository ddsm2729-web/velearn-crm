<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoubtQueryFollowupController extends Controller
{
    public function index()
    {
        return view('doubt-query-followups.index');
    }
}
