<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataInvestasiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogPengunduhanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RealisasiInvestasiController;

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

// Halaman realisasi investasi (user)
Route::get('/realisasi-investasi', [UserController::class, 'realisasi'])->name('realisasi.realisasiinvestasi');

// ✅ Halaman Negara Investor (user)
Route::get('/negara-investor', [RealisasiInvestasiController::class, 'negaraInvestor'])
    ->name('realisasi.negara');

// ✅ Halaman Lokasi (user)
Route::get('/lokasi-investasi', [RealisasiInvestasiController::class, 'lokasi'])
    ->name('realisasi.lokasi');

// Halaman Perbandingan (User)
Route::get('/perbandingan-investasi', [RealisasiInvestasiController::class, 'perbandingan'])
    ->name('realisasi.perbandingan');
    
//Halaman Pernandingan 2 Triwulan
Route::get('/perbandingan2-investasi', [RealisasiInvestasiController::class, 'perbandingan2'])
    ->name('realisasi.perbandingan2');

// Route GET untuk menampilkan form upload
Route::get('/data_investasi/upload', [DataInvestasiController::class, 'uploadForm'])->name('data_investasi.upload.form');

// Route POST untuk memproses file upload
Route::post('/data_investasi/upload', [DataInvestasiController::class, 'upload'])->name('data_investasi.upload');

// Route untuk mengunduh data investasi dalam format Excel
Route::post('/log-pengunduhan', [LogPengunduhanController::class, 'store'])->name('log_pengunduhan.store');



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
