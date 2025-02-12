<?php

namespace App\Http\Controllers\Admin;

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
        $attendances = Attendance::with('user')->latest()->get();
        return view('admin.monthly-report', compact('attendances'));
    }

    public function exportPDF()
    {
        $attendances = Attendance::with('user')->whereMonth('check_in', now()->month)->get();
        
        $pdf = Pdf::loadView('reports.monthly_pdf', compact('attendances'));

        return $pdf->download('Laporan_Bulanan_Absensi.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new MonthlyAttendanceExport, 'Laporan_Bulanan_Absensi.xlsx');
    }
}
