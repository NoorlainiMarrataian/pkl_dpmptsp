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
Route::get('/data_investasi/check/{id}', [DataInvestasiController::class, 'check'])
    ->name('data_investasi.check');

Route::resource('data_investasi', DataInvestasiController::class);

Route::get('/', function () {
    return redirect()->route('user.dashboard');
})->name('home');

Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
Route::get('/realisasi-investasi', [UserController::class, 'realisasi'])->name('realisasi.realisasiinvestasi');
Route::get('/negara-investor', [RealisasiInvestasiController::class, 'negaraInvestor'])
    ->name('realisasi.negara');

Route::get('/lokasi-investasi', [RealisasiInvestasiController::class, 'lokasi'])
    ->name('realisasi.lokasi');

Route::match(['get', 'post'], '/perbandingan-investasi', [RealisasiInvestasiController::class, 'perbandingan'])
    ->name('realisasi.perbandingan');

Route::get('/perbandingan2-investasi', [RealisasiInvestasiController::class, 'perbandingan2'])
    ->name('realisasi.perbandingan2');

Route::get('/perbandingan-investasi/download1', [RealisasiInvestasiController::class, 'downloadBagian1'])
    ->name('realisasi.perbandingan.download1');

Route::get('/perbandingan-investasi/download2', [RealisasiInvestasiController::class, 'downloadBagian2'])
    ->name('realisasi.perbandingan.download2');

Route::get('/data_investasi/upload', [DataInvestasiController::class, 'uploadForm'])->name('data_investasi.upload.form');
Route::post('/data_investasi/upload', [DataInvestasiController::class, 'upload'])->name('data_investasi.upload');
Route::post('/log-pengunduhan', [LogPengunduhanController::class, 'store'])->name('log_pengunduhan.store');

/*
|--------------------------------------------------------------------------
| Web Routes Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('/log-pengunduhan', [LogPengunduhanController::class, 'index'])->name('admin.log_pengunduhan.index');
        Route::get('/laporan', [DataInvestasiController::class, 'index'])->name('admin.laporan.index');
        Route::resource('data_investasi', DataInvestasiController::class);
    });
});
