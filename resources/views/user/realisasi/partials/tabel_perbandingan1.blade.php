{{-- resources/views/user/realisasi/partials/tabel_perbandingan1.blade.php --}}

{{-- Chart --}}
@if(($jenis === 'PMA' || $jenis === 'PMDN' || $jenis === 'PMA+PMDN') && ($dataTahun1->isNotEmpty() || $dataTahun2->isNotEmpty()))
    <div class="mb-4" style="width: 100%; height: 400px;">
        <canvas id="chartTotalInvestasi1"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('chartTotalInvestasi1');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ["{{ $tahun1 }}", "{{ $tahun2 }}"],
                datasets: [{
                    label: 'Total Investasi (Rp Juta)',
                    data: [
                        {{ $dataTahun1->sum('total_investasi_rp_juta') }},
                        {{ $dataTahun2->sum('total_investasi_rp_juta') }}
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
</script>

@endif


{{-- Tabel --}}
@if($jenis === 'PMA')
    <h5 class="mt-4">Tahun {{ $tahun1 }}</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kabupaten/Kota</th>
                <th>Status</th>
                <th>Proyek</th>
                <th>Total Investasi (USD Ribu)</th>
                <th>Total Investasi (Juta Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataTahun1 as $lokasi)
                <tr>
                    <td>{{ $lokasi->kabupaten_kota }}</td>
                    <td>{{ $lokasi->status_penanaman_modal }}</td>
                    <td>{{ $lokasi->proyekpma }}</td>
                    <td>{{ number_format($lokasi->total_investasi_us_ribu ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($lokasi->total_investasi_rp_juta ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
            @endforelse
            @if($dataTahun1->isNotEmpty())
                <tr class="fw-bold">
                    <td colspan="2" class="text-center">Total</td>
                    <td>{{ $dataTahun1->sum('proyekpma') }}</td>
                    <td>{{ number_format($dataTahun1->sum('total_investasi_us_ribu'), 0, ',', '.') }}</td>
                    <td>{{ number_format($dataTahun1->sum('total_investasi_rp_juta'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <h5 class="mt-4">Tahun {{ $tahun2 }}</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kabupaten/Kota</th>
                <th>Status</th>
                <th>Proyek</th>
                <th>Total Investasi (USD Ribu)</th>
                <th>Total Investasi (Juta Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataTahun2 as $lokasi)
                <tr>
                    <td>{{ $lokasi->kabupaten_kota }}</td>
                    <td>{{ $lokasi->status_penanaman_modal }}</td>
                    <td>{{ $lokasi->proyekpma }}</td>
                    <td>{{ number_format($lokasi->total_investasi_us_ribu ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($lokasi->total_investasi_rp_juta ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
            @endforelse
            @if($dataTahun2->isNotEmpty())
                <tr class="fw-bold">
                    <td colspan="2" class="text-center">Total</td>
                    <td>{{ $dataTahun2->sum('proyekpma') }}</td>
                    <td>{{ number_format($dataTahun2->sum('total_investasi_us_ribu'), 0, ',', '.') }}</td>
                    <td>{{ number_format($dataTahun2->sum('total_investasi_rp_juta'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

@elseif($jenis === 'PMDN')
    {{-- sama, pakai $dataTahun1 dan $dataTahun2 untuk PMDN --}}
    <h5 class="mt-4">Tahun {{ $tahun1 }}</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kabupaten/Kota</th>
                <th>Status</th>
                <th>Proyek</th>
                <th>Total Investasi (Juta Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataTahun1 as $lokasi)
                <tr>
                    <td>{{ $lokasi->kabupaten_kota }}</td>
                    <td>{{ $lokasi->status_penanaman_modal }}</td>
                    <td>{{ $lokasi->proyekpmdn }}</td>
                    <td>{{ number_format($lokasi->total_investasi_rp_juta ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
            @endforelse
            @if($dataTahun1->isNotEmpty())
                <tr class="fw-bold">
                    <td colspan="2" class="text-center">Total</td>
                    <td>{{ $dataTahun1->sum('proyekpmdn') }}</td>
                    <td>{{ number_format($dataTahun1->sum('total_investasi_rp_juta'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <h5 class="mt-4">Tahun {{ $tahun2 }}</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kabupaten/Kota</th>
                <th>Status</th>
                <th>Proyek</th>
                <th>Total Investasi (Juta Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataTahun2 as $lokasi)
                <tr>
                    <td>{{ $lokasi->kabupaten_kota }}</td>
                    <td>{{ $lokasi->status_penanaman_modal }}</td>
                    <td>{{ $lokasi->proyekpmdn }}</td>
                    <td>{{ number_format($lokasi->total_investasi_rp_juta ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
            @endforelse
            @if($dataTahun2->isNotEmpty())
                <tr class="fw-bold">
                    <td colspan="2" class="text-center">Total</td>
                    <td>{{ $dataTahun2->sum('proyekpmdn') }}</td>
                    <td>{{ number_format($dataTahun2->sum('total_investasi_rp_juta'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

@elseif($jenis === 'PMA+PMDN')
    <h5 class="mt-4">Tahun {{ $tahun1 }}</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kabupaten/Kota</th>
                <th>Proyek PMDN</th>
                <th>Total Investasi PMDN (Juta Rp)</th>
                <th>Proyek PMA</th>
                <th>Total Investasi PMA (USD Ribu)</th>
                <th>Total Investasi PMA (Juta Rp)</th>
                <th>Total Proyek (PMA+PMDN)</th>
                <th>Total Investasi (Juta Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataTahun1 as $lokasi)
                <tr>
                    <td>{{ $lokasi->kabupaten_kota }}</td>
                    <td>{{ $lokasi->proyekpmdn }}</td>
                    <td>{{ number_format($lokasi->total_investasi_pmdn_rp ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $lokasi->proyekpma }}</td>
                    <td>{{ number_format($lokasi->total_investasi_pma_us ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($lokasi->total_investasi_pma_rp ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $lokasi->total_proyek }}</td>
                    <td>{{ number_format($lokasi->total_investasi_rp_all ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Tidak ada data</td></tr>
            @endforelse
            @if($dataTahun1->isNotEmpty())
                <tr class="fw-bold">
                    <td colspan="1" class="text-center">Total</td>
                    <td>{{ $dataTahun1->sum('proyekpmdn') }}</td>
                    <td>{{ number_format($dataTahun1->sum('total_investasi_pmdn_rp'), 0, ',', '.') }}</td>
                    <td>{{ $dataTahun1->sum('proyekpma') }}</td>
                    <td>{{ number_format($dataTahun1->sum('total_investasi_pma_us'), 0, ',', '.') }}</td>
                    <td>{{ number_format($dataTahun1->sum('total_investasi_pma_rp'), 0, ',', '.') }}</td>
                    <td>{{ $dataTahun1->sum('total_proyek') }}</td>
                    <td>{{ number_format($dataTahun1->sum('total_investasi_rp_all'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <h5 class="mt-4">Tahun {{ $tahun2 }}</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kabupaten/Kota</th>
                <th>Proyek PMDN</th>
                <th>Total Investasi PMDN (Juta Rp)</th>
                <th>Proyek PMA</th>
                <th>Total Investasi PMA (USD Ribu)</th>
                <th>Total Investasi PMA (Juta Rp)</th>
                <th>Total Proyek (PMA+PMDN)</th>
                <th>Total Investasi (Juta Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataTahun2 as $lokasi)
                <tr>
                    <td>{{ $lokasi->kabupaten_kota }}</td>
                    <td>{{ $lokasi->proyekpmdn }}</td>
                    <td>{{ number_format($lokasi->total_investasi_pmdn_rp ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $lokasi->proyekpma }}</td>
                    <td>{{ number_format($lokasi->total_investasi_pma_us ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($lokasi->total_investasi_pma_rp ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $lokasi->total_proyek }}</td>
                    <td>{{ number_format($lokasi->total_investasi_rp_all ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Tidak ada data</td></tr>
            @endforelse
            @if($dataTahun2->isNotEmpty())
                <tr class="fw-bold">
                    <td colspan="1" class="text-center">Total</td>
                    <td>{{ $dataTahun2->sum('proyekpmdn') }}</td>
                    <td>{{ number_format($dataTahun2->sum('total_investasi_pmdn_rp'), 0, ',', '.') }}</td>
                    <td>{{ $dataTahun2->sum('proyekpma') }}</td>
                    <td>{{ number_format($dataTahun2->sum('total_investasi_pma_us'), 0, ',', '.') }}</td>
                    <td>{{ number_format($dataTahun2->sum('total_investasi_pma_rp'), 0, ',', '.') }}</td>
                    <td>{{ $dataTahun2->sum('total_proyek') }}</td>
                    <td>{{ number_format($dataTahun2->sum('total_investasi_rp_all'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif

{{-- ... (tabel kamu tetap sama, tidak diubah) ... --}}

