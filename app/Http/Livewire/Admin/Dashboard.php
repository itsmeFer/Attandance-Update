<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Attendance;

class Dashboard extends Component
{
    public $attendances;

    public function mount()
    {
        $this->attendances = Attendance::with('user')->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.app'); // Gunakan layout dari Jetstream
    }
}
