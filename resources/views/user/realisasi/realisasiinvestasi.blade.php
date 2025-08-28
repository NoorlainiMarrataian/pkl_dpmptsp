@extends('layouts.app')

@section('title', 'Realisasi Investasi')

@section('content')
<div class="realisasi-container">

    {{-- Section Grafik --}}
    <section class="grafik-section">
        <h2>TREN REALISASI INVESTASI</h2>
        <div class="grafik-card">
            {{-- nanti bisa ganti dengan chart.js --}}
            <img src="https://via.placeholder.com/1000x400?text=Grafik+Realisasi+Investasi" 
                 alt="Grafik Realisasi Investasi">
        </div>
    </section>

    {{-- Section Realisasi Card --}}
    <section class="realisasi-section">
        <h2>Realisasi Investasi</h2>
        <div class="realisasi-card-container">

            <div class="realisasi-card">
                <a href="{{ route('realisasi.negara') }}">
                    <div class="icon-wrapper">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h4>NEGARA INVESTOR</h4>
                </a>
            </div>

            <div class="realisasi-card">
                <a href="{{ route('realisasi.lokasi') }}">
                    <div class="icon-wrapper">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4>LOKASI</h4>
                </a>
            </div>

            <div class="realisasi-card">
                <a href="{{ route('realisasi.perbandingan') }}">
                    <div class="icon-wrapper">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4>Bandingkan Data</h4>
                </a>
            </div>

        </div>
    </section>
</div>
@endsection

@push('styles')
    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/realisasi.css') }}">
@endpush
