<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataInvestasiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogPengunduhanController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes (Frontend / User)
|--------------------------------------------------------------------------
*/

// ✅ resource otomatis sudah berisi index, create, store, show, edit, update, destroy
Route::resource('data_investasi', DataInvestasiController::class);

// Halaman utama (/) diarahkan ke dashboard user, dan diberi nama "home"
Route::get('/', function () {
    return redirect()->route('user.dashboard');
})->name('home');

// Halaman dashboard user
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');


/*
|--------------------------------------------------------------------------
| Web Routes Admin
|--------------------------------------------------------------------------
*/

// halaman login admin
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

        // log pengunduhan
        Route::get('/log-pengunduhan', [LogPengunduhanController::class, 'index'])->name('admin.log_pengunduhan.index');

        // ✅ Data Laporan: langsung arahkan ke data_investasi.index
        Route::get('/laporan', [DataInvestasiController::class, 'index'])->name('admin.laporan.index');
    });
});
