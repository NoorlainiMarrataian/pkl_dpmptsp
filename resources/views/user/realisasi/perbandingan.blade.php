@extends('layouts.app')

@section('content')
<section class="perbandingan-investasi">

    {{-- =======================
         BAGIAN 3: Perbandingan Antar Tahun
    ======================== --}}
    <div class="card-section">
        <h2>Perbandingan Data Realisasi Investasi Antar Tahun di Kalsel</h2>

        {{-- Filter Tahun Range --}}
        <form class="filter-bar" action="{{ route('realisasi.perbandingan') }}" method="GET">
            <select class="dropdown-tahun" name="tahun_awal">
                <option value="">Pilih Tahun Awal</option>
                @foreach(range(date('Y'), 2010) as $th)
                    <option value="{{ $th }}" {{ request('tahun_awal') == $th ? 'selected' : '' }}>{{ $th }}</option>
                @endforeach
            </select>

            <select class="dropdown-tahun" name="tahun_akhir">
                <option value="">Pilih Tahun Akhir</option>
                @foreach(range(date('Y'), 2010) as $th)
                    <option value="{{ $th }}" {{ request('tahun_akhir') == $th ? 'selected' : '' }}>{{ $th }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-filter">Bandingkan</button>
            <a href="#" class="btn-download"><i class="fas fa-download"></i> Download</a>
        </form>

        {{-- Grafik Perbandingan --}}
        <div class="grafik-card">
            <canvas id="chartPerbandingan" width="1000" height="400"></canvas>
        </div>

        {{-- Tabel Perbandingan --}}
        <div class="tabel-card">
            <h3 class="judul-tabel">TABEL PERBANDINGAN DATA</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Investasi (Rp Juta)</th>
                        <th>Investasi (US$ Ribu)</th>
                        <th>Jumlah Proyek</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataPerbandingan as $row)
                        <tr>
                            <td>{{ $row->tahun }}</td>
                            <td>{{ number_format($row->investasi_rp_juta, 0, ',', '.') }}</td>
                            <td>{{ number_format($row->investasi_us_ribu, 0, ',', '.') }}</td>
                            <td>{{ $row->proyek ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- =======================
         BAGIAN 4: Perbandingan Antar Tahun & Periode
    ======================== --}}
    <div class="card-section">
        <h2>Perbandingan Data Realisasi Investasi Antar Tahun & Periode di Kalsel</h2>

        {{-- Filter Tahun & Periode --}}
        <form class="filter-bar" action="{{ route('realisasi.perbandingan') }}" method="GET">
            <select class="dropdown-tahun" name="tahun_awal4">
                <option value="">Pilih Tahun Awal</option>
                @foreach(range(date('Y'), 2010) as $th)
                    <option value="{{ $th }}" {{ request('tahun_awal4') == $th ? 'selected' : '' }}>{{ $th }}</option>
                @endforeach
            </select>

            <select class="dropdown-tahun" name="tahun_akhir4">
                <option value="">Pilih Tahun Akhir</option>
                @foreach(range(date('Y'), 2010) as $th)
                    <option value="{{ $th }}" {{ request('tahun_akhir4') == $th ? 'selected' : '' }}>{{ $th }}</option>
                @endforeach
            </select>

            <select class="dropdown-periode" name="periode4">
                <option value="">Pilih Periode</option>
                <option value="Tahun" {{ request('periode4') == 'Tahun' ? 'selected' : '' }}>1 Tahun</option>
                <option value="Triwulan 1" {{ request('periode4') == 'Triwulan 1' ? 'selected' : '' }}>Triwulan 1</option>
                <option value="Triwulan 2" {{ request('periode4') == 'Triwulan 2' ? 'selected' : '' }}>Triwulan 2</option>
                <option value="Triwulan 3" {{ request('periode4') == 'Triwulan 3' ? 'selected' : '' }}>Triwulan 3</option>
                <option value="Triwulan 4" {{ request('periode4') == 'Triwulan 4' ? 'selected' : '' }}>Triwulan 4</option>
            </select>

            <button type="submit" class="btn-filter">Bandingkan</button>
            <a href="#" class="btn-download"><i class="fas fa-download"></i> Download</a>
        </form>

        {{-- Grafik Perbandingan Periode --}}
        <div class="grafik-card">
            <canvas id="chartPerbandinganPeriode" width="1000" height="400"></canvas>
        </div>

        {{-- Tabel Perbandingan Periode --}}
        <div class="tabel-card">
            <h3 class="judul-tabel">TABEL PERBANDINGAN DATA (TAHUN & PERIODE)</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Periode</th>
                        <th>Investasi (Rp Juta)</th>
                        <th>Investasi (US$ Ribu)</th>
                        <th>Jumlah Proyek</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataPerbandinganPeriode as $row)
                        <tr>
                            <td>{{ $row->tahun }}</td>
                            <td>{{ $row->periode }}</td>
                            <td>{{ number_format($row->investasi_rp_juta, 0, ',', '.') }}</td>
                            <td>{{ number_format($row->investasi_us_ribu, 0, ',', '.') }}</td>
                            <td>{{ $row->proyek ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/lokasi.css') }}">
<style>
.card-section {
    border: 1px solid #ddd;
    padding: 20px;
    margin-bottom: 30px;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Chart Perbandingan Antar Tahun --}}
@if(!empty($dataPerbandingan))
<script>
new Chart(document.getElementById('chartPerbandingan'), {
    type: 'line',
    data: { 
        labels: @json($perbandinganLabels), 
        datasets: [{ 
            label: 'Investasi (Rp Juta)', 
            data: @json($perbandinganData), 
            backgroundColor: 'rgba(54,162,235,0.5)', 
            borderColor: 'rgba(54,162,235,1)', 
            fill: false 
        }] 
    },
    options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
});
</script>
@endif

{{-- Chart Perbandingan Antar Tahun & Periode --}}
@if(!empty($dataPerbandinganPeriode))
<script>
new Chart(document.getElementById('chartPerbandinganPeriode'), {
    type: 'bar',
    data: { 
        labels: @json($perbandinganPeriodeLabels), 
        datasets: [{ 
            label: 'Investasi (Rp Juta)', 
            data: @json($perbandinganPeriodeData), 
            backgroundColor: 'rgba(255,99,132,0.5)', 
            borderColor: 'rgba(255,99,132,1)' 
        }] 
    },
    options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
});
</script>
@endif
@endpush
