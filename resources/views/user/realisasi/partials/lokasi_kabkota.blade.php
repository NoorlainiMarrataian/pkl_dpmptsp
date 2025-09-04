{{-- partial: lokasi_kabkota.blade.php --}}
<div class="card-section">
    <h2 class="judul-lokasi">Data Realisasi Investasi Kalimantan Selatan Berdasarkan Kabupaten/Kota</h2>

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

    {{-- Grafik Lokasi --}}
    <div class="grafik-card">
        <h3 class="judul-grafik" >GRAFIK DATA LOKASI</h3>
        <canvas id="chartLokasi" width="1000" height="400"></canvas>
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
                        @if(request('triwulan') && request('triwulan') !== 'Tahun') <th>Periode</th> @endif
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
                            @if(request('triwulan') && request('triwulan') !== 'Tahun') <td>{{ $lokasi->periode }}</td> @endif
                            <td>{{ number_format($lokasi->total_investasi_us_ribu ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($lokasi->total_investasi_rp_juta ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ request('triwulan') && request('triwulan') !== 'Tahun' ? 6 : 5 }}" class="text-center">Tidak ada data</td></tr>
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
                        @if(request('triwulan') && request('triwulan') !== 'Tahun') <th>Periode</th> @endif
                        <th>Tambahan Investasi (Juta Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataLokasi as $lokasi)
                        <tr>
                            <td>{{ $lokasi->kabupaten_kota }}</td>
                            <td>{{ $lokasi->status_penanaman_modal }}</td>
                            <td>{{ $lokasi->proyekpmdn }}</td>
                            @if(request('triwulan') && request('triwulan') !== 'Tahun') <td>{{ $lokasi->periode }}</td> @endif
                            <td>{{ number_format($lokasi->total_investasi_rp_juta ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ request('triwulan') && request('triwulan') !== 'Tahun' ? 5 : 4 }}" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>

        @elseif($jenisBagian1 === 'PMA+PMDN')
            {{-- tabel gabungan --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kabupaten/Kota</th>
                        @if(request('triwulan') && request('triwulan') !== 'Tahun') <th>Periode</th> @endif
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
                            @if(request('triwulan') && request('triwulan') !== 'Tahun') <td>{{ $lokasi->periode ?? '-' }}</td> @endif
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

{{-- Popup Modal Unduh --}}
<div id="popupFormLokasi" class="popup-overlay">
    <div class="popup-content">
        <h2>Data Diri</h2>
        <div class="warning-icon"><i class="fas fa-exclamation"></i></div>
        <p>Silahkan isi formulir untuk mengunduh file ini</p>

        <form id="downloadFormLokasi" method="POST" action="{{ route('log_pengunduhan.store') }}">
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
                <button type="button" id="closePopupLokasi" class="btn-red">Batalkan</button>
            </div>
        </form>
    </div>
</div>



@push('scripts')
<script 
src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    //Popup Form
    document.getElementById("openPopupLokasi").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("popupFormLokasi").style.display = "flex";
    });

    document.getElementById("closePopupLokasi").addEventListener("click", function() {
        document.getElementById("popupFormLokasi").style.display = "none";
    });


    //Download PDF
    document.getElementById("downloadFormLokasi").addEventListener("submit", function(e) {
        e.preventDefault(); // Prevent form submission
        var form= this;
        var formData = new FormData(form);
        fetch(form.action, {
            method:'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                setTimeout(function() {
                    var element = document.querySelector('.tabel-card');
                    html2pdf().set({
                        margin:       10,
                        filename:     'data_lokasi_investasi.pdf',
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 2, useCORS: true },
                        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    }).from(element).save();
                    document.getElementById("popupFormLokasi").style.display = "none";
                }, 300);

            }else {
                alert('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
            }
        });
    });
</script>
@endpush

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
                    label: 'Investasi (Rp Juta)',
                    data: @json($chartData),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    }
</script>
@endif
@endpush

{{-- endpartial --}}

