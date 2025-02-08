<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Bisa mengembalikan view dashboard untuk admin
        return view('admin.dashboard');  // Pastikan ada view 'admin.dashboard'
    }
}
