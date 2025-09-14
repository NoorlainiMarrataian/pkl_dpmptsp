<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('user.dashboard.index');
    }

    public function realisasi()
    {
        $data_investasi = DB::table('data_investasi')
            ->select('tahun', DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta'))
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        $labels = $data_investasi->pluck('tahun');
        $data   = $data_investasi->pluck('total_investasi_rp_juta');

        return view('user.realisasi.realisasiinvestasi', compact('labels', 'data'));
    }
}
