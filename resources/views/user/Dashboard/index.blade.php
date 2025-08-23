@extends('layouts.app')

@section('content')
<section class="hero">
    <img src="{{ asset('images/gedung.jpg') }}" alt="Gedung" class="hero-img">
</section>

<section class="realisasi">
    <div class="realisasi-content">
        <div class="realisasi-text">
            <div class="dashboard-card">
                <h2>Realisasi Investasi</h2>
                <p>
                    Selamat datang di Website Realisasi Investasi DPMPTSP Provinsi Kalimantan Selatan. 
                    Website ini dikelola oleh Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) 
                    Provinsi Kalimantan Selatan sebagai wadah resmi untuk menyajikan data dan informasi terkini terkait perkembangan realisasi investasi di daerah.
                </p>
                
                <!-- Tombol -->
                <a href="javascript:void(0)" class="btn-info" onclick="toggleInfo()">Info selengkapnya</a>

                <!-- Konten tambahan -->
                <div id="more-info">
                    <p>
                        Data realisasi investasi ini meliputi perkembangan dari berbagai sektor usaha, baik Penanaman Modal Asing (PMA) 
                        maupun Penanaman Modal Dalam Negeri (PMDN). Melalui website ini, masyarakat dapat memantau tren, capaian target, 
                        serta distribusi investasi yang tersebar di seluruh wilayah Kalimantan Selatan.
                    </p>
                    <p>
                        Informasi ini diharapkan dapat menjadi rujukan bagi para pelaku usaha, investor, maupun pihak-pihak yang berkepentingan 
                        untuk mengambil keputusan strategis dalam mengembangkan kegiatan investasi yang berdaya saing dan berkelanjutan.
                    </p>
                </div>
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

{{-- Script toggle --}}
<script>
function toggleInfo() {
    const moreInfo = document.getElementById("more-info");
    const btn = document.querySelector(".btn-info");

    if (moreInfo.classList.contains("show")) {
        moreInfo.classList.remove("show");
        btn.textContent = "Info selengkapnya";
    } else {
        moreInfo.classList.add("show");
        btn.textContent = "Tutup info";
    }
}
</script>
@endsection
