<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-bg">
        <div class="login-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="login-logo">
            <span class="login-appname">DARISIMANTAN</span>
        </div>
        <div class="login-main">
            <div class="login-title">
                <span>"Data Rapi<br><span class="highlight">Investasi Happy</span>"<br>Silahkan Masuk</span>
            </div>
            <div class="login-card">
                <div class="login-card-img">
                    <img src="{{ asset('images/gedung.JPG') }}" alt="Gedung" />
                </div>
                <form method="POST" action="{{ route('admin.login') }}" class="login-form">
                    @csrf
                    <label for="username" class="login-label">Nama Pengguna</label>
                    <input id="username" type="text" class="login-input" name="username" placeholder="Masukkan nama pengguna" required autofocus>
                    <label for="password" class="login-label">Kata Sandi</label>
                    <input id="password" type="password" class="login-input" name="password" placeholder="Masukkan kata sandi" required>
                    <button type="submit" class="login-btn">Masuk</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
