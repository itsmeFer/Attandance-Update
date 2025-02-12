<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function monthlyReport()
    {
        return view('admin.monthly-report');
    }
}
