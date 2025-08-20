<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <title>Index - Data Realisasi Investasi</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-warning">
    <div class="container-fluid">
      <a class="navbar-brand h1" href="{{ route('data_investasi.index') }}">CRUD Data Realisasi Investasi</a>
      <div class="justify-end">
        <div class="col">
          <a class="btn btn-sm btn-success" href="{{ route('data_investasi.create') }}">Add Data</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row">
      @forelse ($data_investasi as $data)
        <div class="col-sm-4 mb-3">
          <div class="card h-100">
            <div class="card-header">
              <h5 class="card-title">Data Realisasi Investasi</h5>
            </div>
            <div class="card-body">
              <p><strong>Tahun:</strong> {{ $data->tahun }}</p>
              <p><strong>Periode:</strong> {{ $data->periode }}</p>
              <p><strong>Negara:</strong> {{ $data->negara ?? '-' }}</p>
              <p><strong>Investasi Rp:</strong> {{ number_format($data->investasi_rp_juta, 0, ',', '.') }}</p>
              <p><strong>Jumlah TKI:</strong> {{ $data->jumlah_tki }}</p>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-sm">
                  <a href="{{ route('data_investasi.edit', $data->id) }}" class="btn btn-primary btn-sm">Edit</a>
                </div>
                <div class="col-sm">
                  <form action="{{ route('data_investasi.destroy', $data->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
        <p class="text-center">Belum ada data investasi.</p>
      @endforelse
    </div>
  </div>
</body>
</html>
