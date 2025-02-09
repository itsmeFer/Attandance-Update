<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;

class Dashboard extends Component
{
    public $totalKaryawan, $hadirHariIni, $belumAbsen, $lateEmployees;
    public function mount()
    {
        $this->totalKaryawan = User::where('role', 'karyawan')->count();
        $this->hadirHariIni = Attendance::whereDate('check_in', today())->count();
        $this->belumAbsen = $this->totalKaryawan - $this->hadirHariIni;
    
        $jamTerlambat = '09:00:00'; // Misalnya terlambat jika absen setelah 09:00
        $this->lateEmployees = Attendance::whereDate('check_in', today())
            ->whereTime('check_in', '>', $jamTerlambat)
            ->count();
    }
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'attendances' => Attendance::with('user')->latest()->get(),
        ]);
    }
}
