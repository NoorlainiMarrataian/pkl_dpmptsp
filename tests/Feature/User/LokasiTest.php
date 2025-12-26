<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Datainvestasi;

class LokasiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function TL_01()
    {
        $dataPMA_Bjm_T1 = Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Singapura',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 5000,
            'investasi_us_ribu' => 350,
            'jumlah_tki' => 50,
        ]);

        $dataPMDN_Bjb_T1 = Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMDN',
            'regional' => 'Domestik',
            'negara' => 'Indonesia',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Industri',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarbaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 3000,
            'investasi_us_ribu' => 200,
            'jumlah_tki' => 30,
        ]);

        $dataPMA_Bjm_T2 = Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Malaysia',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Perdagangan',
            'deskripsi_kbli_2digit' => '(47-2015) Perdagangan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 2000,
            'investasi_us_ribu' => 140,
            'jumlah_tki' => 20,
        ]);

        $response = $this->get(route('realisasi.lokasi'));
        $response->assertStatus(200);
        $response->assertSee('LOKASI');

        $filterResponse1 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2023,
            'jenis' => 'PMA',
            'triwulan' => 'Triwulan 1'
        ]));

        $filterResponse1->assertStatus(200);

        $filterResponse1->assertViewHas('dataLokasi', function ($data) {
            if (!$data || $data->isEmpty()) {
                return false;
            }

            $hasBanjarmasin = $data->contains('kabupaten_kota', 'Banjarmasin');
            $hasBanjarbaru = $data->contains('kabupaten_kota', 'Banjarbaru');

            return $hasBanjarmasin && !$hasBanjarbaru;
        });

        $filterResponse2 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2023,
            'jenis' => 'PMDN',
            'triwulan' => 'Triwulan 1'
        ]));

        $filterResponse2->assertStatus(200);
        $filterResponse2->assertViewHas('dataLokasi', function ($data) {
            if (!$data || $data->isEmpty()) {
                return false;
            }

            $hasBanjarbaru = $data->contains('kabupaten_kota', 'Banjarbaru');
            $hasBanjarmasin = $data->contains('kabupaten_kota', 'Banjarmasin');

            return $hasBanjarbaru && !$hasBanjarmasin;
        });

        $filterResponse3 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2023,
            'jenis' => 'PMA+PMDN',
            'triwulan' => 'Triwulan 1'
        ]));

        $filterResponse3->assertStatus(200);
        $filterResponse3->assertViewHas('dataLokasi', function ($data) {
            if (!$data || $data->isEmpty()) {
                return false;
            }

            $hasBanjarmasin = $data->contains('kabupaten_kota', 'Banjarmasin');
            $hasBanjarbaru = $data->contains('kabupaten_kota', 'Banjarbaru');

            return $hasBanjarmasin && $hasBanjarbaru;
        });

        $filterResponse4 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2023,
            'jenis' => 'PMA',
            'triwulan' => 'Triwulan 2'
        ]));

        $filterResponse4->assertStatus(200);
        $filterResponse4->assertViewHas('dataLokasi', function ($data) use ($dataPMA_Bjm_T2) {
            if (!$data || $data->isEmpty()) {
                return false;
            }

            $hasBanjarmasin = $data->contains('kabupaten_kota', 'Banjarmasin');
            return $hasBanjarmasin && $data->count() > 0;
        });

        $filterResponse5 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2023,
            'jenis' => 'PMA',
            'triwulan' => 'Tahun'
        ]));

        $filterResponse5->assertStatus(200);
        $filterResponse5->assertViewHas('dataLokasi', function ($data) {
            if (!$data || $data->isEmpty()) {
                return false;
            }
            return $data->contains('kabupaten_kota', 'Banjarmasin');
        });
    }

    /** @test */
    public function TL_02()
    {
        Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Singapura',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 5000,
            'investasi_us_ribu' => 350,
            'jumlah_tki' => 50,
        ]);

        $response = $this->get(route('realisasi.lokasi'));
        $response->assertStatus(200);
        $response->assertSee('LOKASI');

        $filterResponse = $this->get(route('realisasi.lokasi', [
            'tahun' => 2025,
            'jenis' => 'PMA',
            'triwulan' => 'Triwulan 1'
        ]));

        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Tidak ada data');
        $filterResponse->assertViewHas('dataLokasi', function ($data) {
            return $data->isEmpty();
        });

        $filterResponse->assertDontSee('Banjarmasin');
        $filterResponse->assertDontSee('Singapura');
        $filterResponse2 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2025,
            'jenis' => 'PMDN',
            'triwulan' => 'Triwulan 2'
        ]));

        $filterResponse2->assertStatus(200);
        $filterResponse2->assertSee('Tidak ada data');
        $filterResponse2->assertViewHas('dataLokasi', function ($data) {
            return $data->isEmpty();
        });

        $filterResponse3 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2025,
            'jenis' => 'PMA+PMDN',
            'triwulan' => 'Triwulan 3'
        ]));

        $filterResponse3->assertStatus(200);
        $filterResponse3->assertSee('Tidak ada data');
        $filterResponse3->assertViewHas('dataLokasi', function ($data) {
            return $data->isEmpty();
        });

        $filterResponse4 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2025,
            'jenis' => 'PMA',
            'triwulan' => 'Tahun'
        ]));

        $filterResponse4->assertStatus(200);
        $filterResponse4->assertSee('Tidak ada data');
        $filterResponse4->assertViewHas('dataLokasi', function ($data) {
            return $data->isEmpty();
        });

        $filterResponse->assertViewHas('chartLabels', function ($labels) {
            return empty($labels) || (is_array($labels) && count($labels) === 0);
        });

        $filterResponse->assertViewHas('chartData', function ($chartData) {
            return empty($chartData) || (is_array($chartData) && count($chartData) === 0);
        });
    }

    /** @test */
    public function TL_03()
    {
        Datainvestasi::create([
            'tahun' => 2024,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Singapura',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 5000,
            'investasi_us_ribu' => 350,
            'jumlah_tki' => 50,
        ]);

        Datainvestasi::create([
            'tahun' => 2024,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMDN',
            'regional' => 'Indonesia',
            'negara' => 'Indonesia',
            'sektor_utama' => 'Primer',
            'nama_sektor' => 'Pertanian',
            'deskripsi_kbli_2digit' => '(01-2020) Pertanian',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarbaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 3000,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 30,
        ]);

        $response = $this->get(route('realisasi.lokasi'));
        $response->assertStatus(200);
        $response->assertSee('LOKASI');

        $filterResponse = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMA',
            'triwulan' => 'Triwulan 1'
        ]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Banjarmasin');
        $filterResponse->assertViewHas('dataLokasi', function ($data) {
            return $data->isNotEmpty();
        });

        $downloadData = [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'John Doe',
            'email_pengunduh' => 'john.doe@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk penelitian dan analisis data investasi',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ];

        $downloadResponse = $this->postJson(route('log_pengunduhan.store'), $downloadData);
        $downloadResponse->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'John Doe',
            'email_pengunduh' => 'john.doe@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk penelitian dan analisis data investasi',
        ]);

        $filterResponse2 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMDN',
            'triwulan' => 'Triwulan 1'
        ]));

        $filterResponse2->assertStatus(200);
        $filterResponse2->assertSee('Banjarbaru');

        $downloadData2 = [
            'kategori_pengunduh' => 'Perusahaan',
            'nama_instansi' => 'PT ABC Indonesia',
            'email_pengunduh' => 'info@abc.co.id',
            'telpon' => '021123456',
            'keperluan' => 'Analisis pasar untuk ekspansi bisnis',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ];

        $downloadResponse2 = $this->postJson(route('log_pengunduhan.store'), $downloadData2);
        $downloadResponse2->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Perusahaan',
            'email_pengunduh' => 'info@abc.co.id',
        ]);

        $filterResponse3 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMA+PMDN',
            'triwulan' => 'Triwulan 1'
        ]));

        $filterResponse3->assertStatus(200);
        $filterResponse3->assertSee('Banjarmasin');
        $filterResponse3->assertSee('Banjarbaru');

        $downloadData3 = [
            'kategori_pengunduh' => 'Lainnya',
            'nama_instansi' => 'Universitas XYZ',
            'email_pengunduh' => 'research@xyz.ac.id',
            'telpon' => '05113579',
            'keperluan' => 'Riset akademik tentang investasi daerah',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ];

        $downloadResponse3 = $this->postJson(route('log_pengunduhan.store'), $downloadData3);
        $downloadResponse3->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Lainnya',
            'email_pengunduh' => 'research@xyz.ac.id',
        ]);

        $this->assertDatabaseCount('log_pengunduhan', 3);
        $this->assertDatabaseHas('log_pengunduhan', ['kategori_pengunduh' => 'Individu']);
        $this->assertDatabaseHas('log_pengunduhan', ['kategori_pengunduh' => 'Perusahaan']);
        $this->assertDatabaseHas('log_pengunduhan', ['kategori_pengunduh' => 'Lainnya']);
    }

    /** @test */
    public function TL_04()
    {
        $dataInvestasi = [
            ['kota' => 'Banjarmasin', 'pma' => 10000, 'pmdn' => 8000],
            ['kota' => 'Banjarbaru', 'pma' => 9000, 'pmdn' => 7500],
            ['kota' => 'Tanah Laut', 'pma' => 8000, 'pmdn' => 6000],
            ['kota' => 'Banjar', 'pma' => 7000, 'pmdn' => 5500],
            ['kota' => 'Barito Kuala', 'pma' => 6000, 'pmdn' => 5000],
            ['kota' => 'Tapin', 'pma' => 3000, 'pmdn' => 2000],
            ['kota' => 'Hulu Sungai Selatan', 'pma' => 2000, 'pmdn' => 1000],
        ];

        foreach ($dataInvestasi as $data) {
            Datainvestasi::create([
                'tahun' => 2024,
                'periode' => 'Triwulan 1',
                'status_penanaman_modal' => 'PMA',
                'regional' => 'Asia',
                'negara' => 'Singapura',
                'sektor_utama' => 'Tersier',
                'nama_sektor' => 'Teknologi',
                'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
                'provinsi' => 'Kalimantan Selatan',
                'kabupaten_kota' => $data['kota'],
                'wilayah_jawa' => 'Luar Jawa',
                'pulau' => 'Kalimantan',
                'investasi_rp_juta' => $data['pma'],
                'investasi_us_ribu' => $data['pma'] / 15,
                'jumlah_tki' => 50,
            ]);

            Datainvestasi::create([
                'tahun' => 2024,
                'periode' => 'Triwulan 1',
                'status_penanaman_modal' => 'PMDN',
                'regional' => 'Indonesia',
                'negara' => 'Indonesia',
                'sektor_utama' => 'Primer',
                'nama_sektor' => 'Pertanian',
                'deskripsi_kbli_2digit' => '(01-2020) Pertanian',
                'provinsi' => 'Kalimantan Selatan',
                'kabupaten_kota' => $data['kota'],
                'wilayah_jawa' => 'Luar Jawa',
                'pulau' => 'Kalimantan',
                'investasi_rp_juta' => $data['pmdn'],
                'investasi_us_ribu' => 0,
                'jumlah_tki' => 30,
            ]);
        }

        $response = $this->get(route('realisasi.lokasi'));
        $response->assertStatus(200);
        $response->assertSee('LOKASI');

        $filterResponse = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2024,
            'triwulan2' => 'Triwulan 1',
            'jenisBagian2' => '5 Realisasi Investasi Terbesar Berdasarkan Kab Kota',
        ]));

        $filterResponse->assertStatus(200);

        $filterResponse->assertViewHas('topPMA', function ($topPMA) {
            if ($topPMA->count() !== 5) {
                dump(['topPMA_count' => $topPMA->count(), 'data' => $topPMA->toArray()]);
                return false;
            }
            
            $previous = PHP_FLOAT_MAX;
            foreach ($topPMA as $item) {
                if ($item->total_investasi < $previous) {
                    $previous = $item->total_investasi;
                } else {
                    dump(['error' => 'Data tidak terurut descending', 'item' => $item]);
                    return false;
                }
            }
            
            return true;
        });

        $filterResponse->assertViewHas('topPMDN', function ($topPMDN) {
            if ($topPMDN->count() !== 5) {
                dump(['topPMDN_count' => $topPMDN->count(), 'data' => $topPMDN->toArray()]);
                return false;
            }
            
            $previous = PHP_FLOAT_MAX;
            foreach ($topPMDN as $item) {
                if ($item->total_investasi < $previous) {
                    $previous = $item->total_investasi;
                } else {
                    dump(['error' => 'Data tidak terurut descending', 'item' => $item]);
                    return false;
                }
            }
            
            return true;
        });

        $filterResponse->assertSee('Banjarmasin');
        $filterResponse->assertSee('Banjarbaru');
        $filterResponse->assertSee('Tanah Laut');
        $filterResponse->assertSee('Banjar');
        $filterResponse->assertSee('Barito Kuala');
        $filterResponse->assertDontSee('Tapin');
        $filterResponse->assertDontSee('Hulu Sungai Selatan');

        $filterResponse2 = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2025,
            'triwulan2' => 'Triwulan 1',
            'jenisBagian2' => '5 Realisasi Investasi Terbesar Berdasarkan Kab Kota',
        ]));

        $filterResponse2->assertStatus(200);
        $filterResponse2->assertViewHas('topPMA', function ($topPMA) {
            return $topPMA->isEmpty();
        });
        $filterResponse2->assertViewHas('topPMDN', function ($topPMDN) {
            return $topPMDN->isEmpty();
        });

        $filterResponse3 = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2024,
            'triwulan2' => 'Tahun',
            'jenisBagian2' => '5 Realisasi Investasi Terbesar Berdasarkan Kab Kota',
        ]));

        $filterResponse3->assertStatus(200);
        $filterResponse3->assertViewHas('topPMA', function ($topPMA) {
            return $topPMA->count() === 5;
        });
        $filterResponse3->assertViewHas('topPMDN', function ($topPMDN) {
            return $topPMDN->count() === 5;
        });

        $filterResponse3->assertSee('Banjarmasin');
        $filterResponse3->assertSee('Banjarbaru');
        $filterResponse3->assertSee('Tanah Laut');
        $filterResponse4 = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2024,
            'triwulan2' => 'Triwulan 2',
            'jenisBagian2' => '5 Realisasi Investasi Terbesar Berdasarkan Kab Kota',
        ]));

        $filterResponse4->assertStatus(200);
        $filterResponse4->assertViewHas('topPMA', function ($topPMA) {
            return $topPMA->isEmpty();
        });
        $filterResponse4->assertViewHas('topPMDN', function ($topPMDN) {
            return $topPMDN->isEmpty();
        });

        $topPMAData = $filterResponse->viewData('topPMA');
        $firstPMA = $topPMAData->first();
        
        $this->assertNotNull($firstPMA);
        $this->assertObjectHasProperty('kabupaten_kota', $firstPMA);
        $this->assertObjectHasProperty('total_investasi', $firstPMA);
        $this->assertObjectHasProperty('status_penanaman_modal', $firstPMA);
        $this->assertEquals('PMA', $firstPMA->status_penanaman_modal);

        $topPMDNData = $filterResponse->viewData('topPMDN');
        $firstPMDN = $topPMDNData->first();
        
        $this->assertNotNull($firstPMDN);
        $this->assertObjectHasProperty('kabupaten_kota', $firstPMDN);
        $this->assertObjectHasProperty('total_investasi', $firstPMDN);
        $this->assertObjectHasProperty('status_penanaman_modal', $firstPMDN);
        $this->assertEquals('PMDN', $firstPMDN->status_penanaman_modal);
        $this->assertEquals('Banjarmasin', $firstPMA->kabupaten_kota);
        $this->assertEquals(10000, $firstPMA->total_investasi);
        $this->assertEquals('Banjarmasin', $firstPMDN->kabupaten_kota);
        $this->assertEquals(8000, $firstPMDN->total_investasi);
    }
    
    /** @test */
    public function TL_05()
    {
        $dataProyek = [
            ['kota' => 'Banjarmasin', 'jumlah_proyek_pma' => 15, 'jumlah_proyek_pmdn' => 12],
            ['kota' => 'Banjarbaru', 'jumlah_proyek_pma' => 12, 'jumlah_proyek_pmdn' => 10],
            ['kota' => 'Tanah Laut', 'jumlah_proyek_pma' => 10, 'jumlah_proyek_pmdn' => 9],
            ['kota' => 'Banjar', 'jumlah_proyek_pma' => 8, 'jumlah_proyek_pmdn' => 7],
            ['kota' => 'Barito Kuala', 'jumlah_proyek_pma' => 6, 'jumlah_proyek_pmdn' => 5],
            ['kota' => 'Tapin', 'jumlah_proyek_pma' => 3, 'jumlah_proyek_pmdn' => 2],
            ['kota' => 'Hulu Sungai Selatan', 'jumlah_proyek_pma' => 2, 'jumlah_proyek_pmdn' => 1],
        ];

        foreach ($dataProyek as $data) {
            for ($i = 0; $i < $data['jumlah_proyek_pma']; $i++) {
                Datainvestasi::create([
                    'tahun' => 2024,
                    'periode' => 'Triwulan 1',
                    'status_penanaman_modal' => 'PMA',
                    'regional' => 'Asia',
                    'negara' => 'Singapura',
                    'sektor_utama' => 'Tersier',
                    'nama_sektor' => 'Teknologi',
                    'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
                    'provinsi' => 'Kalimantan Selatan',
                    'kabupaten_kota' => $data['kota'],
                    'wilayah_jawa' => 'Luar Jawa',
                    'pulau' => 'Kalimantan',
                    'investasi_rp_juta' => 1000 + ($i * 100),
                    'investasi_us_ribu' => 70 + ($i * 5),
                    'jumlah_tki' => 10 + $i,
                ]);
            }

            for ($i = 0; $i < $data['jumlah_proyek_pmdn']; $i++) {
                Datainvestasi::create([
                    'tahun' => 2024,
                    'periode' => 'Triwulan 1',
                    'status_penanaman_modal' => 'PMDN',
                    'regional' => 'Indonesia',
                    'negara' => 'Indonesia',
                    'sektor_utama' => 'Primer',
                    'nama_sektor' => 'Pertanian',
                    'deskripsi_kbli_2digit' => '(01-2020) Pertanian',
                    'provinsi' => 'Kalimantan Selatan',
                    'kabupaten_kota' => $data['kota'],
                    'wilayah_jawa' => 'Luar Jawa',
                    'pulau' => 'Kalimantan',
                    'investasi_rp_juta' => 800 + ($i * 80),
                    'investasi_us_ribu' => 0,
                    'jumlah_tki' => 8 + $i,
                ]);
            }
        }

        $response = $this->get(route('realisasi.lokasi'));
        $response->assertStatus(200);
        $response->assertSee('LOKASI');

        $filterResponse = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2024,
            'triwulan2' => 'Triwulan 1',
            'jenisBagian2' => '5 Proyek Terbesar Berdasarkan Kab Kota',
        ]));

        $filterResponse->assertStatus(200);
        $filterResponse->assertViewHas('topPMA', function ($topPMA) {
            if ($topPMA->count() !== 5) {
                dump(['topPMA_count' => $topPMA->count(), 'data' => $topPMA->toArray()]);
                return false;
            }

            $previous = PHP_INT_MAX;
            foreach ($topPMA as $item) {
                $jumlahProyek = $item->proyekpma ?? 0;
                if ($jumlahProyek <= $previous) {
                    $previous = $jumlahProyek;
                } else {
                    dump(['error' => 'Data tidak terurut descending', 'item' => $item]);
                    return false;
                }
            }
            
            return true;
        });

        $filterResponse->assertViewHas('topPMDN', function ($topPMDN) {
            if ($topPMDN->count() !== 5) {
                dump(['topPMDN_count' => $topPMDN->count(), 'data' => $topPMDN->toArray()]);
                return false;
            }
            
            $previous = PHP_INT_MAX;
            foreach ($topPMDN as $item) {
                $jumlahProyek = $item->proyekpmdn ?? 0;
                if ($jumlahProyek <= $previous) {
                    $previous = $jumlahProyek;
                } else {
                    dump(['error' => 'Data tidak terurut descending', 'item' => $item]);
                    return false;
                }
            }
            
            return true;
        });

        $filterResponse->assertSee('Banjarmasin');
        $filterResponse->assertSee('Banjarbaru');
        $filterResponse->assertSee('Tanah Laut');
        $filterResponse->assertSee('Banjar');
        $filterResponse->assertSee('Barito Kuala');
        $filterResponse->assertDontSee('Tapin');
        $filterResponse->assertDontSee('Hulu Sungai Selatan');

        $filterResponse2 = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2025,
            'triwulan2' => 'Triwulan 1',
            'jenisBagian2' => '5 Proyek Terbesar Berdasarkan Kab Kota',
        ]));
        $filterResponse2->assertStatus(200);
        $filterResponse2->assertViewHas('topPMA', function ($topPMA) {
            return $topPMA->isEmpty();
        });
        $filterResponse2->assertViewHas('topPMDN', function ($topPMDN) {
            return $topPMDN->isEmpty();
        });
        $filterResponse3 = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2024,
            'triwulan2' => 'Tahun',
            'jenisBagian2' => '5 Proyek Terbesar Berdasarkan Kab Kota',
        ]));
        $filterResponse3->assertStatus(200);
        $filterResponse3->assertViewHas('topPMA', function ($topPMA) {
            return $topPMA->count() === 5;
        });
        $filterResponse3->assertViewHas('topPMDN', function ($topPMDN) {
            return $topPMDN->count() === 5;
        });
        $filterResponse3->assertSee('Banjarmasin');
        $filterResponse3->assertSee('Banjarbaru');
        $filterResponse3->assertSee('Tanah Laut');
        $filterResponse4 = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2024,
            'triwulan2' => 'Triwulan 2',
            'jenisBagian2' => '5 Proyek Terbesar Berdasarkan Kab Kota',
        ]));
        $filterResponse4->assertStatus(200);
        $filterResponse4->assertViewHas('topPMA', function ($topPMA) {
            return $topPMA->isEmpty();
        });
        $filterResponse4->assertViewHas('topPMDN', function ($topPMDN) {
            return $topPMDN->isEmpty();
        });

        $topPMAData = $filterResponse->viewData('topPMA');
        $firstPMA = $topPMAData->first();
        
        $this->assertNotNull($firstPMA);
        $this->assertObjectHasProperty('kabupaten_kota', $firstPMA);
        $this->assertObjectHasProperty('proyekpma', $firstPMA);
        $this->assertObjectHasProperty('status_penanaman_modal', $firstPMA);
        $this->assertEquals('PMA', $firstPMA->status_penanaman_modal);

        $topPMDNData = $filterResponse->viewData('topPMDN');
        $firstPMDN = $topPMDNData->first();
        
        $this->assertNotNull($firstPMDN);
        $this->assertObjectHasProperty('kabupaten_kota', $firstPMDN);
        $this->assertObjectHasProperty('proyekpmdn', $firstPMDN);
        $this->assertObjectHasProperty('status_penanaman_modal', $firstPMDN);
        $this->assertEquals('PMDN', $firstPMDN->status_penanaman_modal);
        $this->assertEquals('Banjarmasin', $firstPMA->kabupaten_kota);
        $this->assertEquals(15, $firstPMA->proyekpma);
        $this->assertEquals('Banjarmasin', $firstPMDN->kabupaten_kota);
        $this->assertEquals(12, $firstPMDN->proyekpmdn);

        $topPMAArray = $topPMAData->pluck('proyekpma', 'kabupaten_kota')->toArray();
        $this->assertEquals(15, $topPMAArray['Banjarmasin']);
        $this->assertEquals(12, $topPMAArray['Banjarbaru']);
        $this->assertEquals(10, $topPMAArray['Tanah Laut']);
        $this->assertEquals(8, $topPMAArray['Banjar']);
        $this->assertEquals(6, $topPMAArray['Barito Kuala']);

        $topPMDNArray = $topPMDNData->pluck('proyekpmdn', 'kabupaten_kota')->toArray();
        $this->assertEquals(12, $topPMDNArray['Banjarmasin']);
        $this->assertEquals(10, $topPMDNArray['Banjarbaru']);
        $this->assertEquals(9, $topPMDNArray['Tanah Laut']);
        $this->assertEquals(7, $topPMDNArray['Banjar']);
        $this->assertEquals(5, $topPMDNArray['Barito Kuala']);
        $this->assertArrayNotHasKey('Tapin', $topPMAArray);
        $this->assertArrayNotHasKey('Hulu Sungai Selatan', $topPMAArray);
        $this->assertArrayNotHasKey('Tapin', $topPMDNArray);
        $this->assertArrayNotHasKey('Hulu Sungai Selatan', $topPMDNArray);
    }

    /** @test */
    public function TL_06()
    {
        $dataSektorPMA = [
            ['sektor' => 'Teknologi Informasi', 'investasi' => 15000, 'jumlah_proyek' => 8],
            ['sektor' => 'Pertambangan', 'investasi' => 12000, 'jumlah_proyek' => 5],
            ['sektor' => 'Manufaktur', 'investasi' => 10000, 'jumlah_proyek' => 6],
            ['sektor' => 'Pariwisata', 'investasi' => 8000, 'jumlah_proyek' => 4],
        ];

        $dataSektorPMDN = [
            ['sektor' => 'Pertanian', 'investasi' => 9000, 'jumlah_proyek' => 7],
            ['sektor' => 'Perikanan', 'investasi' => 7000, 'jumlah_proyek' => 5],
            ['sektor' => 'Perdagangan', 'investasi' => 6000, 'jumlah_proyek' => 4],
            ['sektor' => 'Konstruksi', 'investasi' => 5000, 'jumlah_proyek' => 3],
        ];

        foreach ($dataSektorPMA as $data) {
            for ($i = 0; $i < $data['jumlah_proyek']; $i++) {
                Datainvestasi::create([
                    'tahun' => 2024,
                    'periode' => 'Triwulan 1',
                    'status_penanaman_modal' => 'PMA',
                    'regional' => 'Asia',
                    'negara' => 'Singapura',
                    'sektor_utama' => 'Tersier',
                    'nama_sektor' => $data['sektor'],
                    'deskripsi_kbli_2digit' => "(62-2020) {$data['sektor']}",
                    'provinsi' => 'Kalimantan Selatan',
                    'kabupaten_kota' => 'Banjarmasin',
                    'wilayah_jawa' => 'Luar Jawa',
                    'pulau' => 'Kalimantan',
                    'investasi_rp_juta' => $data['investasi'] / $data['jumlah_proyek'],
                    'investasi_us_ribu' => ($data['investasi'] / $data['jumlah_proyek']) / 15,
                    'jumlah_tki' => 10 + $i,
                ]);
            }
        }

        foreach ($dataSektorPMDN as $data) {
            for ($i = 0; $i < $data['jumlah_proyek']; $i++) {
                Datainvestasi::create([
                    'tahun' => 2024,
                    'periode' => 'Triwulan 1',
                    'status_penanaman_modal' => 'PMDN',
                    'regional' => 'Indonesia',
                    'negara' => 'Indonesia',
                    'sektor_utama' => 'Primer',
                    'nama_sektor' => $data['sektor'],
                    'deskripsi_kbli_2digit' => "(01-2020) {$data['sektor']}",
                    'provinsi' => 'Kalimantan Selatan',
                    'kabupaten_kota' => 'Banjarbaru',
                    'wilayah_jawa' => 'Luar Jawa',
                    'pulau' => 'Kalimantan',
                    'investasi_rp_juta' => $data['investasi'] / $data['jumlah_proyek'],
                    'investasi_us_ribu' => 0,
                    'jumlah_tki' => 8 + $i,
                ]);
            }
        }

        $response = $this->get(route('realisasi.lokasi'));
        $response->assertStatus(200);
        $response->assertSee('LOKASI');
        $filterResponse = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2024,
            'triwulan2' => 'Triwulan 1',
            'jenisBagian2' => 'sektor',
        ]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertViewHas('sektor', function ($sektor) {
            if ($sektor->isEmpty()) {
                dump(['error' => 'Data sektor kosong', 'data' => $sektor]);
                return false;
            }
            return true;
        });
        $filterResponse->assertSee('Teknologi Informasi');
        $filterResponse->assertSee('Pertambangan');
        $filterResponse->assertSee('Manufaktur');
        $filterResponse->assertSee('Pariwisata');
        $filterResponse->assertSee('Pertanian');
        $filterResponse->assertSee('Perikanan');
        $filterResponse->assertSee('Perdagangan');
        $filterResponse->assertSee('Konstruksi');

        $sektorData = $filterResponse->viewData('sektor');
        $this->assertNotNull($sektorData);
        $this->assertGreaterThan(0, $sektorData->count());

        $firstSektor = $sektorData->first();
        $this->assertObjectHasProperty('nama_sektor', $firstSektor);
        $this->assertObjectHasProperty('proyek_pma', $firstSektor);
        $this->assertObjectHasProperty('proyek_pmdn', $firstSektor);
        $this->assertObjectHasProperty('total_investasi_rp_pma', $firstSektor);
        $this->assertObjectHasProperty('total_investasi_rp_pmdn', $firstSektor);
        $this->assertObjectHasProperty('total_investasi_us_pma', $firstSektor);
        $this->assertObjectHasProperty('total_proyek', $firstSektor);
        $this->assertObjectHasProperty('total_investasi_rp_all', $firstSektor);

        $sektorTI = $sektorData->firstWhere('nama_sektor', 'Teknologi Informasi');
        if ($sektorTI) {
            $this->assertEquals(8, $sektorTI->proyek_pma);
            $this->assertEquals(0, $sektorTI->proyek_pmdn);
            $this->assertEqualsWithDelta(15000, $sektorTI->total_investasi_rp_pma, 10);
        }

        $sektorPertanian = $sektorData->firstWhere('nama_sektor', 'Pertanian');
        if ($sektorPertanian) {
            $this->assertEquals(0, $sektorPertanian->proyek_pma);
            $this->assertEquals(7, $sektorPertanian->proyek_pmdn);
            $this->assertEqualsWithDelta(9000, $sektorPertanian->total_investasi_rp_pmdn, 10);
        }

        $filterResponse2 = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2025,
            'triwulan2' => 'Triwulan 1',
            'jenisBagian2' => 'sektor',
        ]));
        $filterResponse2->assertStatus(200);
        $filterResponse2->assertViewHas('sektor', function ($sektor) {
            return $sektor->isEmpty();
        });
        $filterResponse3 = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2024,
            'triwulan2' => 'Tahun',
            'jenisBagian2' => 'sektor',
        ]));
        $filterResponse3->assertStatus(200);
        $filterResponse3->assertViewHas('sektor', function ($sektor) {
            return $sektor->isNotEmpty();
        });
        $filterResponse3->assertSee('Teknologi Informasi');
        $filterResponse3->assertSee('Pertanian');
        $filterResponse4 = $this->get(route('realisasi.lokasi', [
            'tahun2' => 2024,
            'triwulan2' => 'Triwulan 2',
            'jenisBagian2' => 'sektor',
        ]));
        $filterResponse4->assertStatus(200);
        $filterResponse4->assertViewHas('sektor', function ($sektor) {
            return $sektor->isEmpty();
        });

        $sektorData = $filterResponse->viewData('sektor');
        
        $totalProyekPMA = $sektorData->sum('proyek_pma');
        $this->assertEquals(23, $totalProyekPMA);

        $totalProyekPMDN = $sektorData->sum('proyek_pmdn');
        $this->assertEquals(19, $totalProyekPMDN);

        $totalInvestasiPMA = $sektorData->sum('total_investasi_rp_pma');
        $this->assertEqualsWithDelta(45000, $totalInvestasiPMA, 50);

        $totalInvestasiPMDN = $sektorData->sum('total_investasi_rp_pmdn');
        $this->assertEqualsWithDelta(27000, $totalInvestasiPMDN, 50);

        foreach ($sektorData as $sektor) {
            $expectedTotal = $sektor->proyek_pma + $sektor->proyek_pmdn;
            $this->assertEquals($expectedTotal, $sektor->total_proyek, 
                "Total proyek tidak sesuai untuk sektor {$sektor->nama_sektor}");
        }

        foreach ($sektorData as $sektor) {
            $expectedTotal = $sektor->total_investasi_rp_pma + $sektor->total_investasi_rp_pmdn;
            $this->assertEqualsWithDelta($expectedTotal, $sektor->total_investasi_rp_all, 1,
                "Total investasi tidak sesuai untuk sektor {$sektor->nama_sektor}");
        }

        $sektorNames = $sektorData->pluck('nama_sektor')->toArray();
        $sortedNames = $sektorData->pluck('nama_sektor')->sort()->values()->toArray();
        $this->assertEquals($sortedNames, $sektorNames, 
            'Data sektor harus terurut berdasarkan nama_sektor');

        $uniqueSektors = $sektorData->pluck('nama_sektor')->unique();
        $this->assertEquals($sektorData->count(), $uniqueSektors->count(),
            'Tidak boleh ada duplikasi sektor');

        $allSektorNames = array_merge(
            array_column($dataSektorPMA, 'sektor'),
            array_column($dataSektorPMDN, 'sektor')
        );
        
        foreach ($allSektorNames as $sektorName) {
            $this->assertNotNull(
                $sektorData->firstWhere('nama_sektor', $sektorName),
                "Sektor {$sektorName} harus ada dalam hasil"
            );
        }
    }

    /** @test */
    public function TL_07()
    {
        Datainvestasi::create([
            'tahun' => 2024,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Singapura',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kota Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 5000,
            'investasi_us_ribu' => 350,
            'jumlah_tki' => 50,
        ]);

        Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Malaysia',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Industri',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Tanah Laut',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 3000,
            'investasi_us_ribu' => 200,
            'jumlah_tki' => 30,
        ]);

        $response = $this->get(route('realisasi.lokasi'));
        $response->assertStatus(200);
        $response->assertSee('LOKASI');

        $filter2024 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMA',
            'triwulan' => 'Triwulan 1'
        ]));
        $filter2024->assertStatus(200);
        $filter2024->assertSee('Kota Banjarmasin');
        $filter2024->assertDontSee('Kabupaten Tanah Laut');
        $filter2023 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2023,
            'jenis' => 'PMA',
            'triwulan' => 'Triwulan 1'
        ]));
        $filter2023->assertStatus(200);
        $filter2023->assertSee('Kabupaten Tanah Laut');
        $filter2023->assertDontSee('Kota Banjarmasin');

        $filterPMDN = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMDN',
            'triwulan' => 'Triwulan 1'
        ]));
        $filterPMDN->assertStatus(200);
        $filterPMDN->assertDontSee('Kota Banjarmasin');
        $filterPMDN->assertDontSee('Kabupaten Tanah Laut');

        $filterGabungan = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMA+PMDN',
            'triwulan' => 'Triwulan 1'
        ]));

        $filterGabungan->assertStatus(200);
        $filterGabungan->assertSee('Kota Banjarmasin');
        $filterGabungan->assertDontSee('Kabupaten Tanah Laut');

        $filterTahunPenuh = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMA',
            'triwulan' => 'Tahun'
        ]));

        $filterTahunPenuh->assertStatus(200);
        $filterTahunPenuh->assertSee('Kota Banjarmasin');

        $this->assertNotEquals(
            $filter2024->getContent(),
            $filter2023->getContent(),
            'Response untuk tahun berbeda harus berbeda'
        );
    }

    /** @test */
    public function TL_08()
    {
        Datainvestasi::create([
            'tahun' => 2024,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Singapura',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kota Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 5000,
            'investasi_us_ribu' => 350,
            'jumlah_tki' => 50,
        ]);

        $response = $this->get(route('realisasi.lokasi'));
        $response->assertStatus(200);
        $response->assertSee('LOKASI');

        $filterResponse = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'triwulan' => 'Triwulan 1'
        ]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertDontSee('Kota Banjarmasin');

        $filterResponse2 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
        ]));
        $filterResponse2->assertStatus(200);
        $filterResponse2->assertDontSee('Kota Banjarmasin');

        $filterResponse3 = $this->get(route('realisasi.lokasi', [
            'triwulan' => 'Triwulan 1'
        ]));
        $filterResponse3->assertStatus(200);
        $filterResponse3->assertDontSee('Kota Banjarmasin');

        $filterResponse4 = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'triwulan' => 'Tahun'
        ]));
        $filterResponse4->assertStatus(200);
        $filterResponse4->assertDontSee('Kota Banjarmasin');

        $validFilterPMA = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMA',
            'triwulan' => 'Triwulan 1'
        ]));
        $validFilterPMA->assertStatus(200);
        $validFilterPMA->assertSee('Kota Banjarmasin');

        $validFilterPMDN = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMDN',
            'triwulan' => 'Triwulan 1'
        ]));
        $validFilterPMDN->assertStatus(200);
        $validFilterPMDN->assertDontSee('Kota Banjarmasin');

        $validFilterGabungan = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMA+PMDN',
            'triwulan' => 'Triwulan 1'
        ]));
        $validFilterGabungan->assertStatus(200);
        $validFilterGabungan->assertSee('Kota Banjarmasin');

        $this->assertNotEquals(
            $filterResponse->getContent(),
            $validFilterPMA->getContent(),
            'Response tanpa jenis harus berbeda dengan response dengan jenis PMA'
        );
    }

    /** @test */
    public function TL_09()
    {
        Datainvestasi::create([
            'tahun' => 2024,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Singapura',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kota Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 5000,
            'investasi_us_ribu' => 350,
            'jumlah_tki' => 50,
        ]);

        $response = $this->get(route('realisasi.lokasi'));
        $response->assertStatus(200);
        $response->assertSee('LOKASI');

        $filterResponse = $this->get(route('realisasi.lokasi', [
            'tahun' => 2024,
            'jenis' => 'PMA',
            'triwulan' => 'Triwulan 1'
        ]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Kota Banjarmasin');
        $filterResponse->assertSee('id="openPopupLokasi"', false);
        $filterResponse->assertSee('btn-download', false);
        $filterResponse->assertSee('id="popupForm"', false);
        $filterResponse->assertSee('Data Diri');
        $filterResponse->assertSee('Silahkan isi formulir untuk mengunduh file ini');

        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => '',
            'nama_instansi' => '',
            'email_pengunduh' => '',
            'telpon' => '',
            'keperluan' => ''
        ]);
        $downloadResponse1->assertStatus(302);
        $downloadResponse1->assertSessionHasErrors([
            'kategori_pengunduh',
            'nama_instansi',
            'email_pengunduh',
            'telpon',
            'keperluan'
        ]);

        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => '',
            'email_pengunduh' => '',
            'telpon' => '',
            'keperluan' => ''
        ]);
        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors([
            'nama_instansi',
            'email_pengunduh',
            'telpon',
            'keperluan'
        ]);

        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'John Doe',
            'email_pengunduh' => 'john@example.com',
            'telpon' => '',
            'keperluan' => ''
        ]);
        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors([
            'telpon',
            'keperluan'
        ]);

        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'John Doe',
            'email_pengunduh' => 'john@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk penelitian'
        ]);

        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors([
            'persetujuan_tanggung_jawab',
            'persetujuan_dpmptsp'
        ]);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $validDownloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'John Doe',
            'email_pengunduh' => 'john@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk penelitian akademik',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1'
        ]);
        $validDownloadResponse->assertStatus(200);
        $validDownloadResponse->assertJson(['success' => true]);

        $this->assertDatabaseCount('log_pengunduhan', 1);
        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'John Doe',
            'email_pengunduh' => 'john@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk penelitian akademik'
        ]);
    }
}