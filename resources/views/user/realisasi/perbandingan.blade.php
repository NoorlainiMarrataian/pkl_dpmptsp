{{-- resources/views/user/realisasi/perbandingan.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Perbandingan Investasi</h2>
    <div class="card shadow-sm p-4">
        

        {{-- ====================== PERBANDINGAN 1: PERTAHUN ====================== --}}
        <h4 class="mt-4">Perbandingan Pertahun</h4>
        {{-- Filter --}}
        <form id ="form-perbandingan1" action="{{ route('realisasi.perbandingan') }}" method="GET" class="filter-bar">

            {{-- Jenis Investasi --}}
            <div class="filter-item">
                <select name="jenis" id="jenis" class="form-select">
                    <option value="">Pilih Jenis</option>
                    <option value="PMA" {{ request('jenis') == 'PMA' ? 'selected' : '' }}>PMA</option>
                    <option value="PMDN" {{ request('jenis') == 'PMDN' ? 'selected' : '' }}>PMDN</option>
                    <option value="PMA+PMDN" {{ request('jenis') == 'PMA+PMDN' ? 'selected' : '' }}>PMA + PMDN</option>
                </select>
            </div>

            {{-- Tahun --}}
            <div class="filter-item tahun-group">
                <div class="tahun-selects">
                    <select name="tahun1" id="tahun1" class="form-select">
                        <option value="">Tahun 1</option>
                        @foreach(range(date('Y'), 2010) as $th)
                            <option value="{{ $th }}" {{ request('tahun1') == $th ? 'selected' : '' }}>{{ $th }}</option>
                        @endforeach
                    </select>
                    <span class="dash">-</span>
                    <select name="tahun2" id="tahun2" class="form-select">
                        <option value="">Tahun 2</option>
                        @foreach(range(date('Y'), 2010) as $th)
                            <option value="{{ $th }}" {{ request('tahun2') == $th ? 'selected' : '' }}>{{ $th }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tombol --}}
            <div>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <a href="#" class="btn btn-success w-100" id="openPopupBagian1">
                    <i class="fas fa-download"></i> Unduh Bagian 1
                </a>
            </div>
            
        </form>

        {{-- Tabel Perbandingkan 1--}}
        <div id="tabel-perbandingan1">
            @include('user.realisasi.partials.tabel_perbandingan1')
        </div>
    </div>
    
    <div class="card shadow-sm p-4 mt-5">
        {{-- ====================== PERBANDINGAN 2: PETRIWULAN ====================== --}}
        <h4 class="mt-5">Perbandingan Petriwulan</h4>
        {{-- Filter --}}
        <form id="form-perbandingan2" action="{{ route('realisasi.perbandingan2') }}" method="GET" class="filter-bar mb-3">

            {{-- Jenis Investasi --}}
            <div class="filter-item">
                <select name="jenis" id="jenis_2" class="form-select">
                    <option value="">Pilih Jenis</option>
                    <option value="PMA" {{ request('jenis') == 'PMA' ? 'selected' : '' }}>PMA</option>
                    <option value="PMDN" {{ request('jenis') == 'PMDN' ? 'selected' : '' }}>PMDN</option>
                    <option value="PMA+PMDN" {{ request('jenis') == 'PMA+PMDN' ? 'selected' : '' }}>PMA + PMDN</option>
                </select>
            </div>

            {{-- Tahun dan Triwulan--}}
            <div class="filter-item tahun-group">
                <div class="tahun-selects">
                    {{-- Tahun 1 --}}
                    <select name="tahun1" id="tahun1_2" class="form-select">
                        <option value="">Tahun 1</option>
                        @foreach(range(date('Y'), 2010) as $th)
                            <option value="{{ $th }}" {{ request('tahun1') == $th ? 'selected' : '' }}>{{ $th }}</option>
                        @endforeach
                    </select>
                    {{-- Periode 1 --}}
                    <select class="form-select" name="periode1" id="periode1">
                        <option value="">Pilih Periode</option>
                        <option value="Triwulan 1" {{ request('periode1') == 'Triwulan 1' ? 'selected' : '' }}>Triwulan 1</option>
                        <option value="Triwulan 2" {{ request('periode1') == 'Triwulan 2' ? 'selected' : '' }}>Triwulan 2</option>
                        <option value="Triwulan 3" {{ request('periode1') == 'Triwulan 3' ? 'selected' : '' }}>Triwulan 3</option>
                        <option value="Triwulan 4" {{ request('periode1') == 'Triwulan 4' ? 'selected' : '' }}>Triwulan 4</option>
                    </select>
                    <span class="dash">-</span>
                    {{-- Tahun 2 --}}
                    <select name="tahun2" id="tahun2_2" class="form-select">
                        <option value="">Tahun 2</option>
                        @foreach(range(date('Y'), 2010) as $th)
                            <option value="{{ $th }}" {{ request('tahun2') == $th ? 'selected' : '' }}>{{ $th }}</option>
                        @endforeach
                    </select>
                    {{-- Periode 2 --}}
                    <select class="form-select" name="periode2" id="periode2">
                        <option value="">Pilih Periode</option>
                        <option value="Triwulan 1" {{ request('periode2') == 'Triwulan 1' ? 'selected' : '' }}>Triwulan 1</option>
                        <option value="Triwulan 2" {{ request('periode2') == 'Triwulan 2' ? 'selected' : '' }}>Triwulan 2</option>
                        <option value="Triwulan 3" {{ request('periode2') == 'Triwulan 3' ? 'selected' : '' }}>Triwulan 3</option>
                        <option value="Triwulan 4" {{ request('periode2') == 'Triwulan 4' ? 'selected' : '' }}>Triwulan 4</option>
                    </select>
                </div>
            </div>          

            {{-- Tombol --}}
            <div>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>

        {{-- Tabel Perbandingkan 2--}}
        <div id="tabel-perbandingan2">
            @include('user.realisasi.partials.tabel_perbandingan2')
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/perbandingan.css') }}">
@endpush


@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    let chart1 = null;

    $('#form-perbandingan1').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('realisasi.perbandingan') }}",
            type: "GET",
            data: $(this).serialize(),
            success: function(response){
                $('#tabel-perbandingan1').html(response.html);

                if (chart1) chart1.destroy();
                let ctx = document.getElementById('chartPerbandingan1').getContext('2d');
                chart1 = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: response.chartLabels,
                        datasets: [{
                            label: 'Total Investasi (Rp Juta)',
                            data: response.chartData1.concat(response.chartData2),
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(153, 102, 255, 0.5)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        });
    });

    // ===== PERBANDINGAN 2 =====
    let chart2 = null;

    $('#form-perbandingan2').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('realisasi.perbandingan2') }}",
            type: "GET",
            data: $(this).serialize(),
            success: function(response){
                $('#tabel-perbandingan2').html(response.html);

                if (chart2) chart2.destroy();
                let ctx = document.getElementById('chartPerbandingan2').getContext('2d');
                chart2 = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: response.chartLabels,
                        datasets: [{
                            label: 'Total Investasi (Rp Juta)',
                            data: response.chartData1.concat(response.chartData2),
                            backgroundColor: [
                                'rgba(255, 159, 64, 0.5)',
                                'rgba(54, 162, 235, 0.5)'
                            ],
                            borderColor: [
                                'rgba(255, 159, 64, 1)',
                                'rgba(54, 162, 235, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        });
    });


});
</script>
@endpush
