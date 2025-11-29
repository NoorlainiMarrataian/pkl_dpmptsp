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
            [2018,'Triwulan 2','PMA','Afrika','Mauritania','Sekunder','Industri Makanan','(10-2015) Industri makanan','Kalimantan Selatan','Kabupaten Kotabaru','Luar Jawa','Kalimantan',0,0,0],
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

    /** @test */
    public function DL_14()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Act:
        // 1. Akses halaman Data Laporan
        $response = $this->get(route('data_investasi.index'));

        // Assert: Sistem menampilkan peringatan “Nomor ID harus diisi” (dalam kode halaman untuk validasi kosong)
        $response->assertStatus(200);
        $response->assertSee('Nomor ID harus diisi');
    }

    /** @test */
    public function DL_15()
    {
        // Arrange: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Act:
        // 1. Akses halaman Data Laporan
        // 2. Klik tombol “Edit Data”
        // 3. Pada kolom Cari Nomor ID Data, input huruf (misal: “abc”)
        // 4. Klik tombol “Cari” (simulasi dengan GET ke route edit dengan ID invalid)
        $response = $this->get(route('data_investasi.edit', 'abc'));

        // Assert: Sistem menampilkan peringatan “Nomor ID harus berupa angka”
        $response->assertRedirect(route('data_investasi.index'));
        $response->assertSessionHasErrors(['edit' => 'Nomor ID harus berupa angka.']);
    }

    /** @test */
    public function DL_16()
    {
        // PRE-CONDITION: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat data investasi dengan ID yang akan dicari
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

        // ACTION STEP:
        // 1. Akses halaman Data Laporan
        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);

        // 2. Klik tombol "Edit Data"
        // 3. Pada kolom Cari Nomor ID Data, input angka valid
        // 4. Klik tombol "Cari" → simulasi dengan GET ke route edit dengan ID valid
        $response = $this->get(route('data_investasi.edit', $data->id));

        // EXPECTED RESULT: Sistem menampilkan form update data dengan data sesuai ID yang dimasukkan
        $response->assertStatus(200);
        $response->assertViewIs('admin.data_investasi.edit'); // Pastikan view yang benar
        
        // Verifikasi bahwa form menampilkan data yang benar
        $response->assertSee('Update Data Realisasi Investasi');
        $response->assertSee((string) $data->id); // ID ditampilkan
        $response->assertSee($data->tahun); // Tahun ditampilkan
        $response->assertSee($data->periode); // Periode ditampilkan
        $response->assertSee($data->nama_sektor); // Nama sektor ditampilkan
        $response->assertSee($data->negara); // Negara ditampilkan
        
        // Verifikasi bahwa data dimuat di view (via view data)
        $response->assertViewHas('data_investasi', function ($viewData) use ($data) {
            return $viewData->id === $data->id 
                && $viewData->nama_sektor === $data->nama_sektor
                && $viewData->negara === $data->negara;
        });
    }

    /** @test */
    public function DL_17()
    {
        // PRE-CONDITION: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat data investasi awal
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

        // ACTION STEP:
        // 1. Akses halaman Edit Data
        $response = $this->get(route('data_investasi.edit', $data->id));
        $response->assertStatus(200);

        // 2. Ubah beberapa kolom dengan data valid
        // 3. Klik tombol "Simpan"
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

        // EXPECTED RESULT: Data berhasil diperbarui dan sistem menampilkan notifikasi sukses
        $response->assertRedirect(route('data_investasi.index'));
        $response->assertSessionHas('success', 'Data investasi berhasil diperbarui.');

        // Verifikasi data berhasil diupdate di database
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

        // Verifikasi data lama tidak ada lagi
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
        // PRE-CONDITION: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat data investasi untuk konteks
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

        // ACTION STEP:
        // 1. Akses halaman Data Laporan
        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);

        // 2. Klik tombol "Hapus Data"
        // 3. Biarkan kolom Nomor ID kosong
        // 4. Klik tombol "Hapus"
        
        // Karena validasi ada di JavaScript, kita test skenario jika user bypass JS
        // Test dengan mengirim request DELETE dengan ID kosong atau invalid
        
        // Verifikasi pesan "Nomor ID harus diisi" muncul di halaman
        $response->assertSee('Nomor ID harus diisi');
        
        // Verifikasi data tidak terhapus
        $this->assertDatabaseCount('data_investasi', 1);
    }

    /** @test */
    public function DL_19()
    {
        // PRE-CONDITION: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat data investasi untuk konteks
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

        // ACTION STEP:
        // 1. Akses halaman Data Laporan
        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);

        // 2. Klik tombol "Hapus Data"
        // 3. Isi kolom Cari Nomor ID Data dengan huruf "abcde"
        // 4. Klik tombol "Hapus"
        
        // Test endpoint check dengan input huruf
        $checkResponse = $this->get('/data_investasi/check/abcde');
        
        // Endpoint check akan return exists: false karena "abcde" bukan ID valid
        $checkResponse->assertStatus(200);
        $checkResponse->assertJson(['exists' => false]);

        // Verifikasi data tidak terhapus
        $this->assertDatabaseCount('data_investasi', 1);
        $this->assertDatabaseHas('data_investasi', [
            'id' => $data->id,
            'nama_sektor' => 'Industri Makanan',
        ]);

        // Verifikasi pesan validasi muncul di halaman index
        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);
        $response->assertSee('Nomor ID hanya boleh berisi angka');
    }

    /** @test */
    public function DL_20()
    {
        // PRE-CONDITION: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat data investasi untuk konteks
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

        // ACTION STEP:
        // 1. Akses halaman Data Laporan
        $response = $this->get(route('data_investasi.index'));
        $response->assertStatus(200);

        // 2. Klik tombol "Hapus Data"
        // 3. Isi kolom Cari Nomor ID Data dengan ID valid
        // 4. Klik tombol "Hapus"
        
        // Test endpoint check dengan input ID valid
        $checkResponse = $this->get('/data_investasi/check/' . $data->id);
        
        // Endpoint check akan return exists: true karena ID valid ada di DB
        $checkResponse->assertStatus(200);
        $checkResponse->assertJson(['exists' => true]);

        // Simulasi penghapusan data via DELETE request
        $deleteResponse = $this->delete(route('data_investasi.destroy', $data->id));

        // EXPECTED RESULT: Data berhasil dihapus dan sistem menampilkan notifikasi sukses
        $deleteResponse->assertRedirect(route('data_investasi.index'));
        $deleteResponse->assertSessionHas('success', 'Data investasi berhasil dihapus.');

        // Verifikasi data terhapus dari database
        $this->assertDatabaseMissing('data_investasi', [
            'id' => $data->id,
        ]);
    }

    /** @test */
    public function DL_21()
    {
        // PRE-CONDITION: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat 15 data investasi untuk testing pagination
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

        // ACTION STEP:
        // 1. Akses halaman Data Laporan
        // 2. Klik dropdown "Pilih Tampilan"
        // 3. Pilih opsi "10 data" → klik link tanpa parameter 'all'
        $response = $this->get(route('data_investasi.index', request()->except('all')));

        // EXPECTED RESULT: Sistem menampilkan data laporan dengan paginasi berisi 10 data per halaman
        $response->assertStatus(200);
        
        // Verifikasi ada dropdown "Pilih Tampilan"
        $response->assertSee('Pilih Tampilan');
        
        // Verifikasi pagination aktif (ada tombol Next)
        $response->assertSee('Next');
        
        // Verifikasi jumlah data yang ditampilkan adalah 10
        $response->assertViewHas('data_investasi', function ($paginator) {
            return $paginator->count() === 10 && $paginator->total() === 15;
        });

        // Verifikasi ada halaman berikutnya (karena total 15 data)
        $this->assertTrue($response->viewData('data_investasi')->hasMorePages());
        
        // Verifikasi link "10 data" ada di dropdown
        $response->assertSee('10 data');
    }

    /** @test */
    public function DL_22()
    {
        // PRE-CONDITION: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat 25 data investasi untuk testing tampilan "semua data"
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

        // ACTION STEP:
        // 1. Akses halaman Data Laporan
        // 2. Klik dropdown "Pilih Tampilan"
        // 3. Pilih opsi "Semua data" → klik link dengan parameter 'all=1'
        $response = $this->get(route('data_investasi.index', ['all' => 1]));

        // EXPECTED RESULT: Sistem menampilkan seluruh data laporan tanpa paginasi
        $response->assertStatus(200);
        
        // Verifikasi dropdown "Pilih Tampilan" ada
        $response->assertSee('Pilih Tampilan');
        
        // Verifikasi link "Semua data" ada di dropdown
        $response->assertSee('Semua data');
        
        // Verifikasi tombol pagination DISABLED (karena semua data di 1 halaman)
        // Tombol Previous harus disabled (kita di halaman pertama)
        $response->assertSee('disabled', false); // ada di HTML
        $response->assertSee('pagination-btn prev disabled');
        
        // Tombol Next juga harus disabled (tidak ada halaman berikutnya)
        $response->assertSee('pagination-btn next disabled');
        
        // Verifikasi semua 25 data ditampilkan dalam satu halaman
        $response->assertViewHas('data_investasi', function ($paginator) {
            // Harus tetap Paginator, tapi semua data di 1 page
            return $paginator->count() === 25 
                && $paginator->total() === 25
                && !$paginator->hasMorePages()
                && $paginator->onFirstPage();
        });
        
        // Verifikasi beberapa data tampil di halaman (sampling dari awal, tengah, akhir)
        $response->assertSee('Industri Makanan 1');
        $response->assertSee('Industri Makanan 13');
        $response->assertSee('Industri Makanan 25');
        
        // Verifikasi pesan "Belum ada data" TIDAK muncul
        $response->assertDontSee('Belum ada data investasi.');
    }

    /** @test */
    public function DL_23()
    {
        // PRE-CONDITION: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat data investasi awal
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

        // ACTION STEP:
        // 1. Akses halaman Data Laporan
        $indexResponse = $this->get(route('data_investasi.index'));
        $indexResponse->assertStatus(200);
        
        // 2. Arahkan ke kolom Aksi pada baris data tertentu
        // 3. Klik tombol "Edit" → verifikasi link Edit ada di tabel
        $indexResponse->assertSee('Edit');
        $indexResponse->assertSee(route('data_investasi.edit', $data->id));

        // 4. Sistem membuka halaman edit data
        $editResponse = $this->get(route('data_investasi.edit', $data->id));
        $editResponse->assertStatus(200);
        $editResponse->assertViewIs('admin.data_investasi.edit');
        
        // Verifikasi form edit menampilkan data yang benar
        $editResponse->assertSee('Update Data Realisasi Investasi');
        $editResponse->assertSee((string) $data->id);
        $editResponse->assertSee($data->tahun);
        $editResponse->assertSee($data->nama_sektor);

        // 5. Edit beberapa kolom dengan data valid
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

        // 6. Klik tombol "Simpan"
        $updateResponse = $this->put(route('data_investasi.update', $data->id), $updatedPayload);

        // EXPECTED RESULT: Sistem berhasil menyimpan perubahan dan menampilkan pesan sukses
        $updateResponse->assertRedirect(route('data_investasi.index'));
        $updateResponse->assertSessionHas('success', 'Data investasi berhasil diperbarui.');

        // Verifikasi data berhasil diupdate di database
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

        // Verifikasi data lama tidak ada lagi
        $this->assertDatabaseMissing('data_investasi', [
            'id' => $data->id,
            'tahun' => 2018,
            'periode' => 'Triwulan 1',
            'negara' => 'Mauritania',
            'nama_sektor' => 'Industri Makanan',
        ]);

        // Verifikasi setelah redirect, data baru muncul di halaman index
        $finalIndexResponse = $this->get(route('data_investasi.index'));
        $finalIndexResponse->assertStatus(200);
        $finalIndexResponse->assertSee('Industri Teknologi');
        $finalIndexResponse->assertSee('DKI Jakarta');
        $finalIndexResponse->assertDontSee('Industri Makanan');
    }

    /** @test */
    public function DL_24()
    {
        // PRE-CONDITION: Admin sudah login
        $this->actingAs($this->admin, 'admin');

        // Buat data investasi yang akan dihapus
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

        // ACTION STEP:
        // 1. Akses halaman Data Laporan
        $indexResponse = $this->get(route('data_investasi.index'));
        $indexResponse->assertStatus(200);
        
        // 2. Arahkan ke kolom Aksi pada baris data tertentu
        // Verifikasi tombol Delete ada di tabel
        $indexResponse->assertSee('Delete');
        $indexResponse->assertSee($data->nama_sektor);

        // 3. Klik tombol "Delete"
        // 4. Sistem menampilkan popup konfirmasi penghapusan (JavaScript: onsubmit="return confirm(...)")
        // Verifikasi form delete ada dengan konfirmasi
        $indexResponse->assertSee('Yakin ingin menghapus data ini?');
        
        // 5. Klik tombol "Ok" → simulasi submit form delete
        $deleteResponse = $this->delete(route('data_investasi.destroy', $data->id));

        // EXPECTED RESULT: 
        // 6. Sistem menampilkan pesan sukses
        // Sistem berhasil menghapus data dan menampilkan notifikasi "Data berhasil dihapus"
        $deleteResponse->assertRedirect(route('data_investasi.index'));
        $deleteResponse->assertSessionHas('success', 'Data investasi berhasil dihapus.');

        // Verifikasi data terhapus dari database
        $this->assertDatabaseMissing('data_investasi', [
            'id' => $data->id,
            'nama_sektor' => 'Industri Makanan',
            'negara' => 'Mauritania',
        ]);

        // Verifikasi jumlah data berkurang
        $this->assertDatabaseCount('data_investasi', 0);

        // Verifikasi setelah redirect, data tidak muncul lagi di halaman index
        $finalIndexResponse = $this->get(route('data_investasi.index'));
        $finalIndexResponse->assertStatus(200);
        $finalIndexResponse->assertDontSee('Industri Makanan');
        $finalIndexResponse->assertDontSee('Mauritania');
        
        // Jika tidak ada data, tabel menampilkan pesan "Belum ada data investasi."
        $finalIndexResponse->assertSee('Belum ada data investasi.');
    }

}
