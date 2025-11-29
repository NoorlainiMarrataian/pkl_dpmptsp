@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    {{-- Judul Utama --}}
    <h2>Selamat datang, {{ Auth::guard('admin')->user()->username }}</h2>
    <p class="dashboard-subtitle">
        Pantau perkembangan data investasi dan aktivitas pengunduhan secara real-time melalui dashboard ini.
    </p>

    {{-- ✅ Statistik Ringkas + Pengantar --}}
    <div class="dashboard-stats">
        
        {{-- Card Total Kunjungan Website --}}
        <div class="stat-card stat-visit">
            <h3>Total Kunjungan Website</h3>
            <div class="stat-content">
                <p class="stat-value">{{ $totalVisits }}</p>
                <i class="fa fa-users stat-icon"></i>
            </div>
        </div>

        {{-- Pengantar Data Pengunduh --}}
        <div class="stat-card stat-info">
            <h3>📊 Data Pengunduh</h3>
            <p>Gunakan informasi ini untuk melihat siapa saja yang telah mengunduh data investasi.</p>
        </div>
    </div>

    {{-- ✅ Tabel Data Pengunduh --}}
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
