<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function dashboard()
    {
        // Your logic for the dashboard goes here
        return view('karyawan.dashboard'); // Return the appropriate view for the dashboard
    }
}
