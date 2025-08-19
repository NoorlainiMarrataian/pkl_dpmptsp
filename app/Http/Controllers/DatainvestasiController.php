<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datainvestasi;

class DatainvestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data_investasi = Datainvestasi::all();
            return view ('data_investasi.index', compact('data_investasi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        // SIMPAN KE DATABASE
        Datainvestasi::create($validated);

        // REDIRECT DENGAN FLASH MESSAGE
        return redirect()->route('data_investasi.index')
                        ->with('success', 'Data investasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data_investasi = Datainvestasi::findOrFail($id);
            return view('data_investasi.show', compact('data_investasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

        // CARI DATA BERDASARKAN ID
        $data = Datainvestasi::findOrFail($id);

        // UPDATE DATA
        $data->update($validated);

        // REDIRECT DENGAN FLASH MESSAGE
        return redirect()->route('data_investasi.index')
                        ->with('success', 'Data investasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Datainvestasi::findOrFail($id);
        $data->delete();

            return redirect()->route('data_investasi.index')
                            ->with('success', 'Data investasi berhasil dihapus.');
    }

    public function create()
    {
        return view('data_investasi.create');
    }

    public function edit($id)
    {
        $data_investasi = Datainvestasi::find($id);

        return view('data_investasi.edit', compact('data_investasi'));
    }
}
