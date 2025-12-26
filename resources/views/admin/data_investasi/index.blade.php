@extends('layouts.admin')
@section('title', 'Data Laporan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/investasi.css') }}">
<div class="container-fluid investasi-container">
    <div class="investasi-header">
        <div class="d-flex align-items-center">
        <h2 class="investasi-title mb-0">Data Realisasi Investasi</h2>
            <form action="{{ route('data_investasi.index') }}" method="GET" class="investasi-search-form">
                <input type="text" name="search" class="form-control investasi-search-input" placeholder="Cari ID" value="{{ request('search') }}">
                <button type="submit" class="btn investasi-search-btn">
                    <i class="fa fa-search"></i>
                </button>
            </form>
            @if ($errors->has('search'))
                <div class="alert alert-danger mt-2" style="padding:8px; font-size:14px;">
                    {{ $errors->first('search') }}
                </div>
            @endif
            @if ($errors->has('edit'))
                <div class="alert alert-danger mt-2" style="padding:8px; font-size:14px;">
                    {{ $errors->first('edit') }}
                </div>
            @endif
        </div>

        <div class="investasi-actions">
            <button type="button" class="btn investasi-btn-round investasi-btn-plus" data-toggle="modal" data-target="#tambahDataModal">
                <i class="fa fa-plus"></i>
            </button>
            <button type="button" class="btn investasi-btn-round investasi-btn-edit" data-toggle="modal" data-target="#editModal">
                <i class="fa fa-pencil"></i>
            </button>
                <button type="button" class="btn investasi-btn-round investasi-btn-delete" data-toggle="modal" data-target="#deleteModal">
                <i class="fa fa-trash"></i>
            </button>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle investasi-dropdown" type="button" id="dropdownTampilan" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pilih Tampilan
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownTampilan">
                    <a class="dropdown-item" href="{{ route('data_investasi.index', array_merge(request()->except('page'), ['all' => 1])) }}">Semua data</a>
                    <a class="dropdown-item" href="{{ route('data_investasi.index', request()->except('all')) }}">10 data</a>
                </div>
            </div>
        </div>
    </div>

    <div class="custom-pagination">
    @if ($data_investasi->onFirstPage())
        <button disabled class="pagination-btn prev disabled">
            <span class="arrow">«</span> Previous
        </button>
    @else
        <a href="{{ $data_investasi->previousPageUrl() }}" style="text-decoration: none;">
            <button class="pagination-btn prev">
                <span class="arrow">«</span> Previous
            </button>
        </a>
    @endif

    @if ($data_investasi->hasMorePages())
        <a href="{{ $data_investasi->nextPageUrl() }}" style="text-decoration: none;">
            <button class="pagination-btn next">
                Next <span class="arrow">»</span>
            </button>
        </a>
    @else
        <button disabled class="pagination-btn next disabled">
            Next <span class="arrow">»</span>
        </button>
    @endif
    </div>

    {{-- Table --}}
    <div class="table-responsive mt-1">
        <table class="table table-bordered table-striped investasi-table mb-0 text-center align-middle">
            <thead class="thead-dark">
                <tr>
                    <th style="min-width:70px;">ID</th>
                    <th style="min-width:90px;">Tahun</th>
                    <th style="min-width:100px;">Periode</th>
                    <th style="min-width:160px;">Status Penanaman Modal</th>
                    <th style="min-width:120px;">Regional</th>
                    <th style="min-width:120px;">Negara</th>
                    <th style="min-width:140px;">Sektor Utama</th>
                    <th style="min-width:140px;">Nama Sektor</th>
                    <th style="min-width:180px;">Deskripsi KBLI</th>
                    <th style="min-width:140px;">Provinsi</th>
                    <th style="min-width:140px;">Kab/Kota</th>
                    <th style="min-width:150px;">Jawa / Luar Jawa</th>
                    <th style="min-width:120px;">Pulau</th>
                    <th style="min-width:150px;">Investasi Rp</th>
                    <th style="min-width:150px;">Investasi US$</th>
                    <th style="min-width:130px;">Jumlah TKI</th>
                    <th style="text-align:center; min-width:120px;">Aksi</th>
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
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('data_investasi.edit', $data->id) }}" class="btn-table edit">Edit</a>
                            <form action="{{ route('data_investasi.destroy', $data->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-table delete">Delete</button>
                            </form>
                        </div>
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

