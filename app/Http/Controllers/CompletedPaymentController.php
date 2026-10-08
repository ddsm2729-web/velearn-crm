<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompletedPaymentController extends Controller
{
    public function index()
    {
        return view('completed-payments.index');
    }
}
