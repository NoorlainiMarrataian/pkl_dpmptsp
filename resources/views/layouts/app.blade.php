<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPMPTSP Kalsel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    {{-- Navbar --}}
    <header class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <span>DATA REALISASI INVESTASI KALIMANTAN SELATAN</span>
        </div>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('user.dashboard') }}">Realisasi Investasi</a></li>
            </ul>
        </nav>
        <div class="search-box">
            <input type="text" placeholder="Cari...">
        </div>
    </header>

    {{-- Konten Halaman --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer">
        <div class="footer-content">
            <div class="alamat">
                <h4>Hubungi Kami</h4>
                <p>Jalan Bangun Praja, Kawasan Perkantoran Pemerintah Provinsi Kalimantan Selatan, 
                   Cempaka, Banjarbaru, Kalimantan Selatan</p>
                <p>📞 0511 - 6749344 | 📱 0811 506 1000</p>
                <p>📧 set@dpmptsp.kalselprov.go.id</p>
            </div>
            <div class="jam">
                <h4>Jam Kerja Layanan</h4>
                <p>Senin - Jum’at</p>
                <p>08.00 – 15.00 WITA</p>
                <p>Istirahat 11.00 – 15.00 WITA</p>
            </div>
            <div class="sosmed">
                <h4>Ikuti Kami</h4>
                <p>
                    <a href="#">Facebook</a> |
                    <a href="#">Instagram</a> |
                    <a href="#">YouTube</a>
                </p>
            </div>
        </div>
        <div class="copyright">
            © 2025 DPMPTSP Provinsi Kalimantan Selatan. All rights reserved.
        </div>
    </footer>
</body>
</html>
