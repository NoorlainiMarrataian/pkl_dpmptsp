<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Admin;
use App\Models\Datainvestasi;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

class AdminDataLaporanTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);
    }

    /** @test */
    public function DL_01()
    {
        $this->actingAs($this->admin, 'admin');

        Datainvestasi::create([
            'tahun' => 2018,
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 0,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 0,
        ]);

        $response = $this->get(route('data_investasi.index', ['search' => 'abc']));
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['search']);
        $this->assertTrue(session('errors')->has('search'), 'Error pesan harus ada untuk field search');
    }

    /** @test */
    public function DL_02()
    {
        $this->actingAs($this->admin, 'admin');

        $data = Datainvestasi::create([
            'tahun' => 2018,
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 0,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 0,
        ]);

        $response = $this->get(route('data_investasi.index', ['search' => $data->id]));
        $response->assertStatus(200);
        $response->assertSeeText((string) $data->id);
        $response->assertSeeText($data->nama_sektor);
    }

    /** @test */
    public function DL_03()
    {
        $this->actingAs($this->admin, 'admin');

        $payload = [
            'tahun' => '2018',
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 0,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 0,
        ];

        $response = $this->post(route('data_investasi.store'), $payload);
        $response->assertRedirect(route('data_investasi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('data_investasi', [
            'nama_sektor' => 'Industri Makanan',
            'negara' => 'Mauritania',
        ]);

        $index = $this->get(route('data_investasi.index'));
        $index->assertStatus(200);
        $index->assertSeeText('Industri Makanan');
    }

    /** @test */
    public function DL_04()
    {
        $this->actingAs($this->admin, 'admin');

        $payload = [
            'periode' => '',
            'status_penanaman_modal' => '',
            'regional' => 'Afrika',
            'sektor_utama' => 'Sektor Sekunder',
            'deskripsi_kbli_2digit' => '',
            'provinsi' => '',
            'kabupaten_kota' => '',
            'wilayah_jawa' => '',
            'pulau' => '',
        ];

        $response = $this->post(route('data_investasi.store'), $payload);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tahun', 'periode', 'status_penanaman_modal', 'negara', 'nama_sektor', 'deskripsi_kbli_2digit', 'provinsi', 'kabupaten_kota', 'wilayah_jawa', 'pulau'
        ]);

        $this->assertDatabaseCount('data_investasi', 0);
    }

    /** @test */
    public function DL_05()
    {
        $this->actingAs($this->admin, 'admin');

        $payload = [
            'tahun' => 'abcd',
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 0,
            'investasi_us_ribu' => 0,
            'jumlah_tki' => 0,
        ];

        $response = $this->post(route('data_investasi.store'), $payload);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['tahun']);

        $this->assertDatabaseMissing('data_investasi', [
            'nama_sektor' => 'Industri Makanan',
        ]);
    }

    /** @test */
    public function DL_06()
    {
        $this->actingAs($this->admin, 'admin');

        $payload = [
            'tahun' => '202',
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => 200,
            'jumlah_tki' => 10,
        ];

        $response = $this->post(route('data_investasi.store'), $payload);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['tahun']);

        $this->assertDatabaseMissing('data_investasi', [
            'tahun' => '202',
            'nama_sektor' => 'Industri Makanan',
        ]);
    }

    /** @test */
    public function DL_07()
    {
        $this->actingAs($this->admin, 'admin');

        $payload = [
            'tahun' => '2018',
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 'abcde',
            'investasi_us_ribu' => 500,
            'jumlah_tki' => 12,
        ];

        $response = $this->post(route('data_investasi.store'), $payload);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['investasi_rp_juta']);
        $this->assertDatabaseMissing('data_investasi', [
            'nama_sektor' => 'Industri Makanan',
            'investasi_rp_juta' => 'abcde',
        ]);
    }

    /** @test */
    public function DL_08()
    {
        $this->actingAs($this->admin, 'admin');

        $payload = [
            'tahun' => '2018',
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => -12,
            'investasi_us_ribu' => 200,
            'jumlah_tki' => 10,
        ];

        $response = $this->post(route('data_investasi.store'), $payload);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['investasi_rp_juta']);

        $this->assertDatabaseMissing('data_investasi', [
            'investasi_rp_juta' => -12,
        ]);
    }

    /** @test */
    public function DL_09()
    {
        $this->actingAs($this->admin, 'admin');

        $payload = [
            'tahun' => '2018',
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => 'abcde',
            'jumlah_tki' => 5,
        ];

        $response = $this->post(route('data_investasi.store'), $payload);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['investasi_us_ribu']);

        $this->assertDatabaseMissing('data_investasi', [
            'investasi_us_ribu' => 'abcde',
        ]);
    }

    /** @test */
    public function DL_10()
    {
        $this->actingAs($this->admin, 'admin');

        $payload = [
            'tahun' => '2018',
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => -102,
            'jumlah_tki' => 5,
        ];

        $response = $this->post(route('data_investasi.store'), $payload);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['investasi_us_ribu']);

        $this->assertDatabaseMissing('data_investasi', [
            'investasi_us_ribu' => -102,
        ]);
    }

    /** @test */
    public function DL_11()
    {
        $this->actingAs($this->admin, 'admin');

        $payload = [
            'tahun' => '2018',
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => 50,
            'jumlah_tki' => -2,
        ];

        $response = $this->post(route('data_investasi.store'), $payload);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['jumlah_tki']);

        $this->assertDatabaseMissing('data_investasi', [
            'jumlah_tki' => -2,
        ]);
    }
   
    /** @test */
    public function DL_12()
    {
        $this->actingAs($this->admin, 'admin');
        $file = UploadedFile::fake()->create('data.xlsx', 100);
        $sheet = [
            ['tahun','periode','status','regional','negara','sektor_utama','nama_sektor','deskripsi_kbli_2digit','provinsi','kabupaten_kota','wilayah_jawa','pulau','investasi_rp_juta','investasi_us_ribu','jumlah_tki'],
            [2018,'Triwulan 2','PMA','Afrika','Mauritania','Sekunder','Industri Makanan','(10-2015) Industri makanan','Kalimantan Selatan','Kabupaten Kotabaru','Luar Jawa','Kalimantan',0,0,0],
        ];

        Excel::shouldReceive('toArray')
            ->once()
            ->andReturn([$sheet]);

        Excel::shouldReceive('import')
            ->once()
            ->andReturnNull();

        $response = $this->post(route('data_investasi.upload'), [
            'file' => $file,
        ]);
        $response->assertRedirect(route('data_investasi.index'));
        $response->assertSessionHas('success');
    }

    /** @test */
    public function DL_13()
    {
        $this->actingAs($this->admin, 'admin');
        $fakeFile = \Illuminate\Http\UploadedFile::fake()
            ->create('data_kosong.xlsx', 10, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response = $this->post(route('data_investasi.upload'), [
            'file' => $fakeFile,
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('error');

        $this->assertDatabaseCount('data_investasi', 0);
    }

    /** @test */
    public function DL_14()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);
        $response->assertSee('Nomor ID harus diisi');
    }

    /** @test */
    public function DL_15()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('data_investasi.edit', 'abc'));
        $response->assertRedirect(route('data_investasi.index'));
        $response->assertSessionHasErrors(['edit' => 'Nomor ID harus berupa angka.']);
    }

    /** @test */
    public function DL_16()
    {
        $this->actingAs($this->admin, 'admin');
        $data = Datainvestasi::create([
            'tahun' => 2018,
            'periode' => 'Triwulan 2',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sektor Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => 50,
            'jumlah_tki' => 10,
        ]);

        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);
        $response = $this->get(route('data_investasi.edit', $data->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.data_investasi.edit');
        $response->assertSee('Update Data Realisasi Investasi');
        $response->assertSee((string) $data->id);
        $response->assertSee($data->tahun);
        $response->assertSee($data->periode);
        $response->assertSee($data->nama_sektor);
        $response->assertSee($data->negara);
        $response->assertViewHas('data_investasi', function ($viewData) use ($data) {
            return $viewData->id === $data->id 
                && $viewData->nama_sektor === $data->nama_sektor
                && $viewData->negara === $data->negara;
        });
    }

    /** @test */
    public function DL_17()
    {
        $this->actingAs($this->admin, 'admin');
        $data = Datainvestasi::create([
            'tahun' => 2018,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => 50,
            'jumlah_tki' => 10,
        ]);

        $response = $this->get(route('data_investasi.edit', $data->id));
        $response->assertStatus(200);

        $updatedPayload = [
            'tahun' => '2020',
            'periode' => 'Triwulan 3',
            'status_penanaman_modal' => 'PMDN',
            'regional' => 'Asia',
            'negara' => 'Indonesia',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Industri Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'DKI Jakarta',
            'kabupaten_kota' => 'Jakarta Selatan',
            'wilayah_jawa' => 'Jawa',
            'pulau' => 'Jawa',
            'investasi_rp_juta' => 500,
            'investasi_us_ribu' => 200,
            'jumlah_tki' => 25,
        ];

        $response = $this->put(route('data_investasi.update', $data->id), $updatedPayload);
        $response->assertRedirect(route('data_investasi.index'));
        $response->assertSessionHas('success', 'Data investasi berhasil diperbarui.');

        $this->assertDatabaseHas('data_investasi', [
            'id' => $data->id,
            'tahun' => 2020,
            'periode' => 'Triwulan 3',
            'status_penanaman_modal' => 'PMDN',
            'negara' => 'Indonesia',
            'nama_sektor' => 'Industri Teknologi',
            'provinsi' => 'DKI Jakarta',
            'investasi_rp_juta' => 500,
            'jumlah_tki' => 25,
        ]);

        $this->assertDatabaseMissing('data_investasi', [
            'id' => $data->id,
            'tahun' => 2018,
            'periode' => 'Triwulan 1',
            'negara' => 'Mauritania',
        ]);
    }
    
    /** @test */
    public function DL_18()
    {
        $this->actingAs($this->admin, 'admin');
        Datainvestasi::create([
            'tahun' => 2018,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => 50,
            'jumlah_tki' => 10,
        ]);

        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);
        $response->assertSee('Nomor ID harus diisi');
        
        $this->assertDatabaseCount('data_investasi', 1);
    }

    /** @test */
    public function DL_19()
    {
        $this->actingAs($this->admin, 'admin');
        $data = Datainvestasi::create([
            'tahun' => 2018,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => 50,
            'jumlah_tki' => 10,
        ]);

        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);
        $checkResponse = $this->get('/data_investasi/check/abcde');
        $checkResponse->assertStatus(200);
        $checkResponse->assertJson(['exists' => false]);

        $this->assertDatabaseCount('data_investasi', 1);
        $this->assertDatabaseHas('data_investasi', [
            'id' => $data->id,
            'nama_sektor' => 'Industri Makanan',
        ]);

        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);
        $response->assertSee('Nomor ID hanya boleh berisi angka');
    }

    /** @test */
    public function DL_20()
    {
        $this->actingAs($this->admin, 'admin');
        $data = Datainvestasi::create([
            'tahun' => 2018,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => 50,
            'jumlah_tki' => 10,
        ]);

        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);
        $checkResponse = $this->get('/data_investasi/check/' . $data->id);
        $checkResponse->assertStatus(200);
        $checkResponse->assertJson(['exists' => true]);
        $deleteResponse = $this->delete(route('data_investasi.destroy', $data->id));
        $deleteResponse->assertRedirect(route('data_investasi.index'));
        $deleteResponse->assertSessionHas('success', 'Data investasi berhasil dihapus.');

        $this->assertDatabaseMissing('data_investasi', [
            'id' => $data->id,
        ]);
    }

    /** @test */
    public function DL_21()
    {
        $this->actingAs($this->admin, 'admin');
        for ($i = 1; $i <= 15; $i++) {
            Datainvestasi::create([
                'tahun' => 2018,
                'periode' => 'Triwulan 1',
                'status_penanaman_modal' => 'PMA',
                'regional' => 'Afrika',
                'negara' => 'Mauritania',
                'sektor_utama' => 'Sekunder',
                'nama_sektor' => "Industri Makanan {$i}",
                'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
                'provinsi' => 'Kalimantan Selatan',
                'kabupaten_kota' => 'Kabupaten Kotabaru',
                'wilayah_jawa' => 'Luar Jawa',
                'pulau' => 'Kalimantan',
                'investasi_rp_juta' => 100 * $i,
                'investasi_us_ribu' => 50 * $i,
                'jumlah_tki' => 10 * $i,
            ]);
        }

        $response = $this->get(route('data_investasi.index', request()->except('all')));
        $response->assertStatus(200);
        $response->assertSee('Pilih Tampilan');
        $response->assertSee('Next');
        $response->assertViewHas('data_investasi', function ($paginator) {
            return $paginator->count() === 10 && $paginator->total() === 15;
        });

        $this->assertTrue($response->viewData('data_investasi')->hasMorePages());
        $response->assertSee('10 data');
    }

    /** @test */
    public function DL_22()
    {
        $this->actingAs($this->admin, 'admin');
        for ($i = 1; $i <= 25; $i++) {
            Datainvestasi::create([
                'tahun' => 2018,
                'periode' => 'Triwulan 1',
                'status_penanaman_modal' => 'PMA',
                'regional' => 'Afrika',
                'negara' => 'Mauritania',
                'sektor_utama' => 'Sekunder',
                'nama_sektor' => "Industri Makanan {$i}",
                'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
                'provinsi' => 'Kalimantan Selatan',
                'kabupaten_kota' => 'Kabupaten Kotabaru',
                'wilayah_jawa' => 'Luar Jawa',
                'pulau' => 'Kalimantan',
                'investasi_rp_juta' => 100 * $i,
                'investasi_us_ribu' => 50 * $i,
                'jumlah_tki' => 10 * $i,
            ]);
        }

        $response = $this->get(route('data_investasi.index', ['all' => 1]));
        $response->assertStatus(200);
        $response->assertSee('Pilih Tampilan');
        $response->assertSee('Semua data');
        $response->assertSee('disabled', false);
        $response->assertSee('pagination-btn prev disabled');
        $response->assertSee('pagination-btn next disabled');
        $response->assertViewHas('data_investasi', function ($paginator) {
            return $paginator->count() === 25 
                && $paginator->total() === 25
                && !$paginator->hasMorePages()
                && $paginator->onFirstPage();
        });
        
        $response->assertSee('Industri Makanan 1');
        $response->assertSee('Industri Makanan 13');
        $response->assertSee('Industri Makanan 25');
        $response->assertDontSee('Belum ada data investasi.');
    }

    /** @test */
    public function DL_23()
    {
        $this->actingAs($this->admin, 'admin');
        $data = Datainvestasi::create([
            'tahun' => 2018,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => 50,
            'jumlah_tki' => 10,
        ]);

        $indexResponse = $this->get(route('data_investasi.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Edit');
        $indexResponse->assertSee(route('data_investasi.edit', $data->id));

        $editResponse = $this->get(route('data_investasi.edit', $data->id));
        $editResponse->assertStatus(200);
        $editResponse->assertViewIs('admin.data_investasi.edit');
        $editResponse->assertSee('Update Data Realisasi Investasi');
        $editResponse->assertSee((string) $data->id);
        $editResponse->assertSee($data->tahun);
        $editResponse->assertSee($data->nama_sektor);

        $updatedPayload = [
            'tahun' => '2020',
            'periode' => 'Triwulan 3',
            'status_penanaman_modal' => 'PMDN',
            'regional' => 'Asia',
            'negara' => 'Indonesia',
            'sektor_utama' => 'Tersier',
            'nama_sektor' => 'Industri Teknologi',
            'deskripsi_kbli_2digit' => '(62-2020) Teknologi Informasi',
            'provinsi' => 'DKI Jakarta',
            'kabupaten_kota' => 'Jakarta Selatan',
            'wilayah_jawa' => 'Jawa',
            'pulau' => 'Jawa',
            'investasi_rp_juta' => 500,
            'investasi_us_ribu' => 200,
            'jumlah_tki' => 25,
        ];

        $updateResponse = $this->put(route('data_investasi.update', $data->id), $updatedPayload);
        $updateResponse->assertRedirect(route('data_investasi.index'));
        $updateResponse->assertSessionHas('success', 'Data investasi berhasil diperbarui.');

        $this->assertDatabaseHas('data_investasi', [
            'id' => $data->id,
            'tahun' => 2020,
            'periode' => 'Triwulan 3',
            'status_penanaman_modal' => 'PMDN',
            'negara' => 'Indonesia',
            'nama_sektor' => 'Industri Teknologi',
            'provinsi' => 'DKI Jakarta',
            'investasi_rp_juta' => 500,
            'jumlah_tki' => 25,
        ]);

        $this->assertDatabaseMissing('data_investasi', [
            'id' => $data->id,
            'tahun' => 2018,
            'periode' => 'Triwulan 1',
            'negara' => 'Mauritania',
            'nama_sektor' => 'Industri Makanan',
        ]);

        $finalIndexResponse = $this->get(route('data_investasi.index'));
        $finalIndexResponse->assertStatus(200);
        $finalIndexResponse->assertSee('Industri Teknologi');
        $finalIndexResponse->assertSee('DKI Jakarta');
        $finalIndexResponse->assertDontSee('Industri Makanan');
    }

    /** @test */
    public function DL_24()
    {
        $this->actingAs($this->admin, 'admin');
        $data = Datainvestasi::create([
            'tahun' => 2018,
            'periode' => 'Triwulan 1',
            'status_penanaman_modal' => 'PMA',
            'regional' => 'Afrika',
            'negara' => 'Mauritania',
            'sektor_utama' => 'Sekunder',
            'nama_sektor' => 'Industri Makanan',
            'deskripsi_kbli_2digit' => '(10-2015) Industri makanan',
            'provinsi' => 'Kalimantan Selatan',
            'kabupaten_kota' => 'Kabupaten Kotabaru',
            'wilayah_jawa' => 'Luar Jawa',
            'pulau' => 'Kalimantan',
            'investasi_rp_juta' => 100,
            'investasi_us_ribu' => 50,
            'jumlah_tki' => 10,
        ]);

        $indexResponse = $this->get(route('data_investasi.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Delete');
        $indexResponse->assertSee($data->nama_sektor);
        $indexResponse->assertSee('Yakin ingin menghapus data ini?');
        
        $deleteResponse = $this->delete(route('data_investasi.destroy', $data->id));
        $deleteResponse->assertRedirect(route('data_investasi.index'));
        $deleteResponse->assertSessionHas('success', 'Data investasi berhasil dihapus.');

        $this->assertDatabaseMissing('data_investasi', [
            'id' => $data->id,
            'nama_sektor' => 'Industri Makanan',
            'negara' => 'Mauritania',
        ]);

        $this->assertDatabaseCount('data_investasi', 0);
        $finalIndexResponse = $this->get(route('data_investasi.index'));
        $finalIndexResponse->assertStatus(200);
        $finalIndexResponse->assertDontSee('Industri Makanan');
        $finalIndexResponse->assertDontSee('Mauritania');
        $finalIndexResponse->assertSee('Belum ada data investasi.');
    }

}
