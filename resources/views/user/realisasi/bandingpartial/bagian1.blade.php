<div class="card shadow-sm p-4">
    <h2 class="text-center mb-4">Perbandingan Data Realisasi Investasi Antar Tahun di Kalsel</h2>

    {{-- ======================= FILTER ======================== --}}
    <form id="filterBagian1" class="row g-3 justify-content-center mb-4"
          action="{{ route('realisasi.perbandingan') }}" method="GET">
        <div class="col-md-3">
            <label for="jenis" class="form-label">Jenis Investasi:</label>
            <select name="jenis" id="jenis" class="form-select" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="PMA" {{ request('jenis') == 'PMA' ? 'selected' : '' }}>PMA</option>
                <option value="PMDN" {{ request('jenis') == 'PMDN' ? 'selected' : '' }}>PMDN</option>
                <option value="PMA+PMDN" {{ request('jenis') == 'PMA+PMDN' ? 'selected' : '' }}>PMA + PMDN</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="tahun_awal" class="form-label">Tahun Awal:</label>
            <input type="number" name="tahun_awal" id="tahun_awal" class="form-control"
                   value="{{ request('tahun_awal', date('Y')) }}" required>
        </div>
        <div class="col-md-2">
            <label for="tahun_akhir" class="form-label">Tahun Akhir:</label>
            <input type="number" name="tahun_akhir" id="tahun_akhir" class="form-control"
                   value="{{ request('tahun_akhir', date('Y')) }}" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
        </div>
    </form>

    {{-- ======================= CHART ======================== --}}
    @if($dataPerbandingan->isNotEmpty())
        <div class="chart-section mb-4">
            <h4 class="text-center">Grafik Perbandingan Investasi</h4>
            <canvas id="chartInvestasi"></canvas>
        </div>
        <script>
            (function () {
                const ctx = document.getElementById('chartInvestasi').getContext('2d');
                if (window.chartInvestasi) window.chartInvestasi.destroy();
                window.chartInvestasi = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($perbandinganLabels),
                        datasets: [{
                            label: 'Total Investasi (Rp Juta)',
                            data: @json($perbandinganData),
                            backgroundColor: 'rgba(54,162,235,0.6)',
                            borderColor: 'rgba(54,162,235,1)',
                            borderWidth: 1
                        }]
                    },
                    options: { responsive: true, scales:{ y:{ beginAtZero:true } } }
                });
            })();
        </script>
    @endif

    {{-- ======================= TABEL ======================== --}}
    <div class="tabel-wrapper">
        @forelse($rows as $tahun => $rowGroup)
            <div class="mb-4">
                <h4 class="judul-tabel text-center">TABEL DATA TAHUN {{ $tahun }}</h4>

                @if(request('jenis') == 'PMA')
                    {{-- Tabel PMA --}}
                    <table class="table table-bordered table-striped mt-3">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Kabupaten/Kota</th>
                                <th>Proyek</th>
                                <th>Tambahan Investasi (US$ Ribu)</th>
                                <th>Tambahan Investasi (Rp Juta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rowGroup as $row)
                                <tr>
                                    <td>{{ $row->kabupaten_kota }}</td>
                                    <td class="text-center">{{ $row->total_status }}</td>
                                    <td class="text-end">{{ number_format($row->total_usd, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($row->total_rp, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @elseif(request('jenis') == 'PMDN')
                    {{-- Tabel PMDN --}}
                    <table class="table table-bordered table-striped mt-3">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Kabupaten/Kota</th>
                                <th>Proyek</th>
                                <th>Tambahan Investasi (Rp Juta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rowGroup as $row)
                                <tr>
                                    <td>{{ $row->kabupaten_kota }}</td>
                                    <td class="text-center">{{ $row->total_status }}</td>
                                    <td class="text-end">{{ number_format($row->total_rp, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @elseif(request('jenis') == 'PMA+PMDN')
                    {{-- Tabel PMA --}}
                    <h5 class="mt-3 text-center">Tabel PMA</h5>
                    <table class="table table-bordered table-striped mt-2">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Kabupaten/Kota</th>
                                <th>Proyek</th>
                                <th>Tambahan Investasi (US$ Ribu)</th>
                                <th>Tambahan Investasi (Rp Juta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rowGroup['PMA'] as $row)
                                <tr>
                                    <td>{{ $row->kabupaten_kota }}</td>
                                    <td class="text-center">{{ $row->total_status }}</td>
                                    <td class="text-end">{{ number_format($row->total_usd, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($row->total_rp, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Tabel PMDN --}}
                    <h5 class="mt-4 text-center">Tabel PMDN</h5>
                    <table class="table table-bordered table-striped mt-2">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Kabupaten/Kota</th>
                                <th>Total Status</th>
                                <th>Tambahan Investasi (Rp Juta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rowGroup['PMDN'] as $row)
                                <tr>
                                    <td>{{ $row->kabupaten_kota }}</td>
                                    <td class="text-center">{{ $row->total_status }}</td>
                                    <td class="text-end">{{ number_format($row->total_rp, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Tabel Gabungan --}}
                    <h5 class="mt-4 text-center">Tabel Gabungan PMA + PMDN</h5>
                    <table class="table table-bordered table-striped mt-2">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Kabupaten/Kota</th>
                                <th>Proyek</th>
                                <th>Total Investasi (Rp Juta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rowGroup['ALL'] as $row)
                                <tr>
                                    <td>{{ $row->kabupaten_kota }}</td>
                                    <td class="text-center">{{ $row->total_status }}</td>
                                    <td class="text-end">{{ number_format($row->total_rp, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @empty
            <p class="text-center">Tidak ada data untuk periode yang dipilih</p>
        @endforelse
    </div>
</div>


{{-- ======================= SCRIPT AJAX ======================== --}}
<script>
document.addEventListener("DOMContentLoaded", function(){
    const form = document.querySelector("#filterBagian1");
    if(form){
        form.addEventListener("submit", function(e){
            e.preventDefault();
            const targetDiv = document.querySelector("#bagian1");
            targetDiv.innerHTML = `
                <div class="d-flex justify-content-center align-items-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>`;
            fetch(this.action + "?" + new URLSearchParams(new FormData(this)))
                .then(res => res.text())
                .then(html => targetDiv.innerHTML = html);
        });
    }
});
</script>