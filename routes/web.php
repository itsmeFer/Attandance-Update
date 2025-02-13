<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Admin\Dashboard;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MonthlyReportController;
use App\Http\Controllers\IzinController;
use App\Exports\MonthlyAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

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

// Route untuk Admin dengan Livewire
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Laporan Bulanan
    Route::get('/monthly-report', [MonthlyReportController::class, 'index'])->name('monthly-report');
    Route::get('/monthly-report/pdf', [MonthlyReportController::class, 'exportPdf'])->name('monthly-report.pdf');
    Route::get('/monthly-report/excel', function () {
        $month = now()->format('m'); 
        return Excel::download(new MonthlyAttendanceExport($month), 'Laporan_Bulanan.xlsx');
    })->name('monthly-report.excel');

    // Rute Izin Admin
    Route::get('/izin', [IzinController::class, 'index'])->name('izin.index');
});

// Route untuk Absensi
Route::middleware(['auth'])->group(function () {
    Route::get('/absen', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/absen', [AttendanceController::class, 'store'])->name('attendance.store');
});

// Route untuk Karyawan
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/dashboard', [KaryawanController::class, 'dashboard'])->name('dashboard');
    Route::get('/absen', [AttendanceController::class, 'show'])->name('absen');

    // Rute Izin Karyawan
    Route::get('/izin', [IzinController::class, 'index'])->name('izin.index');
    Route::get('/izin/create', [IzinController::class, 'create'])->name('izin.create');
    Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');
});
Route::post('/absen', [AttendanceController::class, 'store'])->name('absen.store');


// Route untuk Profile User
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/karyawan/izin', [IzinController::class, 'izin'])->name('karyawan.izin');
Route::get('/karyawan/izin', [IzinController::class, 'izin'])->name('karyawan.izin');
Route::get('/karyawan/izin/create', [IzinController::class, 'create'])->name('izin.create');
Route::get('/karyawan/izin', [IzinController::class, 'izin'])->name('karyawan.izin');
Route::post('/karyawan/izin/store', [IzinController::class, 'store'])->name('izin.store');


Route::middleware(['auth'])->group(function () {
    Route::get('/karyawan/izin', [IzinController::class, 'create'])->name('karyawan.izin.create');
    Route::post('/karyawan/izin', [IzinController::class, 'store'])->name('izin.store');
});

// Route logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Include route authentication dari Laravel Breeze
require __DIR__.'/auth.php';
