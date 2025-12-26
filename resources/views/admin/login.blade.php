<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Masuk Admin</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
  <div class="login-header">
    <img src="{{ asset('images/logo-darisimantan.png') }}" alt="Logo DARISIMANTAN" class="login-logo">
  </div>
  <div class="login-card">
    <div class="login-card-img"></div>
    <form method="POST" action="{{ route('admin.login') }}" class="login-form">
      @csrf
      @if($errors->any())
        <div class="alert alert-danger">
          {{ $errors->first() }}
        </div>
      @endif
      <div class="form-group">
        <label for="username" class="login-label">Nama Pengguna</label>
        <input id="username" type="text" 
               class="form-control login-input @error('username') is-invalid @enderror" 
               name="username" placeholder="Masukkan nama pengguna" required autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="login-label">Kata Sandi</label>
        <input id="password" type="password" 
               class="form-control login-input @error('password') is-invalid @enderror" 
               name="password" placeholder="Masukkan kata sandi" required>
      </div>
      <button type="submit" class="btn btn-login">Masuk</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
