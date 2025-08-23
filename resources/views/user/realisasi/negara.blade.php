@extends('layouts.app')

@section('content')
<section class="negara-investor">
    <h2>NEGARA INVESTOR</h2>

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

        <a href="#" class="btn-download">
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
                {{-- Data kosong dulu, nanti diisi dari database --}}
            </tbody>
        </table>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/negara.css') }}">
@endpush
