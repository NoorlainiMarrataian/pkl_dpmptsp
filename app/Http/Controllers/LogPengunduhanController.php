<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogPengunduhan;

class LogPengunduhanController extends Controller
{
    // Simpan data dari form user
    public function store(Request $request)
    {
        $request->validate([
            'kategori_pengunduh' => 'required|string|max:50',
            'nama_instansi' => 'required|string|max:100',
            'email_pengunduh' => 'required|email|max:100',
        ]);

        LogPengunduhan::create([
            'kategori_pengunduh' => $request->kategori_pengunduh,
            'nama_instansi' => $request->nama_instansi,
            'email_pengunduh' => $request->email_pengunduh,
            'telpon' => $request->telpon,
            'keperluan' => $request->keperluan,
            'waktu_download' => now(),
        ]);

        // Setelah isi form langsung download file
        return response()->download(public_path('files/data.pdf'));
    }

    // Tampilkan data untuk admin
    public function index()
    {
        $logs = LogPengunduhan::latest()->get();
        return view('admin.log_pengunduhan.index', compact('logs'));
    }
}
