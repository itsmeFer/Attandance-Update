<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $today = Carbon::today();
        
        $totalKaryawan = User::where('role', 'karyawan')->count();
        $hadirHariIni = Attendance::whereDate('check_in', $today)->count();
        $belumAbsen = $totalKaryawan - $hadirHariIni;
        $lateEmployees = Attendance::whereDate('check_in', $today)
            ->whereTime('check_in', '>', '08:30:00')
            ->count();

        $attendances = Attendance::with('user')
            ->whereDate('check_in', $today)
            ->get();

        return view('livewire.admin.dashboard', [
            'totalKaryawan' => $totalKaryawan,
            'hadirHariIni' => $hadirHariIni,
            'belumAbsen' => $belumAbsen,
            'lateEmployees' => $lateEmployees,
            'attendances' => $attendances
        ]);
    }
}