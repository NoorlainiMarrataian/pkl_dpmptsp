<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DPMPTSP Kalsel')</title>

    {{-- Global CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard_user.css') }}">
    <script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>

    {{-- CSS per halaman --}}
    @stack('styles')
</head>
<body>
    {{-- Header --}}
    @include('layouts.header')

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Script per halaman --}}
    @stack('scripts')
</body>
</html>