@extends('layouts.app')

@section('content')
<section class="lokasi-investasi container mt-4">

    <h2 class="mb-4">LOKASI</h2>

    {{-- ===== BAGIAN 1: Kab/Kota ===== --}}
    <div class="card mb-5">
        <div class="card-body">
            @include('user.realisasi.partials.lokasi_kabkota')
        </div>
    </div>

    {{-- ===== BAGIAN 2: 5 Realisasi, 5 Proyek, Sektor ===== --}}
    <div class="card" id="bagian2-content">
        <div class="card-body">
            @include('user.realisasi.partials.lokasi_sektor')
        </div>
    </div>
</section>
@endsection


@push('styles')
<link rel="stylesheet" href="{{ asset('css/lokasi.css') }}">
<style>
    .card-section { border:1px solid #ddd; padding:20px; margin-bottom:30px; border-radius:8px; background:#f9f9f9; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
</style>
@endpush
