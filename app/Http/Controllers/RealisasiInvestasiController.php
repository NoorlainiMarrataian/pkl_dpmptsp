<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datainvestasi;

class RealisasiInvestasiController extends Controller
{
    // Halaman utama realisasi investasi
    public function index()
    {
        // nanti bisa ambil data realisasi dari database
        return view('realisasi.index');
    }

    // Halaman Negara Investor
    public function negaraInvestor(Request $request)
    {
        $tahun = $request->input('tahun');
        $triwulan = $request->input('triwulan');

        $query = Datainvestasi::query();

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        if ($triwulan) {
            $query->where('periode', $triwulan);
        }

        $data_investasi = $query->get();

        return view('user.realisasi.negara', compact('data_investasi', 'tahun', 'triwulan'));
    }


    public function lokasi()
    {
        return view('user.realisasi.lokasi'); // arahkan ke view Lokasi Investasi
    }
}
