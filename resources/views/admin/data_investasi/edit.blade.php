@extends('layouts.admin')

@section('title', 'Data Laporan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endpush

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Update Data Realisasi Investasi</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data yang kamu isi!</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('data_investasi.update', $data_investasi->id) }}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label for="id">Nomor ID</label>
          <input type="text" class="form-control" value="{{ $data_investasi->id }}" readonly>
        </div>

        <div class="form-group">
          <label for="tahun">Tahun</label>
          <input type="text" class="form-control" id="tahun" name="tahun"
                 value="{{ $data_investasi->tahun }}" 
                 inputmode="numeric" pattern="[0-9]*" required>
        </div>

        <div class="form-group">
          <label for="periode">Periode</label>
          <select class="form-control" id="periode" name="periode" required>
            <option value="Triwulan 1" {{ $data_investasi->periode == 'Triwulan 1' ? 'selected' : '' }}>Triwulan 1</option>
            <option value="Triwulan 2" {{ $data_investasi->periode == 'Triwulan 2' ? 'selected' : '' }}>Triwulan 2</option>
            <option value="Triwulan 3" {{ $data_investasi->periode == 'Triwulan 3' ? 'selected' : '' }}>Triwulan 3</option>
            <option value="Triwulan 4" {{ $data_investasi->periode == 'Triwulan 4' ? 'selected' : '' }}>Triwulan 4</option>
          </select>
        </div>

        <div class="form-group">
          <label for="status_penanaman_modal">Status Penanaman Modal</label>
          <select class="form-control" id="status_penanaman_modal" name="status_penanaman_modal" required>
            <option value="PMA" {{ $data_investasi->status_penanaman_modal == 'PMA' ? 'selected' : '' }}>PMA</option>
            <option value="PMDN" {{ $data_investasi->status_penanaman_modal == 'PMDN' ? 'selected' : '' }}>PMDN</option>
          </select>
        </div>

        <div class="form-group">
          <label for="regional">Regional</label>
          <input type="text" class="form-control" id="regional" name="regional" 
                 value="{{ $data_investasi->regional }}" required>
        </div>

        <div class="form-group">
          <label for="negara">Negara</label>
          <input type="text" class="form-control" id="negara" name="negara" 
                 value="{{ $data_investasi->negara }}" required>
        </div>

        <div class="form-group">
          <label for="sektor_utama">Sektor Utama</label>
          <select class="form-control" id="sektor_utama" name="sektor_utama" required>
            <option value="Primer" {{ $data_investasi->sektor_utama == 'Primer' ? 'selected' : '' }}>Sektor Primer</option>
            <option value="Sekunder" {{ $data_investasi->sektor_utama == 'Sekunder' ? 'selected' : '' }}>Sektor Sekunder</option>
            <option value="Tersier" {{ $data_investasi->sektor_utama == 'Tersier' ? 'selected' : '' }}>Sektor Tersier</option>
          </select>
        </div>

        <div class="form-group">
          <label for="nama_sektor">Nama Sektor</label>
          <input type="text" class="form-control" id="nama_sektor" name="nama_sektor"
                 value="{{ $data_investasi->nama_sektor }}" required>
        </div>

        <div class="form-group">
          <label for="deskripsi_kbli_2digit">Deskripsi KBLI 2 Digit</label>
          <input type="text" class="form-control" id="deskripsi_kbli_2digit" name="deskripsi_kbli_2digit" 
                 value="{{ $data_investasi->deskripsi_kbli_2digit }}" required>
        </div>

        <div class="form-group">
          <label for="provinsi">Provinsi</label>
          <input type="text" class="form-control" id="provinsi" name="provinsi" 
                 value="{{ $data_investasi->provinsi }}" required>
        </div>

        <div class="form-group">
          <label for="kabupaten_kota">Kabupaten/Kota</label>
          <input type="text" class="form-control" id="kabupaten_kota" name="kabupaten_kota" 
                 value="{{ $data_investasi->kabupaten_kota }}" required>
        </div>

        <div class="form-group">
          <label for="wilayah_jawa">Wilayah Jawa</label>
          <input type="text" class="form-control" id="wilayah_jawa" name="wilayah_jawa" 
                 value="{{ $data_investasi->wilayah_jawa }}" required>
        </div>

        <div class="form-group">
          <label for="pulau">Pulau</label>
          <input type="text" class="form-control" id="pulau" name="pulau" 
                 value="{{ $data_investasi->pulau }}" required>
        </div>

        <div class="form-group">
          <label for="investasi_rp_juta">Investasi Rp Juta</label>
          <input type="text" class="form-control" id="investasi_rp_juta" name="investasi_rp_juta" 
                 value="{{ $data_investasi->investasi_rp_juta }}" required>
        </div>

        <div class="form-group">
          <label for="investasi_us_ribu">Investasi US Ribu</label>
          <input type="text" class="form-control" id="investasi_us_ribu" name="investasi_us_ribu" 
                 value="{{ $data_investasi->investasi_us_ribu }}" required>
        </div>

        <div class="form-group">
          <label for="jumlah_tki">Jumlah TKI</label>
          <input type="text" class="form-control" id="jumlah_tki" name="jumlah_tki" 
                 value="{{ $data_investasi->jumlah_tki }}" required>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-4 d-flex justify-content-end">
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary mr-2">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
