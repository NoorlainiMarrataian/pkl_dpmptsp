<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/investasi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal-custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-header">
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
        <div class="logout">
            <button type="button" id="btnLogoutSidebar">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </div>
    </div>
    <div class="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Logout</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar dari akun admin?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Ya, Logout</button>
                    </form>
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#btnLogoutSidebar').on('click', function(e) {
            e.preventDefault();
            $('#logoutConfirmModal').modal('show');
        });
    </script>
</body>
</html>
