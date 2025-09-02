{{-- resources/views/user/realisasi/partials/tabel_perbandingan2.blade.php --}}

@foreach([1,2] as $i)
    @php
        $data = $i === 1 ? $dataTriwulan1 : $dataTriwulan2;
        $tahun = $i === 1 ? $tahun1 : $tahun2;
        $periode = $i === 1 ? $periode1 : $periode2;
    @endphp

    <h5 class="mt-4">Tahun {{ $tahun }} - {{ $periode }}</h5>

    @if($jenis === 'PMA' || $jenis === 'PMDN')
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kabupaten/Kota</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>Proyek</th>
                    @if($jenis === 'PMA')
                        <th>Total Investasi (USD Ribu)</th>
                        <th>Total Investasi (Juta Rp)</th>
                    @else
                        <th>Total Investasi (Juta Rp)</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($data as $lokasi)
                    <tr>
                        <td>{{ $lokasi->kabupaten_kota }}</td>
                        <td>{{ $periode }}</td>
                        <td>{{ $lokasi->status_penanaman_modal }}</td>
                        <td>{{ $jenis === 'PMA' ? $lokasi->proyekpma : $lokasi->proyekpmdn }}</td>
                        @if($jenis === 'PMA')
                            <td>{{ number_format($lokasi->total_investasi_us_ribu ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($lokasi->total_investasi_rp_juta ?? 0, 0, ',', '.') }}</td>
                        @else
                            <td>{{ number_format($lokasi->total_investasi_rp_juta ?? 0, 0, ',', '.') }}</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $jenis === 'PMA' ? 6 : 5 }}" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse

                @if($data->isNotEmpty())
                    <tr class="fw-bold">
                        <td colspan="3" class="text-center">Total</td>
                        <td>{{ $jenis === 'PMA' ? $data->sum('proyekpma') : $data->sum('proyekpmdn') }}</td>
                        @if($jenis === 'PMA')
                            <td>{{ number_format($data->sum('total_investasi_us_ribu'), 0, ',', '.') }}</td>
                            <td>{{ number_format($data->sum('total_investasi_rp_juta'), 0, ',', '.') }}</td>
                        @else
                            <td>{{ number_format($data->sum('total_investasi_rp_juta'), 0, ',', '.') }}</td>
                        @endif
                    </tr>
                @endif
            </tbody>
        </table>

    @elseif($jenis === 'PMA+PMDN')
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kabupaten/Kota</th>
                    <th>Periode</th>
                    <th>Status (PMDN)</th>
                    <th>Proyek (PMDN)</th>
                    <th>Total Investasi (PMDN Juta Rp)</th>
                    <th>Status (PMA)</th>
                    <th>Proyek (PMA)</th>
                    <th>Total Investasi (PMA USD Ribu)</th>
                    <th>Total Investasi (PMA Juta Rp)</th>
                    <th>Total Proyek (PMA+PMDN)</th>
                    <th>Total Investasi (PMA+PMDN Juta Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $lokasi)
                    <tr>
                        <td>{{ $lokasi->kabupaten_kota }}</td>
                        <td>{{ $periode }}</td>
                        <td>PMDN</td>
                        <td>{{ $lokasi->proyekpmdn ?? 0 }}</td>
                        <td>{{ number_format($lokasi->total_investasi_pmdn_rp ?? 0, 0, ',', '.') }}</td>
                        <td>PMA</td>
                        <td>{{ $lokasi->proyekpma ?? 0 }}</td>
                        <td>{{ number_format($lokasi->total_investasi_pma_us ?? 0, 0, ',', '.') }}</td>
                        <td>{{ number_format($lokasi->total_investasi_pma_rp ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $lokasi->total_proyek ?? 0 }}</td>
                        <td>{{ number_format($lokasi->total_investasi_rp_all ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse

                @if($data->isNotEmpty())
                    <tr class="fw-bold">
                        <td colspan="3" class="text-center">Total</td>
                        <td>{{ $data->sum('proyekpmdn') }}</td>
                        <td>{{ number_format($data->sum('total_investasi_pmdn_rp'), 0, ',', '.') }}</td>
                        <td></td>
                        <td>{{ $data->sum('proyekpma') }}</td>
                        <td>{{ number_format($data->sum('total_investasi_pma_us'), 0, ',', '.') }}</td>
                        <td>{{ number_format($data->sum('total_investasi_pma_rp'), 0, ',', '.') }}</td>
                        <td>{{ $data->sum('total_proyek') }}</td>
                        <td>{{ number_format($data->sum('total_investasi_rp_all'), 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif
@endforeach
