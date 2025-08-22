<header class="navbar">
    <!-- Logo dan Judul -->
    <div class="logo">
        <img src="{{ asset('images/logo-prov.png') }}" alt="Logo Provinsi">
        <img src="{{ asset('images/logo-dpmptsp.png') }}" alt="Logo DPMPTSP">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
        <span>DATA REALISASI INVESTASI<br>KALIMANTAN SELATAN</span>
    </div>

    <!-- Menu Navigasi -->
    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Beranda</a></li>
            <li><a href="{{ route('user.dashboard') }}">Realisasi Investasi</a></li>
        </ul>
    </nav>

    <!-- Search Box -->
    <div class="search-box">
        <input type="text" placeholder="Cari">
        <button type="submit"><i class="fas fa-search"></i></button>
    </div>
</header>
