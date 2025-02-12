<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{  
    public function index()
    {
        $totalKaryawan = \App\Models\User::count();
        $hadirHariIni = Attendance::whereDate('check_in', now()->toDateString())->count();
        $belumAbsen = $totalKaryawan - $hadirHariIni;
        $attendances = Attendance::with('user')->latest()->get();
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

    public function monthlyReport(Request $request)
    {
        // Ambil daftar tahun dari tabel attendance berdasarkan check_in
        $years = Attendance::whereNotNull('check_in')
            ->selectRaw('YEAR(check_in) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');
    
        // Ambil tahun yang dipilih dari request atau gunakan tahun saat ini
        $selectedYear = $request->input('year', now()->year);
    
        // Ambil data absensi berdasarkan tahun yang dipilih
        $attendances = Attendance::whereYear('check_in', $selectedYear)->get();
    
        // Kirim data ke view
        return view('admin.monthly-report', compact('years', 'selectedYear', 'attendances'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string',
            'photo' => 'required|string', // Ubah validasi untuk menerima base64
        ]);

        // Proses decode base64 image
        $image_parts = explode(";base64,", $request->photo);
        if (count($image_parts) < 2) {
            return back()->with('error', 'Format foto tidak valid');
        }

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'absensi_' . time() . '_' . Str::random(10) . '.jpg';
        $path = 'absensi/' . $fileName;

        // Simpan file ke storage
        Storage::disk('public')->put($path, $image_base64);

        $user = Auth::user();
        $today = now()->toDateString();
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', $today)
            ->first();

        if (!$attendance) {
            // Absen masuk
            Attendance::create([
                'user_id' => $user->id,
                'check_in' => now(),
                'check_in_location' => $request->location,
                'check_in_photo' => $path,
            ]);
            return back()->with('success', 'Berhasil absen masuk!');
        } elseif (!$attendance->check_out) {
            // Absen keluar
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