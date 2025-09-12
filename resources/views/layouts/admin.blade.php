<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- CSS Lama --}}
    <link rel="stylesheet" href="{{ asset('css/investasi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    {{-- CSS Baru --}}
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal-custom.css') }}">

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <div class="sidebar-header">
                <!-- Logo Pemerintah / Aplikasi -->
                <img src="/images/logo-darisimantan.png" alt="Logo DARISIMANTAN">
            </div>
            <div class="profile">
                <h3>
                    @if(Auth::guard('admin')->check())
                        {{ Auth::guard('admin')->user()->username }}
                    @endif
                </h3>
                <p>Admin</p>
            </div>
            <div class="menu">
                <a href="{{ route('admin.dashboard') }}" 
                   class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="{{ route('data_investasi.index') }}" 
                   class="{{ request()->is('data_investasi') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Data Laporan
                </a>
            </div>
        </div>

        <!-- Tombol Keluar -->
        <div class="logout">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"><i class="fas fa-sign-out-alt"></i> Keluar</button>
            </form>
        </div>
    </div>

    <!-- Konten -->
    <div class="content">
        @yield('content')
    </div>

    {{-- JS Tambahan --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
