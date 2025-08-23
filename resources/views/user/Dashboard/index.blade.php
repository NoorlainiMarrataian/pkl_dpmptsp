@extends('layouts.app')

@section('content')
<section class="hero">
    <img src="{{ asset('images/gedung.jpg') }}" alt="Gedung" class="hero-img">
</section>

<section class="realisasi">
    <div class="realisasi-content">
        <div class="realisasi-text">
            <div class="realisasi-card">
                <h2>Realisasi Investasi</h2>
                <p>Selamat datang di Website Realisasi Investasi DPMPTSP Provinsi Kalimantan Selatan. 
                Website ini dikelola oleh Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) 
                Provinsi Kalimantan Selatan sebagai wadah resmi untuk menyajikan data dan informasi terkini terkait perkembangan realisasi investasi di daerah.</p>
               <a href="#" class="btn-info">Info selengkapnya</a>
            </div>
        </div>
        <div class="realisasi-img">
            <img src="{{ asset('images/peta.png') }}" alt="Peta Kalimantan Selatan" class="peta">
        </div>
    </div>
</section>

<section class="tentang">
    <h2>Tentang Kami</h2>
    <div class="tentang-content">
        <img src="{{ asset('images/kepala.png') }}" alt="Kepala Dinas">
        <div class="deskripsi">
            <h3>Dinas Penanaman Modal Dan Pelayanan Terpadu Satu Pintu</h3>
            <p>DPMPTSP Provinsi Kalimantan Selatan merupakan Perangkat Daerah yang memiliki kedudukan sebagai unsur pelaksana urusan pemerintahan bidang penanaman modal dan penyelenggaraan.</p>
        </div>
    </div>
</section>
@endsection
