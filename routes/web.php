<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Auth;

// Route utama
Route::get('/', function () {
    return view('welcome');
});

// Route untuk dashboard, menyesuaikan role user
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('karyawan.dashboard');
    })->name('dashboard');
});

// Route untuk admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Route untuk karyawan (Tanpa Livewire)
Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/karyawan/absen', [AttendanceController::class, 'show'])->name('absen.show');
    Route::post('/karyawan/absen', [AttendanceController::class, 'store'])->name('absen.store');
});


// Route untuk profile user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); // Redirect langsung ke halaman login
})->name('logout');

// Include route authentication dari Laravel Breeze
require __DIR__.'/auth.php';
