<header class="navbar">
    <div class="logo">
        <img src="{{ asset('images/logo-prov.png') }}" alt="Logo Provinsi">
        <img src="{{ asset('images/logo-dpmptsp.png') }}" alt="Logo DPMPTSP">
        <span>DATA REALISASI INVESTASI<br>KALIMANTAN SELATAN</span>
    </div>

    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Beranda</a></li>
            <li><a href="{{ route('realisasi') }}">Realisasi Investasi</a></li>
        </ul>
    </nav>

    <div class="search-box">
        <input type="text" placeholder="Cari">
        <button type="submit"><i class="fas fa-search"></i></button>
    </div>
</header>
