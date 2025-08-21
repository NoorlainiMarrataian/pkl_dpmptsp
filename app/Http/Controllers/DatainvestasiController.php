<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datainvestasi;

class DatainvestasiController extends Controller
{
    public function index()
    {
        $data_investasi = Datainvestasi::all();
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
        return view('admin.data_investasi.create');
    }

    public function edit($id)
    {
        $data_investasi = Datainvestasi::find($id);
        return view('admin.data_investasi.edit', compact('data_investasi'));
    }
}
