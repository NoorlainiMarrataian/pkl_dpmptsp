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

        if ($request->has('search') && $request->search != '') {
            if (!ctype_digit($request->search)) {
                return redirect()
                    ->route('data_investasi.index')
                    ->withErrors(['search' => 'Nomor ID harus berupa angka.'])
                    ->withInput();
            }
            $query->where('id', $request->search);
        }

        if ($request->has('all')) {
            $data_investasi = $query->paginate(10000);
        } else {
            $data_investasi = $query->paginate(10);
        }

        return view('admin.data_investasi.index', compact('data_investasi'));
    }



    public function store(Request $request)
    {
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
        $lastId = \DB::table('data_investasi')->max('id'); 

        $newId = $lastId ? $lastId + 1 : 1;

        return view('admin.data_investasi.create', compact('newId'));
    }

    public function edit($id)
    {
        if (!ctype_digit($id)) {
            return redirect()
                ->route('data_investasi.index')
                ->withErrors(['edit' => 'Nomor ID harus berupa angka.']);
        }

        $data_investasi = Datainvestasi::find($id);

        if (!$data_investasi) {
            return redirect()
                ->route('data_investasi.index')
                ->withErrors(['edit' => 'Data dengan Nomor ID tersebut tidak ada di sistem.']);
        }

        return view('admin.data_investasi.edit', compact('data_investasi'));
    }

    public function check($id)
    {
        $exists = Datainvestasi::where('id', $id)->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    public function uploadForm()
    {
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

        try {
            $sheets = Excel::toArray([], $file);
        } catch (Throwable $e) {
            return redirect()
                ->route('data_investasi.index')
                ->with('error', 'Gagal membaca file Excel: '.$e->getMessage());
        }

        if (!isset($sheets[0]) || !is_array($sheets[0])) {
            return redirect()->route('data_investasi.index')
                ->with('error', 'File Excel kosong atau tidak dapat dibaca.');
        }

        $sheet = $sheets[0];

        if (count($sheet) <= 1) {
            return redirect()->route('data_investasi.index')
                ->with('error', 'Isi file Excel kosong. Pastikan file sesuai template dan memiliki data.');
        }

        $foundValidRow = false;
        foreach ($sheet as $index => $row) {
            if ($index === 0) continue;
            if (isset($row[0]) && is_numeric($row[0]) && trim((string)$row[0]) !== '') {
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
        try {
            Excel::import(new DataInvestasiImport, $file);

            return redirect()
                ->route('data_investasi.index')
                ->with('success', 'Data Excel berhasil diimpor.');
        } catch (Throwable $e) {
            return redirect()
                ->route('data_investasi.index')
                ->with('error', 'Gagal mengimpor: '.$e->getMessage());
        }
    }

}
