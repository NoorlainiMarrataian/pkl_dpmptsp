@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h2>Selamat datang, {{ Auth::guard('admin')->user()->username }}</h2>

    {{-- ✅ Statistik ringkas --}}
    <div style="display: flex; gap: 20px; margin: 20px 0;">
        <div style="flex: 1; background: #f8f9fa; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
            <h3 style="margin: 0; font-size: 16px; color: #333;">Total Kunjungan Website</h3>
            <p style="font-size: 22px; font-weight: bold; color: #0A4C70;">{{ $totalVisits }}</p>
        </div>
    </div>

    {{-- ✅ Tabel Data Pengunduh --}}
    <h3 style="margin-top: 30px;">Data Pengunduh</h3>
    <div style="overflow-x: auto; margin-top: 10px;">
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
            <thead style="background: #0A4C70; color: #fff;">
                <tr>
                    <th style="padding: 10px; text-align: left;">ID</th>
                    <th style="padding: 10px; text-align: left;">Kategori</th>
                    <th style="padding: 10px; text-align: left;">Nama Instansi</th>
                    <th style="padding: 10px; text-align: left;">Email</th>
                    <th style="padding: 10px; text-align: left;">Telpon</th>
                    <th style="padding: 10px; text-align: left;">Keperluan</th>
                    <th style="padding: 10px; text-align: left;">Waktu Download</th>
                </tr>
            </thead>
            <tbody>
                @forelse($downloads as $d)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;">{{ $d->id_download }}</td>
                        <td style="padding: 10px;">{{ $d->kategori_pengunduh }}</td>
                        <td style="padding: 10px;">{{ $d->nama_instansi }}</td>
                        <td style="padding: 10px;">{{ $d->email_pengunduh }}</td>
                        <td style="padding: 10px;">{{ $d->telpon }}</td>
                        <td style="padding: 10px;">{{ $d->keperluan }}</td>
                        <td style="padding: 10px;">{{ $d->waktu_download }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 10px; text-align: center;">Belum ada data pengunduhan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
