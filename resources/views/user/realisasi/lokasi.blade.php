@extends('layouts.app')

@section('content')
<section class="lokasi-investasi">

    {{-- =======================         BAGIAN 1: Data Kabupaten/Kota    ======================== --}}
    <div class="card-section">
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

            @if($jenis === 'PMA')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kabupaten/Kota</th>
                            <th>Status</th>
                            <th>Total Investasi (USD Ribu)</th>
                            <th>Total Investasi (Juta Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataLokasi as $lokasi)
                            <tr>
                                <td>{{ $lokasi->kabupaten_kota }}</td>
                                <td>{{ $lokasi->status_penanaman_modal }}</td>
                                <td>{{ number_format($lokasi->total_investasi_us_ribu, 0, ',', '.') }}</td>
                                <td>{{ number_format($lokasi->total_investasi_rp_juta, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($jenis === 'PMDN')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kabupaten/Kota</th>
                            <th>Status</th>
                            <th>Tambahan Investasi (Juta Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataLokasi as $lokasi)
                            <tr>
                                <td>{{ $lokasi->kabupaten_kota }}</td>
                                <td>{{ $lokasi->status_penanaman_modal }}</td>
                                <td>{{ number_format($lokasi->tambahan_investasi_dalam_juta, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($jenis === 'PMA+PMDN')
                <h4>Tabel PMA</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kabupaten/Kota</th>
                            <th>Status</th>
                            <th>Tambahan Investasi (USD Ribu)</th>
                            <th>Tambahan Investasi (Juta Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataPMA as $row)
                            <tr>
                                <td>{{ $row->kabupaten_kota }}</td>
                                <td>{{ $row->status_penanaman_modal }}</td>
                                <td>{{ number_format($row->tambahan_investasi_dalam_ribu_usd, 0, ',', '.') }}</td>
                                <td>{{ number_format($row->tambahan_investasi_dalam_juta, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Tidak ada data PMA</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <h4>Tabel PMDN</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kabupaten/Kota</th>
                            <th>Status</th>
                            <th>Tambahan Investasi (Juta Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataPMDN as $row)
                            <tr>
                                <td>{{ $row->kabupaten_kota }}</td>
                                <td>{{ $row->status_penanaman_modal }}</td>
                                <td>{{ number_format($row->tambahan_investasi_dalam_juta, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center">Tidak ada data PMDN</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <h4>Tabel Gabungan (PMA+PMDN)</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Total Investasi (Juta Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataGabungan as $row)
                            <tr>
                                <td>{{ $row->status_penanaman_modal }}</td>
                                <td>{{ number_format($row->tambahan_investasi_dalam_juta, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center">Tidak ada data gabungan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- =======================    BAGIAN 2: Data Investasi Provinsi    ======================== --}}
    <div class="card-section">
        <h2>Data Investasi Provinsi Kalimantan Selatan</h2>

        {{-- Filter Tahun dan Periode --}}
        <form class="filter-bar" action="{{ route('realisasi.lokasi') }}" method="GET">
            <select class="dropdown-tahun" name="tahun2">
                <option value="">Pilih Tahun</option>
                @foreach(range(date('Y'), 2010) as $th)
                    <option value="{{ $th }}" {{ request('tahun2') == $th ? 'selected' : '' }}>{{ $th }}</option>
                @endforeach
            </select>

            <select class="dropdown-periode" name="triwulan2">
                <option value="">Pilih Periode</option>
                <option value="Tahun" {{ request('triwulan2') == 'Tahun' ? 'selected' : '' }}>1 Tahun</option>
                <option value="Triwulan 1" {{ request('triwulan2') == 'Triwulan 1' ? 'selected' : '' }}>Triwulan 1</option>
                <option value="Triwulan 2" {{ request('triwulan2') == 'Triwulan 2' ? 'selected' : '' }}>Triwulan 2</option>
                <option value="Triwulan 3" {{ request('triwulan2') == 'Triwulan 3' ? 'selected' : '' }}>Triwulan 3</option>
                <option value="Triwulan 4" {{ request('triwulan2') == 'Triwulan 4' ? 'selected' : '' }}>Triwulan 4</option>
            </select>

            <button type="submit" name="filter" value="top_investasi">5 Realisasi Investasi Terbesar Berdasarkan Kota</button>
            <button type="submit" name="filter" value="top_proyek">5 Proyek Terbesar Berdasarkan Kota</button>
            <button type="submit" name="filter" value="sektor">Berdasarkan Sektor</button>

            {{-- Tombol Download --}}
            <a href="#" class="btn-download">
                <i class="fas fa-download"></i> Download
            </a>
        </form>

        {{-- Tabel Data --}}
        <div class="table-container">
            @if(request('filter') == 'top_investasi')
                <h3>5 Realisasi Investasi Terbesar - PMDN</h3>
                <table class="tabel-investasi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kabupaten/Kota</th>
                            <th>Total Investasi (Rp Juta)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topPMDN as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->kabupaten_kota }}</td>
                                <td>{{ number_format($row->total_investasi_rp_juta, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif(request('filter') == 'top_proyek')
                <h3>5 Proyek Terbesar - PMA</h3>
                <table class="tabel-investasi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kabupaten/Kota</th>
                            <th>Total Proyek PMA</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- PMA --}}
                        @forelse($topPMA as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->kabupaten_kota }}</td>
                            <td>{{ $row->total_proyek }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
                        @endforelse

                    </tbody>
                </table>

                <h3>5 Proyek Terbesar - PMDN</h3>
                <table class="tabel-investasi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kabupaten/Kota</th>
                            <th>Total Proyek PMDN</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- PMDN --}}
                        @forelse($topPMDN as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->kabupaten_kota }}</td>
                            <td>{{ $row->total_proyek }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
                        @endforelse

                    </tbody>
                </table>
                
            @elseif(request('filter') == 'sektor')
            <h3>Data Investasi Berdasarkan Sektor</h3>
            <table class="tabel-investasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kabupaten/Kota</th>
                        <th>Status</th>
                        <th>Tambahan Investasi (USD Ribu)</th>
                        <th>Tambahan Investasi (Juta Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sektor as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->kabupaten_kota }}</td>
                            <td>{{ $row->status_penanaman_modal }}</td>
                            <td>{{ number_format($row->total_usd, 0, ',', '.') }}</td>
                            <td>{{ number_format($row->total_rp, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada data sektor</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif
        </div>
    </div>

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
@if(!empty($chartLabels))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartLokasi').getContext('2d');
    new Chart(ctx, {
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
@endif
@endpush
