<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datainvestasi;
use Illuminate\Support\Facades\DB;

class RealisasiInvestasiController extends Controller
{
    // Halaman utama realisasi investasi
    public function index()
    {
        return view('realisasi.index');
    }

    // Halaman Negara Investor
    public function negaraInvestor(Request $request)
    {
        $tahun = $request->input('tahun');
        $triwulan = $request->input('triwulan');

        if (!$tahun || !$triwulan) {
            $data_investasi = collect(); 
            $total = null;
            return view('user.realisasi.negara', compact('data_investasi', 'tahun', 'triwulan', 'total'));
        }

        $query = Datainvestasi::where('status_penanaman_modal', 'PMA');

        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        if ($triwulan && $triwulan !== 'Tahun') {
            $query->where('periode', $triwulan);

            $data_investasi = $query
                ->selectRaw('negara, status_penanaman_modal, tahun, periode,
                            COUNT(status_penanaman_modal) as jumlah_pma,
                            SUM(investasi_us_ribu) as total_investasi_us_ribu,
                            SUM(investasi_rp_juta) as total_investasi_rp_juta')
                ->groupBy('negara', 'status_penanaman_modal', 'tahun', 'periode')
                ->get();
        } else { 
            // 1 tahun saja
        $data_investasi = $query
            ->selectRaw('negara, status_penanaman_modal, tahun,
                        COUNT(status_penanaman_modal) as jumlah_pma,
                        SUM(investasi_us_ribu) as total_investasi_us_ribu,
                        SUM(investasi_rp_juta) as total_investasi_rp_juta')
            ->groupBy('negara', 'status_penanaman_modal', 'tahun')
            ->get();
        }
        $total = [
            'jumlah_pma' => $data_investasi->sum('jumlah_pma'),
            'total_investasi_us_ribu' => $data_investasi->sum('total_investasi_us_ribu'),
            'total_investasi_rp_juta' => $data_investasi->sum('total_investasi_rp_juta'),
        ];

        return view('user.realisasi.negara', compact('data_investasi', 'tahun', 'triwulan', 'total'));
    }

    // Halaman Lokasi (Bagian 1 & 2)
    public function lokasi(Request $request)
    {
        $jenis = $request->jenis;

        // Bagian 1: Data Kabupaten/Kota sesuai jenis
        $dataLokasi = collect();
        $dataPMA = collect();
        $dataPMDN = collect();
        $dataGabungan = collect();

        if ($jenis === 'PMA') {
            $dataLokasi = DB::table('data_investasi')
                ->where('status_penanaman_modal', 'PMA')
                ->when($request->tahun, fn($q) => $q->where('tahun', $request->tahun))
                ->select('kabupaten_kota', 'status_penanaman_modal',
                    DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu'),
                    DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta'))
                ->groupBy('kabupaten_kota','status_penanaman_modal')
                ->get();

        } elseif ($jenis === 'PMDN') {
            $dataLokasi = DB::table('data_investasi')
                ->where('status_penanaman_modal', 'PMDN')
                ->when($request->tahun, fn($q) => $q->where('tahun', $request->tahun))
                ->select('kabupaten_kota', 'status_penanaman_modal',
                    DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta'))
                ->groupBy('kabupaten_kota','status_penanaman_modal')
                ->get();

        } elseif ($jenis === 'PMA+PMDN') {
            $dataPMA = DB::table('data_investasi')
                ->where('status_penanaman_modal', 'PMA')
                ->when($request->tahun, fn($q) => $q->where('tahun', $request->tahun))
                ->select('kabupaten_kota', 'status_penanaman_modal',
                    DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu'),
                    DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta'))
                ->groupBy('kabupaten_kota','status_penanaman_modal')
                ->get();

            $dataPMDN = DB::table('data_investasi')
                ->where('status_penanaman_modal', 'PMDN')
                ->when($request->tahun, fn($q) => $q->where('tahun', $request->tahun))
                ->select('kabupaten_kota', 'status_penanaman_modal',
                    DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta'))
                ->groupBy('kabupaten_kota','status_penanaman_modal')
                ->get();

            $dataGabungan = DB::table('data_investasi')
                ->when($request->tahun, fn($q) => $q->where('tahun', $request->tahun))
                ->select(DB::raw("'PMA+PMDN' as status_penanaman_modal"),
                    DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta'))
                ->get();
        }

        // Data untuk chart
        $chartLabels = $dataLokasi->pluck('kabupaten_kota');
        $chartData   = $dataLokasi->pluck('total_investasi_rp_juta'); // perbaiki dari field yg ada

        // Bagian 2: Data Provinsi
        $dataProvinsi = DB::table('data_investasi')
            ->when($request->tahun2, fn($q) => $q->where('tahun', $request->tahun2))
            ->select('provinsi',
                DB::raw('SUM(proyek) as proyek'),
                DB::raw('SUM(investasi_rp_juta) as investasi_rp_juta'),
                DB::raw('SUM(investasi_us_ribu) as investasi_us_ribu'))
            ->groupBy('provinsi')
            ->get();

        // Bagian 3: Top 5 PMA & PMDN
        // Top 5 PMA
        $topPMA = DB::table('data_investasi')
            ->where('status_penanaman_modal', 'PMA')
            ->when($request->tahun2, fn($q) => $q->where('tahun', $request->tahun2))
            ->select(
                'kabupaten_kota',
                DB::raw('SUM(proyek) as total_proyek'),
                DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta'),
                DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu')
            )
            ->groupBy('kabupaten_kota')
            ->orderByDesc('total_investasi_rp_juta')
            ->limit(5)
            ->get();

        // Top 5 PMDN
        $topPMDN = DB::table('data_investasi')
            ->where('status_penanaman_modal', 'PMDN')
            ->when($request->tahun2, fn($q) => $q->where('tahun', $request->tahun2))
            ->select(
                'kabupaten_kota',
                DB::raw('SUM(proyek) as total_proyek'),
                DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta'),
                DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu')
            )
            ->groupBy('kabupaten_kota')
            ->orderByDesc('total_investasi_rp_juta')
            ->limit(5)
            ->get();

        // Bagian sektor
        $sektor = DB::table('data_investasi')
            ->when($request->tahun2, fn($q) => $q->where('tahun', $request->tahun2))
            ->when($request->triwulan2 && $request->triwulan2 !== 'Tahun', 
                fn($q) => $q->where('periode', $request->triwulan2))
            ->select(
                'kabupaten_kota',
                'status_penanaman_modal',
                DB::raw('SUM(investasi_us_ribu) as total_usd'),
                DB::raw('SUM(investasi_rp_juta) as total_rp')
            )
            ->groupBy('kabupaten_kota', 'status_penanaman_modal')
            ->orderByDesc('total_rp')
            ->get();

        return view('user.realisasi.lokasi', compact(
            'dataLokasi',
            'chartLabels',
            'chartData',
            'dataProvinsi',
            'dataPMA',
            'dataPMDN',
            'dataGabungan',
            'jenis',
            'topPMA',
            'topPMDN',
            'sektor'
        ));
    }


    // Halaman Perbandingan (Bagian 3 & 4)
    public function perbandingan(Request $request)
    {
        // Bagian 3: Perbandingan Antar Tahun
        if ($request->filled(['tahun_awal','tahun_akhir'])) {
            $dataPerbandingan = DB::table('data_investasi')
                ->select('tahun',
                    DB::raw('SUM(proyek) as proyek'),
                    DB::raw('SUM(investasi_rp_juta) as investasi_rp_juta'),
                    DB::raw('SUM(investasi_us_ribu) as investasi_us_ribu'))
                ->whereBetween('tahun', [$request->tahun_awal, $request->tahun_akhir])
                ->groupBy('tahun')
                ->orderBy('tahun','asc')
                ->get();

            $perbandinganLabels = $dataPerbandingan->pluck('tahun');
            $perbandinganData   = $dataPerbandingan->pluck('investasi_rp_juta');
        } else {
            $dataPerbandingan = collect();
            $perbandinganLabels = [];
            $perbandinganData   = [];
        }

        // Bagian 4: Perbandingan Tahun + Periode
        if ($request->filled(['tahun_awal4','tahun_akhir4','periode4'])) {
            $dataPerbandinganPeriode = DB::table('data_investasi')
                ->select('tahun','periode',
                    DB::raw('SUM(proyek) as proyek'),
                    DB::raw('SUM(investasi_rp_juta) as investasi_rp_juta'),
                    DB::raw('SUM(investasi_us_ribu) as investasi_us_ribu'))
                ->whereBetween('tahun', [$request->tahun_awal4, $request->tahun_akhir4])
                ->when($request->periode4, fn($q) => $q->where('periode', $request->periode4))
                ->groupBy('tahun','periode')
                ->orderBy('tahun','asc')
                ->get();

            $perbandinganPeriodeLabels = $dataPerbandinganPeriode->map(fn($d) => $d->tahun.' '.$d->periode);
            $perbandinganPeriodeData   = $dataPerbandinganPeriode->pluck('investasi_rp_juta');
        } else {
            $dataPerbandinganPeriode = collect();
            $perbandinganPeriodeLabels = [];
            $perbandinganPeriodeData   = [];
        }

        return view('user.realisasi.perbandingan', compact(
            'dataPerbandingan',
            'perbandinganLabels',
            'perbandinganData',
            'dataPerbandinganPeriode',
            'perbandinganPeriodeLabels',
            'perbandinganPeriodeData'
        ));
    }
}
