<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RealisasiInvestasiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function RI_01()
    {
        // PRE-CONDITION: User berada di laman beranda sistem DARISIMANTAN
        
        // ACTION STEP:
        // 1. Akses laman beranda sistem DARISIMANTAN
        $berandaResponse = $this->get(route('user.dashboard'));
        $berandaResponse->assertStatus(200);
        
        // Verifikasi menu "Realisasi Investasi" ada di navbar
        $berandaResponse->assertSee('Realisasi Investasi');
        $berandaResponse->assertSee(route('realisasi.realisasiinvestasi'));
        
        // 2. Klik menu "Realisasi Investasi"
        // 3. Sistem menampilkan halaman realisasi investasi
        $response = $this->get(route('realisasi.realisasiinvestasi'));
        
        // EXPECTED RESULT: Sistem berhasil menampilkan halaman realisasi investasi
        $response->assertStatus(200);
        
        // Verifikasi view yang benar dimuat
        $response->assertViewIs('user.realisasi.realisasiinvestasi');
        
        // Verifikasi section "Tren Realisasi Investasi" ada
        $response->assertSee('TREN REALISASI INVESTASI');
        
        // Verifikasi section "Realisasi Investasi" dengan 3 card utama
        $response->assertSee('Realisasi Investasi');
        
        // Verifikasi card "NEGARA INVESTOR" ada
        $response->assertSee('NEGARA INVESTOR');
        $response->assertSee(route('realisasi.negara'));
        
        // Verifikasi card "LOKASI" ada
        $response->assertSee('LOKASI');
        $response->assertSee(route('realisasi.lokasi'));
        
        // Verifikasi card "Bandingkan Data" ada
        $response->assertSee('Bandingkan Data');
        $response->assertSee(route('realisasi.perbandingan'));
        
        // Verifikasi navbar masih ada (consistent layout)
        $response->assertSee('Beranda');
        $response->assertSee('logo-darisimantan.png', false);
    }

    /** @test */
    public function RI_02()
    {
        // PRE-CONDITION: User berada di laman beranda sistem DARISIMANTAN
        
        // ACTION STEP:
        // 1. Akses laman beranda sistem DARISIMANTAN
        $berandaResponse = $this->get(route('user.dashboard'));
        $berandaResponse->assertStatus(200);
        
        // 2. Klik menu "Realisasi Investasi"
        $realisasiResponse = $this->get(route('realisasi.realisasiinvestasi'));
        
        // 3. Sistem menampilkan halaman realisasi investasi
        $realisasiResponse->assertStatus(200);
        
        // Verifikasi card "NEGARA INVESTOR" ada
        $realisasiResponse->assertSee('NEGARA INVESTOR');
        $realisasiResponse->assertSee(route('realisasi.negara'));
        
        // 4. Klik menu "Negara Investor"
        // 5. Sistem menampilkan halaman Negara Investor
        $response = $this->get(route('realisasi.negara'));
        
        // EXPECTED RESULT: Sistem berhasil menampilkan halaman Negara Investor
        $response->assertStatus(200);
        
        // Verifikasi view yang benar dimuat
        $response->assertViewIs('user.realisasi.negara');
        
        // Verifikasi judul halaman "Negara Investor" ada
        $response->assertSee('NEGARA INVESTOR');
        
        // Verifikasi navbar masih ada (consistent layout)
        $response->assertSee('Beranda');
        $response->assertSee('Realisasi Investasi');
        $response->assertSee('logo-darisimantan.png', false);
    }

    /** @test */
    public function RI_03()
    {
        // PRE-CONDITION: User berada di laman beranda sistem DARISIMANTAN
        
        // ACTION STEP:
        // 1. Akses laman beranda sistem DARISIMANTAN
        $berandaResponse = $this->get(route('user.dashboard'));
        $berandaResponse->assertStatus(200);
        
        // 2. Klik menu "Realisasi Investasi"
        $realisasiResponse = $this->get(route('realisasi.realisasiinvestasi'));
        
        // 3. Sistem menampilkan halaman realisasi investasi
        $realisasiResponse->assertStatus(200);
        
        // Verifikasi card "LOKASI" ada
        $realisasiResponse->assertSee('LOKASI');
        $realisasiResponse->assertSee(route('realisasi.lokasi'));
        
        // 4. Klik menu "Lokasi"
        // 5. Sistem menampilkan halaman Lokasi
        $response = $this->get(route('realisasi.lokasi'));
        
        // EXPECTED RESULT: Sistem berhasil menampilkan halaman Lokasi
        $response->assertStatus(200);
        // Verifikasi view yang benar dimuat
        $response->assertViewIs('user.realisasi.lokasi');
        // Verifikasi judul halaman "Lokasi" ada
        $response->assertSee('LOKASI');
        // Verifikasi navbar masih ada (consistent layout)
        $response->assertSee('Beranda');
        $response->assertSee('Realisasi Investasi');
        $response->assertSee('logo-darisimantan.png', false);
    }

    /** @test */
    public function RI_04()
    {
        // PRE-CONDITION: User berada di laman beranda sistem DARISIMANTAN
        
        // ACTION STEP:
        // 1. Akses laman beranda sistem DARISIMANTAN
        $berandaResponse = $this->get(route('user.dashboard'));
        $berandaResponse->assertStatus(200);
        
        // 2. Klik menu "Realisasi Investasi"
        $realisasiResponse = $this->get(route('realisasi.realisasiinvestasi'));
        
        // 3. Sistem menampilkan halaman realisasi investasi
        $realisasiResponse->assertStatus(200);
        
        // Verifikasi card "Bandingkan Data" ada
        $realisasiResponse->assertSee('Bandingkan Data');
        $realisasiResponse->assertSee(route('realisasi.perbandingan'));
        
        // 4. Klik menu "Bandingkan Data"
        // 5. Sistem menampilkan halaman Bandingkan Data
        $response = $this->get(route('realisasi.perbandingan'));
        
        // EXPECTED RESULT: Sistem berhasil menampilkan halaman Bandingkan Data
        $response->assertStatus(200);
        // Verifikasi view yang benar dimuat
        $response->assertViewIs('user.realisasi.perbandingan');
        // Verifikasi judul halaman "Bandingkan Data" ada
        $response->assertSee('PERBANDINGAN REALISASI INVESTASI');
        // Verifikasi navbar masih ada (consistent layout)
        $response->assertSee('Beranda');
        $response->assertSee('Realisasi Investasi');
        $response->assertSee('logo-darisimantan.png', false);
    }
}