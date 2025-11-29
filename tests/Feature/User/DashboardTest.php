<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function BD_01()
    {
        $homeResponse = $this->get('/');
        $homeResponse->assertRedirect(route('user.dashboard'));

        $response = $this->get(route('user.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('user.dashboard.index');
        $response->assertSee('Beranda');
        $response->assertSee('Realisasi Investasi');
        $response->assertSee('logo-darisimantan.png');
        $response->assertSee('Realisasi Investasi');
        $response->assertSee('Selamat datang di Website Realisasi Investasi DPMPTSP Provinsi Kalimantan Selatan');
        $response->assertSee('Info selengkapnya');
        $response->assertSee('Tentang Kami');
        $response->assertSee('Dinas Penanaman Modal Dan Pelayanan Terpadu Satu Pintu');
        $response->assertSee('gedung.jpg');
        $response->assertSee('peta.png');
        $response->assertSee(route('home'));
        $response->assertSee(route('realisasi.realisasiinvestasi'));
    }

    /** @test */
    public function BD_02()
    {
        $response = $this->get(route('user.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Info selengkapnya');
        $response->assertSee('btn-info', false);
        $response->assertSee('toggleInfo()', false);
        $response->assertSee('more-info', false);
        $response->assertSee('Data realisasi investasi ini meliputi perkembangan dari berbagai sektor usaha');
        $response->assertSee('Penanaman Modal Asing (PMA)');
        $response->assertSee('Penanaman Modal Dalam Negeri (PMDN)');
        $response->assertSee('masyarakat dapat memantau tren');
        $response->assertSee('distribusi investasi yang tersebar di seluruh wilayah Kalimantan Selatan');
        $response->assertSee('Informasi ini diharapkan dapat menjadi rujukan bagi para pelaku usaha');
        $response->assertSee('investor');
        $response->assertSee('mengambil keputusan strategis');
        $response->assertSee('berdaya saing dan berkelanjutan');
        
        $response->assertSee('function toggleInfo()', false);
        $response->assertSee('getElementById("more-info")', false);
        $response->assertSee('querySelector(".btn-info")', false);

        $response->assertSee('classList.contains("show")', false);
        $response->assertSee('classList.add("show")', false);
        $response->assertSee('Tutup info');
    }
}