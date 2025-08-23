<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPMPTSP Kalsel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard_user.css') }}?v={{ time() }}">
</head>
<body>
    {{-- Header/Navbar --}}
    @include('layouts.header')

    {{-- Konten Halaman --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
</body>
</html>