{{-- Modal Popup Pesan --}}
@if(session('error') || session('success'))
<div class="modal fade" id="popupMessage" tabindex="-1" role="dialog" aria-labelledby="popupMessageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg rounded">
            <div class="modal-header {{ session('error') ? 'bg-danger text-white' : 'bg-success text-white' }}">
                <h5 class="modal-title" id="popupMessageLabel">
                    @if(session('error'))
                        <i class="fas fa-exclamation-circle"></i> Terjadi Kesalahan
                    @else
                        <i class="fas fa-check-circle"></i> Berhasil
                    @endif
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>      
            <div class="modal-body">
                {{ session('error') ?? session('success') }}
            </div>        
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Script Bootstrap & jQuery --}}
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

{{-- Script untuk menampilkan modal otomatis --}}
@if(session('error') || session('success'))
<script>
    $(document).ready(function(){
        $('#popupMessage').modal('show');
    });
</script>
@endif

<div class="modal fade" id="tambahDataModal" tabindex="-1" role="dialog" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content investasi-modal-content">
            <h5 class="investasi-modal-title">Tambah Data</h5>
            <a href="{{ route('data_investasi.create') }}" class="btn investasi-modal-btn">Tambah manual</a>
            <button type="button" class="btn investasi-modal-btn investasi-btn-upload" data-target="#uploadExcelModal">
                Upload data excel
            </button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Batalkan</button>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,0.10); padding:32px 24px;">
            <h5 class="text-center mb-4" style="font-weight:700; font-size:1.25rem;">Cari Nomor ID Data</h5>
            <form id="editDataForm">
                <input type="text" id="editDataInput" class="form-control mb-4" placeholder="Masukkan Nomor ID" style="height:54px; font-size:1.15rem; border-radius:8px; text-align:center;">
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

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-custom">
            <h5 class="modal-title-custom">Masukkan Nomor ID Data Yang Akan Dihapus</h5>
            <form id="deleteDataForm">
                <input type="text" id="deleteDataInput" class="form-control input-custom mb-4" placeholder="Masukkan Nomor ID">
                <div class="row justify-content-center">
                    <div class="col-6 col-md-5 mb-2 mb-md-0">
                        <button type="button" class="btn btn-danger w-100" data-dismiss="modal" style="height:48px; font-size:1.08rem; font-weight:500; border-radius:8px;">Batalkan</button>
                    </div>
                    <div class="col-6 col-md-5">
                        <button id="showConfirmDelete" type="button" class="btn w-100" style="background:#07486a; color:#fff; height:48px; font-size:1.08rem; font-weight:500; border-radius:8px;">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-custom">
            <h5 class="modal-title-custom mb-5" id="confirmDeleteText">Data akan dihapus</h5>
            <div class="row justify-content-center">
                <div class="col-6 col-md-5 mb-2 mb-md-0">
                    <button type="button" class="btn btn-danger w-100" data-dismiss="modal" style="height:48px; font-size:1.08rem; font-weight:500; border-radius:8px;">Batalkan</button>
                </div>
                <div class="col-6 col-md-5">
                    <button id="confirmDeleteBtn" type="button" class="btn w-100" style="background:#07486a; color:#fff; height:48px; font-size:1.08rem; font-weight:500; border-radius:8px;">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content investasi-modal-content">
            <h5 class="investasi-modal-title">Proses Unggah Dokumen</h5>
            {{-- Alert error/success --}}
            <div id="uploadErrorAlert" class="alert alert-danger" style="display:none;"></div>
            <div id="uploadSuccessAlert" class="alert alert-success" style="display:none;"></div>
            <div class="mb-3 fw-bold text-center">
                Unggah Data Dari Komputer / Tarik Dan Letakkan
            </div>
            <form action="{{ route('data_investasi.upload') }}" method="POST" enctype="multipart/form-data" id="uploadExcelForm">
                @csrf
                <div class="investasi-modal-file">
                    <span class="investasi-modal-file-icon"><i class="fa fa-file"></i></span>
                    <input type="text" id="fileNameDisplayExcel" class="form-control investasi-modal-file-input" placeholder="namadokumen.xlsx" readonly>
                    <label for="excelFileInput" class="investasi-modal-file-label">Telusuri</label>
                    <input type="file" id="excelFileInput" name="file" accept=".xlsx" style="display:none;" required>
                </div>
                <div class="row justify-content-center mt-3">
                    <div class="col-6 col-md-5 mb-2">
                        <button type="button" class="btn btn-danger w-100"
                            style="height:48px; font-size:1.08rem; font-weight:500; border-radius:8px;"
                            data-dismiss="modal">
                            Batalkan
                        </button>
                    </div>
                    <div class="col-6 col-md-5">
                        <button type="submit" class="btn w-100"
                            style="background:#07486a; color:#fff; height:48px; font-size:1.08rem; font-weight:500; border-radius:8px;">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var fileInput = document.getElementById('excelFileInput');
                    var fileNameDisplay = document.getElementById('fileNameDisplayExcel');
                    fileInput.addEventListener('change', function() {
                        fileNameDisplay.value = fileInput.files.length ? fileInput.files[0].name : '';
                    });
                });
            </script>
        </div>
    </div>
