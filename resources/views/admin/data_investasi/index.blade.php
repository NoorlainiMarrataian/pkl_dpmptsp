@extends('layouts.admin')
@section('title', 'Data Laporan')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Data Realisasi Investasi</h2>
        <form action="{{ route('data_investasi.index') }}" method="GET" class="d-flex me-2">
            <input type="text" name="search" class="form-control" placeholder="Cari ID" value="{{ request('search') }}">
            
        </form>
        <a href="{{ route('data_investasi.create') }}" class="btn btn-success">Tambah Data</a>
        <button type="button" class="btn btn-primary me-2" onclick="editData()">Edit Data</button>
        <button type="button" class="btn btn-danger" onclick="deleteData()">Hapus Data</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover investasi-table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Tahun</th>
                    <th>Periode</th>
                    <th>Status Penanaman Modal</th>
                    <th>Regional</th>
                    <th>Negara</th>
                    <th>Sektor Utama</th>
                    <th>Nama Sektor</th>
                    <th>Deskripsi KBLI</th>
                    <th>Provinsi</th>
                    <th>Kab/Kota</th>
                    <th>Jawa / Luar Jawa</th>
                    <th>Pulau</th>
                    <th>Investasi Rp</th>
                    <th>Investasi US$</th>
                    <th>Jumlah TKI</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data_investasi as $data)
                <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->tahun }}</td>
                    <td>{{ $data->periode }}</td>
                    <td>{{ $data->status_penanaman_modal ?? '-' }}</td>
                    <td>{{ $data->regional ?? '-' }}</td>
                    <td>{{ $data->negara ?? '-' }}</td>
                    <td>{{ $data->sektor_utama ?? '-' }}</td>
                    <td>{{ $data->nama_sektor ?? '-' }}</td>
                    <td>{{ $data->deskripsi_kbli_2digit ?? '-' }}</td>
                    <td>{{ $data->provinsi ?? '-' }}</td>
                    <td>{{ $data->kabupaten_kota ?? '-' }}</td>
                    <td>{{ $data->wilayah_jawa ?? '-' }}</td>
                    <td>{{ $data->pulau ?? '-' }}</td>
                    <td>{{ isset($data->investasi_rp_juta) ? number_format($data->investasi_rp_juta, 2, ',', '.') : '-' }}</td>
                    <td>{{ isset($data->investasi_us_ribu) ? number_format($data->investasi_us_ribu, 2, ',', '.') : '-' }}</td>
                    <td>{{ $data->jumlah_tki ?? '-' }}</td>
                    <td>
                        <a href="{{ route('data_investasi.edit', $data->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('data_investasi.destroy', $data->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="17" class="text-center">Belum ada data investasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function editData() {
        let id = prompt("Masukkan ID data yang ingin diedit:");
        if (id) {
            window.location.href = "/data_investasi/" + id + "/edit";
        }
    }

    function deleteData() {
        let id = prompt("Masukkan ID data yang ingin dihapus:");
        if (id) {
            if (confirm("Yakin ingin menghapus data dengan ID " + id + "?")) {
                let form = document.createElement("form");
                form.action = "/data_investasi/" + id;
                form.method = "POST";

                let csrf = document.createElement("input");
                csrf.type = "hidden";
                csrf.name = "_token";
                csrf.value = "{{ csrf_token() }}";

                let method = document.createElement("input");
                method.type = "hidden";
                method.name = "_method";
                method.value = "DELETE";

                form.appendChild(csrf);
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            }
        }
    }
</script>
@endsection
