{{-- partial: lokasi_kabkota.blade.php --}}
    <h2 class="judul-lokasi">Data Realisasi Investasi Berdasarkan Kabupaten/Kota</h2>

    {{-- Filter Tahun, Jenis, dan Periode --}}
    <form class="filter-bar" action="{{ route('realisasi.lokasi') }}" method="GET">
        <select class="dropdown-tahun" name="tahun">
            <option value="">Pilih Tahun</option>
            @foreach(range(date('Y'), 2010) as $th)
                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
            @endforeach
        </select>

        <select class="dropdown-jenis" name="jenis">
            <option value="">Pilih Status</option>
            <option value="PMA" {{ request('jenis') == 'PMA' ? 'selected' : '' }}>PMA</option>
            <option value="PMDN" {{ request('jenis') == 'PMDN' ? 'selected' : '' }}>PMDN</option>
            <option value="PMA+PMDN" {{ request('jenis') == 'PMA+PMDN' ? 'selected' : '' }}>PMA + PMDN</option>
        </select>

        <button type="submit" name="triwulan" value="Tahun">1 Tahun</button>
        <button type="submit" name="triwulan" value="Triwulan 1">Triwulan 1</button>
        <button type="submit" name="triwulan" value="Triwulan 2">Triwulan 2</button>
        <button type="submit" name="triwulan" value="Triwulan 3">Triwulan 3</button>
        <button type="submit" name="triwulan" value="Triwulan 4">Triwulan 4</button>

        {{-- Hidden input untuk Bagian 2 --}}
        <input type="hidden" name="tahun2" value="{{ request('tahun2') }}">
        <input type="hidden" name="triwulan2" value="{{ request('triwulan2') }}">
        <input type="hidden" name="jenisBagian2" value="{{ request('jenisBagian2') }}">

        {{-- Tombol Download dengan popup --}}
        <a href="#" class="btn-download" id="openPopupLokasi">
            <i class="fas fa-download"></i> Download
        </a>
    </form>

    {{-- === AREA UNTUK PDF: Grafik + Tabel === --}}
    <div id="exportArea">
        {{-- Grafik Lokasi --}}
        <div class="grafik-card">
            <h3 class="judul-grafik">GRAFIK DATA LOKASI</h3>
            <div style="position: relative; height:400px; width:100%">
            <canvas id="chartLokasi"></canvas>
        </div>

        {{-- Tabel Data Lokasi --}}
        <div class="tabel-card">
            <h3 class="judul-tabel">TABEL DATA LOKASI</h3>

            @if($jenisBagian1 === 'PMA')
                {{-- tabel PMA --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kabupaten/Kota</th>
                            <th>Status</th>
                            <th>Proyek</th>
                            @if(request('triwulan') && request('triwulan') !== 'Tahun') 
                                <th>Periode</th> 
                            @endif
                            <th>Total Investasi (USD Ribu)</th>
                            <th>Total Investasi (Juta Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataLokasi as $lokasi)
                            <tr>
                                <td>{{ $lokasi->kabupaten_kota }}</td>
                                <td>{{ $lokasi->status_penanaman_modal }}</td>
                                <td>{{ $lokasi->proyekpma }}</td>
                                @if(request('triwulan') && request('triwulan') !== 'Tahun') 
                                    <td>{{ $lokasi->periode }}</td> 
                                @endif
                                <td>{{ number_format($lokasi->total_investasi_us_ribu ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($lokasi->total_investasi_rp_juta ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ request('triwulan') && request('triwulan') !== 'Tahun' ? 6 : 5 }}" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($jenisBagian1 === 'PMDN')
                {{-- tabel PMDN --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kabupaten/Kota</th>
                            <th>Status</th>
                            <th>Proyek</th>
                            @if(request('triwulan') && request('triwulan') !== 'Tahun') 
                                <th>Periode</th> 
                            @endif
                            <th>Tambahan Investasi (Juta Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataLokasi as $lokasi)
                            <tr>
                                <td>{{ $lokasi->kabupaten_kota }}</td>
                                <td>{{ $lokasi->status_penanaman_modal }}</td>
                                <td>{{ $lokasi->proyekpmdn }}</td>
                                @if(request('triwulan') && request('triwulan') !== 'Tahun') 
                                    <td>{{ $lokasi->periode }}</td> 
                                @endif
                                <td>{{ number_format($lokasi->total_investasi_rp_juta ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ request('triwulan') && request('triwulan') !== 'Tahun' ? 5 : 4 }}" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($jenisBagian1 === 'PMA+PMDN')
                {{-- tabel gabungan --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kabupaten/Kota</th>
                            @if(request('triwulan') && request('triwulan') !== 'Tahun') 
                                <th>Periode</th> 
                            @endif
                            <th>Proyek PMDN</th>
                            <th>Total Investasi PMDN (Rp Juta)</th>
                            <th>Proyek PMA</th>
                            <th>Total Investasi PMA (Ribu US$)</th>
                            <th>Total Investasi PMA (Rp Juta)</th>
                            <th>Total Proyek (PMA + PMDN)</th>
                            <th>Total Investasi (Rp Juta, PMA+PMDN)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataLokasi as $lokasi)
                            <tr>
                                <td>{{ $lokasi->kabupaten_kota }}</td>
                                @if(request('triwulan') && request('triwulan') !== 'Tahun') 
                                    <td>{{ $lokasi->periode ?? '-' }}</td> 
                                @endif
                                <td>{{ $lokasi->proyekpmdn ?? 0 }}</td>
                                <td>{{ number_format($lokasi->total_investasi_pmdn_rp ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $lokasi->proyekpma ?? 0 }}</td>
                                <td>{{ number_format($lokasi->total_investasi_pma_us ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($lokasi->total_investasi_pma_rp ?? 0, 0, ',', '.') }}</td>
                                <td>{{ ($lokasi->proyekpmdn ?? 0) + ($lokasi->proyekpma ?? 0) }}</td>
                                <td>{{ number_format(($lokasi->total_investasi_pmdn_rp ?? 0) + ($lokasi->total_investasi_pma_rp ?? 0), 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@push('scripts')
@if(!empty($chartLabels))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartLokasi')?.getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Investasi Rp',
                    data: @json($chartData),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
            responsive: true,
            maintainAspectRatio: false, // biar fleksibel
            aspectRatio: 2,             // lebar : tinggi
            plugins: { 
                legend: { display: false } 
            },
            scales: { 
                y: { beginAtZero: true } 
            }
        }
        });
    }
</script>
@endif
@endpush

{{-- endpartial --}}
