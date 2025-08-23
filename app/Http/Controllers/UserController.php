<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('user.dashboard.index');
    }

    public function realisasi()
    {
        // arahkan ke view realisasi.blade.php
        return view('user.realisasi.realisasiinvestasi'); 
        // pastikan file ada di resources/views/user/realisasi/index.blade.php
    }
}
