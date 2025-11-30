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
        $berandaResponse = $this->get(route('user.dashboard'));
        $berandaResponse->assertStatus(200);
        $berandaResponse->assertSee('Realisasi Investasi');
        $berandaResponse->assertSee(route('realisasi.realisasiinvestasi'));
        
        $response = $this->get(route('realisasi.realisasiinvestasi'));
        $response->assertStatus(200);
        $response->assertViewIs('user.realisasi.realisasiinvestasi');
        $response->assertSee('TREN REALISASI INVESTASI');        
        $response->assertSee('Realisasi Investasi');        
        $response->assertSee('NEGARA INVESTOR');
        $response->assertSee(route('realisasi.negara'));
        $response->assertSee('LOKASI');
        $response->assertSee(route('realisasi.lokasi'));
        $response->assertSee('Bandingkan Data');
        $response->assertSee(route('realisasi.perbandingan'));
        $response->assertSee('Beranda');
        $response->assertSee('logo-darisimantan.png', false);
    }

    /** @test */
    public function RI_02()
    {
        $berandaResponse = $this->get(route('user.dashboard'));
        $berandaResponse->assertStatus(200);
        
        $realisasiResponse = $this->get(route('realisasi.realisasiinvestasi'));
        $realisasiResponse->assertStatus(200);
        $realisasiResponse->assertSee('NEGARA INVESTOR');
        $realisasiResponse->assertSee(route('realisasi.negara'));

        $response = $this->get(route('realisasi.negara'));
        $response->assertStatus(200);
        $response->assertViewIs('user.realisasi.negara');
        $response->assertSee('NEGARA INVESTOR');
        $response->assertSee('Beranda');
        $response->assertSee('Realisasi Investasi');
        $response->assertSee('logo-darisimantan.png', false);
    }

    /** @test */
    public function RI_03()
    {
        $berandaResponse = $this->get(route('user.dashboard'));
        $berandaResponse->assertStatus(200);
        
        $realisasiResponse = $this->get(route('realisasi.realisasiinvestasi'));
        $realisasiResponse->assertStatus(200);
        $realisasiResponse->assertSee('LOKASI');
        $realisasiResponse->assertSee(route('realisasi.lokasi'));

        $response = $this->get(route('realisasi.lokasi'));
        $response->assertStatus(200);
        $response->assertViewIs('user.realisasi.lokasi');
        $response->assertSee('LOKASI');
        $response->assertSee('Beranda');
        $response->assertSee('Realisasi Investasi');
        $response->assertSee('logo-darisimantan.png', false);
    }

    /** @test */
    public function RI_04()
    {
        $berandaResponse = $this->get(route('user.dashboard'));
        $berandaResponse->assertStatus(200);
        
        $realisasiResponse = $this->get(route('realisasi.realisasiinvestasi'));
        $realisasiResponse->assertStatus(200);
        $realisasiResponse->assertSee('Bandingkan Data');
        $realisasiResponse->assertSee(route('realisasi.perbandingan'));

        $response = $this->get(route('realisasi.perbandingan'));
        $response->assertStatus(200);
        $response->assertViewIs('user.realisasi.perbandingan');
        $response->assertSee('PERBANDINGAN REALISASI INVESTASI');
        $response->assertSee('Beranda');
        $response->assertSee('Realisasi Investasi');
        $response->assertSee('logo-darisimantan.png', false);
    }
}