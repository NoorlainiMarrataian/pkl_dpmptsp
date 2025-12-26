<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Datainvestasi;

class NegaraInvestorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function NI_01()
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

        Datainvestasi::create([
            'tahun' => 2023,
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Malaysia',
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

        $response = $this->get(route('realisasi.negara'));
        $response->assertStatus(200);
        $response->assertSee('NEGARA INVESTOR');
        $response->assertSee('Pilih Tahun');
        $response->assertSee('id="tahunSelect"', false);
        $response->assertSee('2023');
        $response->assertSee('1 Tahun');
        $response->assertSee('Triwulan 1');
        $response->assertSee('Triwulan 2');
        $response->assertSee('Triwulan 3');
        $response->assertSee('Triwulan 4');
        $filterResponse = $this->get(route('realisasi.negara', [
            'tahun' => 2023,
            'triwulan' => 'Triwulan 1'
        ]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('id="chartNegara"', false);
        $filterResponse->assertSee('PMA - 2023');
        $filterResponse->assertSee('tabel-negara', false);
        $filterResponse->assertSee('Negara');
        $filterResponse->assertSee('Proyek');
        $filterResponse->assertSee('Periode');
        $filterResponse->assertSee('Tambahan Investasi (US$ Ribu)');
        $filterResponse->assertSee('Tambahan Investasi (Rp Juta)');
        $filterResponse->assertSee('Singapura');
        $filterResponse->assertDontSee('Malaysia');
        $filterResponse->assertViewHas('data_investasi', function ($data) {
            $hasSingapura = $data->contains('negara', 'Singapura');
            $hasMalaysia = $data->contains('negara', 'Malaysia');
            
            return $data->count() > 0 
                && $hasSingapura 
                && !$hasMalaysia;
        });
        $filterResponse->assertSee('Total');
    }

    /** @test */
    public function NI_02()
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

        $response = $this->get(route('realisasi.negara'));
        $response->assertStatus(200);
        $response->assertSee('NEGARA INVESTOR');
        $response->assertSee('Pilih Tahun');
        $response->assertSee('id="tahunSelect"', false);
        $response->assertSee('2025');
        $filterResponse = $this->get(route('realisasi.negara', [
            'tahun' => 2025,
            'triwulan' => 'Triwulan 4'
        ]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Data belum ada');
        $filterResponse->assertDontSee('PMA - 2025');
        $filterResponse->assertViewHas('data_investasi', function ($data) {
            return $data->isEmpty();
        });
        $filterResponse->assertDontSee('Singapura');
        $filterResponse->assertDontSee('Tambahan Investasi (US$ Ribu)');
        $filterResponse->assertDontSee('Tambahan Investasi (Rp Juta)');
    }

    /** @test */
    public function NI_03()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara'));
        $response->assertStatus(200);
        $response->assertSee('Pilih Tahun');
        $response->assertSee('2022');

        $filterResponse = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('PMA - 2022');
        $filterResponse->assertSee('Jepang');
        $filterResponse->assertSee('id="openPopup"', false);
        $filterResponse->assertSee('bi-download', false);
        $filterResponse->assertSee('id="popupForm"', false);
        $filterResponse->assertSee('Data Diri');
        $filterResponse->assertSee('Silahkan isi formulir untuk mengunduh file ini');
        $filterResponse->assertSee('kategori_pengunduh', false);
        $filterResponse->assertSee('Individu');
        $filterResponse->assertSee('Perusahaan');
        $filterResponse->assertSee('Lainnya');
        $filterResponse->assertSee('nama_instansi', false);
        $filterResponse->assertSee('email_pengunduh', false);
        $filterResponse->assertSee('telpon', false);
        $filterResponse->assertSee('keperluan', false);
        $filterResponse->assertSee('Anda setuju bertanggung jawab atas data yang diunduh');
        $filterResponse->assertSee('Pihak DPMPTSP tidak bertanggung jawab atas dampak penggunaan data');
        
        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'John Doe',
            'email_pengunduh' => 'john@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk penelitian akademik',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',        
        ]);
        $downloadResponse->assertStatus(200);
        $downloadResponse->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('log_pengunduhan', [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'John Doe',
            'email_pengunduh' => 'john@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk penelitian akademik'
        ]);
    }

    /** @test */
    public function NI_04()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);
        $response->assertSee('PMA - 2022');
        $response->assertSee('Jepang');
        $response->assertSee('id="openPopup"', false);
        $response->assertSee('id="popupForm"', false);
        $response->assertSee('required', false);
        $response->assertSee('name="kategori_pengunduh" value="Individu" required', false);
        $response->assertSee('name="nama_instansi"', false);
        $response->assertSee('name="email_pengunduh"', false);
        $response->assertSee('name="telpon"', false);
        $response->assertSee('name="keperluan" placeholder="Keperluan" required', false);
        
        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => '',
            'nama_instansi' => '',
            'email_pengunduh' => '',
            'telpon' => '',
            'keperluan' => ''
        ]);
        $downloadResponse->assertStatus(302);
        $downloadResponse->assertSessionHasErrors([
            'kategori_pengunduh',
            'nama_instansi',
            'email_pengunduh',
            'telpon',
            'keperluan'
        ]);
        
        $this->assertDatabaseCount('log_pengunduhan', 0);
        
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('kategori_pengunduh'), 
                'Should have validation error for kategori_pengunduh');
            
            $this->assertTrue($errors->has('nama_instansi'), 
                'Should have validation error for nama_instansi');
            
            $this->assertTrue($errors->has('email_pengunduh'), 
                'Should have validation error for email_pengunduh');
            
            $this->assertTrue($errors->has('telpon'), 
                'Should have validation error for telpon');
            
            $this->assertTrue($errors->has('keperluan'), 
                'Should have validation error for keperluan');
        }
    }

    /** @test */
    public function NI_05()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => '',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar'
        ]);
        $downloadResponse->assertStatus(302);
        $downloadResponse->assertSessionHasErrors(['kategori_pengunduh']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('kategori_pengunduh'), 
                'Should have validation error for kategori_pengunduh');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'email_pengunduh' => 'user@example.com'
        ]);
    }

    /** @test */
    public function NI_06()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => '',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar'
        ]);
        $downloadResponse->assertStatus(302);
        $downloadResponse->assertSessionHasErrors(['nama_instansi']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('nama_instansi'), 
                'Should have validation error for nama_instansi');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'email_pengunduh' => 'user@example.com',
            'kategori_pengunduh' => 'Individu'
        ]);
    }

    /** @test */
    public function NI_07()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => '😊😊😊',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse->assertStatus(302);
        $downloadResponse->assertSessionHasErrors(['nama_instansi']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('nama_instansi'), 
                'Should have validation error for nama_instansi');
            
            $errorMessage = $errors->first('nama_instansi');
            $this->assertStringContainsString('emoji', strtolower($errorMessage),
                'Error message should mention emoji');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => '😊😊😊'
        ]);
    }

    /** @test */
    public function NI_08()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $namaInstansiPanjang = str_repeat('A', 101);
        
        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => $namaInstansiPanjang,
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar'
        ]);
        $downloadResponse->assertStatus(302);
        $downloadResponse->assertSessionHasErrors(['nama_instansi']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('nama_instansi'), 
                'Should have validation error for nama_instansi');
            
            $errorMessage = $errors->first('nama_instansi');
            $this->assertMatchesRegularExpression('/100|karakter|character|maksimal|max/i', $errorMessage,
                'Error message should mention character limit');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => $namaInstansiPanjang
        ]);
    }

    /** @test */
    public function NI_09()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user @example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse1->assertStatus(302);
        $downloadResponse1->assertSessionHasErrors(['email_pengunduh']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@ example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['email_pengunduh']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => ' user @ example . com ',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar'
        ]);
        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['email_pengunduh']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('email_pengunduh'), 
                'Should have validation error for email_pengunduh');
            
            $errorMessage = $errors->first('email_pengunduh');
            $this->assertMatchesRegularExpression('/email|valid|format/i', $errorMessage,
                'Error message should mention email format');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
    }

    /** @test */
    public function NI_10()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => 'abcd',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse1->assertStatus(302);
        $downloadResponse1->assertSessionHasErrors(['telpon']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => 'abc123xyz',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['telpon']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => 'abc-123-xyz', 
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['telpon']);

        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => 'abcd efgh',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['telpon']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('telpon'), 
                'Should have validation error for telpon');
            
            $errorMessage = $errors->first('telpon');
            $this->assertMatchesRegularExpression('/angka|number|digit|numeric/i', $errorMessage,
                'Error message should mention that phone must be numeric');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => 'abcd'
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => 'abc123xyz'
        ]);
    }

    /** @test */
    public function NI_11()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $telpon21Digit = '081234567890123456789';
        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => $telpon21Digit,
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse1->assertStatus(302);
        $downloadResponse1->assertSessionHasErrors(['telpon']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $telpon25Digit = '0812345678901234567890123';
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => $telpon25Digit,
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['telpon']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $telpon30Digit = '081234567890123456789012345678';
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => $telpon30Digit,
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['telpon']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('telpon'), 
                'Should have validation error for telpon');
            
            $errorMessage = $errors->first('telpon');
            $this->assertMatchesRegularExpression('/20|digit|karakter|maksimal|max|panjang|length/i', $errorMessage,
                'Error message should mention the 20 digit limit');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => $telpon21Digit
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => $telpon25Digit
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => $telpon30Digit
        ]);

        $telpon20Digit = '08123456789012345678';
        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => $telpon20Digit,
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse4->assertStatus(200);
        $downloadResponse4->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'telpon' => $telpon20Digit
        ]);
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_12()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '0812',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse1->assertStatus(302);
        $downloadResponse1->assertSessionHasErrors(['telpon']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '123',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['telpon']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '12',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['telpon']);

        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '1',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['telpon']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('telpon'), 
                'Should have validation error for telpon');
            
            $errorMessage = $errors->first('telpon');
            $this->assertMatchesRegularExpression('/5|digit|karakter|minimal|min|antara/i', $errorMessage,
                'Error message should mention the 5 digit minimum');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => '0812'
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => '123'
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => '12'
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => '1'
        ]);
        $downloadResponse5 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '08123',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse5->assertStatus(200);
        $downloadResponse5->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'telpon' => '08123'
        ]);
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_13()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse1->assertStatus(302);
        $downloadResponse1->assertSessionHasErrors(['telpon']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['telpon']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '   ',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['telpon']);

        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => null, 
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['telpon']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('telpon'), 
                'Should have validation error for telpon');
            
            $errorMessage = $errors->first('telpon');
            $this->assertMatchesRegularExpression('/required|wajib|diisi|harus|field|isi/i', $errorMessage,
                'Error message should mention that the field is required');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'email_pengunduh' => 'user@example.com'
        ]);
        $downloadResponse5 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse5->assertStatus(200);
        $downloadResponse5->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'telpon' => '081234567890'
        ]);
        
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_14()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => '',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse1->assertStatus(302);
        $downloadResponse1->assertSessionHasErrors(['email_pengunduh']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['email_pengunduh']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => '   ',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['email_pengunduh']);

        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => null,
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['email_pengunduh']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('email_pengunduh'), 
                'Should have validation error for email_pengunduh');
            
            $errorMessage = $errors->first('email_pengunduh');
            $this->assertMatchesRegularExpression('/required|wajib|diisi|harus|field|isi/i', $errorMessage,
                'Error message should mention that the field is required');
        }
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);

        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => '081234567890'
        ]);
        $downloadResponse5 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1', 
        ]);
        $downloadResponse5->assertStatus(200);
        $downloadResponse5->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com'
        ]);
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_15()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => '',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse1->assertStatus(302);
        $downloadResponse1->assertSessionHasErrors(['keperluan']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['keperluan']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => '   ',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['keperluan']);

        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => null,
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['keperluan']);

        $downloadResponse5 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => "\n\n\n",
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);

        $downloadResponse5->assertStatus(302);
        $downloadResponse5->assertSessionHasErrors(['keperluan']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('keperluan'), 
                'Should have validation error for keperluan');
            
            $errorMessage = $errors->first('keperluan');
            $this->assertMatchesRegularExpression('/required|wajib|diisi|harus|field|isi/i', $errorMessage,
                'Error message should mention that the field is required');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'email_pengunduh' => 'user@example.com'
        ]);
        $downloadResponse6 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset dan analisis pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse6->assertStatus(200);
        $downloadResponse6->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'keperluan' => 'Untuk keperluan riset dan analisis pasar'
        ]);
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_16()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse1->assertStatus(302);
        $downloadResponse1->assertSessionHasErrors(['persetujuan_tanggung_jawab']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
        ]);
        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['persetujuan_dpmptsp']);

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
        ]);
        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['persetujuan_tanggung_jawab', 'persetujuan_dpmptsp']);

        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '0',
            'persetujuan_dpmptsp' => '0',
        ]);
        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['persetujuan_tanggung_jawab', 'persetujuan_dpmptsp']);

        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('persetujuan_tanggung_jawab'), 
                'Should have validation error for persetujuan_tanggung_jawab');
            
            $this->assertTrue($errors->has('persetujuan_dpmptsp'), 
                'Should have validation error for persetujuan_dpmptsp');
            
            $errorMessage1 = $errors->first('persetujuan_tanggung_jawab');
            $errorMessage2 = $errors->first('persetujuan_dpmptsp');
            
            $this->assertMatchesRegularExpression('/accept|setuju|wajib|required|dicentang|checked|harus/i', $errorMessage1,
                'Error message should mention that checkbox must be checked');
            
            $this->assertMatchesRegularExpression('/accept|setuju|wajib|required|dicentang|checked|harus/i', $errorMessage2,
                'Error message should mention that checkbox must be checked');
        }

        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'email_pengunduh' => 'user@example.com'
        ]);
        $downloadResponse5 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $downloadResponse5->assertStatus(200);
        $downloadResponse5->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com'
        ]);
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_17()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara'));
        $response->assertStatus(200);
        $response->assertSee('NEGARA INVESTOR');
        $response->assertViewHas('data_investasi', function ($data) {
            return $data->isEmpty();
        });
        $response->assertSee('Silahkan Pilih Tahun dan Periode Untuk Melihat Data');
        $response->assertSee('id="openPopup"', false);
        $response->assertSee('bi-download', false);
        $response->assertDontSee('<div class="tabel-card">', false);
        $response->assertDontSee('class="tabel-negara"', false);
        $response->assertDontSee('PMA - ');
        $response->assertSee("var tabelCard = document.querySelector('.tabel-card');", false);
        $response->assertSee("if (!tabelCard) {", false);
        $response->assertSee("alert('Tidak ada data yang diunduh. Silahkan pilih tahun dan periode valid');", false);

        $withFilterResponse = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $withFilterResponse->assertStatus(200);
        $withFilterResponse->assertViewHas('data_investasi', function ($data) {
            return $data->isNotEmpty();
        });
        $withFilterResponse->assertSee('<div class="tabel-card">', false);
        $withFilterResponse->assertSee('PMA - 2022');
        $withFilterResponse->assertSee('Jepang');
        $withFilterResponse->assertDontSee('Silahkan Pilih Tahun dan Periode Untuk Melihat Data');
        $withFilterResponse->assertSee('class="tabel-negara"', false);

        $successDownloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $successDownloadResponse->assertStatus(200);
        $successDownloadResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('log_pengunduhan', [
            'email_pengunduh' => 'valid@example.com'
        ]);
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_18()
    {
        Datainvestasi::create([
            'tahun' => 2022,
            'periode' => 'Triwulan 4',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Asia',
            'negara' => 'Jepang',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Banjarmasin',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 10000,
            'investasi_us_ribu' => 700,
            'jumlah_tki' => 100,
        ]);

        $response = $this->get(route('realisasi.negara'));
        $response->assertStatus(200);
        $response->assertSee('NEGARA INVESTOR');

        $noDataResponse = $this->get(route('realisasi.negara', [
            'tahun' => 2025,
            'triwulan' => 'Triwulan 1'
        ]));
        $noDataResponse->assertStatus(200);
        $noDataResponse->assertSee('value="2025" selected', false);
        $noDataResponse->assertViewHas('data_investasi', function ($data) {
            return $data->isEmpty();
        });
        $noDataResponse->assertSee('Data belum ada');
        $noDataResponse->assertDontSee('<div class="tabel-card">', false);
        $noDataResponse->assertDontSee('class="tabel-negara"', false);
        $noDataResponse->assertDontSee('PMA - 2025'); 
        $noDataResponse->assertDontSee('id="chartNegara"', false);
        $noDataResponse->assertSee('id="openPopup"', false);
        $noDataResponse->assertSee('bi-download', false);
        $noDataResponse->assertSee("var tabelCard = document.querySelector('.tabel-card');", false);
        $noDataResponse->assertSee("if (!tabelCard) {", false);
        $noDataResponse->assertSee("alert('Tidak ada data yang diunduh. Silahkan pilih tahun dan periode valid');", false);

        $withDataResponse = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $withDataResponse->assertStatus(200);
        $withDataResponse->assertViewHas('data_investasi', function ($data) {
            return $data->isNotEmpty();
        });
        $withDataResponse->assertSee('<div class="tabel-card">', false);
        $withDataResponse->assertSee('class="tabel-negara"', false);
        $withDataResponse->assertSee('PMA - 2022');
        $withDataResponse->assertSee('Jepang');
        $withDataResponse->assertSee('<thead>', false); 
        $withDataResponse->assertSee('<tbody>', false); 
        $withDataResponse->assertSee('id="chartNegara"', false);

        $successDownloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',
        ]);
        $successDownloadResponse->assertStatus(200);
        $successDownloadResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('log_pengunduhan', [
            'email_pengunduh' => 'valid@example.com'
        ]);
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

}