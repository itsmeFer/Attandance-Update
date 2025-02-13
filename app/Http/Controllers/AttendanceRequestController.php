<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'absent_date' => 'required|date',
            'reason' => 'required|string',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('supporting_document')) {
            $path = $request->file('supporting_document')->store('supporting_documents', 'public');
        }

        AttendanceRequest::create([
            'user_id' => Auth::id(),
            'absent_date' => $request->absent_date,
            'reason' => $request->reason,
            'supporting_document' => $path,
        ]);

        return back()->with('success', 'Pengajuan izin absen berhasil dikirim.');
    }

    public function index()
    {
        $requests = AttendanceRequest::where('status', 'pending')->with('user')->get();
        return view('admin.attendance-requests', compact('requests'));
    }

    public function update(Request $request, AttendanceRequest $attendanceRequest)
    {
        $attendanceRequest->update(['status' => $request->status]);

        if ($request->status == 'approved') {
            Attendance::create([
                'user_id' => $attendanceRequest->user_id,
                'check_in' => $attendanceRequest->absent_date . ' 09:00:00', // Simulasi absen masuk
                'check_out' => $attendanceRequest->absent_date . ' 17:00:00', // Simulasi absen keluar
            ]);
        }

        return back()->with('success', 'Status izin absen diperbarui.');
    }
}
