@extends('layouts.app')

@section('content')
<section class="negara-investor">
    <h2>NEGARA INVESTOR</h2>

    {{-- Filter Tahun & Periode --}}
    <form class="filter-bar" action="{{ route('realisasi.negara') }}" method="GET">
        <select class="dropdown-tahun" name="tahun" id="tahunSelect">
            <option value="">Pilih Tahun</option>
            @foreach(range(date('Y'), 2010) as $th)
                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
            @endforeach
        </select>
        <button type="submit" name="triwulan" value="Tahun" class="btn-periode" disabled>1 Tahun</button>
        <button type="submit" name="triwulan" value="Triwulan 1" class="btn-periode" disabled>Triwulan 1</button>
        <button type="submit" name="triwulan" value="Triwulan 2" class="btn-periode" disabled>Triwulan 2</button>
        <button type="submit" name="triwulan" value="Triwulan 3" class="btn-periode" disabled>Triwulan 3</button>
        <button type="submit" name="triwulan" value="Triwulan 4" class="btn-periode" disabled>Triwulan 4</button>

        <a href="#" class="btn-download" id="openPopup">
            <i class="fas fa-download"></i> Download
        </a>
    </form>

    {{-- Grafik --}}
    @if($data_investasi->isNotEmpty())
        <div class="grafik-card">
            <canvas id="chartNegara" width="1000" height="400"></canvas>
        </div>
    @endif

    {{-- Tabel --}}
    @if($data_investasi->isNotEmpty())
        <div class="tabel-card">
            <h3 class="judul-tabel">PMA</h3>
            <table class="tabel-negara">
                <thead>
                    <tr>
                        <th>Negara</th>
                        <th>Proyek</th>
                        <th>Tahun</th>
                        <th>Periode</th>
                        <th>Tambahan Investasi (US$ Ribu)</th>
                        <th>Tambahan Investasi (Rp Juta)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_investasi as $data)
                    <tr>
                        <td>{{ $data->negara ?? '-' }}</td>
                        <td>{{ $data->status_penanaman_modal ?? '-' }}</td>
                        <td>{{ $data->tahun ?? '-' }}</td>
                        <td>{{ $data->periode ?? '-' }}</td>
                        <td>{{ isset($data->total_investasi_us_ribu) ? number_format($data->total_investasi_us_ribu, 2, ',', '.') : '-' }}</td>
                        <td>{{ isset($data->total_investasi_rp_juta) ? number_format($data->total_investasi_rp_juta, 2, ',', '.') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Pesan kosong --}}
    @if($data_investasi->isEmpty())
        <p class="text-center" style="margin-top:20px; font-style:italic; color:#777;">
            Silakan pilih <b>Tahun</b> dan <b>Periode</b> terlebih dahulu untuk melihat data.
        </p>
    @endif
</section>

{{-- Popup Modal --}}
<div id="popupForm" class="popup-overlay">
    <div class="popup-content">
        <h2>Data Diri</h2>
        <div class="warning-icon">
            <i class="fas fa-exclamation"></i>
        </div>
        <p>Silahkan isi formulir untuk mengunduh file ini</p>

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
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/negara.css') }}">
<link rel="stylesheet" href="{{ asset('css/popup.css') }}">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Enable tombol periode setelah pilih tahun
    const tahunSelect = document.getElementById("tahunSelect");
    const periodeButtons = document.querySelectorAll(".btn-periode");
    tahunSelect.addEventListener("change", function(){
        periodeButtons.forEach(btn => {
            btn.disabled = (this.value === "");
        });
    });
    // Jalankan sekali saat load halaman
    periodeButtons.forEach(btn => {
        btn.disabled = (tahunSelect.value === "");
    });

    // Popup
    document.getElementById("openPopup").addEventListener("click", function(e){
        e.preventDefault();
        document.getElementById("popupForm").style.display = "flex";
    });
    document.getElementById("closePopup").addEventListener("click", function(){
        document.getElementById("popupForm").style.display = "none";
    });

    // Download
    document.getElementById('downloadForm').addEventListener('submit', function(e){
        e.preventDefault();
        var form = this;
        var formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                setTimeout(function() {
                    var element = document.querySelector('.tabel-card');
                    html2pdf().set({
                        margin: 10,
                        filename: 'tabel-negara-investor.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2, useCORS: true },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    }).from(element).save();
                    document.getElementById("popupForm").style.display = "none";
                }, 300);
            } else {
                alert('Gagal menyimpan data.');
            }
        });
    });

    @if($data_investasi->isNotEmpty())
    // Grafik Chart.js
    const ctx = document.getElementById('chartNegara');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($data_investasi->pluck('negara')),
            datasets: [
                {
                    label: 'Investasi Rp (juta)',
                    data: @json($data_investasi->pluck('total_investasi_rp_juta')),
                    backgroundColor: 'rgba(255, 0, 0, 1)',
                    borderColor: 'rgba(255, 0, 0, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: { 
            responsive: true, 
            scales: { y: { beginAtZero: true } } 
        }
    });
    @endif
</script>
@endpush
