@extends('layouts.admin')
@section('title', 'Data Laporan')

@section('content')
<div class="container mt-4 investasi-container">

    <div class="investasi-header">
        <div class="d-flex align-items-center">
        <h2 class="investasi-title mb-0">Data Realisasi Investasi</h2>
            <form action="{{ route('data_investasi.index') }}" method="GET" class="investasi-search-form">
                <input type="text" name="search" class="form-control investasi-search-input" placeholder="Cari ID" value="{{ request('search') }}">
                <button type="submit" class="btn investasi-search-btn">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
        <div class="investasi-actions">
            <button type="button" class="btn investasi-btn-round investasi-btn-plus" data-toggle="modal" data-target="#tambahDataModal">
                <i class="fa fa-plus"></i>
            </button>
            <button type="button" class="btn investasi-btn-round investasi-btn-edit" data-toggle="modal" data-target="#editModal">
                <i class="fa fa-pencil"></i>
            </button>
            <button type="button" class="btn investasi-btn-round investasi-btn-delete" onclick="deleteData()">
                <i class="fa fa-trash"></i>
            </button>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle investasi-dropdown" type="button" id="dropdownTampilan" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pilih Tampilan
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownTampilan">
                    <a class="dropdown-item" href="{{ route('data_investasi.index', array_merge(request()->except('all'), ['all' => 1])) }}">Semua data</a>
                    <a class="dropdown-item" href="{{ route('data_investasi.index', request()->except('all')) }}">10 data</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped investasi-table mb-0">
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

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambahDataModal" tabindex="-1" role="dialog" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content investasi-modal-content">
            <h5 class="investasi-modal-title">Tambah Data</h5>
            <a href="{{ route('data_investasi.create') }}" class="btn investasi-modal-btn">Tambah manual</a>
            <button type="button" class="btn investasi-modal-btn" data-toggle="modal" data-target="#uploadExcelModal" data-dismiss="modal">
                Upload data excel
            </button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Batalkan</button>
        </div>
    </div>
</div>

<!-- Modal Edit Data -->

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,0.10); padding:32px 24px;">
            <h5 class="text-center mb-4" style="font-weight:700; font-size:1.25rem;">Cari Nomor ID Data</h5>
            <form id="editDataForm">
                <input type="text" id="editDataInput" class="form-control mb-4" placeholder="12324" style="height:54px; font-size:1.15rem; border-radius:8px; text-align:center;">
                <div class="row justify-content-center">
                    <div class="col-6 col-md-5 mb-2 mb-md-0">
                        <button type="button" class="btn btn-danger w-100" data-dismiss="modal" style="height:48px; font-size:1.08rem; font-weight:500; border-radius:8px;">Batalkan</button>
                    </div>
                    <div class="col-6 col-md-5">
                        <button type="submit" class="btn w-100" style="background:#07486a; color:#fff; height:48px; font-size:1.08rem; font-weight:500; border-radius:8px;">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload Excel -->
<div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content investasi-modal-content">
            <h5 class="investasi-modal-title">Proses Unggah Dokumen</h5>
            <div class="mb-3 fw-bold" style="text-align:center;">Unggah Data Dari Komputer / Tarik Dan Letakkan</div>
            <form action="{{ route('data_investasi.upload') }}" method="POST" enctype="multipart/form-data" id="uploadExcelForm">
                @csrf
                <div class="investasi-modal-file">
                    <span class="investasi-modal-file-icon"><i class="fa fa-file"></i></span>
                    <input type="text" id="fileNameDisplayExcel" class="form-control investasi-modal-file-input" placeholder="namadokumen.xlsx" readonly>
                    <label for="excelFileInput" class="investasi-modal-file-label">Telusuri</label>
                    <input type="file" id="excelFileInput" name="file" accept=".xlsx" style="display:none;" required>
                </div>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" style="border-radius:8px; font-weight:500; width:120px;">Batalkan</button>
                    <button type="submit" class="btn" style="background:#003366; color:#fff; border-radius:8px; font-weight:500; width:120px;">Simpan</button>
                </div>
            </form>
            <script>
                // Update file name display
                document.addEventListener('DOMContentLoaded', function() {
                    var fileInput = document.getElementById('excelFileInput');
                    var fileNameDisplay = document.getElementById('fileNameDisplayExcel');
                    var labelTelusuri = document.querySelector('label[for="excelFileInput"]');
                    labelTelusuri.addEventListener('click', function(e) {
                        fileInput.click();
                    });
                    fileInput.addEventListener('change', function() {
                        fileNameDisplay.value = fileInput.files.length ? fileInput.files[0].name : '';
                    });
                });
            </script>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('editDataForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var id = document.getElementById('editDataInput').value;
            if (id) {
                window.location.href = "/data_investasi/" + id + "/edit";
            }
        });
    });

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
