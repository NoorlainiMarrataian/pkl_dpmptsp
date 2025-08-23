<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RealisasiInvestasiController extends Controller
{
    // Halaman utama realisasi investasi
    public function index()
    {
        // nanti bisa ambil data realisasi dari database
        return view('realisasi.index');
    }

    // Halaman Negara Investor
    public function negaraInvestor()
    {
        return view('user.realisasi.negara'); // arahkan ke view Negara Investor
    }

    public function lokasi()
    {
        return view('user.realisasi.lokasi'); // arahkan ke view Lokasi Investasi
    }
}
