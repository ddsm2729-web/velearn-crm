<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyDatabaseController extends Controller
{
    public function index()
    {
        return view('company-database.index');
    }
}
