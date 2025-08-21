<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/investasi.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    @stack('styles')

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: #0A4C70; /* biru tua */
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
            padding: 20px 0;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar-header {
            text-align: center;
            padding: 0 20px;
        }

        .sidebar-header img {
            max-width: 180px;
        }

        .profile {
            text-align: center;
            margin: 20px 0;
        }

        .profile img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .profile h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .profile p {
            margin: 0;
            font-size: 14px;
            opacity: 0.8;
        }

        .menu {
            flex-grow: 1;
        }

        .menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .menu a:hover,
        .menu a.active {
            background: #fff;
            color: #0A4C70;
            font-weight: bold;
        }

        .menu a i {
            margin-right: 10px;
        }

        .logout {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }

        .logout button {
            width: 100%;
            background: none;
            border: none;
            color: #fff;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .logout button i {
            margin-right: 10px;
        }

        /* Konten utama */
        .content {
            margin-left: 260px; /* supaya tidak ketutup sidebar */
            padding: 20px;
            width: calc(100% - 260px);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <div class="sidebar-header">
                <!-- Logo Pemerintah / Aplikasi -->
                <img src="/images/logo_darisimantan.png" alt="Logo DARISIMANTAN">
            </div>
            <div class="profile">
                <img src="https://i.ibb.co/4pzjz9w/avatar.png" alt="Foto Admin">
                <h3>{{ Auth::guard('admin')->user()->username }}</h3>
                <p>Admin</p>
            </div>
            <div class="menu">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="{{ route('data_investasi.index') }}" class="{{ request()->is('data_investasi') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Data Laporan
                </a>
                <a href="{{ url('admin/pengaturan') }}" class="{{ request()->is('admin/pengaturan') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>
            </div>
        </div>
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
</body>
</html>
