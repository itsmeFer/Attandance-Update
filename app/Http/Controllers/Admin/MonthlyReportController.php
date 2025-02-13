<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MonthlyAttendanceExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\PDF;
use App\Exports\MonthlyReportExport;

class MonthlyReportController extends Controller
{

    public function index()
    {
        // Ambil daftar tahun unik dari tabel attendance
        $years = Attendance::selectRaw('YEAR(check_in) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');
    
        $attendances = Attendance::with('user')->latest()->get();
    
        return view('admin.monthly-report', compact('attendances', 'years'));
    }
    

    public function exportPDF(Request $request)
    {
        $selectedYear = $request->input('year', now()->year);
        $employeeName = $request->input('employee_name');
        $status = $request->input('status');
    
        $attendances = Attendance::with('user')
            ->whereYear('check_in', $selectedYear)
            ->when($employeeName, function ($query) use ($employeeName) {
                $query->whereHas('user', function ($q) use ($employeeName) {
                    $q->where('name', 'like', '%' . $employeeName . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'hadir') {
                    $query->whereNotNull('check_in');
                } elseif ($status === 'tidak_hadir') {
                    $query->whereNull('check_in');
                }
            })
            ->get();
    
        $pdf = Pdf::loadView('reports.monthly_pdf', compact('attendances'));
        return $pdf->download('Laporan_Bulanan_Absensi.pdf');
    }
    

    public function exportExcel(Request $request)
{
    return Excel::download(new MonthlyAttendanceExport($request), 'Laporan_Bulanan_Absensi.xlsx');
}

}
