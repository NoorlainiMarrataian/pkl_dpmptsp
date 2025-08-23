@extends('layouts.app')

@section('content')
<section class="lokasi-investasi">
    <h2>LOKASI</h2>

    {{-- Filter Tahun & Periode --}}
    <div class="filter-bar">
        <select class="dropdown-tahun">
            <option value="2025">2025</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
        </select>

        <ul class="periode-nav">
            <li class="active"><a href="#">1 TAHUN</a></li>
            <li><a href="#">TRIWULAN 1</a></li>
            <li><a href="#">TRIWULAN 2</a></li>
            <li><a href="#">TRIWULAN 3</a></li>
            <li><a href="#">TRIWULAN 4</a></li>
        </ul>

        {{-- Tombol Download --}}
        <a href="#" class="btn-download">
            <i class="fas fa-download"></i> Download
        </a>
    </div>

    {{-- Grafik Lokasi --}}
    <div class="grafik-card">
        <img src="https://via.placeholder.com/1000x400?text=Grafik+Lokasi+Investasi" alt="Grafik Lokasi">
    </div>

    {{-- Tabel Triwulan --}}
    <div class="tabel-card">
        <h3 class="judul-tabel">TABEL DATA TRIWULAN 1</h3>
        <div class="placeholder">TABEL DATA TRIWULAN 1</div>
    </div>

    {{-- Filter Tambahan --}}
    <div class="filter-bar extra">
        <select>
            <option>Realisasi Investasi Terbesar</option>
        </select>
        <select>
            <option>Proyek Terbesar</option>
        </select>
        <select>
            <option>Sektor</option>
        </select>
        <select>
            <option>TRIWULAN I</option>
        </select>

        <a href="#" class="btn-download">
            <i class="fas fa-download"></i> Download
        </a>
    </div>

    {{-- Diagram Lokasi --}}
    <div class="diagram-card">
        <img src="https://via.placeholder.com/600x400?text=Diagram+Lokasi+Kabupaten/Kota" alt="Diagram Lokasi">
    </div>

    <div class="tabel-card">
        <h3 class="judul-tabel">TABEL DATA TRIWULAN 1</h3>
        <div class="placeholder">TABEL DATA TRIWULAN 1</div>
    </div>

    {{-- Grafik Perbandingan Tahun --}}
    <h3 class="subjudul">GRAFIK PERBANDINGAN TAHUN DATA REALISASI INVESTASI</h3>
    <div class="filter-bar">
        <select>
            <option>PMA / PMDN</option>
        </select>
        <select>
            <option>2021</option>
        </select>
        <span>-</span>
        <select>
            <option>2025</option>
        </select>
        <a href="#" class="btn-download">
            <i class="fas fa-download"></i> Download
        </a>
    </div>
    <div class="grafik-card">
        <img src="https://via.placeholder.com/1000x400?text=Grafik+Perbandingan+Tahun" alt="Grafik Tahun">
    </div>
    <div class="tabel-card">
        <h3 class="judul-tabel">TABEL DATA TRIWULAN 1</h3>
        <div class="placeholder">TABEL DATA TRIWULAN 1</div>
    </div>

    {{-- Grafik Perbandingan Triwulan --}}
    <h3 class="subjudul">GRAFIK PERBANDINGAN TRIWULAN DATA REALISASI INVESTASI</h3>
    <div class="filter-bar">
        <select>
            <option>PMA / PMDN</option>
        </select>
        <select>
            <option>2021</option>
        </select>
        <select>
            <option>TRIWULAN 1</option>
        </select>
        <span>-</span>
        <select>
            <option>2025</option>
        </select>
        <select>
            <option>TRIWULAN 1</option>
        </select>
        <a href="#" class="btn-download">
            <i class="fas fa-download"></i> Download
        </a>
    </div>
    <div class="grafik-card">
        <img src="https://via.placeholder.com/1000x400?text=Grafik+Perbandingan+Triwulan" alt="Grafik Triwulan">
    </div>
    <div class="tabel-card">
        <h3 class="judul-tabel">TABEL DATA TRIWULAN 1</h3>
        <div class="placeholder">TABEL DATA TRIWULAN 1</div>
    </div>

</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/lokasi.css') }}">
@endpush
