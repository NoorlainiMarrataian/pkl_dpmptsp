<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>"Data Rapi <br> Investasi Happy!" <br> <span>Silahkan Masuk</span></h2>
            <form method="POST" action="{{ url('admin/login') }}">
                @csrf
                <div class="form-group">
                    <p> Nama Pengguna
                    <input type="text" name="username" placeholder="Masukkan nama pengguna" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Masukkan kata anda" required>
                </div>
                <button type="submit" class="btn-login">MASUK</button>
            </form>
        </div>
    </div>
</body>
</html>
