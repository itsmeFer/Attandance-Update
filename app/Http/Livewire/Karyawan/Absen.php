<?php

namespace App\Http\Livewire\Karyawan;

use Livewire\Component;
use Carbon\Carbon;

class Absen extends Component
{
    public $currentTime;

    public function mount()
    {
        $this->currentTime = Carbon::now()->format('H:i');
    }

    public function render()
    {
        return view('livewire.karyawan.absen', [
            'currentTime' => $this->currentTime,
        ]);
    }
}
