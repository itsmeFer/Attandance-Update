<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;

class Dashboard extends Component
{
    public $totalKaryawan;
    public $hadirHariIni;
    public $belumAbsen;
    public $attendances;

    public function mount()
    {
        $this->totalKaryawan = User::where('role', 'karyawan')->count();
        $this->hadirHariIni = Attendance::whereDate('check_in', today())->count();
        $this->belumAbsen = $this->totalKaryawan - $this->hadirHariIni;
        $this->attendances = Attendance::with('user')->latest()->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
