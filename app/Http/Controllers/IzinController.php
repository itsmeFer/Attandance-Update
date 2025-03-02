<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Izin;

class IzinController extends Controller
{
    public function update(Request $request, $id)
    {
        $izin = Izin::findOrFail($id);
    
        if ($request->status == 'disetujui') {
            $izin->update([
                'status' => 'disetujui',
                'disetujui_oleh_id' => auth()->id(),
                'disetujui_oleh' => auth()->user()->name,
            ]);
        } elseif ($request->status == 'ditolak') {
            $izin->update([
                'status' => 'ditolak',
                'disetujui_oleh_id' => auth()->id(),
                'disetujui_oleh' => auth()->user()->name,
            ]);
        } elseif ($request->status == 'draft') {
            $izin->update([
                'status' => 'draft',
                'disetujui_oleh_id' => null,
                'disetujui_oleh' => null,
            ]);
        }
    
        return redirect()->route('admin.izin.index')->with('success', 'Status izin berhasil diperbarui.');
    }
    

    

public function approve($id)
{
    $izin = Izin::findOrFail($id);

    $izin->disetujui_oleh_id = auth()->user()->id;
    $izin->disetujui_oleh = auth()->user()->name;
    $izin->save();

    return back()->with('success', 'Izin disetujui.');
}

public function reject($id)
{
    $izin = Izin::findOrFail($id);

    $izin->disetujui_oleh_id = null;
    $izin->disetujui_oleh = null;
    $izin->save();

    return back()->with('error', 'Izin ditolak.');
}


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
