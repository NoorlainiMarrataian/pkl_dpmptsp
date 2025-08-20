<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatainvestasiController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Route untuk Data Investasi (frontend/user)
|
*/

// returns the home page with all posts
Route::get('/', [DatainvestasiController::class, 'index'])->name('data_investasi.index');
// returns the form for adding a post
Route::get('/data_investasi/create', [DatainvestasiController::class, 'create'])->name('data_investasi.create');
// adds a post to the database
Route::post('/data_investasi', [DatainvestasiController::class, 'store'])->name('data_investasi.store');
// returns a page that shows a full post
Route::get('/data_investasi/{data_investasi}', [DatainvestasiController::class, 'show'])->name('data_investasi.show');
// returns the form for editing a post
Route::get('/data_investasi/{data_investasi}/edit', [DatainvestasiController::class, 'edit'])->name('data_investasi.edit');
// updates a post
Route::put('/data_investasi/{data_investasi}', [DatainvestasiController::class, 'update'])->name('data_investasi.update');
// deletes a post
Route::delete('/data_investasi/{data_investasi}', [DatainvestasiController::class, 'destroy'])->name('data_investasi.destroy');


/*
|--------------------------------------------------------------------------
| Web Routes Admin
|--------------------------------------------------------------------------
|
| Route untuk halaman backend admin
|
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard')
        ->middleware('auth:admin');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

     // ✅ Tambahan route untuk log pengunduhan
    Route::get('/log-pengunduhan', [LogPengunduhanController::class, 'index'])
        ->name('admin.log_pengunduhan.index')
        ->middleware('auth:admin');
});
