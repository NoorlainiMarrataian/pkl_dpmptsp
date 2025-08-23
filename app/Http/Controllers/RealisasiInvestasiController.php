<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RealisasiInvestasiController extends Controller
{
    public function index()
    {
        // nanti bisa ambil data realisasi dari database
        return view('realisasi.index');
    }
}
