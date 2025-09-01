@extends('layouts.app')

@section('content')
<section class="perbandingan-investasi">

    {{-- ======================= BAGIAN 1 ======================== --}}
    <div id="bagian1">
        @include('user.realisasi.bandingpartial.bagian1')
    </div>

    {{-- ======================= BAGIAN 2 ======================== --}}
    <div id="bagian2">
        @include('user.realisasi.bandingpartial.bagian2')
    </div>

</section>
@endsection

<link rel="stylesheet" href="{{ asset('css/perbandingan.css') }}">
@stack('styles')
