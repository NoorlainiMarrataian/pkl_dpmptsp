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

    // ===== PERBANDINGAN 1 =====
    $('#form-perbandingan1').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('realisasi.perbandingan') }}",
            type: "GET",
            data: $(this).serialize(),
            success: function(data){
                $('#tabel-perbandingan1').html(data);
            },
            error: function(){
                alert('Gagal memuat data perbandingan pertahun');
            }
        });
    });

    // ===== PERBANDINGAN 2 =====
    $('#form-perbandingan2').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('realisasi.perbandingan2') }}",
            type: "GET",
            data: $(this).serialize(),
            success: function(data){
                $('#tabel-perbandingan2').html(data);
            },
            error: function(){
                alert('Gagal memuat data perbandingan triwulan');
            }
        });
    });

});
</script>
@endpush
