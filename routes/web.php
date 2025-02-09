<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Admin\Dashboard;
use App\Http\Controllers\AttendanceController;

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
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
});

// Route untuk Karyawan
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
    return redirect('/login');
})->name('logout');

// Include route authentication dari Laravel Breeze
require __DIR__.'/auth.php';
