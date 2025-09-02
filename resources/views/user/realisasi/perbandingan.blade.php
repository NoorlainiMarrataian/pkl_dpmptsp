@extends('layouts.app')

@section('content')
<section class="perbandingan-investasi">

    {{-- ======================= BAGIAN 1 ======================== --}}

    <div class="card shadow-sm p-4">
        <h2 class="text-center mb-4">Perbandingan Data Realisasi Investasi Antar Tahun di Kalsel</h2>

        {{-- ======================= FILTER ======================== --}}
        <form class="filter-bar row g-3 justify-content-center mb-4" action="{{ route('realisasi.perbandingan') }}" method="GET">
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

            <div class="col-md-2 d-flex align-items-end">
                <a href="#" class="btn btn-success w-100" id="openPopupBagian1">
                    <i class="fas fa-download"></i> Unduh Bagian 1
                </a>
            </div>
            
        </form>

        {{-- ======================= CHART ======================== --}}
        @if($dataPerbandingan->isNotEmpty())
            <div class="chart-section mb-4">
                <h4 class="text-center">Grafik Perbandingan Investasi</h4>
                <canvas id="chartInvestasi"></canvas>
            </div>
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
                <p class="text-center">Silahkan pilih tahun</p>
            @endforelse
        </div>
    </div>

    
    {{-- ======================= BAGIAN 2 ======================== --}}
    <div class="card shadow-sm p-4" id="bagian2-content">
    <h2 class="text-center mb-4">Perbandingan Data Antar Tahun-Periode di Kalsel</h2>

    {{-- ======================= FILTER ======================== --}}
    <form class="filter-bar row g-3 justify-content-center mb-4" id="form-bagian2" action="{{ route('realisasi.perbandingan') }}" method="GET">
        <div class="col-md-3">
            <label for="jenis_investasi" class="form-label">Jenis Investasi:</label>
            <select name="jenis_investasi" id="jenis_investasi" class="form-select" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="PMA" {{ request('jenis_investasi') == 'PMA' ? 'selected' : '' }}>PMA</option>
                <option value="PMDN" {{ request('jenis_investasi') == 'PMDN' ? 'selected' : '' }}>PMDN</option>
                <option value="PMA+PMDN" {{ request('jenis_investasi') == 'PMA+PMDN' ? 'selected' : '' }}>PMA + PMDN</option>
            </select>
        </div>

        {{-- Tahun & Periode Awal --}}
    <div class="col-md-2">
        <label for="tahun_awal4" class="form-label">Tahun Awal:</label>
        <input type="number" name="tahun_awal4" id="tahun_awal4" class="form-control"
               value="{{ request('tahun_awal4', date('Y')) }}" required>
    </div>
    <div class="col-md-2">
        <label for="periode_awal4" class="form-label">Periode Awal:</label>
        <select name="periode_awal4" id="periode_awal4" class="form-select" required>
            <option value="">-- Pilih Periode --</option>
            @foreach(['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'] as $p)
                <option value="{{ $p }}" {{ request('periode_awal4') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tahun & Periode Akhir --}}
    <div class="col-md-2">
        <label for="tahun_akhir4" class="form-label">Tahun Akhir:</label>
        <input type="number" name="tahun_akhir4" id="tahun_akhir4" class="form-control"
               value="{{ request('tahun_akhir4', date('Y')) }}" required>
    </div>
    <div class="col-md-2">
        <label for="periode_akhir4" class="form-label">Periode Akhir:</label>
        <select name="periode_akhir4" id="periode_akhir4" class="form-select" required>
            <option value="">-- Pilih Periode --</option>
            @foreach(['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'] as $p)
                <option value="{{ $p }}" {{ request('periode_akhir4') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
    </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <a href="#" class="btn btn-success w-100" id="openPopupBagian2">
                <i class="fas fa-download"></i> Unduh Bagian 2
            </a>
        </div>

    </form>

    {{-- ======================= CHART ======================== --}}
    @if($dataPerbandinganPeriode->isNotEmpty())
        <div class="chart-section mb-4">
            <h4 class="text-center">Grafik Perbandingan Investasi</h4>
            <canvas id="chartInvestasiPeriode"></canvas>
        </div>
    @endif

    {{-- ======================= TABEL ======================== --}}
    <div class="tabel-wrapper">
    @forelse($dataPerbandinganPeriodeByTahun as $tahun => $rowGroup)
            <div class="mb-4">
                <h4 class="judul-tabel text-center">TABEL DATA TAHUN {{ $tahun }}</h4>

                @if(request('jenis_investasi') == 'PMA')
                    <table class="table table-bordered table-striped mt-3">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Kabupaten/Kota</th>
                                <th>Periode</th>
                                <th>Proyek</th>
                                <th>Tambahan Investasi (US$ Ribu)</th>
                                <th>Tambahan Investasi (Rp Juta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rowGroup as $row)
                                <tr>
                                    <td>{{ $row->kabupaten_kota }}</td>
                                    <td class="text-center">{{ $row->periode }}</td>
                                    <td class="text-center">{{ $row->total_status }}</td>
                                    <td class="text-end">{{ number_format($row->total_usd, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($row->total_rp, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @elseif(request('jenis_investasi') == 'PMDN')
                    <table class="table table-bordered table-striped mt-3">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Kabupaten/Kota</th>
                                <th>Periode</th>
                                <th>Proyek</th>
                                <th>Tambahan Investasi (Rp Juta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rowGroup as $row)
                                <tr>
                                    <td>{{ $row->kabupaten_kota }}</td>
                                    <td class="text-center">{{ $row->periode }}</td>
                                    <td class="text-center">{{ $row->total_status }}</td>
                                    <td class="text-end">{{ number_format($row->total_rp, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @elseif(request('jenis_investasi') == 'PMA+PMDN')
                    {{-- Tabel PMA --}}
                    <h5 class="mt-3 text-center">Tabel PMA</h5>
                    <table class="table table-bordered table-striped mt-2">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Kabupaten/Kota</th>
                                <th>Periode</th>
                                <th>Proyek</th>
                                <th>Tambahan Investasi (US$ Ribu)</th>
                                <th>Tambahan Investasi (Rp Juta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(($rowGroup['PMA'] ?? collect()) as $row)
                                <tr>
                                    <td>{{ $row->kabupaten_kota }}</td>
                                    <td class="text-center">{{ $row->periode }}</td>
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
                                <th>Periode</th>
                                <th>Proyek</th>
                                <th>Tambahan Investasi (Rp Juta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(($rowGroup['PMDN'] ?? collect()) as $row)
                                <tr>
                                    <td>{{ $row->kabupaten_kota }}</td>
                                    <td class="text-center">{{ $row->periode }}</td>
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
                                <th>Periode</th>
                                <th>Proyek</th>
                                <th>Total Investasi (Rp Juta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(($rowGroup['ALL'] ?? collect()) as $row)
                                <tr>
                                    <td>{{ $row->kabupaten_kota }}</td>
                                    <td class="text-center">{{ $row->periode }}</td>
                                    <td class="text-center">{{ $row->total_status }}</td>
                                    <td class="text-end">{{ number_format($row->total_rp, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @empty
            <p class="text-center">Silakan pilih tahun dan periode</p>
        @endforelse
    </div>
</div>

</section>
@endsection

{{-- ======================= POPUP ======================== --}}
<div id="popupForm" class="popup-overlay">
    <div class="popup-content">
        <h2>Data Diri</h2>
        <div class="warning-icon">
            <i class="fas fa-exclamation"></i>
        </div>
        <p>kan isi formulir untuk mengunduh file ini</p>

        <form id="downloadForm" method="POST" action="{{ route('log_pengunduhan.store') }}">
            @csrf
            <div class="checkbox-group horizontal">
                <label><input type="radio" name="kategori_pengunduh" value="Individu" required> Individu</label>
                <label><input type="radio" name="kategori_pengunduh" value="Perusahaan"> Perusahaan</label>
                <label><input type="radio" name="kategori_pengunduh" value="Lainnya"> Lainnya</label>
            </div>
            <input type="text" name="nama_instansi" placeholder="Nama Lengkap/Instansi" required>
            <input type="email" name="email_pengunduh" placeholder="Email" required>
            <input type="text" name="telpon" placeholder="Telpon">
            <textarea name="keperluan" placeholder="Keperluan"></textarea>
            <div class="checkbox-group">
                <label><input type="checkbox" required> Anda setuju bertanggung jawab atas data yang diunduh</label>
                <label><input type="checkbox" required> Pihak DPMPTSP tidak bertanggung jawab atas dampak penggunaan data</label>
            </div>
            <div class="popup-buttons">
                <button type="submit" class="btn-blue">Unduh</button>
                <button type="button" id="closePopup" class="btn-red">Batalkan</button>
            </div>
        </form>
    </div>
</div>

<!-- Script Unduh Bagian 1 & 2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('popupForm');
    const closeBtn = document.getElementById('closePopup');
    const form = document.getElementById('downloadForm');
    let target = null; // Menyimpan bagian mana yang dipilih

    // Tombol buka popup Bagian 1
    const btnBagian1 = document.getElementById('openPopupBagian1');
    if (btnBagian1) {
        btnBagian1.addEventListener('click', function(e) {
            e.preventDefault();
            target = 'bagian1';
            popup.style.display = 'flex';
        });
    }

    // Tombol buka popup Bagian 2
    const btnBagian2 = document.getElementById('openPopupBagian2');
    if (btnBagian2) {
        btnBagian2.addEventListener('click', function(e) {
            e.preventDefault();
            target = 'bagian2';
            popup.style.display = 'flex';
        });
    }

    // Tombol tutup popup
    closeBtn.addEventListener('click', function() {
        popup.style.display = 'none';
    });

    // Submit form popup
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Simpan log ke server
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            // Pilih bagian sesuai target
            let element = null;
            if (target === 'bagian1') {
                element = document.querySelector('.tabel-wrapper');
            } else if (target === 'bagian2') {
                element = document.getElementById('bagian2-content');
            }

            if (element) {
                html2pdf().from(element).set({
                    margin: 0.5,
                    filename: target === 'bagian1' ? 'Bagian1.pdf' : 'Bagian2.pdf',
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
                }).save();
            }

            popup.style.display = 'none';
        });
    });
});
</script>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ========================== BAGIAN 1 ==========================
    @if($dataPerbandingan->isNotEmpty())
        const ctx1 = document.getElementById('chartInvestasi').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: {!! json_encode($perbandinganLabels ?? []) !!}, // label = tahun
                datasets: [{
                    label: 'Investasi (Rp Juta)',
                    data: {!! json_encode($perbandinganData ?? []) !!},
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: '#3498db',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 7
                }]
            },
            options: { 
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: { 
                    y: { beginAtZero: true } 
                }
            }
        });
    @endif

    // ========================== BAGIAN 2 ==========================
    @if($dataPerbandinganPeriode->isNotEmpty())
        const ctx2 = document.getElementById('chartInvestasiPeriode').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: {!! json_encode($perbandinganPeriodeLabels ?? []) !!}, // label = tahun + periode
                datasets: [{
                    label: 'Investasi (Rp Juta)',
                    data: {!! json_encode($perbandinganPeriodeData ?? []) !!},
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: '#e74c3c',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 7
                }]
            },
            options: { 
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: { 
                    y: { beginAtZero: true } 
                }
            }
        });
    @endif
</script>
@endpush


<link rel="stylesheet" href="{{ asset('css/perbandingan.css') }}">
@stack('styles')

@push('scripts')
<script>
$('#form-bagian2').on('submit', function(e) {
    // jika tombol download ditekan, biarkan submit normal
    if($(document.activeElement).attr('name') === 'download') return;

    e.preventDefault();
    let url = $(this).attr('action');
    let data = $(this).serialize();

    $.get(url, data, function(response) {
        $('#bagian2-content').html(response);
    });
});
</script>
@endpush