</div>

<script>
    let deleteId = null;
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('editDataForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var id = document.getElementById('editDataInput').value.trim();

            if (!id) {
                alert("Isi Nomor ID terlebih dahulu!");
                return;
            }

            if (!/^\d+$/.test(id)) {
                alert("Nomor ID hanya boleh berisi angka!");
                return;
            }

            fetch("/data_investasi/" + id + "/edit", {
                method: "HEAD"
            })
            .then(response => {
                if (response.status === 200) {
                    window.location.href = "/data_investasi/" + id + "/edit";
                } else {
                    alert("Data dengan Nomor ID tersebut tidak ada di sistem!");
                }
            })
        });

        document.querySelectorAll('.btn-table-delete').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                deleteId = this.getAttribute('data-id');
                $('#confirmDeleteModal').modal('show');
            });
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteId) {
                var form = document.createElement('form');
                form.action = '/data_investasi/' + deleteId;
                form.method = 'POST';
                var csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = "{{ csrf_token() }}";
                var method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });

        const deleteModal = $('#deleteModal');
        const confirmDeleteModal = $('#confirmDeleteModal');
        document.getElementById('showConfirmDelete').addEventListener('click', function(e) {
            e.preventDefault();

            let id = document.getElementById('deleteDataInput').value.trim();

            if (!id) {
                alert("Nomor ID harus diisi!");
                return;
            }

            if (!/^\d+$/.test(id)) {
                alert("Nomor ID hanya boleh berisi angka!");
                return;
            }

            fetch(`/data_investasi/check/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.exists) {
                        alert("Nomor ID tidak ditemukan di database!");
                        return;
                    }
                    deleteId = id;
                    const title = document.querySelector('#confirmDeleteModal h5');
                    if (title) {
                        title.innerText = `Data dengan Nomor ID ${id} akan dihapus`;
                    }
                    deleteModal.modal('hide');
                    deleteModal.off('hidden.bs.modal');
                    deleteModal.on('hidden.bs.modal', function() {
                        confirmDeleteModal.modal('show');
                    });
                })
                .catch(err => {
                    console.error(err);
                    alert("Terjadi kesalahan. Coba lagi.");
                });
        });

        const tambahDataModal = $('#tambahDataModal');
        const uploadExcelModal = $('#uploadExcelModal');
        const btnUploadExcel = document.querySelector('#tambahDataModal .investasi-btn-upload');
        if(btnUploadExcel){
            btnUploadExcel.addEventListener('click', function(e){
                e.preventDefault();
                tambahDataModal.modal('hide');
                tambahDataModal.on('hidden.bs.modal', function(){
                    uploadExcelModal.modal('show');
                    tambahDataModal.off('hidden.bs.modal');
                });
            });
        }
    });
</script>
@endsection