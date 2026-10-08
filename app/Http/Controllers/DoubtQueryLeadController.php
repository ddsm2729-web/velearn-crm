<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoubtQueryLeadController extends Controller
{
    public function index()
    {
        return view('doubt-query-leads.index');
    }
}
