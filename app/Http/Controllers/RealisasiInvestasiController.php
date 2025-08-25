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
        $query = Datainvestasi::query();

        $tahun = $request->input ('tahun');
        $triwulan = $request->input ('triwulan');

        // Mengambil semua data user yang statusnya 'aktif'
        

        // if ($request->has('tahun') && $request ->has('triwulan')) {
        //     $data_investasi = Datainvestasi::where('tahun', '=', $tahun) -> where('periode', '=', $triwulan) ->get();
        // } else {
        //     $data_investasi = $query->get();
        // }

        $data_investasi = Datainvestasi::where('periode', '=', $triwulan) ->get();


        //$data_investasi = $query->where('id', 4);

        return view('user.realisasi.negara', compact('data_investasi'));
    }


    public function lokasi()
    {
        return view('user.realisasi.lokasi'); // arahkan ke view Lokasi Investasi
    }
}
