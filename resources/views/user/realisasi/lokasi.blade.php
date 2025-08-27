@extends('layouts.app')

@section('content')
<section class="lokasi-investasi">
    <h2>Data Kabupaten/Kota di Provinsi Kalimantan Selatan</h2>

    {{-- Filter Tahun, Jenis, dan Periode --}}
    <form class="filter-bar" action="{{ route('realisasi.lokasi') }}" method="GET">
        {{-- Filter Tahun --}}
        <select class="dropdown-tahun" name="tahun">
            <option value="">Pilih Tahun</option>
            @foreach(range(date('Y'), 2010) as $th)
                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
            @endforeach
        </select>

        {{-- Filter Jenis Investasi --}}
        <select class="dropdown-jenis" name="jenis">
            <option value="">Pilih Status</option>
            <option value="PMA" {{ request('jenis') == 'PMA' ? 'selected' : '' }}>PMA</option>
            <option value="PMDN" {{ request('jenis') == 'PMDN' ? 'selected' : '' }}>PMDN</option>
            <option value="PMA+PMDN" {{ request('jenis') == 'PMA+PMDN' ? 'selected' : '' }}>PMA + PMDN</option>
        </select>

        {{-- Filter Periode --}}
        <button type="submit" name="triwulan" value="Tahun">1 Tahun</button>
        <button type="submit" name="triwulan" value="Triwulan 1">Triwulan 1</button>
        <button type="submit" name="triwulan" value="Triwulan 2">Triwulan 2</button>
        <button type="submit" name="triwulan" value="Triwulan 3">Triwulan 3</button>
        <button type="submit" name="triwulan" value="Triwulan 4">Triwulan 4</button>

        {{-- Tombol Download --}}
        <a href="#" class="btn-download">
            <i class="fas fa-download"></i> Download
        </a>
    </form>

    {{-- Grafik Lokasi --}}
    <div class="grafik-card">
        <canvas id="chartLokasi" width="1000" height="400"></canvas>
    </div>

    {{-- Tabel Data Lokasi --}}
    <div class="tabel-card">
        <h3 class="judul-tabel">TABEL DATA LOKASI</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    {{-- Hapus kolom No, Provinsi, dan Jumlah TKI --}}
                    <th>Kabupaten/Kota</th>
                    <th>Status</th>
                    <th>Jumlah Proyek</th>
                    <th>Investasi (Rp Juta)</th>
                    <th>Investasi (USD Ribu)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataLokasi as $lokasi)
                    <tr>
                        {{-- Hapus kolom No, Provinsi, dan Jumlah TKI --}}
                        <td>{{ $lokasi->kabupaten_kota }}</td>
                        <td>{{ $lokasi->status_penanaman_modal }}</td>
                        <td>{{ $lokasi->proyek }}</td>
                        <td>{{ number_format($lokasi->investasi_rp_juta, 0, ',', '.') }}</td>
                        <td>{{ number_format($lokasi->investasi_us_ribu, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        {{-- Sesuaikan colspan menjadi 5 --}}
                        <td colspan="5" class="text-center">Tidak ada data untuk filter yang dipilih</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/lokasi.css') }}">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartLokasi').getContext('2d');
    const chartLokasi = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels), 
            datasets: [{
                label: 'Investasi (Rp Juta)',
                data: @json($chartData),
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@endpush
