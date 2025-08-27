<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datainvestasi;
use Illuminate\Support\Facades\DB; // <-- tambahkan ini


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

        // Jika triwulan dipilih dan bukan "Tahun", filter periode
        if ($triwulan && $triwulan !== 'Tahun') {
            $query->where('periode', $triwulan);
        }

        $data_investasi = $query
        ->selectRaw('negara, status_penanaman_modal, tahun, periode, 
                     SUM(investasi_us_ribu) as total_investasi_us_ribu, 
                     SUM(investasi_rp_juta) as total_investasi_rp_juta')
        ->groupBy('negara', 'status_penanaman_modal', 'tahun', 'periode')
        ->get();

        return view('user.realisasi.negara', compact('data_investasi', 'tahun', 'triwulan'));
    }



    public function lokasi(Request $request)
    {
        // Ambil filter dari request
        $tahun = $request->input('tahun');
        $jenis = $request->input('jenis');
        $triwulan = $request->input('triwulan');

        // Query data dari tabel data_investasi
        $dataLokasi = DB::table('data_investasi')
            ->when($tahun, function($q) use ($tahun) {
                $q->where('tahun', $tahun);
            })
            ->when($jenis && $jenis != 'PMA+PMDN', function($q) use ($jenis) {
                $q->where('status_penanaman_modal', $jenis);
            })
            ->when($triwulan && $triwulan != 'Tahun', function($q) use ($triwulan) {
                $q->where('periode', $triwulan);
            })
            ->select(
                'provinsi',
                'kabupaten_kota',
                'status_penanaman_modal',
                DB::raw('SUM(proyek) as proyek'),
                DB::raw('SUM(investasi_rp_juta) as investasi_rp_juta'),
                DB::raw('SUM(investasi_us_ribu) as investasi_us_ribu'),
                DB::raw('SUM(jumlah_tki) as jumlah_tki')
            )
            ->groupBy('provinsi','kabupaten_kota','status_penanaman_modal')
            ->get();

        // Data untuk grafik
        $chartLabels = $dataLokasi->pluck('kabupaten_kota');
        $chartData = $dataLokasi->pluck('investasi_rp_juta');

        // Kirim ke view
        return view('user.realisasi.lokasi', compact('dataLokasi', 'chartLabels', 'chartData', 'tahun', 'jenis', 'triwulan'));
    }
}
