<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Admin\Dashboard;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProfileController;

// Route utama
Route::get('/', function () {
    return view('welcome');
});

// Middleware autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->role == 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('karyawan.dashboard');
    })->name('dashboard');
});
use App\Http\Controllers\Admin\MonthlyReportController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/monthly-report', [MonthlyReportController::class, 'index'])->name('admin.monthly-report');
});

// Route untuk Admin dengan Livewire
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/admin/monthly-report', [AttendanceController::class, 'monthlyReport'])->name('admin.monthly-report'); // Pastikan ini benar
});


// Route untuk Absensi
Route::middleware(['auth'])->group(function () {
    Route::get('/absen', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/absen', [AttendanceController::class, 'store'])->name('attendance.store');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/absen', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/absen', [AttendanceController::class, 'store'])->name('attendance.store');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/absen', [AttendanceController::class, 'show'])->name('absen.show');
    Route::post('/absen', [AttendanceController::class, 'store'])->name('absen.store'); // Pastikan ini ada
});

Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/karyawan/absen', [AttendanceController::class, 'show'])->name('karyawan.absen');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/monthly-report', [MonthlyReportController::class, 'index'])->name('monthly.report');
});Route::get('/admin/monthly-report/pdf', [MonthlyReportController::class, 'exportPdf'])->name('admin.monthly-report.pdf');

Route::get('admin/monthly-report/export/pdf', [MonthlyReportController::class, 'exportPDF'])->name('admin.monthly-report.pdf');
Route::get('admin/monthly-report/export/excel', [MonthlyReportController::class, 'exportExcel'])->name('admin.monthly-report.excel');
use App\Exports\MonthlyAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/admin/monthly-report/excel', function () {
    $month = now()->format('m'); // atau bisa pakai request parameter
    return Excel::download(new MonthlyAttendanceExport($month), 'Laporan_Bulanan.xlsx');
})->name('admin.monthly-report.excel');
// Route untuk Karyawan
Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/karyawan/dashboard', [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard');
});

// Route untuk Profile User
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/admin/monthly-report', [AttendanceController::class, 'monthlyReport'])->name('admin.monthly.report');
// Route logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Include route authentication dari Laravel Breeze
require __DIR__.'/auth.php';
