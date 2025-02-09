<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{   

    public function index()
{
    $totalKaryawan = \App\Models\User::count(); // Total karyawan
    $hadirHariIni = Attendance::whereDate('check_in', now()->toDateString())->count(); // Karyawan yang sudah absen
    $belumAbsen = $totalKaryawan - $hadirHariIni; // Karyawan yang belum absen
    $attendances = Attendance::with('user')->latest()->get(); // Data absensi

    return view('livewire.admin.dashboard', compact('totalKaryawan', 'hadirHariIni', 'belumAbsen', 'attendances'));
}



    public function show()
{
    $user = Auth::user();
    $today = now()->toDateString();

    $attendance = Attendance::where('user_id', $user->id)
        ->whereDate('check_in', $today)
        ->first();

    return view('karyawan.absen', compact('attendance'));
}

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', $today)
            ->first();

        if (!$attendance) {
            $path = $request->file('photo')->store('absensi', 'public');
            Attendance::create([
                'user_id' => $user->id,
                'check_in' => now(),
                'check_in_location' => $request->location,
                'check_in_photo' => $path,
            ]);
            return back()->with('success', 'Berhasil absen masuk!');
        } elseif (!$attendance->check_out) {
            $path = $request->file('photo')->store('absensi', 'public');
            $attendance->update([
                'check_out' => now(),
                'check_out_location' => $request->location,
                'check_out_photo' => $path,
            ]);
            return back()->with('success', 'Berhasil absen keluar!');
        }

        return back()->with('error', 'Anda sudah absen hari ini.');
    }
}
