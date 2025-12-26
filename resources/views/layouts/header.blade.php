<header class="navbar">
    <div class="logo-top">
        <img src="{{ asset('images/logo-darisimantan.png') }}" alt="Logo DARRISIMANTAN">
    </div>
        <nav class="navbar">
            <ul>
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('realisasi.realisasiinvestasi') }}">Realisasi Investasi</a></li>
            </ul>
        </nav>
</header>

<script>
    let lastScrollTop = 0;
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function () {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
            navbar.classList.add('hidden');
        } else {
            navbar.classList.remove('hidden');
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
</script>
