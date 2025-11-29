<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datainvestasi;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataInvestasiImport;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Throwable;

class DatainvestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Datainvestasi::query();

        // Jika ada input search
        if ($request->has('search') && $request->search != '') {

            // Cek apakah isinya angka (id harus numeric)
            if (!ctype_digit($request->search)) {
                return redirect()
                    ->route('data_investasi.index')
                    ->withErrors(['search' => 'ID harus berupa angka.'])
                    ->withInput();
            }

            // Baru lakukan filter jika valid angka
            $query->where('id', $request->search);
        }

        // Jika ada parameter 'all', tampilkan tanpa pagination
        if ($request->has('all')) {
            $data_investasi = $query->paginate(10000);
        } else {
            $data_investasi = $query->paginate(10);
        }

        return view('admin.data_investasi.index', compact('data_investasi'));
    }



    public function store(Request $request)
    {
        // di DataInvestasiController.php (store)
        $validated = $request->validate([
            'tahun'                     => ['required', 'digits:4', 'regex:/^\d{4}$/'],
            'periode'                   => 'required|string|max:50',
            'status_penanaman_modal'    => 'required|string|max:10',
            'regional'                  => 'nullable|string|max:100',
            'negara'                    => 'required|string|max:100',
            'sektor_utama'              => 'nullable|string|max:100',
            'nama_sektor'               => 'required|string|max:150',
            'deskripsi_kbli_2digit'     => 'required|string|max:255',
            'provinsi'                  => 'required|string|max:100',
            'kabupaten_kota'            => 'required|string|max:100',
            'wilayah_jawa'              => 'required|string|max:40',
            'pulau'                     => 'required|string|max:50',
            'investasi_rp_juta'         => 'nullable|numeric|min:0',
            'investasi_us_ribu'         => 'nullable|numeric|min:0',
            'jumlah_tki'                => 'nullable|integer|min:0',
        ], [
            'tahun.required' => 'Kolom Tahun wajib diisi.',
            'tahun.digits'   => 'Tahun harus terdiri dari 4 digit angka.',
            'tahun.regex'    => 'Format Tahun tidak valid. Masukkan 4 digit angka, contoh: 2024.',
        ]);


        Datainvestasi::create($validated);

        return redirect()->route('data_investasi.index')
                         ->with('success', 'Data investasi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $data_investasi = Datainvestasi::findOrFail($id);
        return view('admin.data_investasi.show', compact('data_investasi'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tahun'                     => 'required|digits:4|integer',
            'periode'                   => 'required|string|max:50',
            'status_penanaman_modal'    => 'required|string|max:10',
            'regional'                  => 'nullable|string|max:100',
            'negara'                    => 'required|string|max:100',
            'sektor_utama'              => 'nullable|string|max:100',
            'nama_sektor'               => 'required|string|max:150',
            'deskripsi_kbli_2digit'     => 'required|string|max:255',
            'provinsi'                  => 'required|string|max:100',
            'kabupaten_kota'            => 'required|string|max:100',
            'wilayah_jawa'              => 'required|string|max:40',
            'pulau'                     => 'required|string|max:50',
            'investasi_rp_juta'         => 'nullable|numeric|min:0',
            'investasi_us_ribu'         => 'nullable|numeric|min:0',
            'jumlah_tki'                => 'nullable|integer|min:0',
        ]);

        $data = Datainvestasi::findOrFail($id);
        $data->update($validated);

        return redirect()->route('data_investasi.index')
                         ->with('success', 'Data investasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $data = Datainvestasi::findOrFail($id);
        $data->delete();

        return redirect()->route('data_investasi.index')
                         ->with('success', 'Data investasi berhasil dihapus.');
    }

    public function create()
    {
        // ambil id terakhir dari tabel data_investasi
        $lastId = \DB::table('data_investasi')->max('id'); 

        // kalau kosong, mulai dari 1
        $newId = $lastId ? $lastId + 1 : 1;

        // kirim ke view
        return view('admin.data_investasi.create', compact('newId'));
    }

    public function edit($id)
    {
        $data_investasi = Datainvestasi::find($id);
        return view('admin.data_investasi.edit', compact('data_investasi'));
    }

    public function check($id)
    {
        // cek apakah data dengan ID tersebut ada
        $exists = Datainvestasi::where('id', $id)->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    // DataInvestasiController.php
    public function uploadForm()
    {
        // Menampilkan halaman upload
        return view('admin.data_investasi.upload');
    }


    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file.mimes'    => 'Format harus .xlsx / .xls / .csv',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        /** @var UploadedFile $file */
        $file = $request->file('file');

        // BACA KE ARRAY dulu (tanpa melakukan import)
        try {
            $sheets = Excel::toArray([], $file); // [] karena kita cuma mau array
        } catch (Throwable $e) {
            return redirect()
                ->route('data_investasi.index')
                ->with('error', 'Gagal membaca file Excel: '.$e->getMessage());
        }

        // Ambil sheet pertama
        if (!isset($sheets[0]) || !is_array($sheets[0])) {
            return redirect()->route('data_investasi.index')
                ->with('error', 'File Excel kosong atau tidak dapat dibaca.');
        }

        $sheet = $sheets[0];

        // Jika file hanya ada header / kosong -> tolak
        // Asumsi: header ada di baris 0, data mulai baris 1 (sesuaikan kalau berbeda)
        if (count($sheet) <= 1) {
            return redirect()->route('data_investasi.index')
                ->with('error', 'Isi file Excel kosong. Pastikan file sesuai template dan memiliki data.');
        }

        // Cek minimal satu baris data valid.
        $foundValidRow = false;
        foreach ($sheet as $index => $row) {
            // lewati indeks header (biasanya 0)
            if ($index === 0) continue;

            // Pastikan kolom pertama (tahun) ada dan numeric (ubah index jika struktur berbeda)
            if (isset($row[0]) && is_numeric($row[0]) && trim((string)$row[0]) !== '') {
                // contoh tambahan: periksa 4 digit tahun
                $tahun = (string)trim($row[0]);
                if (preg_match('/^\d{4}$/', $tahun)) {
                    $foundValidRow = true;
                    break;
                }
            }
        }

        if (! $foundValidRow) {
            return redirect()->route('data_investasi.index')
                ->with('error', 'Tidak ditemukan baris data yang valid pada file. Pastikan template dan kolom Tahun terisi dengan benar (4 digit).');
        }

        // Semua ok -> lakukan import menggunakan Import class kamu
        try {
            Excel::import(new DataInvestasiImport, $file);

            return redirect()
                ->route('data_investasi.index')
                ->with('success', 'Data Excel berhasil diimpor.');
        } catch (Throwable $e) {
            // Bila import melempar error (format baris salah, exception), tolak dan tampilkan pesan
            return redirect()
                ->route('data_investasi.index')
                ->with('error', 'Gagal mengimpor: '.$e->getMessage());
        }
    }

}
