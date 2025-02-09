<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Attendance;

class Dashboard extends Component
{
    public $attendances;

    public function mount()
    {
        // Ambil data absensi untuk admin
        $this->attendances = Attendance::with('user')->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
