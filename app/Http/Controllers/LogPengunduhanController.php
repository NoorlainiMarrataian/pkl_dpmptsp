<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogPengunduhan;
use Illuminate\Validation\ValidationException;

class LogPengunduhanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kategori_pengunduh' => 'required|string|max:50',
            'nama_instansi' => 'required|string|max:100',
            'email_pengunduh' => 'required|email|max:100',
            'telpon' => 'required|string|max:20',
            'keperluan' => 'required|string|max:500',
            'persetujuan_tanggung_jawab' => 'required|accepted',
            'persetujuan_dpmptsp' => 'required|accepted',
        ],[
            'persetujuan_tanggung_jawab.required' => 'Anda harus menyetujui untuk bertanggung jawab atas data yang diunduh.',
            'persetujuan_tanggung_jawab.accepted' => 'Anda harus menyetujui untuk bertanggung jawab atas data yang diunduh.',
            'persetujuan_dpmptsp.required' => 'Anda harus menyetujui bahwa DPMPTSP tidak bertanggung jawab atas dampak penggunaan data.',
            'persetujuan_dpmptsp.accepted' => 'Anda harus menyetujui bahwa DPMPTSP tidak bertanggung jawab atas dampak penggunaan data.',
        ]);

        $emojiRegex = '/[\x{203C}-\x{3299}\x{1F000}-\x{1F9FF}\x{1FA00}-\x{1FAFF}]/u';

        $fieldsToCheck = [
            'kategori_pengunduh',
            'nama_instansi',
            'email_pengunduh',
            'telpon',
            'keperluan',
        ];

        foreach ($fieldsToCheck as $field) {
            if ($request->has($field) && preg_match($emojiRegex, $request->input($field))) {
                throw ValidationException::withMessages([
                    $field => 'Field ini tidak boleh mengandung emoji.',
                ]);
            }
        }

        $email = $request->input('email_pengunduh');
        if (strpos($email, ' ') !== false) {
            throw ValidationException::withMessages([
                'email_pengunduh' => 'Email tidak boleh mengandung spasi.',
            ]);
        }

        $telpon = $request->input('telpon');
        if (!preg_match('/^[0-9]+$/', $telpon)) {
            throw ValidationException::withMessages([
                'telpon' => 'Nomor telepon hanya boleh berisi angka.',
            ]);
        }

        if (strlen($telpon) < 5 || strlen($telpon) > 20) {
            throw ValidationException::withMessages([
                'telpon' => 'Nomor telepon harus antara 5-20 digit.',
            ]);
        }

        LogPengunduhan::create([
            'kategori_pengunduh' => $request->kategori_pengunduh,
            'nama_instansi' => $request->nama_instansi,
            'email_pengunduh' => $request->email_pengunduh,
            'telpon' => $request->telpon,
            'keperluan' => $request->keperluan,
            'waktu_download' => now(),
        ]);

    return response()->json(['success' => true]);
    }

    public function index()
    {
        $logs = LogPengunduhan::latest()->get();
        return view('admin.log_pengunduhan.index', compact('logs'));
    }
}
