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

    // Halaman Lokasi (Bagian 1)
    public function lokasi(Request $request)
    {
        $jenisBagian1 = $request->jenis;
        $triwulan = $request->triwulan; // periode Bagian 1
        $tahun = $request->tahun;

        $tahun2 = $request->tahun2; // Bagian 2
        $triwulan2 = $request->triwulan2; // Bagian 2
        $jenisBagian2 = $request->jenisBagian2; // untuk filter di bagian 2
        $filter = $request->filter; // untuk filter di bagian 1

        $topPMA = collect();
        $topPMDN = collect();
        $dataLokasi = collect();   // untuk chart/data lain
        $chartLabels = [];
        $chartData = [];
        $sektor = collect();

        // Bagian 1: Data Kabupaten/Kota sesuai jenis
        $dataLokasi = DB::table('data_investasi')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($triwulan && $triwulan !== 'Tahun', fn($q) => $q->where('periode', $triwulan));


        if ($jenisBagian1 === 'PMA') {
            $dataLokasi = $dataLokasi
                ->where('status_penanaman_modal', 'PMA')              
                ->select(
                    'kabupaten_kota',
                    'status_penanaman_modal',
                    DB::raw('COUNT(status_penanaman_modal) as proyekpma'),
                    DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu'),
                    DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                )
                ->when(
                    $request->triwulan && $request->triwulan !== 'Tahun',
                    fn($q) => $q->addSelect('periode')->groupBy('kabupaten_kota','status_penanaman_modal','periode'),
                    fn($q) => $q->groupBy('kabupaten_kota','status_penanaman_modal')
                )
                ->get();

        } elseif ($jenisBagian1 === 'PMDN') {
           $dataLokasi = $dataLokasi
                ->where('status_penanaman_modal', 'PMDN')
                ->select(
                    'kabupaten_kota',
                    'status_penanaman_modal',
                    DB::raw('COUNT(status_penanaman_modal) as proyekpmdn'),
                    DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                );

            // Group by
            if ($request->triwulan && $request->triwulan !== 'Tahun') {
                $dataLokasi->addSelect('periode')
                    ->groupBy('kabupaten_kota','status_penanaman_modal','periode');
            } else {
                $dataLokasi->groupBy('kabupaten_kota','status_penanaman_modal');
            }

            $dataLokasi = $dataLokasi->get();


        } elseif ($jenisBagian1 === 'PMA+PMDN') {
            // Gabungan PMA + PMDN  
            $dataLokasi = $dataLokasi
                
                ->select(
                    'kabupaten_kota',

                    // agregasi PMDN
                    DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN 1 ELSE 0 END) as proyekpmdn"),
                    DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN investasi_rp_juta ELSE 0 END) as total_investasi_pmdn_rp"),

                    // agregasi PMA
                    DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN 1 ELSE 0 END) as proyekpma"),
                    DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_us_ribu ELSE 0 END) as total_investasi_pma_us"),
                    DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_rp_juta ELSE 0 END) as total_investasi_pma_rp"),

                    // total gabungan proyek & investasi
                    DB::raw("SUM(CASE WHEN status_penanaman_modal IN ('PMDN','PMA') THEN 1 ELSE 0 END) as total_proyek"),
                    DB::raw("SUM(investasi_rp_juta) as total_investasi_rp_all")
                );

                if ($triwulan && $triwulan !== 'Tahun') {
                    $dataLokasi
                        ->addSelect('periode')
                        ->groupBy('kabupaten_kota','periode');
                } else {
                    // kalau filter tahun → groupBy kabupaten_kota saja
                    $dataLokasi ->groupBy('kabupaten_kota');
                }

                $dataLokasi = $dataLokasi->get();
        }
            

        // Data untuk chart
        if ($jenisBagian1 === 'PMA') {
            $chartLabels = $dataLokasi->pluck('kabupaten_kota');
            $chartData   = $dataLokasi->pluck('total_investasi_rp_juta');

        } elseif ($jenisBagian1 === 'PMDN') {
            $chartLabels = $dataLokasi->pluck('kabupaten_kota');
            $chartData   = $dataLokasi->pluck('total_investasi_rp_juta');

        } elseif ($jenisBagian1 === 'PMA+PMDN') {
            $chartLabels = $dataLokasi->pluck('kabupaten_kota');
            $chartData   = $dataLokasi->pluck('total_investasi_rp_all'); 
        } else {
            $chartLabels = [];
            $chartData   = [];
        }

        if ($jenisBagian2 === '5 Realisasi Investasi Terbesar Berdasarkan Kab Kota') {

            // PMA
            $topPMAQuery = DB::table('data_investasi')
                ->select('kabupaten_kota', 'status_penanaman_modal', DB::raw('SUM(investasi_rp_juta) as total_investasi'))
                ->where('status_penanaman_modal', 'PMA')
                ->when($tahun2, fn($q) => $q->where('tahun', $tahun2));

            // Jika triwulan dipilih bukan "Tahun", tampilkan per periode
            if ($triwulan2 && $triwulan2 != 'Tahun') {
                $topPMAQuery->addSelect('periode')
                    ->where('periode', $triwulan2)
                    ->groupBy('kabupaten_kota', 'status_penanaman_modal', 'periode');
            } else {
                // 1 Tahun → jumlah semua triwulan
                $topPMAQuery->groupBy('kabupaten_kota', 'status_penanaman_modal');
            }

            $topPMA = $topPMAQuery->orderByDesc('total_investasi')->limit(5)->get();

            // PMDN
            $topPMDNQuery = DB::table('data_investasi')
                ->select('kabupaten_kota', 'status_penanaman_modal', DB::raw('SUM(investasi_rp_juta) as total_investasi'))
                ->where('status_penanaman_modal', 'PMDN')
                ->when($tahun2, fn($q) => $q->where('tahun', $tahun2));

            if ($triwulan2 && $triwulan2 != 'Tahun') {
                $topPMDNQuery->addSelect('periode')
                    ->where('periode', $triwulan2)
                    ->groupBy('kabupaten_kota', 'status_penanaman_modal', 'periode');
            } else {
                $topPMDNQuery->groupBy('kabupaten_kota', 'status_penanaman_modal');
            }

            $topPMDN = $topPMDNQuery->orderByDesc('total_investasi')->limit(5)->get();


        }elseif ($jenisBagian2 === '5 Proyek Terbesar Berdasarkan Kab Kota') {
            // Query jumlah proyek PMA
            $topPMAQuery = DB::table('data_investasi')
                ->select('kabupaten_kota', 'status_penanaman_modal', DB::raw('COUNT(status_penanaman_modal) as proyekpma'))
                ->where('status_penanaman_modal', 'PMA')
                ->when($tahun2, fn($q) => $q->where('tahun', $tahun2));

            if ($triwulan2 && $triwulan2 != 'Tahun') {
                // Per triwulan
                $topPMAQuery->addSelect('periode')
                    ->where('periode', $triwulan2)
                    ->groupBy('kabupaten_kota', 'status_penanaman_modal', 'periode');
            } else {
                // 1 Tahun → jumlah semua triwulan
                $topPMAQuery->groupBy('kabupaten_kota', 'status_penanaman_modal');
            }

            $topPMA = $topPMAQuery->orderByDesc('proyekpma')->limit(5)->get();

            // Query jumlah proyek PMDN
            $topPMDNQuery = DB::table('data_investasi')
                ->select('kabupaten_kota', 'status_penanaman_modal', DB::raw('COUNT(status_penanaman_modal) as proyekpmdn'))
                ->where('status_penanaman_modal', 'PMDN')
                ->when($tahun2, fn($q) => $q->where('tahun', $tahun2));

            if ($triwulan2 && $triwulan2 != 'Tahun') {
                // Per triwulan
                $topPMDNQuery->addSelect('periode')
                    ->where('periode', $triwulan2)
                    ->groupBy('kabupaten_kota', 'status_penanaman_modal', 'periode');
            } else {
                // 1 Tahun → jumlah semua triwulan
                $topPMDNQuery->groupBy('kabupaten_kota', 'status_penanaman_modal');
            }

            $topPMDN = $topPMDNQuery->orderByDesc('proyekpmdn')->limit(5)->get();


        } elseif ($jenisBagian2 === 'sektor') {
            // Query data sektor
            $sektor = DB::table('data_investasi')
                ->select('nama_sektor', 'periode', 'status_penanaman_modal')
                ->when($tahun2, fn($q) => $q->where('tahun', $tahun2))
                ->when($triwulan2 && $triwulan2 != 'Tahun', fn($q) => $q->where('periode', $triwulan2))
                ->orderBy('nama_sektor')
                ->get();
        }
        
        if ($request->ajax()) {
            return view('user.realisasi.partials.ajax_bagian2', compact(
                'jenisBagian2', 'tahun2','triwulan2','topPMA','topPMDN','sektor'
            ))->render();
        }
        
        return view('user.realisasi.lokasi', compact(
            'dataLokasi','chartLabels','chartData',
            'jenisBagian1','jenisBagian2','tahun',
            'tahun2','triwulan2','topPMA','topPMDN','sektor'
        ));
    }            



    public function perbandingan(Request $request)
    {
        // ==== BAGIAN 1 ====
        if ($request->filled(['tahun_awal','tahun_akhir'])) {
            $dataPerbandingan = DB::table('data_investasi')
                ->select('tahun', DB::raw('SUM(investasi_rp_juta) as investasi_rp_juta'))
                ->whereBetween('tahun', [$request->tahun_awal, $request->tahun_akhir])
                ->groupBy('tahun')
                ->orderBy('tahun','asc')
                ->get();

            $perbandinganLabels = $dataPerbandingan->pluck('tahun');
            $perbandinganData   = $dataPerbandingan->pluck('investasi_rp_juta');

            if ($request->jenis == 'PMA') {
                $rows = DB::table('data_investasi')
                    ->select(
                        'tahun','kabupaten_kota','status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as total_status'),
                        DB::raw('SUM(investasi_us_ribu) as total_usd'),
                        DB::raw('SUM(investasi_rp_juta) as total_rp')
                    )
                    ->whereBetween('tahun', [$request->tahun_awal, $request->tahun_akhir])
                    ->where('status_penanaman_modal', 'PMA')
                    ->groupBy('tahun','kabupaten_kota','status_penanaman_modal')
                    ->orderBy('kabupaten_kota','asc')
                    ->get()
                    ->groupBy('tahun');
            } elseif ($request->jenis == 'PMDN') {
                $rows = DB::table('data_investasi')
                    ->select(
                        'tahun','kabupaten_kota','status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as total_status'),
                        DB::raw('SUM(investasi_rp_juta) as total_rp')
                    )
                    ->whereBetween('tahun', [$request->tahun_awal, $request->tahun_akhir])
                    ->where('status_penanaman_modal', 'PMDN')
                    ->groupBy('tahun','kabupaten_kota','status_penanaman_modal')
                    ->orderBy('kabupaten_kota','asc')
                    ->get()
                    ->groupBy('tahun');
            } else {
                $rows = collect();
            }
        } else {
            $dataPerbandingan   = collect();
            $perbandinganLabels = [];
            $perbandinganData   = [];
            $rows               = collect();
        }

        // ==== BAGIAN 2 ====
        if ($request->filled(['tahun_awal4','periode_awal4','tahun_akhir4','periode_akhir4'])) {
            $dataPerbandinganPeriode = DB::table('data_investasi')
                ->select(
                    'tahun','periode','kabupaten_kota','status_penanaman_modal',
                    DB::raw('COUNT(status_penanaman_modal) as total_status'),
                    DB::raw('SUM(investasi_rp_juta) as total_rp'),
                    DB::raw('SUM(investasi_us_ribu) as total_usd')
                )
                ->groupBy('tahun','periode','kabupaten_kota','status_penanaman_modal')
                ->orderBy('tahun','asc')->orderBy('periode','asc')
                ->get();

            $dataPerbandinganPeriodeByTahun = $dataPerbandinganPeriode->groupBy('tahun');
            $perbandinganPeriodeLabels = $dataPerbandinganPeriode->map(fn($d) => $d->tahun.' '.$d->periode);
            $perbandinganPeriodeData   = $dataPerbandinganPeriode->pluck('total_rp');
        } else {
            $dataPerbandinganPeriode        = collect();
            $dataPerbandinganPeriodeByTahun = collect();
            $perbandinganPeriodeLabels      = [];
            $perbandinganPeriodeData        = [];
        }

        return view('user.realisasi.perbandingan', compact(
            'dataPerbandingan','perbandinganLabels','perbandinganData','rows',
            'dataPerbandinganPeriode','perbandinganPeriodeLabels',
            'perbandinganPeriodeData','dataPerbandinganPeriodeByTahun'
        ));
    }

    // ========== Partial View Bagian 1 ==========
    public function perbandinganBagian1(Request $request)
    {
        // logika sama persis dengan BAGIAN 1 di atas
        // supaya bisa dipanggil via AJAX
        if ($request->filled(['tahun_awal','tahun_akhir'])) {
            $dataPerbandingan = DB::table('data_investasi')
                ->select('tahun', DB::raw('SUM(investasi_rp_juta) as investasi_rp_juta'))
                ->whereBetween('tahun', [$request->tahun_awal, $request->tahun_akhir])
                ->groupBy('tahun')
                ->orderBy('tahun','asc')
                ->get();

            $perbandinganLabels = $dataPerbandingan->pluck('tahun');
            $perbandinganData   = $dataPerbandingan->pluck('investasi_rp_juta');

            if ($request->jenis == 'PMA') {
                $rows = DB::table('data_investasi')
                    ->select(
                        'tahun','kabupaten_kota','status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as total_status'),
                        DB::raw('SUM(investasi_us_ribu) as total_usd'),
                        DB::raw('SUM(investasi_rp_juta) as total_rp')
                    )
                    ->whereBetween('tahun', [$request->tahun_awal, $request->tahun_akhir])
                    ->where('status_penanaman_modal', 'PMA')
                    ->groupBy('tahun','kabupaten_kota','status_penanaman_modal')
                    ->orderBy('kabupaten_kota','asc')
                    ->get()
                    ->groupBy('tahun');
            } elseif ($request->jenis == 'PMDN') {
                $rows = DB::table('data_investasi')
                    ->select(
                        'tahun','kabupaten_kota','status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as total_status'),
                        DB::raw('SUM(investasi_rp_juta) as total_rp')
                    )
                    ->whereBetween('tahun', [$request->tahun_awal, $request->tahun_akhir])
                    ->where('status_penanaman_modal', 'PMDN')
                    ->groupBy('tahun','kabupaten_kota','status_penanaman_modal')
                    ->orderBy('kabupaten_kota','asc')
                    ->get()
                    ->groupBy('tahun');
            } else {
                $rows = collect();
            }
        } else {
            $dataPerbandingan   = collect();
            $perbandinganLabels = [];
            $perbandinganData   = [];
            $rows               = collect();
        }

        return view('user.realisasi.bandingpartial.bagian1', compact(
            'dataPerbandingan','perbandinganLabels','perbandinganData','rows'
        ));
    }

    // ========== Partial View Bagian 2 ==========
    public function perbandinganBagian2(Request $request)
    {
        if ($request->filled(['tahun_awal4','periode_awal4','tahun_akhir4','periode_akhir4'])) {
            $dataPerbandinganPeriode = DB::table('data_investasi')
                ->select(
                    'tahun','periode','kabupaten_kota','status_penanaman_modal',
                    DB::raw('COUNT(status_penanaman_modal) as total_status'),
                    DB::raw('SUM(investasi_rp_juta) as total_rp'),
                    DB::raw('SUM(investasi_us_ribu) as total_usd')
                )
                ->groupBy('tahun','periode','kabupaten_kota','status_penanaman_modal')
                ->orderBy('tahun','asc')->orderBy('periode','asc')
                ->get();

            $dataPerbandinganPeriodeByTahun = $dataPerbandinganPeriode->groupBy('tahun');
            $perbandinganPeriodeLabels = $dataPerbandinganPeriode->map(fn($d) => $d->tahun.' '.$d->periode);
            $perbandinganPeriodeData   = $dataPerbandinganPeriode->pluck('total_rp');
        } else {
            $dataPerbandinganPeriode        = collect();
            $dataPerbandinganPeriodeByTahun = collect();
            $perbandinganPeriodeLabels      = [];
            $perbandinganPeriodeData        = [];
        }

        return view('user.realisasi.bandingpartial.bagian2', compact(
            'dataPerbandinganPeriode','dataPerbandinganPeriodeByTahun',
            'perbandinganPeriodeLabels','perbandinganPeriodeData'
        ));
    }
}
