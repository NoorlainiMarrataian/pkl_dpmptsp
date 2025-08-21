@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Data Realisasi Investasi</h2>
        <a href="{{ route('data_investasi.create') }}" class="btn btn-success">Tambah Data</a>
    </div>

    <div class="row">
        @forelse ($data_investasi as $data)
            <div class="col-sm-4 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Data Realisasi Investasi </h3>
                    </div>
                    <div class="card-body">
                        <p><strong>ID:</strong> {{ $data->id }}</p>
                        <p><strong>Tahun:</strong>{{ $data->tahun}}</p>                         
                        <p><strong>Periode:</strong> {{ $data->periode }}</p>
                        <p><strong>Status Penanaman Modal:</strong> {{ $data->status_penanaman_modal ?? '-' }}</p>
                        <p><strong>Regional:</strong> {{ $data->regional ?? '-' }}</p>
                        <p><strong>Negara:</strong> {{ $data->negara ?? '-' }}</p>
                        <p><strong>Sektor Utama:</strong> {{ $data->sektor_utama ?? '-' }}</p>
                        <p><strong>Nama Sektor:</strong> {{ $data->nama_sektor ?? '-' }}</p>
                        <p><strong>Deskripsi KBLI 2 Digit:</strong> {{ $data->deskripsi_kbli_2digit ?? '-' }}</p>
                        <p><strong>Provinsi:</strong> {{ $data->provinsi ?? '-' }}</p>
                        <p><strong>Kab/Kota:</strong> {{ $data->kabupaten_kota ?? '-' }}</p>
                        <p><strong>Jawa / Luar Jawa:</strong> {{ $data->wilayah_jawa ?? '-' }}</p>
                        <p><strong>Pulau:</strong> {{ $data->pulau ?? '-' }}</p>
                        <p><strong>Investasi Rp:</strong> 
                           {{ isset($data->investasi_rp_juta) ? number_format($data->investasi_rp_juta, 2, ',', '.') : '-' }}
                        </p>
                        <p><strong>Investasi US$:</strong> 
                           {{ isset($data->investasi_us_ribu) ? number_format($data->investasi_us_ribu, 2, ',', '.') : '-' }}
                        </p>
                        <p><strong>Jumlah TKI:</strong> {{ $data->jumlah_tki ?? '-' }}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('data_investasi.edit', $data->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('data_investasi.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Belum ada data investasi.</p>
        @endforelse
    </div>
</div>
@endsection
