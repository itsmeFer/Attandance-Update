<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaryawanController;

Route::get('/', function () {
    return view('welcome');
});

// Rute login dan logout sudah ada dari Breeze

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('karyawan.dashboard');
    })->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    Route::middleware(['role:karyawan'])->group(function () {
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.dashboard');
    });
});

use App\Http\Controllers\AttendanceController;

Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::post('/absen', [AttendanceController::class, 'store'])->name('absen.store');
});
use App\Http\Controllers\Auth\LoginController;

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/absen', [AttendanceController::class, 'show'])->name('absen.show');

require __DIR__.'/auth.php';
