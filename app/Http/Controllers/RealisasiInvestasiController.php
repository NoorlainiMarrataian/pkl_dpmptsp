<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datainvestasi;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


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
                ->select('nama_sektor',  

                    // === PMDN ===
                    DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN 1 ELSE 0 END) as proyek_pmdn"),
                    DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN investasi_rp_juta ELSE 0 END) as total_investasi_rp_pmdn"),

                    // === PMA ===
                    DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN 1 ELSE 0 END) as proyek_pma"),
                    DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_rp_juta ELSE 0 END) as total_investasi_rp_pma"),
                    DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_us_ribu ELSE 0 END) as total_investasi_us_pma"),

                    // === TOTAL (PMA + PMDN) ===
                    DB::raw("SUM(CASE WHEN status_penanaman_modal IN ('PMDN','PMA') THEN 1 ELSE 0 END) as total_proyek"),
                    DB::raw("SUM(investasi_rp_juta) as total_investasi_rp_all")
                )
                ->when($tahun2, fn($q) => $q->where('tahun', $tahun2));
                
                // Kalau pilih triwulan (bukan Tahun), tambahkan kolom periode
                if ($triwulan2 && $triwulan2 != 'Tahun') {
                    $sektor->addSelect('periode')
                        ->where('periode', $triwulan2)
                        ->groupBy('nama_sektor', 'periode');
                } else {
                    $sektor->groupBy('nama_sektor');
                }

                $sektor = $sektor->orderBy('nama_sektor')->get();
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
        $tahun1 = $request->tahun1;
        $tahun2 = $request->tahun2;
        $jenis  = $request->jenis; // jenis sama untuk kedua tahun

        $dataTahun1 = collect();
        $dataTahun2 = collect();
        $dataTriwulan1 = collect();
        $dataTriwulan2 = collect();
        $chartLabels = [];
        $chartData1 = [];
        $chartData2 = [];

        if ($tahun1 && $tahun2 && $jenis) {
        // Query dasar
            $queryBase = DB::table('data_investasi')
                ->when($jenis, fn($q) => $q->where('status_penanaman_modal', $jenis))
                ->when($tahun1, fn($q) => $q->where('tahun', $tahun1));

            // --- Data Tahun 1 ---
            if ($jenis === 'PMA') {
                $dataTahun1 = DB::table('data_investasi')
                    ->where('status_penanaman_modal', 'PMA')
                    ->where('tahun', $tahun1)
                    ->select(
                        'kabupaten_kota',
                        'status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as proyekpma'),
                        DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu'),
                        DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                    )
                    ->groupBy('kabupaten_kota','status_penanaman_modal')
                    ->get();
                
                $chartLabels = [$tahun1, $tahun2]; // tetap dua tahun
                $chartData1  = [$dataTahun1->sum('total_investasi_rp_juta')];
                
            } elseif ($jenis === 'PMDN') {
                $dataTahun1 = DB::table('data_investasi')
                    ->where('status_penanaman_modal', 'PMDN')
                    ->where('tahun', $tahun1)
                    ->select(
                        'kabupaten_kota',
                        'status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as proyekpmdn'),
                        DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                    )
                    ->groupBy('kabupaten_kota','status_penanaman_modal')
                    ->get();

                $chartLabels = [$tahun1, $tahun2]; // tetap dua tahun
                $chartData1  = [$dataTahun1->sum('total_investasi_rp_juta')];
            } elseif ($jenis === 'PMA+PMDN') {
                $dataTahun1 = DB::table('data_investasi')
                    ->whereIn('status_penanaman_modal', ['PMA','PMDN'])
                    ->where('tahun', $tahun1)
                    ->select(
                        'kabupaten_kota',
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN 1 ELSE 0 END) as proyekpmdn"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN investasi_rp_juta ELSE 0 END) as total_investasi_pmdn_rp"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN 1 ELSE 0 END) as proyekpma"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_us_ribu ELSE 0 END) as total_investasi_pma_us"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_rp_juta ELSE 0 END) as total_investasi_pma_rp"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal IN ('PMDN','PMA') THEN 1 ELSE 0 END) as total_proyek"),
                        DB::raw("SUM(investasi_rp_juta) as total_investasi_rp_all")
                    )
                    ->groupBy('kabupaten_kota')
                    ->get();

                $chartLabels = [$tahun1, $tahun2]; // tetap dua tahun
                $chartData1  = [$dataTahun1->sum('total_investasi_rp_all')];
            }

            // --- Data Tahun 2 ---
            if ($jenis === 'PMA') {
                $dataTahun2 = DB::table('data_investasi')
                    ->where('status_penanaman_modal', 'PMA')
                    ->where('tahun', $tahun2)
                    ->select(
                        'kabupaten_kota',
                        'status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as proyekpma'),
                        DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu'),
                        DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                    )
                    ->groupBy('kabupaten_kota','status_penanaman_modal')
                    ->get();
                
                $chartData2 = [$dataTahun2->sum('total_investasi_rp_juta')];

            } elseif ($jenis === 'PMDN') {
                $dataTahun2 = DB::table('data_investasi')
                    ->where('status_penanaman_modal', 'PMDN')
                    ->where('tahun', $tahun2)
                    ->select(
                        'kabupaten_kota',
                        'status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as proyekpmdn'),
                        DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                    )
                    ->groupBy('kabupaten_kota','status_penanaman_modal')
                    ->get();

                $chartData2 = [$dataTahun2->sum('total_investasi_rp_juta')];

            } elseif ($jenis === 'PMA+PMDN') {
                $dataTahun2 = DB::table('data_investasi')
                    ->whereIn('status_penanaman_modal', ['PMA','PMDN'])
                    ->where('tahun', $tahun2)
                    ->select(
                        'kabupaten_kota',
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN 1 ELSE 0 END) as proyekpmdn"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN investasi_rp_juta ELSE 0 END) as total_investasi_pmdn_rp"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN 1 ELSE 0 END) as proyekpma"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_us_ribu ELSE 0 END) as total_investasi_pma_us"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_rp_juta ELSE 0 END) as total_investasi_pma_rp"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal IN ('PMDN','PMA') THEN 1 ELSE 0 END) as total_proyek"),
                        DB::raw("SUM(investasi_rp_juta) as total_investasi_rp_all")
                    )
                    ->groupBy('kabupaten_kota')
                    ->get();

                $chartData2 = [$dataTahun2->sum('total_investasi_rp_all')];
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.realisasi.partials.tabel_perbandingan1', compact(
                    'dataTahun1','dataTahun2',
                    'tahun1','tahun2','jenis','chartLabels','chartData1','chartData2'

                ))->render(),
                'chartLabels' => $chartLabels,
                'chartData1'  => $chartData1,
                'chartData2'  => $chartData2,
            ]);
        }

        return view('user.realisasi.perbandingan', compact(
            'dataTahun1',
            'tahun1',
            'jenis',
            'tahun2',
            'dataTahun2',
            'chartLabels',
            'chartData1',
            'chartData2'
        ));
    }

    // Halaman Perbandingan 2 (Petriwulan)
    public function perbandingan2(Request $request)
    {
        $tahun1 = $request->tahun1;
        $tahun2 = $request->tahun2;
        $periode1 = $request->periode1; // triwulan tahun 1
        $periode2 = $request->periode2;
        $jenis  = $request->jenis; // jenis sama untuk kedua tahun
        
        $dataTriwulan1 = collect();
        $dataTriwulan2 = collect();
        $chartLabels = [];
        $chartData1 = [];
        $chartData2 = [];

        if ($tahun1 && $tahun2 && $jenis) {
            if ($jenis === 'PMA') {
            $dataTriwulan1 = DB::table('data_investasi')
                ->where('status_penanaman_modal', 'PMA')
                ->where('tahun', $tahun1)
                ->where('periode', $periode1)
                ->select(
                    'kabupaten_kota',
                    'status_penanaman_modal',
                    DB::raw('COUNT(status_penanaman_modal) as proyekpma'),
                    DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu'),
                    DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                )
                ->groupBy('kabupaten_kota','status_penanaman_modal')
                ->get();

            $chartData1 = [$dataTriwulan1->sum('total_investasi_rp_juta')];
            } elseif ($jenis === 'PMDN') {
                $dataTriwulan1 = DB::table('data_investasi')
                    ->where('status_penanaman_modal', 'PMDN')
                    ->where('tahun', $tahun1)
                    ->where('periode', $periode1)
                    ->select(
                        'kabupaten_kota',
                        'status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as proyekpmdn'),
                        DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                    )
                    ->groupBy('kabupaten_kota','status_penanaman_modal')
                    ->get();
                $chartData1 = [$dataTriwulan1->sum('total_investasi_rp_juta')];
            } elseif ($jenis === 'PMA+PMDN') {
                $dataTriwulan1 = DB::table('data_investasi')
                    ->whereIn('status_penanaman_modal', ['PMA','PMDN'])
                    ->where('tahun', $tahun1)
                    ->where('periode', $periode1)
                    ->select(
                        'kabupaten_kota',
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN 1 ELSE 0 END) as proyekpmdn"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN investasi_rp_juta ELSE 0 END) as total_investasi_pmdn_rp"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN 1 ELSE 0 END) as proyekpma"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_us_ribu ELSE 0 END) as total_investasi_pma_us"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_rp_juta ELSE 0 END) as total_investasi_pma_rp"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal IN ('PMDN','PMA') THEN 1 ELSE 0 END) as total_proyek"),
                        DB::raw("SUM(investasi_rp_juta) as total_investasi_rp_all")
                    )
                    ->groupBy('kabupaten_kota')
                    ->get();
                $chartData1 = [$dataTriwulan1->sum('total_investasi_rp_all')];
            }
            // Data triwulan 2
            if ($jenis === 'PMA') {
                $dataTriwulan2 = DB::table('data_investasi')
                    ->where('status_penanaman_modal', 'PMA')
                    ->where('tahun', $tahun2)
                    ->where('periode', $periode2)
                    ->select(
                        'kabupaten_kota',
                        'status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as proyekpma'),
                        DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu'),
                        DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                    )
                    ->groupBy('kabupaten_kota','status_penanaman_modal')
                    ->get();
                $chartData2 = [$dataTriwulan2->sum('total_investasi_rp_juta')];
            } elseif ($jenis === 'PMDN') {
                $dataTriwulan2 = DB::table('data_investasi')
                    ->where('status_penanaman_modal', 'PMDN')
                    ->where('tahun', $tahun2)
                    ->where('periode', $periode2)
                    ->select(
                        'kabupaten_kota',
                        'status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as proyekpmdn'),
                        DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                    )
                    ->groupBy('kabupaten_kota','status_penanaman_modal')
                    ->get();
                $chartData2 = [$dataTriwulan2->sum('total_investasi_rp_juta')];
            } elseif ($jenis === 'PMA+PMDN') {
                $dataTriwulan2 = DB::table('data_investasi')
                    ->whereIn('status_penanaman_modal', ['PMA','PMDN'])
                    ->where('tahun', $tahun2)
                    ->where('periode', $periode2)
                    ->select(
                        'kabupaten_kota',
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN 1 ELSE 0 END) as proyekpmdn"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMDN' THEN investasi_rp_juta ELSE 0 END) as total_investasi_pmdn_rp"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN 1 ELSE 0 END) as proyekpma"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_us_ribu ELSE 0 END) as total_investasi_pma_us"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal = 'PMA' THEN investasi_rp_juta ELSE 0 END) as total_investasi_pma_rp"),
                        DB::raw("SUM(CASE WHEN status_penanaman_modal IN ('PMDN','PMA') THEN 1 ELSE 0 END) as total_proyek"),
                        DB::raw("SUM(investasi_rp_juta) as total_investasi_rp_all")
                    )
                    ->groupBy('kabupaten_kota')
                    ->get();
                $chartData2 = [$dataTriwulan2->sum('total_investasi_rp_all')];
            }
            $chartLabels = ["$periode1 $tahun1", "$periode2 $tahun2"];
        }
        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.realisasi.partials.tabel_perbandingan2', compact(
                    'dataTriwulan1',
                    'dataTriwulan2',
                    'tahun1',
                    'tahun2',
                    'periode1',
                    'periode2',
                    'jenis',
                    'chartLabels',
                    'chartData1',
                    'chartData2'
                ))->render(),
                'chartLabels' => $chartLabels,
                'chartData1'  => $chartData1,
                'chartData2'  => $chartData2,
            ]);
        }
        return view('user.realisasi.perbandingan2', compact(
            'dataTriwulan1',
            'tahun1',
            'periode1',
            'jenis',
            'tahun2',
            'periode2',
            'dataTriwulan2',
            'chartLabels',
            'chartData1',
            'chartData2'
        ));
    }

    // Download Excel Perbandingan Bagian 1
    public function downloadBagian1(Request $request) {
        $tahun1 = $request->input('tahun1');
        $tahun2 = $request->input('tahun2');
        $jenis  = $request->input('jenis');

        // Ambil data sesuai filter (mirip dengan yang dipakai di view perbandingan)
        $dataTahun1 = DB::table('data_investasi')
            ->where('tahun', $tahun1)
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->get();

        $dataTahun2 = DB::table('data_investasi')
            ->where('tahun', $tahun2)
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->get();

        // Buat PDF dari blade
        $pdf = Pdf::loadView('user.realisasi.partials.tabel_perbandingan1', [
            'dataTahun1' => $dataTahun1,
            'dataTahun2' => $dataTahun2,
            'tahun1' => $tahun1,
            'tahun2' => $tahun2,
            'jenis'  => $jenis,
            'chartLabels' => [], // optional
            'chartData1' => [],
            'chartData2' => [],
        ]);

        return $pdf->download("perbandingan-investasi-{$jenis}-{$tahun1}-{$tahun2}.pdf");
    }

    public function downloadBagian2(Request $request)
    {
        $tahun1   = $request->tahun1;
        $tahun2   = $request->tahun2;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $jenis    = $request->jenis;

        // --- ambil ulang query seperti di perbandingan2 ---
        $dataTriwulan1 = collect();
        $dataTriwulan2 = collect();
        $chartLabels   = [];
        $chartData1    = [];
        $chartData2    = [];

        if ($jenis === 'PMA') {
            $dataTriwulan1 = DB::table('data_investasi')
                ->where('status_penanaman_modal', 'PMA')
                ->where('tahun', $tahun1)
                ->where('periode', $periode1)
                ->select(
                    'kabupaten_kota',
                    'status_penanaman_modal',
                    DB::raw('COUNT(status_penanaman_modal) as proyekpma'),
                    DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu'),
                    DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                )
                ->groupBy('kabupaten_kota','status_penanaman_modal')
                ->get();

            $dataTriwulan2 = DB::table('data_investasi')
                ->where('status_penanaman_modal', 'PMA')
                ->where('tahun', $tahun2)
                ->where('periode', $periode2)
                ->select(
                    'kabupaten_kota',
                    'status_penanaman_modal',
                    DB::raw('COUNT(status_penanaman_modal) as proyekpma'),
                    DB::raw('SUM(investasi_us_ribu) as total_investasi_us_ribu'),
                    DB::raw('SUM(investasi_rp_juta) as total_investasi_rp_juta')
                )
                ->groupBy('kabupaten_kota','status_penanaman_modal')
                ->get();

            $chartData1 = [$dataTriwulan1->sum('total_investasi_rp_juta')];
            $chartData2 = [$dataTriwulan2->sum('total_investasi_rp_juta')];
        }

        // Tambahkan case untuk PMDN & PMA+PMDN seperti di perbandingan2

        $chartLabels = ["$periode1 $tahun1", "$periode2 $tahun2"];

        // --- buat PDF dari partial view ---
        $pdf = Pdf::loadView('user.realisasi.partials.tabel_perbandingan2', [
            'dataTriwulan1' => $dataTriwulan1,
            'dataTriwulan2' => $dataTriwulan2,
            'tahun1'        => $tahun1,
            'tahun2'        => $tahun2,
            'periode1'      => $periode1,
            'periode2'      => $periode2,
            'jenis'         => $jenis,
            'chartLabels'   => $chartLabels,
            'chartData1'    => $chartData1,
            'chartData2'    => $chartData2,
        ]);

        return $pdf->download("perbandingan2-investasi-{$jenis}-{$periode1}{$tahun1}-{$periode2}{$tahun2}.pdf");
    }

     
}