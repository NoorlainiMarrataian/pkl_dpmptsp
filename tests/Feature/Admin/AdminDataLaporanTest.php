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

        // Buat admin untuk pre-condition
        $this->admin = Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);
    }

    /** @test */
    public function DL_01()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat beberapa data investasi untuk konteks
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

        // Act: 
        // 1. Akses halaman Data Laporan
        // 2. Input kolom pencarian dengan huruf "abc"
        // 3. Klik enter (submit form)
        $response = $this->get(route('data_investasi.index', ['search' => 'abc']));

        // Assert: Sistem menolak input tidak valid dan menampilkan pesan "ID harus berupa angka" atau pesan validasi yang sesuai
        $response->assertStatus(302); // Redirect back ke halaman Data Laporan
        $response->assertSessionHasErrors(['search']);
        
        // Verify pesan error yang ditampilkan mencakup validasi format ID
        $this->assertTrue(session('errors')->has('search'), 'Error pesan harus ada untuk field search');
    }

    /** @test */
    public function DL_02()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat data investasi dengan ID otomatis (akan menjadi 1 pada DB kosong)
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

        // Act: Cari dengan ID yang valid (angka)
        $response = $this->get(route('data_investasi.index', ['search' => $data->id]));

        // Assert: Request berhasil (status 200) dan halaman menampilkan data yang dicari
        $response->assertStatus(200);
        $response->assertSeeText((string) $data->id);
        $response->assertSeeText($data->nama_sektor);
    }

    /** @test */
    public function DL_03()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Prepare valid payload sesuai validasi di controller
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

        // Act: kirim POST ke route store
        $response = $this->post(route('data_investasi.store'), $payload);

        // Assert: redirect ke index dan flash success
        $response->assertRedirect(route('data_investasi.index'));
        $response->assertSessionHas('success');

        // Pastikan data tersimpan di database
        $this->assertDatabaseHas('data_investasi', [
            'nama_sektor' => 'Industri Makanan',
            'negara' => 'Mauritania',
        ]);

        // Saat akses index, data muncul di tabel
        $index = $this->get(route('data_investasi.index'));
        $index->assertStatus(200);
        $index->assertSeeText('Industri Makanan');
    }

    /** @test */
    public function DL_04()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Prepare invalid payload: kosongkan beberapa field wajib
        $payload = [
            // 'tahun' => missing
            'periode' => '', // missing
            'status_penanaman_modal' => '', // missing
            'regional' => 'Afrika',
            // 'negara' => missing
            'sektor_utama' => 'Sektor Sekunder',
            // 'nama_sektor' => missing
            'deskripsi_kbli_2digit' => '',
            'provinsi' => '',
            'kabupaten_kota' => '',
            'wilayah_jawa' => '',
            'pulau' => '',
        ];

        // Act: kirim POST ke route store dengan input invalid
        $response = $this->post(route('data_investasi.store'), $payload);

        // Assert: redirect back dan muncul error validasi untuk field-field wajib
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tahun', 'periode', 'status_penanaman_modal', 'negara', 'nama_sektor', 'deskripsi_kbli_2digit', 'provinsi', 'kabupaten_kota', 'wilayah_jawa', 'pulau'
        ]);

        // Pastikan tidak ada data tersimpan
        $this->assertDatabaseCount('data_investasi', 0);
    }

    /** @test */
    public function DL_05()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Payload dengan 'tahun' berisi huruf (invalid)
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

        // Act: kirim POST ke store
        $response = $this->post(route('data_investasi.store'), $payload);

        // Assert: validasi gagal untuk field 'tahun'
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['tahun']);

        // Pastikan tidak ada data tersimpan
        $this->assertDatabaseMissing('data_investasi', [
            'nama_sektor' => 'Industri Makanan',
            // tahun numeric check: ensure no record with this nama_sektor exists
        ]);
    }

    /** @test */
    public function DL_06()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Payload dengan tahun 3 digit (invalid)
        $payload = [
            'tahun' => '202', // ❌ hanya 3 digit
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

        // Act: kirim POST ke store
        $response = $this->post(route('data_investasi.store'), $payload);

        // Assert:
        // Harus gagal dan menampilkan error pada field tahun
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['tahun']);

        // Pastikan data tidak tersimpan
        $this->assertDatabaseMissing('data_investasi', [
            'tahun' => '202', // dipastikan yang invalid tidak tersimpan
            'nama_sektor' => 'Industri Makanan',
        ]);
    }

    /** @test */
    public function DL_07()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Payload dengan investasi_rp_juta berisi teks (invalid)
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

            'investasi_rp_juta' => 'abcde', // ❌ invalid, harus angka
            'investasi_us_ribu' => 500,
            'jumlah_tki' => 12,
        ];

        // Act: kirim POST ke store
        $response = $this->post(route('data_investasi.store'), $payload);

        // Assert: sistem harus menolak dan muncul pesan error pada field investasi_rp_juta
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['investasi_rp_juta']);

        // Pastikan data tidak tersimpan di database
        $this->assertDatabaseMissing('data_investasi', [
            'nama_sektor' => 'Industri Makanan',
            'investasi_rp_juta' => 'abcde',
        ]);
    }

    /** @test */
    public function DL_08()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Payload dengan nilai negatif pada investasi_rp_juta
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

            'investasi_rp_juta' => -12, // ❌ invalid: angka negatif
            'investasi_us_ribu' => 200,
            'jumlah_tki' => 10,
        ];

        // Act
        $response = $this->post(route('data_investasi.store'), $payload);

        // Assert: validasi gagal
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['investasi_rp_juta']);

        // Pastikan data tidak tersimpan
        $this->assertDatabaseMissing('data_investasi', [
            'investasi_rp_juta' => -12,
        ]);
    }

    /** @test */
    public function DL_09()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Payload dengan investasi_us_ribu berisi teks (invalid)
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
            'investasi_us_ribu' => 'abcde', // ❌ invalid: teks
            'jumlah_tki' => 5,
        ];

        // Act: kirim POST ke store
        $response = $this->post(route('data_investasi.store'), $payload);

        // Assert: harus gagal validasi
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['investasi_us_ribu']);

        // Pastikan data tidak tersimpan
        $this->assertDatabaseMissing('data_investasi', [
            'investasi_us_ribu' => 'abcde',
        ]);
    }

    /** @test */
    public function DL_10()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Payload dengan investasi_us_ribu negatif (invalid)
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
            'investasi_us_ribu' => -102, // ❌ invalid: negatif
            'jumlah_tki' => 5,
        ];

        // Act: submit form
        $response = $this->post(route('data_investasi.store'), $payload);

        // Assert: validasi harus gagal
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['investasi_us_ribu']);

        // Pastikan data tidak tersimpan
        $this->assertDatabaseMissing('data_investasi', [
            'investasi_us_ribu' => -102,
        ]);
    }

    /** @test */
    public function DL_11()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Payload dengan jumlah_tki negatif (invalid)
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
            'jumlah_tki' => -2, // ❌ invalid: angka negatif
        ];

        // Act: submit form
        $response = $this->post(route('data_investasi.store'), $payload);

        // Assert: validasi harus gagal
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['jumlah_tki']);

        // Pastikan data tidak tersimpan
        $this->assertDatabaseMissing('data_investasi', [
            'jumlah_tki' => -2,
        ]);
    }
   
    /** @test */
    public function DL_12()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat fake uploaded file (content not needed because we'll mock Excel facade)
        $file = UploadedFile::fake()->create('data.xlsx', 100);

        // Mock Excel::toArray untuk mengembalikan sheet dengan satu baris data valid
        $sheet = [
            // header row
            ['tahun','periode','status','regional','negara','sektor_utama','nama_sektor','deskripsi_kbli_2digit','provinsi','kabupaten_kota','wilayah_jawa','pulau','investasi_rp_juta','investasi_us_ribu','jumlah_tki'],
            // data row (tahun numeric 4 digit -> considered valid by controller)
            [2018,'Triwulan 2','PMA','Afrika','Mauritania','Sektor Sekunder','Industri Makanan','(10-2015) Industri makanan','Kalimantan Selatan','Kabupaten Kotabaru','Luar Jawa','Kalimantan',0,0,0],
        ];

        Excel::shouldReceive('toArray')
            ->once()
            ->andReturn([$sheet]);

        // Mock import to avoid running the actual import class
        Excel::shouldReceive('import')
            ->once()
            ->andReturnNull();

        // Act: kirim POST upload
        $response = $this->post(route('data_investasi.upload'), [
            'file' => $file,
        ]);

        // Assert: redirect ke index dan session success
        $response->assertRedirect(route('data_investasi.index'));
        $response->assertSessionHas('success');
    }

    /** @test */
    public function DL_13()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat file Excel palsu dengan isi kosong (tidak sesuai template)
        $fakeFile = \Illuminate\Http\UploadedFile::fake()
            ->create('data_kosong.xlsx', 10, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Act: kirim request upload
        $response = $this->post(route('data_investasi.upload'), [
            'file' => $fakeFile,
        ]);

        // Assert:
        // Sistem harus redirect dengan pesan error bahwa file tidak valid / template tidak sesuai
        $response->assertStatus(302);
        $response->assertSessionHas('error');  

        // Pastikan TIDAK ada data yang berhasil diimport
        $this->assertDatabaseCount('data_investasi', 0);
    }

}
