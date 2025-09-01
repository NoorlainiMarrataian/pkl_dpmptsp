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
        // ================================        // Bagian 1: Perbandingan Antar Tahun        // ================================
        if ($request->filled(['tahun_awal','tahun_akhir'])) {
            // === Grafik (semua tahun dalam range) ===
            $dataPerbandingan = DB::table('data_investasi')
                ->select(
                    'tahun',
                    DB::raw('SUM(investasi_rp_juta) as investasi_rp_juta')
                )
                ->whereBetween('tahun', [$request->tahun_awal, $request->tahun_akhir])
                ->groupBy('tahun')
                ->orderBy('tahun','asc')
                ->get();

            $perbandinganLabels = $dataPerbandingan->pluck('tahun');
            $perbandinganData   = $dataPerbandingan->pluck('investasi_rp_juta');

            // === Detail per kabupaten/kota sesuai jenis ===
            if ($request->jenis == 'PMA') {
                $rows = DB::table('data_investasi')
                    ->select(
                        'tahun',
                        'kabupaten_kota',
                        'status_penanaman_modal',
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
                        'tahun',
                        'kabupaten_kota',
                        'status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as total_status'),
                        DB::raw('SUM(investasi_rp_juta) as total_rp')
                    )
                    ->whereBetween('tahun', [$request->tahun_awal, $request->tahun_akhir])
                    ->where('status_penanaman_modal', 'PMDN')
                    ->groupBy('tahun','kabupaten_kota','status_penanaman_modal')
                    ->orderBy('kabupaten_kota','asc')
                    ->get()
                    ->groupBy('tahun');

            } elseif ($request->jenis == 'PMA+PMDN') {
                $rowsRaw = DB::table('data_investasi')
                    ->select(
                        'tahun',
                        'kabupaten_kota',
                        'status_penanaman_modal',
                        DB::raw('COUNT(status_penanaman_modal) as total_status'),
                        DB::raw('SUM(investasi_us_ribu) as total_usd'),
                        DB::raw('SUM(investasi_rp_juta) as total_rp')
                    )
                    ->whereBetween('tahun', [$request->tahun_awal, $request->tahun_akhir])
                    ->groupBy('tahun','kabupaten_kota','status_penanaman_modal')
                    ->orderBy('kabupaten_kota','asc')
                    ->get()
                    ->groupBy('tahun');

                // pisahkan jadi 3 bagian (PMA, PMDN, Gabungan)
                $rows = $rowsRaw->map(function($group) {
                    return [
                        'PMA'  => $group->where('status_penanaman_modal','PMA'),
                        'PMDN' => $group->where('status_penanaman_modal','PMDN'),
                        'ALL'  => $group
                        ->groupBy('kabupaten_kota')
                        ->map(function($byKab) {
                            return (object)[
                                'kabupaten_kota' => $byKab->first()->kabupaten_kota,
                                'total_status'   => $byKab->sum('total_status'),
                                'total_usd'      => $byKab->sum('total_usd'),
                                'total_rp'       => $byKab->sum('total_rp'),
                            ];
                        })
                        ->values()

                    ];
                });

            } else {
                $rows = collect();
            }

        } else {
            $dataPerbandingan   = collect();
            $perbandinganLabels = [];
            $perbandinganData   = [];
            $rows               = collect();
        }

    // ================================    // Bagian 2: Perbandingan Tahun + Periode    // ================================
    if ($request->filled(['tahun_awal4','periode_awal4','tahun_akhir4','periode_akhir4'])) {

        $periodeOrder = ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'];

        if ($request->jenis_investasi == 'PMA+PMDN') {
            // 🔹 Ambil semua data dulu
            $rowsRaw = DB::table('data_investasi')
                ->select(
                    'tahun',
                    'periode',
                    'kabupaten_kota',
                    'status_penanaman_modal',
                    DB::raw('COUNT(status_penanaman_modal) as total_status'),
                    DB::raw('SUM(investasi_rp_juta) as total_rp'),
                    DB::raw('SUM(investasi_us_ribu) as total_usd')
                )
                ->where(function($q) use ($request, $periodeOrder) {
                    $periodeAwalIndex  = array_search($request->periode_awal4, $periodeOrder);
                    $periodeAkhirIndex = array_search($request->periode_akhir4, $periodeOrder);

                    $q->where(function($sub) use ($request, $periodeAwalIndex, $periodeOrder) {
                        $sub->where('tahun', '>', $request->tahun_awal4)
                            ->orWhere(function($sub2) use ($request, $periodeAwalIndex, $periodeOrder) {
                                $sub2->where('tahun', $request->tahun_awal4)
                                    ->whereIn('periode', array_slice($periodeOrder, $periodeAwalIndex));
                            });
                    });

                    $q->where(function($sub) use ($request, $periodeAkhirIndex, $periodeOrder) {
                        $sub->where('tahun', '<', $request->tahun_akhir4)
                            ->orWhere(function($sub2) use ($request, $periodeAkhirIndex, $periodeOrder) {
                                $sub2->where('tahun', $request->tahun_akhir4)
                                    ->whereIn('periode', array_slice($periodeOrder, 0, $periodeAkhirIndex+1));
                            });
                    });
                })
                ->groupBy('tahun', 'periode', 'kabupaten_kota', 'status_penanaman_modal')
                ->orderBy('tahun','asc')
                ->orderBy('periode','asc')
                ->get()
                ->groupBy('tahun');

            // 🔹 Pisahkan PMA, PMDN, ALL
            $dataPerbandinganPeriodeByTahun = $rowsRaw->map(function($group) {
                $pma  = $group->where('status_penanaman_modal','PMA')->values();
                $pmdn = $group->where('status_penanaman_modal','PMDN')->values();

                // Gabungan per kabupaten_kota & periode
                $all = $group
                    ->groupBy(fn($row) => $row->kabupaten_kota.'-'.$row->periode)
                    ->map(function($byKey) {
                        return (object)[
                            'kabupaten_kota' => $byKey->first()->kabupaten_kota,
                            'periode'        => $byKey->first()->periode,
                            'total_status'   => $byKey->sum('total_status'),
                            'total_usd'      => $byKey->sum('total_usd'),
                            'total_rp'       => $byKey->sum('total_rp'),
                        ];
                    })
                    ->values();

                return [
                    'PMA'  => $pma,
                    'PMDN' => $pmdn,
                    'ALL'  => $all
                ];
            });

            // Untuk grafik → gabungan total Rp
            $dataPerbandinganPeriode = $rowsRaw->flatten();

        } else {
            // 🔹 Jika hanya PMA atau hanya PMDN
            $dataPerbandinganPeriode = DB::table('data_investasi')
                ->select(
                    'tahun',
                    'periode',
                    'kabupaten_kota',
                    'status_penanaman_modal',
                    DB::raw('COUNT(status_penanaman_modal) as total_status'),
                    DB::raw('SUM(investasi_rp_juta) as total_rp'),
                    DB::raw('SUM(investasi_us_ribu) as total_usd')
                )
                ->where(function($q) use ($request, $periodeOrder) {
                    $periodeAwalIndex  = array_search($request->periode_awal4, $periodeOrder);
                    $periodeAkhirIndex = array_search($request->periode_akhir4, $periodeOrder);

                    $q->where(function($sub) use ($request, $periodeAwalIndex, $periodeOrder) {
                        $sub->where('tahun', '>', $request->tahun_awal4)
                            ->orWhere(function($sub2) use ($request, $periodeAwalIndex, $periodeOrder) {
                                $sub2->where('tahun', $request->tahun_awal4)
                                    ->whereIn('periode', array_slice($periodeOrder, $periodeAwalIndex));
                            });
                    });

                    $q->where(function($sub) use ($request, $periodeAkhirIndex, $periodeOrder) {
                        $sub->where('tahun', '<', $request->tahun_akhir4)
                            ->orWhere(function($sub2) use ($request, $periodeAkhirIndex, $periodeOrder) {
                                $sub2->where('tahun', $request->tahun_akhir4)
                                    ->whereIn('periode', array_slice($periodeOrder, 0, $periodeAkhirIndex+1));
                            });
                    });
                })
                ->where('status_penanaman_modal', $request->jenis_investasi)
                ->groupBy('tahun', 'periode', 'kabupaten_kota', 'status_penanaman_modal')
                ->orderBy('tahun','asc')
                ->orderBy('periode','asc')
                ->get();

            $dataPerbandinganPeriodeByTahun = $dataPerbandinganPeriode->groupBy('tahun');
        }

        // ✅ data chart
        $perbandinganPeriodeLabels = $dataPerbandinganPeriode->map(fn($d) => $d->tahun.' '.$d->periode);
        $perbandinganPeriodeData   = $dataPerbandinganPeriode->pluck('total_rp');

        } else {
            $dataPerbandinganPeriode        = collect();
            $perbandinganPeriodeLabels      = [];
            $perbandinganPeriodeData        = [];
            $dataPerbandinganPeriodeByTahun = collect();
        }

        return view('user.realisasi.perbandingan', compact( 
            'dataPerbandingan', 
            'perbandinganLabels', 
            'perbandinganData', 
            'dataPerbandinganPeriode', 
            'perbandinganPeriodeLabels', 
            'perbandinganPeriodeData', 
            'dataPerbandinganPeriodeByTahun', 
            'rows' 
            ) + [ 
                'tahun_awal' => $request->tahun_awal, 
                'tahun_akhir' => $request->tahun_akhir, 
                'tahun_awal4' => $request->tahun_awal4, 
                'tahun_akhir4' => $request->tahun_akhir4, 
            ]
        ); 
    }
}