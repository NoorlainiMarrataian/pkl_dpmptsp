<!DOCTYPE html> 
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .stat-box {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #444;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h2>Selamat datang, {{ Auth::guard('admin')->user()->username }}</h2>

    {{-- ✅ tampilkan total kunjungan --}}
    <div class="stat-box">
        <strong>Total Kunjungan Website:</strong> {{ $totalVisits }}
    </div>
    
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <h3>Data Pengunduh</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Nama Instansi</th>
                <th>Email</th>
                <th>Telpon</th>
                <th>Keperluan</th>
                <th>Waktu Download</th>
            </tr>
        </thead>
        <tbody>
            @forelse($downloads as $d)
                <tr>
                    <td>{{ $d->id_download }}</td>
                    <td>{{ $d->kategori_pengunduh }}</td>
                    <td>{{ $d->nama_instansi }}</td>
                    <td>{{ $d->email_pengunduh }}</td>
                    <td>{{ $d->telpon }}</td>
                    <td>{{ $d->keperluan }}</td>
                    <td>{{ $d->waktu_download }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada data pengunduhan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
