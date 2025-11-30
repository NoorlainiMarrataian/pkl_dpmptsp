<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Datainvestasi;

class PerbandinganTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function PD_01()
    {
        Datainvestasi::create([
            'tahun' => 2022,
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
            'tahun' => 2023,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Industri',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarbaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 8000,
            'investasi_us_ribu' => 550,
            'jumlah_tki' => 80,
        ]);

        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMDN',
            'regional' => 'Indonesia',
            'negara' => 'Indonesia',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Manufaktur',
            'deskripsi_kbli_2digit' => '(15-2020) Manufaktur Logam',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 3000,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 30,
        ]);

        Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 2',
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
            'investasi_rp_juta' => 4500,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 45,
        ]);

        $response = $this->get(route('realisasi.perbandingan'));
        $response->assertStatus(200);
        $response->assertSee('PERBANDINGAN REALISASI INVESTASI');
        $response->assertSee('Perbandingan Pertahun');
        $response->assertSee('Pilih Jenis');
        $response->assertSee('Tahun 1');
        $response->assertSee('Tahun 2');
        $response->assertSee('Tampilkan');

        $responsePMA = $this->get(route('realisasi.perbandingan', [
            'jenis' => 'PMA',
            'tahun1' => 2022,
            'tahun2' => 2023
        ]), [
            'X-Requested-With' => 'XMLHttpRequest' 
        ]);

        $responsePMA->assertStatus(200);
        $responsePMA->assertJsonStructure([
            'html',
            'chartLabels',
            'chartData1',
            'chartData2'
        ]);

        $jsonPMA = $responsePMA->json();
        $this->assertContains('2022', $jsonPMA['chartLabels']);
        $this->assertContains('2023', $jsonPMA['chartLabels']);
        $this->assertNotEmpty($jsonPMA['chartData1']);
        $this->assertNotEmpty($jsonPMA['chartData2']);
        $this->assertStringContainsString('PMA', $jsonPMA['html']);
        $this->assertStringContainsString('Banjarmasin', $jsonPMA['html']);
        $this->assertStringContainsString('Banjarbaru', $jsonPMA['html']);

        $responsePMDN = $this->get(route('realisasi.perbandingan', [
            'jenis' => 'PMDN',
            'tahun1' => 2022,
            'tahun2' => 2023
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responsePMDN->assertStatus(200);
        
        $jsonPMDN = $responsePMDN->json();
        $this->assertContains('2022', $jsonPMDN['chartLabels']);
        $this->assertContains('2023', $jsonPMDN['chartLabels']);
        $this->assertNotEmpty($jsonPMDN['chartData1']);
        $this->assertNotEmpty($jsonPMDN['chartData2']);
        $this->assertStringContainsString('PMDN', $jsonPMDN['html']);

        $responseCombined = $this->get(route('realisasi.perbandingan', [
            'jenis' => 'PMA+PMDN',
            'tahun1' => 2022,
            'tahun2' => 2023
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responseCombined->assertStatus(200);
        
        $jsonCombined = $responseCombined->json();
        $this->assertContains('2022', $jsonCombined['chartLabels']);
        $this->assertContains('2023', $jsonCombined['chartLabels']);
        $this->assertNotEmpty($jsonCombined['chartData1']);
        $this->assertNotEmpty($jsonCombined['chartData2']);
        $this->assertStringContainsString('Banjarmasin', $jsonCombined['html']);
        $this->assertStringContainsString('Banjarbaru', $jsonCombined['html']);
        $this->assertTrue(
            strpos($jsonCombined['html'], '2022') !== false,
            'HTML should contain data for year 2022'
        );

        $this->assertTrue(
            strpos($jsonCombined['html'], '2023') !== false,
            'HTML should contain data for year 2023'
        );
    }

    /** @test */
    public function PD_02()
    {
        Datainvestasi::create([
            'tahun' => 2022,
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
            'tahun' => 2023,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Industri',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarbaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 8000,
            'investasi_us_ribu' => 550,
            'jumlah_tki' => 80,
        ]);

        $response = $this->get(route('realisasi.perbandingan'));
        $response->assertStatus(200);
        $responseData = $this->get(route('realisasi.perbandingan', [
            'jenis' => 'PMA',
            'tahun1' => 2022,
            'tahun2' => 2023
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responseData->assertStatus(200);
        
        $jsonData = $responseData->json();
        $this->assertNotEmpty($jsonData['html']);
        $this->assertNotEmpty($jsonData['chartData1']);
        $this->assertNotEmpty($jsonData['chartData2']);

        $response->assertSee('Data Diri - Bagian 1');
        $response->assertSee('Silahkan isi formulir untuk mengunduh Bagian 1');
        $response->assertSee('name="kategori_pengunduh"', false);
        $response->assertSee('name="nama_instansi"', false);
        $response->assertSee('name="email_pengunduh"', false);
        $response->assertSee('name="telpon"', false);
        $response->assertSee('name="keperluan"', false);

        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Analisis Data Indonesia',
            'email_pengunduh' => 'analisis@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan analisis perbandingan investasi',
            'persetujuan_tanggung_jawab' => '1', 
            'persetujuan_dpmptsp' => '1',         
        ]);

        $downloadResponse->assertStatus(200);
        $downloadResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Analisis Data Indonesia',
            'email_pengunduh' => 'analisis@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan analisis perbandingan investasi'
        ]);

        $this->assertDatabaseCount('log_pengunduhan', 1);
        $log = \App\Models\LogPengunduhan::first();
        $this->assertNotNull($log->waktu_download);
        $this->assertEquals('Individu', $log->kategori_pengunduh);
        $this->assertEquals('PT Analisis Data Indonesia', $log->nama_instansi);
        $this->assertEquals('analisis@example.com', $log->email_pengunduh);
        $this->assertEquals('081234567890', $log->telpon);

        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMDN',
            'regional' => 'Indonesia',
            'negara' => 'Indonesia',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Manufaktur',
            'deskripsi_kbli_2digit' => '(15-2020) Manufaktur Logam',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 3000,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 30,
        ]);

        Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 2',
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
            'investasi_rp_juta' => 4500,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 45,
        ]);

        $responsePMDN = $this->get(route('realisasi.perbandingan', [
            'jenis' => 'PMDN',
            'tahun1' => 2022,
            'tahun2' => 2023
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responsePMDN->assertStatus(200);

        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Perusahaan',
            'nama_instansi' => 'CV Riset PMDN',
            'email_pengunduh' => 'riset@pmdn.com',
            'telpon' => '082345678901',
            'keperluan' => 'Untuk keperluan riset PMDN',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);

        $downloadResponse2->assertStatus(200);
        $downloadResponse2->assertJson(['success' => true]);

        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Perusahaan',
            'nama_instansi' => 'CV Riset PMDN',
            'email_pengunduh' => 'riset@pmdn.com'
        ]);

        $this->assertDatabaseCount('log_pengunduhan', 2);

        $responseCombined = $this->get(route('realisasi.perbandingan', [
            'jenis' => 'PMA+PMDN',
            'tahun1' => 2022,
            'tahun2' => 2023
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responseCombined->assertStatus(200);

        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Lainnya',
            'nama_instansi' => 'Universitas Lambung Mangkurat',
            'email_pengunduh' => 'akademik@ulm.ac.id',
            'telpon' => '085123456789',
            'keperluan' => 'Untuk keperluan penelitian akademik gabungan PMA dan PMDN',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);

        $downloadResponse3->assertStatus(200);
        $downloadResponse3->assertJson(['success' => true]);

        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Lainnya',
            'nama_instansi' => 'Universitas Lambung Mangkurat',
            'email_pengunduh' => 'akademik@ulm.ac.id'
        ]);

        $this->assertDatabaseCount('log_pengunduhan', 3);
    }

    /** @test */
    public function PD_03()
    {
        Datainvestasi::create([
            'tahun' => 2022,
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
            'tahun' => 2023,
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Industri',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarbaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 8000,
            'investasi_us_ribu' => 550,
            'jumlah_tki' => 80,
        ]);

        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 3',
            'status_penanaman_modal' => 'PMDN',
            'regional' => 'Indonesia',
            'negara' => 'Indonesia',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Manufaktur',
            'deskripsi_kbli_2digit' => '(15-2020) Manufaktur Logam',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 3000,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 30,
        ]);

        Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 4',
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
            'investasi_rp_juta' => 4500,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 45,
        ]);

        $response = $this->get(route('realisasi.perbandingan'));
        $response->assertStatus(200);
        $response->assertSee('PERBANDINGAN REALISASI INVESTASI');
        $response->assertSee('Perbandingan Petriwulan');
        $response->assertSee('Pilih Jenis');
        $response->assertSee('Tahun 1');
        $response->assertSee('Tahun 2');
        $response->assertSee('Pilih Periode');
        $response->assertSee('Tampilkan');
        $responsePMA = $this->get(route('realisasi.perbandingan2', [
            'jenis' => 'PMA',
            'tahun1' => 2022,
            'tahun2' => 2023,
            'periode1' => 'Triwulan 1',
            'periode2' => 'Triwulan 2'
        ]), [
            'X-Requested-With' => 'XMLHttpRequest' 
        ]);

        $responsePMA->assertStatus(200);
        $responsePMA->assertJsonStructure([
            'html',
            'chartLabels',
            'chartData1',
            'chartData2'
        ]);

        $jsonPMA = $responsePMA->json();
        $this->assertStringContainsString('Triwulan 1', implode(' ', $jsonPMA['chartLabels']));
        $this->assertStringContainsString('2022', implode(' ', $jsonPMA['chartLabels']));
        $this->assertStringContainsString('Triwulan 2', implode(' ', $jsonPMA['chartLabels']));
        $this->assertStringContainsString('2023', implode(' ', $jsonPMA['chartLabels']));
        $this->assertNotEmpty($jsonPMA['chartData1']);
        $this->assertNotEmpty($jsonPMA['chartData2']);
        $this->assertStringContainsString('PMA', $jsonPMA['html']);
        $this->assertStringContainsString('Banjarmasin', $jsonPMA['html']);
        $this->assertStringContainsString('Banjarbaru', $jsonPMA['html']);

        $responsePMDN = $this->get(route('realisasi.perbandingan2', [
            'jenis' => 'PMDN',
            'tahun1' => 2022,
            'tahun2' => 2023,
            'periode1' => 'Triwulan 3',
            'periode2' => 'Triwulan 4'
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responsePMDN->assertStatus(200);
        
        $jsonPMDN = $responsePMDN->json();
        $this->assertStringContainsString('Triwulan 3', implode(' ', $jsonPMDN['chartLabels']));
        $this->assertStringContainsString('2022', implode(' ', $jsonPMDN['chartLabels']));
        $this->assertStringContainsString('Triwulan 4', implode(' ', $jsonPMDN['chartLabels']));
        $this->assertStringContainsString('2023', implode(' ', $jsonPMDN['chartLabels']));
        $this->assertNotEmpty($jsonPMDN['chartData1']);
        $this->assertNotEmpty($jsonPMDN['chartData2']);
        $this->assertStringContainsString('PMDN', $jsonPMDN['html']);

        $responseCombined = $this->get(route('realisasi.perbandingan2', [
            'jenis' => 'PMA+PMDN',
            'tahun1' => 2022,
            'tahun2' => 2023,
            'periode1' => 'Triwulan 1',
            'periode2' => 'Triwulan 2'
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responseCombined->assertStatus(200);
        
        $jsonCombined = $responseCombined->json();
        $this->assertStringContainsString('Triwulan 1', implode(' ', $jsonCombined['chartLabels']));
        $this->assertStringContainsString('2022', implode(' ', $jsonCombined['chartLabels']));
        $this->assertStringContainsString('Triwulan 2', implode(' ', $jsonCombined['chartLabels']));
        $this->assertStringContainsString('2023', implode(' ', $jsonCombined['chartLabels']));
        $this->assertNotEmpty($jsonCombined['chartData1']);
        $this->assertNotEmpty($jsonCombined['chartData2']);
        $this->assertStringContainsString('Banjarmasin', $jsonCombined['html']);
        $this->assertStringContainsString('Banjarbaru', $jsonCombined['html']);
        $this->assertTrue(
            strpos($jsonCombined['html'], '2022') !== false,
            'HTML should contain data for year 2022'
        );

        $this->assertTrue(
            strpos($jsonCombined['html'], '2023') !== false,
            'HTML should contain data for year 2023'
        );

        $responseMultiPeriod = $this->get(route('realisasi.perbandingan2', [
            'jenis' => 'PMA+PMDN',
            'tahun1' => 2022,
            'tahun2' => 2023,
            'periode1' => 'Triwulan 1',
            'periode2' => 'Triwulan 4'
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responseMultiPeriod->assertStatus(200);
        
        $jsonMulti = $responseMultiPeriod->json();
        $this->assertStringContainsString('Triwulan 1', implode(' ', $jsonMulti['chartLabels']));
        $this->assertStringContainsString('Triwulan 4', implode(' ', $jsonMulti['chartLabels']));
        $this->assertNotEmpty($jsonMulti['chartData1']);
        $this->assertNotEmpty($jsonMulti['chartData2']);
    }

    /** @test */
    public function PD_04()
    {
        // PRE-CONDITION: Buat data investasi untuk testing perbandingan per triwulan
        // Data untuk Tahun 2022 Triwulan 1 - PMA
        Datainvestasi::create([
            'tahun' => 2022,
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

        // Data untuk Tahun 2023 Triwulan 2 - PMA
        Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Industri',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarbaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 8000,
            'investasi_us_ribu' => 550,
            'jumlah_tki' => 80,
        ]);

        // Data untuk Tahun 2022 Triwulan 3 - PMDN
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 3',
            'status_penanaman_modal' => 'PMDN',
            'regional' => 'Indonesia',
            'negara' => 'Indonesia',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Manufaktur',
            'deskripsi_kbli_2digit' => '(15-2020) Manufaktur Logam',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 3000,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 30,
        ]);

        // Data untuk Tahun 2023 Triwulan 4 - PMDN
        Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 4',
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
            'investasi_rp_juta' => 4500,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 45,
        ]);

        // ACTION STEP:
        // 1. Akses halaman Bandingkan Data
        $response = $this->get(route('realisasi.perbandingan'));
        $response->assertStatus(200);

        // 2. Pilih filter Jenis (PMA)
        // 3. Pilih Tahun 1 (2022) dan Tahun 2 (2023)
        // 4. Pilih Periode 1 (Triwulan 1) dan Periode 2 (Triwulan 2)
        // 5. Klik tombol "Tampilkan"
        $responseData = $this->get(route('realisasi.perbandingan2', [
            'jenis' => 'PMA',
            'tahun1' => 2022,
            'tahun2' => 2023,
            'periode1' => 'Triwulan 1',
            'periode2' => 'Triwulan 2'
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responseData->assertStatus(200);
        
        // Verifikasi data ditampilkan
        $jsonData = $responseData->json();
        $this->assertNotEmpty($jsonData['html']);
        $this->assertNotEmpty($jsonData['chartData1']);
        $this->assertNotEmpty($jsonData['chartData2']);

        // 6. Klik tombol "Unduh"
        // Verifikasi popup unduh Bagian 2 ada di halaman
        $response->assertSee('Data Diri - Bagian 2');
        $response->assertSee('Silahkan isi formulir untuk mengunduh Bagian 2');
        
        // Verifikasi form fields ada
        $response->assertSee('name="kategori_pengunduh"', false);
        $response->assertSee('name="nama_instansi"', false);
        $response->assertSee('name="email_pengunduh"', false);
        $response->assertSee('name="telpon"', false);
        $response->assertSee('name="keperluan"', false);

        // 7. Isi semua form unduh dengan valid
        // 8. Klik tombol "Unduh"
        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Analisis Triwulan Indonesia',
            'email_pengunduh' => 'triwulan@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan analisis perbandingan investasi per triwulan',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Checkbox
            'persetujuan_dpmptsp' => '1',         // ✅ Checkbox
        ]);

        // EXPECTED RESULT: Sistem berhasil mengunduh file PDF sesuai data perbandingan yang difilter
        $downloadResponse->assertStatus(200);
        $downloadResponse->assertJson(['success' => true]);

        // Verifikasi data log pengunduhan tersimpan di database
        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Analisis Triwulan Indonesia',
            'email_pengunduh' => 'triwulan@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan analisis perbandingan investasi per triwulan'
        ]);

        // Verifikasi jumlah data di database
        $this->assertDatabaseCount('log_pengunduhan', 1);

        // Verifikasi waktu download tercatat
        $log = \App\Models\LogPengunduhan::first();
        $this->assertNotNull($log->waktu_download);
        $this->assertEquals('Individu', $log->kategori_pengunduh);
        $this->assertEquals('PT Analisis Triwulan Indonesia', $log->nama_instansi);
        $this->assertEquals('triwulan@example.com', $log->email_pengunduh);
        $this->assertEquals('081234567890', $log->telpon);

        // BONUS: Test dengan filter PMDN dan periode berbeda
        // 2. Pilih filter PMDN
        // 3. Pilih Tahun 1 (2022) Periode 3 dan Tahun 2 (2023) Periode 4
        // 4. Klik tombol "Tampilkan"
        $responsePMDN = $this->get(route('realisasi.perbandingan2', [
            'jenis' => 'PMDN',
            'tahun1' => 2022,
            'tahun2' => 2023,
            'periode1' => 'Triwulan 3',
            'periode2' => 'Triwulan 4'
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responsePMDN->assertStatus(200);

        // 5. Klik tombol "Unduh"
        // 6. Isi form unduh dengan valid
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Perusahaan',
            'nama_instansi' => 'CV Riset PMDN Triwulan',
            'email_pengunduh' => 'riset.pmdn@example.com',
            'telpon' => '082345678901',
            'keperluan' => 'Untuk keperluan riset PMDN per triwulan',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);

        $downloadResponse2->assertStatus(200);
        $downloadResponse2->assertJson(['success' => true]);

        // Verifikasi data PMDN tersimpan
        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Perusahaan',
            'nama_instansi' => 'CV Riset PMDN Triwulan',
            'email_pengunduh' => 'riset.pmdn@example.com'
        ]);

        // Total data harus 2 (PMA + PMDN)
        $this->assertDatabaseCount('log_pengunduhan', 2);

        // BONUS: Test dengan filter PMA+PMDN
        // 2. Pilih filter PMA+PMDN
        // 3. Pilih Tahun 1 (2022) Periode 1 dan Tahun 2 (2023) Periode 4
        $responseCombined = $this->get(route('realisasi.perbandingan2', [
            'jenis' => 'PMA+PMDN',
            'tahun1' => 2022,
            'tahun2' => 2023,
            'periode1' => 'Triwulan 1',
            'periode2' => 'Triwulan 4'
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responseCombined->assertStatus(200);

        // 4. Klik tombol "Unduh"
        // 5. Isi form unduh dengan valid
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Lainnya',
            'nama_instansi' => 'Universitas Lambung Mangkurat - Riset Triwulan',
            'email_pengunduh' => 'akademik.triwulan@ulm.ac.id',
            'telpon' => '085123456789',
            'keperluan' => 'Untuk keperluan penelitian akademik perbandingan investasi gabungan PMA dan PMDN per triwulan',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);

        $downloadResponse3->assertStatus(200);
        $downloadResponse3->assertJson(['success' => true]);

        // Verifikasi data PMA+PMDN tersimpan
        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Lainnya',
            'nama_instansi' => 'Universitas Lambung Mangkurat - Riset Triwulan',
            'email_pengunduh' => 'akademik.triwulan@ulm.ac.id'
        ]);

        // Total data harus 3 (PMA + PMDN + PMA+PMDN)
        $this->assertDatabaseCount('log_pengunduhan', 3);

        // Verifikasi semua log memiliki waktu download
        $allLogs = \App\Models\LogPengunduhan::all();
        foreach ($allLogs as $logEntry) {
            $this->assertNotNull($logEntry->waktu_download);
            $this->assertInstanceOf(\Carbon\Carbon::class, $logEntry->waktu_download);
        }
    }

    /** @test */
    public function PD_05()
    {
        // PRE-CONDITION: Buat data investasi untuk tahun yang sama
        Datainvestasi::create([
            'tahun' => 2022,
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
            'tahun' => 2022,
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMDN',
            'regional' => 'Indonesia',
            'negara' => 'Indonesia',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Manufaktur',
            'deskripsi_kbli_2digit' => '(15-2020) Manufaktur Logam',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 3000,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 30,
        ]);

        // ACTION STEP:
        // 1. Akses halaman Bandingkan Data
        $response = $this->get(route('realisasi.perbandingan'));
        $response->assertStatus(200);
        $response->assertSee('PERBANDINGAN REALISASI INVESTASI');
        $response->assertSee('Perbandingan Pertahun');
        $response->assertSee('Pilih Jenis');
        $response->assertSee('Tahun 1');
        $response->assertSee('Tahun 2');
        $response->assertSee('Tampilkan');

        // 2. Pilih filter Jenis (PMA)
        // 3. Pilih Tahun 1 (2022) dan Tahun 2 (2022) - tahun yang sama
        // 4. Klik tombol "Tampilkan"
        $responsePMA = $this->get(route('realisasi.perbandingan', [
            'jenis' => 'PMA',
            'tahun1' => 2022,
            'tahun2' => 2022
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responsePMA->assertStatus(200);
        $responsePMA->assertJsonStructure([
            'html',
            'chartLabels',
            'chartData1',
            'chartData2'
        ]);

        $jsonPMA = $responsePMA->json();
        // Karena tahun sama, chartLabels harus berisi '2022' dua kali
        $this->assertEquals(['2022', '2022'], $jsonPMA['chartLabels']);
        $this->assertNotEmpty($jsonPMA['chartData1']);
        $this->assertNotEmpty($jsonPMA['chartData2']);
        // Data harus sama karena tahun sama
        $this->assertEquals($jsonPMA['chartData1'], $jsonPMA['chartData2']);
        $this->assertStringContainsString('PMA', $jsonPMA['html']);
        $this->assertStringContainsString('Banjarmasin', $jsonPMA['html']);

        // 5. Pilih filter Jenis (PMDN) dengan tahun sama
        $responsePMDN = $this->get(route('realisasi.perbandingan', [
            'jenis' => 'PMDN',
            'tahun1' => 2022,
            'tahun2' => 2022
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responsePMDN->assertStatus(200);

        $jsonPMDN = $responsePMDN->json();
        $this->assertEquals(['2022', '2022'], $jsonPMDN['chartLabels']);
        $this->assertNotEmpty($jsonPMDN['chartData1']);
        $this->assertNotEmpty($jsonPMDN['chartData2']);
        $this->assertEquals($jsonPMDN['chartData1'], $jsonPMDN['chartData2']);
        $this->assertStringContainsString('PMDN', $jsonPMDN['html']);

        // 6. Pilih filter Jenis (PMA+PMDN) dengan tahun sama
        $responseCombined = $this->get(route('realisasi.perbandingan', [
            'jenis' => 'PMA+PMDN',
            'tahun1' => 2022,
            'tahun2' => 2022
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responseCombined->assertStatus(200);

        $jsonCombined = $responseCombined->json();
        $this->assertEquals(['2022', '2022'], $jsonCombined['chartLabels']);
        $this->assertNotEmpty($jsonCombined['chartData1']);
        $this->assertNotEmpty($jsonCombined['chartData2']);
        $this->assertEquals($jsonCombined['chartData1'], $jsonCombined['chartData2']);
        $this->assertStringContainsString('Banjarmasin', $jsonCombined['html']);
        $this->assertTrue(
            strpos($jsonCombined['html'], '2022') !== false,
            'HTML should contain data for year 2022'
        );

        // EXPECTED RESULT: Sistem berhasil menampilkan perbandingan dengan tahun yang sama, data identik
    }

    /** @test */
    public function PD_06()
    {
        // PRE-CONDITION: Buat data investasi untuk testing perbandingan
        Datainvestasi::create([
            'tahun' => 2022,
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
            'tahun' => 2023,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Industri',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarbaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 8000,
            'investasi_us_ribu' => 550,
            'jumlah_tki' => 80,
        ]);

        // ACTION STEP:
        // 1. Akses halaman Bandingkan Data
        $response = $this->get(route('realisasi.perbandingan'));
        $response->assertStatus(200);
        $response->assertSee('PERBANDINGAN REALISASI INVESTASI');
        $response->assertSee('Perbandingan Pertahun');
        $response->assertSee('Pilih Jenis');
        $response->assertSee('Tahun 1');
        $response->assertSee('Tahun 2');
        $response->assertSee('Tampilkan');

        // 2. Pilih filter valid
        // 3. Klik tombol "Tampilkan"
        $responseData = $this->get(route('realisasi.perbandingan', [
            'jenis' => 'PMA',
            'tahun1' => 2022,
            'tahun2' => 2023
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $responseData->assertStatus(200);

        // Verifikasi data ditampilkan
        $jsonData = $responseData->json();
        $this->assertNotEmpty($jsonData['html']);
        $this->assertNotEmpty($jsonData['chartData1']);
        $this->assertNotEmpty($jsonData['chartData2']);

        // 4. Klik tombol "Unduh"
        // Verifikasi popup unduh Bagian 1 ada di halaman
        $response->assertSee('Data Diri - Bagian 1');
        $response->assertSee('Silahkan isi formulir untuk mengunduh Bagian 1');
        $response->assertSee('name="kategori_pengunduh"', false);
        $response->assertSee('name="nama_instansi"', false);
        $response->assertSee('name="email_pengunduh"', false);
        $response->assertSee('name="telpon"', false);
        $response->assertSee('name="keperluan"', false);

        // 5. Isi form unduh tidak lengkap (misalnya kategori_pengunduh tidak dipilih)
        // 6. Klik tombol "Unduh"
        $downloadResponse = $this->postJson(route('log_pengunduhan.store'), [
            // kategori_pengunduh tidak diisi (required)
            'nama_instansi' => 'PT Test Incomplete',
            'email_pengunduh' => 'test@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk testing form tidak lengkap',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan (validation error)
        $downloadResponse->assertStatus(422);
        $downloadResponse->assertJsonValidationErrors(['kategori_pengunduh']);
    }

    /** @test */
    public function PD_07()
    {
        // PRE-CONDITION: User berada di halaman Bandingkan Data
        Datainvestasi::create([
            'tahun' => 2022,
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

        // ACTION STEP 1: Akses halaman Bandingkan Data
        $response = $this->get(route('realisasi.perbandingan'));
        $response->assertStatus(200);
        $response->assertSee('PERBANDINGAN REALISASI INVESTASI');
        $response->assertSee('Perbandingan Pertahun');
        $response->assertSee('Pilih Jenis');
        $response->assertSee('Tahun 1');
        $response->assertSee('Tahun 2');
        $response->assertSee('Tampilkan');

        // EXPECTED RESULT: Sistem menampilkan peringatan "Harap pilih filter Jenis."
        // Karena validasi ada di client-side (JavaScript), verify bahwa:
        
        // 1. Validasi JavaScript ada di halaman
        $response->assertSee('if(jenis === ""){', false);
        $response->assertSee('alert("Harap pilih filter Jenis.");', false);
        
        // 2. Return statement untuk prevent form submission ada
        $response->assertSee('return;', false);
        
        // 3. Form submit handler ada
        $response->assertSee('$(\'#form-perbandingan1\').submit(function(e)', false);
        
        // Verify pesan validasi ada di halaman (untuk user)
        $response->assertSee('Harap pilih filter Jenis.', false);
    }

}
