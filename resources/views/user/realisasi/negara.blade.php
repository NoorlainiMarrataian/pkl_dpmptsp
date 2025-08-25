@extends('layouts.app')

@section('content')
<section class="negara-investor">
    <h2>NEGARA INVESTOR</h2>

    {{-- Filter Tahun & Periode --}}
    <div class="filter-bar">
        <select class="dropdown-tahun">
            <option value="2025" name="tahun">2025</option>
            <option value="2024" name="tahun">2024</option>
            <option value="2023" name="tahun">2023</option>
        </select>
        
        <form class = "periode-nav" action="/negara-investor" method="GET">
            <button type="submit" name="tahun" value="Tahun">1 tahun</button>
            <button type="submit" name="triwulan" value="Triwulan 1">triwulan 1</button>
            <button type="submit" name="triwulan" value="Triwulan 2">triwulan 2</button>
            <button type="submit" name="triwulan" value="Triwulan 3">triwulan 3</button>
            <button type="submit" name="triwulan" value="Triwulan 4">triwulan 4</button>
        </form>

        <ul class="periode-nav">
            <li class="active"><a href="{{ route('realisasi.negara', request('satutahun'), ['satutahun'=>2020]) }}">1 TAHUN</a></li>
            <li><a href="#">TRIWULAN 1</a></li>
            <li><a href="#">TRIWULAN 2</a></li>
            <li><a href="#">TRIWULAN 3</a></li>
            <li><a href="#">TRIWULAN 4</a></li>
        </ul>

        <a href="#" class="btn-download" id="openPopup">
            <i class="fas fa-download"></i> Download
        </a>
    </div>

    <div class="grafik-card">
        <img src="https://via.placeholder.com/1000x400?text=Grafik+Negara+Investor" 
             alt="Grafik Negara Investor">
    </div>

    <div class="tabel-card">
        <h3 class="judul-tabel">PMA</h3>
        <table class="tabel-negara">
            <thead>
                <tr>
                    <th>Negara</th>
                    <th>Proyek</th>
                    <th>Tambahan Investasi dalam Ribu (US Dollar)</th>
                    <th>Tambahan Investasi dalam Juta (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data_investasi as $data)
                <tr>
                    <td>{{ $data->negara ?? '-' }}</td>
                    <td></td>
                    <td>{{ isset($data->investasi_us_ribu) ? number_format($data->investasi_us_ribu, 2, ',', '.') : '-' }}</td>
                    <td>{{ isset($data->investasi_rp_juta) ? number_format($data->investasi_rp_juta, 2, ',', '.') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="17" class="text-center">Belum ada data investasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- Popup Modal --}}
<div id="popupForm" class="popup-overlay">
    <div class="popup-content">
        <h2>Data Diri</h2>
        <div class="warning-icon">
            <i class="fas fa-exclamation"></i>
        </div>
        <p>Silahkan isi formulir untuk mengunduh file ini</p>

        <form>
            <div class="checkbox-group horizontal">
                <label><input type="checkbox"> Individu</label>
                <label><input type="checkbox"> Perusahaan</label>
                <label><input type="checkbox"> Lainnya</label>
            </div>

            <input type="text" placeholder="Nama Lengkap/Instansi">
            <input type="email" placeholder="Email">
            <input type="text" placeholder="Telpon">
            <textarea placeholder="Keperluan"></textarea>

            <div class="checkbox-group">
                <label><input type="checkbox"> Anda setuju untuk bertanggung jawab atas data yang diunduh</label>
                <label><input type="checkbox"> Pihak DPMPTSP tidak bertanggung jawab atas dampak penggunaan data</label>
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
<script>
    document.getElementById("openPopup").addEventListener("click", function(e){
        e.preventDefault();
        document.getElementById("popupForm").style.display = "flex";
    });

    document.getElementById("closePopup").addEventListener("click", function(){
        document.getElementById("popupForm").style.display = "none";
    });
</script>
@endpush
