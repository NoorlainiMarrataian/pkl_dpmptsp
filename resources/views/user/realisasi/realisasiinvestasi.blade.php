@extends('layouts.app')

@section('title', 'Realisasi Investasi')

@section('content')
<div class="realisasi-container">

    {{-- Section Grafik --}}
    <section class="grafik-section">
        <h2>Tren Realisasi Investasi</h2>
        <div class="grafik-card">
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
                    <i class="fas fa-globe"></i>
                    <h4>NEGARA INVESTOR</h4>
                </a>
            </div>
            <div class="realisasi-card">
                <a href="{{ route('realisasi.lokasi') }}">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4>Lokasi Investasi</h4>
                </a>
        </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/realisasi.css') }}">
@endpush
