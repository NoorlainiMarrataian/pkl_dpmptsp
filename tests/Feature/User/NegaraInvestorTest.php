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
        // PRE-CONDITION: Buat beberapa data investasi untuk testing filter
        // Data untuk tahun 2023, Triwulan 1
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

        // Data untuk tahun 2023, Triwulan 2 (tidak akan muncul saat filter Triwulan 1)
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

        // PRE-CONDITION: User berada di halaman Negara Investor
        
        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara'));
        $response->assertStatus(200);
        
        // Verifikasi halaman Negara Investor ditampilkan
        $response->assertSee('NEGARA INVESTOR');
        
        // 2. Klik menu "Pilih Tahun"
        // Verifikasi dropdown tahun ada
        $response->assertSee('Pilih Tahun');
        $response->assertSee('id="tahunSelect"', false);
        
        // Verifikasi tahun 2023 ada di dropdown
        $response->assertSee('2023');
        
        // Verifikasi tombol periode ada (awalnya disabled)
        $response->assertSee('1 Tahun');
        $response->assertSee('Triwulan 1');
        $response->assertSee('Triwulan 2');
        $response->assertSee('Triwulan 3');
        $response->assertSee('Triwulan 4');
        
        // 3. Pilih filter data, misalnya "Triwulan 1"
        // Simulasi submit form dengan tahun 2023 dan Triwulan 1
        $filterResponse = $this->get(route('realisasi.negara', [
            'tahun' => 2023,
            'triwulan' => 'Triwulan 1'
        ]));
        
        // 4. Sistem menampilkan data sesuai pilihan user
        // EXPECTED RESULT: Sistem berhasil menampilkan data negara investor sesuai filter yang dipilih
        $filterResponse->assertStatus(200);
        
        // Verifikasi grafik muncul (karena ada data)
        $filterResponse->assertSee('id="chartNegara"', false);
        
        // Verifikasi tabel muncul dengan judul yang benar
        $filterResponse->assertSee('PMA - 2023');
        $filterResponse->assertSee('tabel-negara', false);
        
        // Verifikasi header tabel
        $filterResponse->assertSee('Negara');
        $filterResponse->assertSee('Proyek');
        $filterResponse->assertSee('Periode');
        $filterResponse->assertSee('Tambahan Investasi (US$ Ribu)');
        $filterResponse->assertSee('Tambahan Investasi (Rp Juta)');
        
        // Verifikasi data Singapura (Triwulan 1) MUNCUL
        $filterResponse->assertSee('Singapura');
        
        // Verifikasi data Malaysia (Triwulan 2) TIDAK MUNCUL
        // Hanya cek negara Malaysia, karena "Triwulan 2" ada di tombol filter
        $filterResponse->assertDontSee('Malaysia');
        
        // Verifikasi view data memiliki data yang benar
        $filterResponse->assertViewHas('data_investasi', function ($data) {
            // Harus ada 1 data (Singapura, Triwulan 1)
            $hasSingapura = $data->contains('negara', 'Singapura');
            $hasMalaysia = $data->contains('negara', 'Malaysia');
            
            return $data->count() > 0 
                && $hasSingapura 
                && !$hasMalaysia;
        });
        
        // Verifikasi baris total ada
        $filterResponse->assertSee('Total');
    }

    /** @test */
    public function NI_02()
    {
        // PRE-CONDITION: Buat data investasi hanya untuk tahun 2023
        // Tidak ada data untuk tahun 2025
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

        // PRE-CONDITION: User berada di halaman Negara Investor
        
        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara'));
        $response->assertStatus(200);
        
        // Verifikasi halaman Negara Investor ditampilkan
        $response->assertSee('NEGARA INVESTOR');
        
        // 2. Klik menu "Pilih Tahun"
        // Verifikasi dropdown tahun ada
        $response->assertSee('Pilih Tahun');
        $response->assertSee('id="tahunSelect"', false);
        
        // 3. Pilih tahun 2025
        // Verifikasi tahun 2025 ada di dropdown
        $response->assertSee('2025');
        
        // 4. Pilih filter data "Triwulan 4"
        // Simulasi submit form dengan tahun 2025 dan Triwulan 4
        $filterResponse = $this->get(route('realisasi.negara', [
            'tahun' => 2025,
            'triwulan' => 'Triwulan 4'
        ]));
        
        // 5. Sistem menampilkan hasil filter
        // EXPECTED RESULT: Sistem menampilkan pesan "Data belum ada"
        $filterResponse->assertStatus(200);
        
        // Verifikasi pesan "Data belum ada" MUNCUL
        $filterResponse->assertSee('Data belum ada');
        
        // Verifikasi judul tabel dengan tahun 2025 TIDAK muncul
        $filterResponse->assertDontSee('PMA - 2025');
        
        // Verifikasi view data kosong
        $filterResponse->assertViewHas('data_investasi', function ($data) {
            return $data->isEmpty();
        });
        
        // Verifikasi data dari tahun 2023 TIDAK muncul
        $filterResponse->assertDontSee('Singapura');
        
        // Verifikasi header tabel TIDAK muncul (karena tidak ada data)
        $filterResponse->assertDontSee('Tambahan Investasi (US$ Ribu)');
        $filterResponse->assertDontSee('Tambahan Investasi (Rp Juta)');
    }

    /** @test */
    public function NI_03()
    {
        // PRE-CONDITION: Buat data investasi untuk tahun 2022, Triwulan 4
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

        // PRE-CONDITION: User berada di halaman Negara Investor
        
        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara'));
        $response->assertStatus(200);
        
        // 2. Klik menu "Pilih Tahun"
        // Verifikasi dropdown tahun ada
        $response->assertSee('Pilih Tahun');
        
        // 3. Pilih tahun 2022
        $response->assertSee('2022');
        
        // 4. Pilih filter data "Triwulan 4"
        // 5. Sistem menampilkan hasil filter
        $filterResponse = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        
        $filterResponse->assertStatus(200);
        
        // Verifikasi data muncul
        $filterResponse->assertSee('PMA - 2022');
        $filterResponse->assertSee('Jepang');
        
        // 6. Klik tombol "Unduh"
        // Verifikasi tombol unduh ada
        $filterResponse->assertSee('id="openPopup"', false);
        $filterResponse->assertSee('bi-download', false);
        
        // 7. Muncul popup unduh
        // Verifikasi popup form ada di HTML
        $filterResponse->assertSee('id="popupForm"', false);
        $filterResponse->assertSee('Data Diri');
        $filterResponse->assertSee('Silahkan isi formulir untuk mengunduh file ini');
        
        // Verifikasi field form unduh ada
        $filterResponse->assertSee('kategori_pengunduh', false);
        $filterResponse->assertSee('Individu');
        $filterResponse->assertSee('Perusahaan');
        $filterResponse->assertSee('Lainnya');
        $filterResponse->assertSee('nama_instansi', false);
        $filterResponse->assertSee('email_pengunduh', false);
        $filterResponse->assertSee('telpon', false);
        $filterResponse->assertSee('keperluan', false);
        
        // Verifikasi checkbox persetujuan ada
        $filterResponse->assertSee('Anda setuju bertanggung jawab atas data yang diunduh');
        $filterResponse->assertSee('Pihak DPMPTSP tidak bertanggung jawab atas dampak penggunaan data');
        
        // 8. Isi form unduh dengan data valid
        // 9. User menerima file PDF hasil unduhan
        // Simulasi submit form unduh (POST ke log_pengunduhan.store)
        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'John Doe',
            'email_pengunduh' => 'john@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk penelitian akademik',
            'persetujuan_tanggung_jawab' => '1',
            'persetujuan_dpmptsp' => '1',        
        ]);
        
        // EXPECTED RESULT: Sistem berhasil mengunduh file PDF sesuai data yang difilter
        
        // Verifikasi response sukses (biasanya JSON response)
        $downloadResponse->assertStatus(200);
        $downloadResponse->assertJson(['success' => true]);
        
        // Verifikasi data log pengunduhan tersimpan di database
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
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // PRE-CONDITION: User berada di halaman Negara Investor
        
        // ACTION STEP:
        // 1. Akses halaman Negara Investor dengan filter
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        
        $response->assertStatus(200);
        
        // Verifikasi data muncul
        $response->assertSee('PMA - 2022');
        $response->assertSee('Jepang');
        
        // 2. Klik tombol "Unduh"
        // Verifikasi tombol unduh ada
        $response->assertSee('id="openPopup"', false);
        
        // Verifikasi popup form ada dengan field yang required
        $response->assertSee('id="popupForm"', false);
        $response->assertSee('required', false);
        
        // Verifikasi semua field memiliki atribut required
        $response->assertSee('name="kategori_pengunduh" value="Individu" required', false);
        $response->assertSee('name="nama_instansi"', false);
        $response->assertSee('name="email_pengunduh"', false);
        $response->assertSee('name="telpon"', false);
        $response->assertSee('name="keperluan" placeholder="Keperluan" required', false);
        
        // 3. Biarkan seluruh form unduh kosong
        // 4. Klik tombol "Unduh"
        // Simulasi submit form dengan data kosong (semua field kosong)
        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => '',
            'nama_instansi' => '',
            'email_pengunduh' => '',
            'telpon' => '',
            'keperluan' => ''
        ]);
        
        // EXPECTED RESULT: Sistem menampilkan peringatan wajib diisi pada setiap kolom yang kosong
        
        // Verifikasi response redirect back (karena validasi gagal)
        $downloadResponse->assertStatus(302);
        
        // Verifikasi ada error validasi untuk semua field required
        $downloadResponse->assertSessionHasErrors([
            'kategori_pengunduh',
            'nama_instansi',
            'email_pengunduh',
            'telpon',
            'keperluan'
        ]);
        
        // Verifikasi data TIDAK tersimpan di database
        $this->assertDatabaseCount('log_pengunduhan', 0);
        
        // Verifikasi pesan error spesifik untuk setiap field
        $errors = session('errors');
        if ($errors) {
            // Verifikasi ada pesan error untuk field kategori_pengunduh
            $this->assertTrue($errors->has('kategori_pengunduh'), 
                'Should have validation error for kategori_pengunduh');
            
            // Verifikasi ada pesan error untuk field nama_instansi
            $this->assertTrue($errors->has('nama_instansi'), 
                'Should have validation error for nama_instansi');
            
            // Verifikasi ada pesan error untuk field email_pengunduh
            $this->assertTrue($errors->has('email_pengunduh'), 
                'Should have validation error for email_pengunduh');
            
            // Verifikasi ada pesan error untuk field telpon
            $this->assertTrue($errors->has('telpon'), 
                'Should have validation error for telpon');
            
            // Verifikasi ada pesan error untuk field keperluan
            $this->assertTrue($errors->has('keperluan'), 
                'Should have validation error for keperluan');
        }
    }

    /** @test */
    public function NI_05()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Isi semua form kecuali pilihan jenis pengguna (kategori_pengunduh kosong)
        // 4. Klik tombol "Unduh"
        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => '',  // ❌ KOSONG - tidak pilih radio button
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar'
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan "Pilih salah satu opsi berikut."
        $downloadResponse->assertStatus(302); // Redirect back
        $downloadResponse->assertSessionHasErrors(['kategori_pengunduh']);

        // Verifikasi pesan error spesifik untuk kategori_pengunduh
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('kategori_pengunduh'), 
                'Should have validation error for kategori_pengunduh');
        }

        // Verifikasi data TIDAK tersimpan di database
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'email_pengunduh' => 'user@example.com'
        ]);
    }

    /** @test */
    public function NI_06()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Biarkan kolom Nama Lengkap/Instansi kosong
        // 4. Isi kolom lainnya dengan data valid
        // 5. Klik tombol "Unduh"
        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => '',  // ❌ KOSONG
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar'
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan "Harap isi bidang ini."
        $downloadResponse->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse->assertSessionHasErrors(['nama_instansi']);

        // Verifikasi pesan error spesifik untuk nama_instansi
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('nama_instansi'), 
                'Should have validation error for nama_instansi');
        }

        // Verifikasi data TIDAK tersimpan di database
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'email_pengunduh' => 'user@example.com',
            'kategori_pengunduh' => 'Individu'
        ]);
    }

    /** @test */
    public function NI_07()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Isi kolom Nama Lengkap/Instansi dengan emoji
        // 4. Isi kolom lainnya dengan data valid
        // 5. Klik tombol "Unduh"
        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => '😊😊😊',  // ❌ EMOJI
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan "Input tidak boleh menggunakan emoji."
        $downloadResponse->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse->assertSessionHasErrors(['nama_instansi']);

        // Verifikasi pesan error spesifik untuk nama_instansi
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('nama_instansi'), 
                'Should have validation error for nama_instansi');
            
            // Verifikasi pesan error mengandung kata "emoji"
            $errorMessage = $errors->first('nama_instansi');
            $this->assertStringContainsString('emoji', strtolower($errorMessage),
                'Error message should mention emoji');
        }

        // Verifikasi data TIDAK tersimpan di database
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => '😊😊😊'
        ]);
    }

    /** @test */
    public function NI_08()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Isi kolom Nama Lengkap/Instansi dengan teks lebih dari 100 karakter
        // 4. Isi kolom lainnya dengan data valid
        // 5. Klik tombol "Unduh"
        
        // Generate string dengan 101 karakter (melebihi batas 100)
        $namaInstansiPanjang = str_repeat('A', 101); // 101 karakter 'A'
        
        $downloadResponse = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => $namaInstansiPanjang,  // ❌ 101 karakter (melebihi max:100)
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar'
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan
        $downloadResponse->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse->assertSessionHasErrors(['nama_instansi']);

        // Verifikasi pesan error spesifik untuk nama_instansi
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('nama_instansi'), 
                'Should have validation error for nama_instansi');
            
            // Verifikasi pesan error berkaitan dengan panjang karakter
            $errorMessage = $errors->first('nama_instansi');
            $this->assertMatchesRegularExpression('/100|karakter|character|maksimal|max/i', $errorMessage,
                'Error message should mention character limit');
        }

        // Verifikasi data TIDAK tersimpan di database
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => $namaInstansiPanjang
        ]);
    }

    /** @test */
    public function NI_09()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Isi kolom Email dengan spasi setelah/sebelum "@"
        // 4. Isi kolom lainnya dengan data valid
        // 5. Klik tombol "Unduh"
        
        // Test dengan spasi sebelum @
        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user @example.com',  // ❌ Spasi sebelum @
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan
        $downloadResponse1->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse1->assertSessionHasErrors(['email_pengunduh']);

        // Verifikasi data TIDAK tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan spasi setelah @
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@ example.com',  // ❌ Spasi setelah @
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['email_pengunduh']);

        // Verifikasi masih tidak ada data tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan multiple spasi di berbagai posisi
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => ' user @ example . com ',  // ❌ Spasi di banyak tempat
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar'
        ]);

        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['email_pengunduh']);

        // Verifikasi error message berkaitan dengan format email
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('email_pengunduh'), 
                'Should have validation error for email_pengunduh');
            
            $errorMessage = $errors->first('email_pengunduh');
            // Pesan error Laravel untuk email biasanya "The email_pengunduh field must be a valid email address"
            $this->assertMatchesRegularExpression('/email|valid|format/i', $errorMessage,
                'Error message should mention email format');
        }

        // Verifikasi tidak ada data yang tersimpan dari semua percobaan
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
    }

    /** @test */
    public function NI_10()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Isi kolom Telepon dengan huruf (misal: "abcd")
        // 4. Isi kolom lainnya dengan data valid
        // 5. Klik tombol "Unduh"
        
        // Test dengan huruf saja
        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => 'abcd',  // ❌ Huruf saja
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan
        $downloadResponse1->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse1->assertSessionHasErrors(['telpon']);

        // Verifikasi data TIDAK tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan kombinasi huruf dan angka
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => 'abc123xyz',  // ❌ Kombinasi huruf dan angka
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['telpon']);

        // Verifikasi masih tidak ada data tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan karakter spesial dan huruf
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => 'abc-123-xyz',  // ❌ Huruf + karakter spesial
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['telpon']);

        // Test dengan spasi dan huruf
        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => 'abcd efgh',  // ❌ Huruf + spasi
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['telpon']);

        // Verifikasi error message berkaitan dengan format telepon
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('telpon'), 
                'Should have validation error for telpon');
            
            $errorMessage = $errors->first('telpon');
            // Pesan error harus menyebutkan bahwa telepon hanya boleh angka
            $this->assertMatchesRegularExpression('/angka|number|digit|numeric/i', $errorMessage,
                'Error message should mention that phone must be numeric');
        }

        // Verifikasi tidak ada data yang tersimpan dari semua percobaan
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        
        // Verifikasi tidak ada data dengan telepon yang mengandung huruf
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
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Isi kolom Telepon dengan lebih dari 20 angka (misal: "0812345678901234567890")
        // 4. Isi kolom lainnya dengan data valid
        // 5. Klik tombol "Unduh"
        
        // Test dengan 21 digit (melebihi batas 20)
        $telpon21Digit = '081234567890123456789'; // 21 digit
        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => $telpon21Digit,  // ❌ 21 digit (melebihi max:20)
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan
        $downloadResponse1->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse1->assertSessionHasErrors(['telpon']);

        // Verifikasi data TIDAK tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan 25 digit (jauh melebihi batas)
        $telpon25Digit = '0812345678901234567890123'; // 25 digit
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => $telpon25Digit,  // ❌ 25 digit
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['telpon']);

        // Verifikasi masih tidak ada data tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan 30 digit (sangat panjang)
        $telpon30Digit = '081234567890123456789012345678'; // 30 digit
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => $telpon30Digit,  // ❌ 30 digit
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['telpon']);

        // Verifikasi error message berkaitan dengan panjang telepon
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('telpon'), 
                'Should have validation error for telpon');
            
            $errorMessage = $errors->first('telpon');
            // Pesan error harus menyebutkan batasan 20 digit atau maksimal karakter
            $this->assertMatchesRegularExpression('/20|digit|karakter|maksimal|max|panjang|length/i', $errorMessage,
                'Error message should mention the 20 digit limit');
        }

        // Verifikasi tidak ada data yang tersimpan dari semua percobaan
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        
        // Verifikasi tidak ada data dengan telepon yang terlalu panjang
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => $telpon21Digit
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => $telpon25Digit
        ]);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => $telpon30Digit
        ]);

        // BONUS: Verifikasi bahwa 20 digit VALID (batas maksimal yang diperbolehkan)
        $telpon20Digit = '08123456789012345678'; // 20 digit (VALID)
        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => $telpon20Digit,  // ✅ 20 digit (batas maksimal, VALID)
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        // Ini harus BERHASIL (status 200, data tersimpan)
        $downloadResponse4->assertStatus(200);
        $downloadResponse4->assertJson(['success' => true]);
        
        // Verifikasi data tersimpan untuk 20 digit
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'telpon' => $telpon20Digit
        ]);
        
        // Total data di database harus 1 (hanya dari test 20 digit yang valid)
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_12()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Isi kolom Telepon dengan kurang dari 5 angka (misal: "0812")
        // 4. Isi kolom lainnya dengan data valid
        // 5. Klik tombol "Unduh"
        
        // Test dengan 4 digit (kurang dari minimum 5)
        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '0812',  // ❌ 4 digit (kurang dari min:5)
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan
        $downloadResponse1->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse1->assertSessionHasErrors(['telpon']);

        // Verifikasi data TIDAK tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan 3 digit
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '123',  // ❌ 3 digit
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['telpon']);

        // Verifikasi masih tidak ada data tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan 2 digit
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '12',  // ❌ 2 digit
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['telpon']);

        // Test dengan 1 digit
        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '1',  // ❌ 1 digit
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['telpon']);

        // Verifikasi error message berkaitan dengan panjang minimum telepon
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('telpon'), 
                'Should have validation error for telpon');
            
            $errorMessage = $errors->first('telpon');
            // Pesan error harus menyebutkan batasan minimal 5 digit
            $this->assertMatchesRegularExpression('/5|digit|karakter|minimal|min|antara/i', $errorMessage,
                'Error message should mention the 5 digit minimum');
        }

        // Verifikasi tidak ada data yang tersimpan dari semua percobaan
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        
        // Verifikasi tidak ada data dengan telepon yang terlalu pendek
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

        // BONUS: Verifikasi bahwa 5 digit VALID (batas minimal yang diperbolehkan)
        $downloadResponse5 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '08123',  // ✅ 5 digit (batas minimal, VALID)
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        // Ini harus BERHASIL (status 200, data tersimpan)
        $downloadResponse5->assertStatus(200);
        $downloadResponse5->assertJson(['success' => true]);
        
        // Verifikasi data tersimpan untuk 5 digit
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'telpon' => '08123'
        ]);
        
        // Total data di database harus 1 (hanya dari test 5 digit yang valid)
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_13()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Biarkan kolom Telepon kosong
        // 4. Isi kolom lainnya dengan data valid
        // 5. Klik tombol "Unduh"
        
        // Test dengan kolom telepon kosong (empty string)
        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '',  // ❌ KOSONG
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan "Harap isi bidang ini."
        $downloadResponse1->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse1->assertSessionHasErrors(['telpon']);

        // Verifikasi data TIDAK tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test tanpa mengirim field telepon sama sekali
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            // 'telpon' tidak dikirim sama sekali
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',
        ]);

        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['telpon']);

        // Verifikasi masih tidak ada data tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan kolom telepon berisi whitespace saja
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '   ',  // ❌ Hanya spasi
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',
        ]);

        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['telpon']);

        // Test dengan kolom telepon berisi null
        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => null,  // ❌ NULL
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',
        ]);

        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['telpon']);

        // Verifikasi error message berkaitan dengan field required
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('telpon'), 
                'Should have validation error for telpon');
            
            $errorMessage = $errors->first('telpon');
            // Pesan error harus menyebutkan bahwa field wajib diisi
            $this->assertMatchesRegularExpression('/required|wajib|diisi|harus|field|isi/i', $errorMessage,
                'Error message should mention that the field is required');
        }

        // Verifikasi tidak ada data yang tersimpan dari semua percobaan
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        
        // Verifikasi tidak ada data dengan telepon kosong atau null
        $this->assertDatabaseMissing('log_pengunduhan', [
            'email_pengunduh' => 'user@example.com'
        ]);

        // BONUS: Verifikasi bahwa telepon valid bisa tersimpan
        $downloadResponse5 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '081234567890',  // ✅ VALID (10 digit)
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',
        ]);

        // Ini harus BERHASIL (status 200, data tersimpan)
        $downloadResponse5->assertStatus(200);
        $downloadResponse5->assertJson(['success' => true]);
        
        // Verifikasi data tersimpan untuk telepon yang valid
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'telpon' => '081234567890'
        ]);
        
        // Total data di database harus 1 (hanya dari test dengan telepon valid)
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_14()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Biarkan kolom Email kosong
        // 4. Isi kolom lainnya dengan data valid
        // 5. Klik tombol "Unduh"
        
        // Test dengan kolom email kosong (empty string)
        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => '',  // ❌ KOSONG
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan "Harap isi bidang ini."
        $downloadResponse1->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse1->assertSessionHasErrors(['email_pengunduh']);

        // Verifikasi data TIDAK tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test tanpa mengirim field email sama sekali
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            // 'email_pengunduh' tidak dikirim sama sekali
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['email_pengunduh']);

        // Verifikasi masih tidak ada data tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan kolom email berisi whitespace saja
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => '   ',  // ❌ Hanya spasi
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['email_pengunduh']);

        // Test dengan kolom email berisi null
        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => null,  // ❌ NULL
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['email_pengunduh']);

        // Verifikasi error message berkaitan dengan field required
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('email_pengunduh'), 
                'Should have validation error for email_pengunduh');
            
            $errorMessage = $errors->first('email_pengunduh');
            // Pesan error harus menyebutkan bahwa field wajib diisi
            $this->assertMatchesRegularExpression('/required|wajib|diisi|harus|field|isi/i', $errorMessage,
                'Error message should mention that the field is required');
        }

        // Verifikasi tidak ada data yang tersimpan dari semua percobaan
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        
        // Verifikasi tidak ada data dengan email kosong atau null
        $this->assertDatabaseMissing('log_pengunduhan', [
            'telpon' => '081234567890'
        ]);

        // BONUS: Verifikasi bahwa email valid bisa tersimpan
        $downloadResponse5 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',  // ✅ VALID
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1', 
        ]);

        // Ini harus BERHASIL (status 200, data tersimpan)
        $downloadResponse5->assertStatus(200);
        $downloadResponse5->assertJson(['success' => true]);
        
        // Verifikasi data tersimpan untuk email yang valid
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com'
        ]);
        
        // Total data di database harus 1 (hanya dari test dengan email valid)
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_15()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Biarkan kolom Keperluan kosong
        // 4. Isi kolom lainnya dengan data valid
        // 5. Klik tombol "Unduh"
        
        // Test dengan kolom keperluan kosong (empty string)
        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => '',  // ❌ KOSONG
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',         // ✅ Tambahkan checkbox
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan "Harap isi bidang ini."
        $downloadResponse1->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse1->assertSessionHasErrors(['keperluan']);

        // Verifikasi data TIDAK tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test tanpa mengirim field keperluan sama sekali
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            // 'keperluan' tidak dikirim sama sekali
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',         // ✅ Tambahkan checkbox
        ]);

        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['keperluan']);

        // Verifikasi masih tidak ada data tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test dengan kolom keperluan berisi whitespace saja
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => '   ',  // ❌ Hanya spasi
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',         // ✅ Tambahkan checkbox
        ]);

        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['keperluan']);

        // Test dengan kolom keperluan berisi null
        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => null,  // ❌ NULL
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',         // ✅ Tambahkan checkbox
        ]);

        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['keperluan']);

        // Test dengan kolom keperluan berisi newline saja
        $downloadResponse5 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => "\n\n\n",  // ❌ Hanya newline
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',         // ✅ Tambahkan checkbox
        ]);

        $downloadResponse5->assertStatus(302);
        $downloadResponse5->assertSessionHasErrors(['keperluan']);

        // Verifikasi error message berkaitan dengan field required
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('keperluan'), 
                'Should have validation error for keperluan');
            
            $errorMessage = $errors->first('keperluan');
            // Pesan error harus menyebutkan bahwa field wajib diisi
            $this->assertMatchesRegularExpression('/required|wajib|diisi|harus|field|isi/i', $errorMessage,
                'Error message should mention that the field is required');
        }

        // Verifikasi tidak ada data yang tersimpan dari semua percobaan
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        
        // Verifikasi tidak ada data dengan keperluan kosong atau null
        $this->assertDatabaseMissing('log_pengunduhan', [
            'email_pengunduh' => 'user@example.com'
        ]);

        // BONUS: Verifikasi bahwa keperluan valid bisa tersimpan
        $downloadResponse6 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset dan analisis pasar',  // ✅ VALID
            'persetujuan_tanggung_jawab' => '1',  // ✅ Tambahkan checkbox
            'persetujuan_dpmptsp' => '1',         // ✅ Tambahkan checkbox
        ]);

        // Ini harus BERHASIL (status 200, data tersimpan)
        $downloadResponse6->assertStatus(200);
        $downloadResponse6->assertJson(['success' => true]);
        
        // Verifikasi data tersimpan untuk keperluan yang valid
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'keperluan' => 'Untuk keperluan riset dan analisis pasar'
        ]);
        
        // Total data di database harus 1 (hanya dari test dengan keperluan valid)
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_16()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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

        // ACTION STEP:
        // 1. Akses halaman Negara Investor
        $response = $this->get(route('realisasi.negara', [
            'tahun' => 2022,
            'triwulan' => 'Triwulan 4'
        ]));
        $response->assertStatus(200);

        // 2. Klik tombol "Unduh"
        // 3. Isi semua kolom dengan data valid
        // 4. Abaikan (tidak mencentang) checkbox "Saya menyetujui..."
        // 5. Klik tombol "Unduh"
        
        // Test tanpa mencentang checkbox persetujuan_tanggung_jawab
        $downloadResponse1 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            // ❌ persetujuan_tanggung_jawab tidak dikirim (tidak dicentang)
            'persetujuan_dpmptsp' => '1',  // ✅ Checkbox kedua dicentang
        ]);

        // EXPECTED RESULT: Sistem menampilkan peringatan
        $downloadResponse1->assertStatus(302); // Redirect back karena validasi gagal
        $downloadResponse1->assertSessionHasErrors(['persetujuan_tanggung_jawab']);

        // Verifikasi data TIDAK tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test tanpa mencentang checkbox persetujuan_dpmptsp
        $downloadResponse2 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ Checkbox pertama dicentang
            // ❌ persetujuan_dpmptsp tidak dikirim (tidak dicentang)
        ]);

        $downloadResponse2->assertStatus(302);
        $downloadResponse2->assertSessionHasErrors(['persetujuan_dpmptsp']);

        // Verifikasi masih tidak ada data tersimpan
        $this->assertDatabaseCount('log_pengunduhan', 0);

        // Test tanpa mencentang KEDUA checkbox
        $downloadResponse3 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            // ❌ persetujuan_tanggung_jawab tidak dikirim
            // ❌ persetujuan_dpmptsp tidak dikirim
        ]);

        $downloadResponse3->assertStatus(302);
        $downloadResponse3->assertSessionHasErrors(['persetujuan_tanggung_jawab', 'persetujuan_dpmptsp']);

        // Test dengan checkbox bernilai 0 (tidak dicentang)
        $downloadResponse4 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Indonesia',
            'email_pengunduh' => 'user@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '0',  // ❌ Bernilai 0
            'persetujuan_dpmptsp' => '0',  // ❌ Bernilai 0
        ]);

        $downloadResponse4->assertStatus(302);
        $downloadResponse4->assertSessionHasErrors(['persetujuan_tanggung_jawab', 'persetujuan_dpmptsp']);

        // Verifikasi error message berkaitan dengan checkbox yang wajib dicentang
        $errors = session('errors');
        if ($errors) {
            $this->assertTrue($errors->has('persetujuan_tanggung_jawab'), 
                'Should have validation error for persetujuan_tanggung_jawab');
            
            $this->assertTrue($errors->has('persetujuan_dpmptsp'), 
                'Should have validation error for persetujuan_dpmptsp');
            
            $errorMessage1 = $errors->first('persetujuan_tanggung_jawab');
            $errorMessage2 = $errors->first('persetujuan_dpmptsp');
            
            // Pesan error harus menyebutkan bahwa checkbox wajib dicentang
            $this->assertMatchesRegularExpression('/accept|setuju|wajib|required|dicentang|checked|harus/i', $errorMessage1,
                'Error message should mention that checkbox must be checked');
            
            $this->assertMatchesRegularExpression('/accept|setuju|wajib|required|dicentang|checked|harus/i', $errorMessage2,
                'Error message should mention that checkbox must be checked');
        }

        // Verifikasi tidak ada data yang tersimpan dari semua percobaan
        $this->assertDatabaseCount('log_pengunduhan', 0);
        $this->assertDatabaseMissing('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Indonesia'
        ]);
        
        // Verifikasi tidak ada data dengan email yang dicoba
        $this->assertDatabaseMissing('log_pengunduhan', [
            'email_pengunduh' => 'user@example.com'
        ]);

        // BONUS: Verifikasi bahwa jika KEDUA checkbox dicentang, data berhasil tersimpan
        $downloadResponse5 = $this->post(route('log_pengunduhan.store'), [
            'kategori_pengunduh' => 'Individu',
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com',
            'telpon' => '081234567890',
            'keperluan' => 'Untuk keperluan riset pasar',
            'persetujuan_tanggung_jawab' => '1',  // ✅ CHECKED
            'persetujuan_dpmptsp' => '1',  // ✅ CHECKED
        ]);

        // Ini harus BERHASIL (status 200, data tersimpan)
        $downloadResponse5->assertStatus(200);
        $downloadResponse5->assertJson(['success' => true]);
        
        // Verifikasi data tersimpan untuk checkbox yang valid
        $this->assertDatabaseHas('log_pengunduhan', [
            'nama_instansi' => 'PT Teknologi Valid',
            'email_pengunduh' => 'valid@example.com'
        ]);
        
        // Total data di database harus 1 (hanya dari test dengan checkbox tercentang)
        $this->assertDatabaseCount('log_pengunduhan', 1);
    }

    /** @test */
    public function NI_17()
    {
        // PRE-CONDITION: Buat data investasi untuk testing
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