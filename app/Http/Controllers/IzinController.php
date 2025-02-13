<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Izin;

class IzinController extends Controller
{
    public function index()
    {
        $izin = Izin::all(); // Pastikan mengambil semua izin
        return view('admin.izin', compact('izin'));
        
    }
    public function izin()
{
    $izin = Izin::with('user')->latest()->get(); // Pastikan model 'Izin' benar
    return view('karyawan.izin', compact('izin'));
}


public function create()
{
    $izin = Izin::with('user')->latest()->get(); // Ambil data izin
    return view('karyawan.izin', compact('izin'));
}



    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'alasan' => 'required',
            'dokumen' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'izin_dari' => 'required|date',
            'izin_sampai' => 'required|date|after_or_equal:izin_dari',
        ]);

        // Simpan file dokumen
        $path = $request->file('dokumen')->store('dokumen_izin', 'public');

        // Simpan ke database
        Izin::create([
            'user_id' => auth()->id(),
            'alasan' => $request->alasan,
            'dokumen' => $path,
            'izin_dari' => $request->izin_dari,
            'izin_sampai' => $request->izin_sampai,
        ]);

        return redirect()->route('izin.create')->with('success', 'Izin berhasil diajukan!');
    }
}
