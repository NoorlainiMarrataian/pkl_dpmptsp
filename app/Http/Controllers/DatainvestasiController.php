<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datainvestasi;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataInvestasiImport;

class DatainvestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Datainvestasi::query();

        // jika ada parameter search
        if ($request->has('search') && $request->search != '') {
            // filter berdasarkan kolom id
            $query->where('id', $request->search);
        }

        // Jika ada parameter 'all', tampilkan semua data tanpa pagination
        if ($request->has('all')) {
            $data_investasi = $query->paginate(10000); // Tetap gunakan paginator agar view tidak error
        } else {
            $data_investasi = $query->paginate(10);
        }

        return view('admin.data_investasi.index', compact('data_investasi'));
    }


    public function store(Request $request)
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
            'investasi_rp_juta'         => 'nullable|numeric',
            'investasi_us_ribu'         => 'nullable|numeric',
            'jumlah_tki'                => 'nullable|integer',
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
            'investasi_rp_juta'         => 'nullable|numeric',
            'investasi_us_ribu'         => 'nullable|numeric',
            'jumlah_tki'                => 'nullable|integer',
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

    try {
        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\DataInvestasiImport, $request->file('file'));

        return redirect()
            ->route('data_investasi.index')
            ->with('success', 'Data Excel berhasil diimpor.');
    } catch (\Throwable $e) {
        return redirect()
            ->route('data_investasi.index')
            ->with('error', 'Gagal mengimpor: '.$e->getMessage());
    }
    }
}
