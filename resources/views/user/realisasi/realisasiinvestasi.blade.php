@extends('layouts.app')

@section('title', 'Realisasi Investasi')

@section('content')
<div class="realisasi-container">

    {{-- Section Grafik --}}
    <section class="grafik-section">
        <h2>TREN REALISASI INVESTASI</h2>
        <div class="grafik-card">
            <canvas id="chartRealisasi" width="1000" height="400"></canvas>
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
                        <i class="fas fa-columns"></i>
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
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let ctx = document.getElementById('chartRealisasi').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Total Investasi Rp',
                data: @json($data),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                y: { beginAtZero: true },
                x: {
                grid: {
                    display: false 
                }
            }
            }
        }
    });
});
</script>

@endpush



