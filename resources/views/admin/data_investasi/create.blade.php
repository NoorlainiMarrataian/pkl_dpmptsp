@extends('layouts.admin')

@section('content')
<div class="container h-100 mt-5">
  <div class="row h-100 justify-content-center align-items-center">
    <div class="col-10 col-md-8 col-lg-6">
      <h4 class="mb-4">Tambah Data Realisasi Investasi</h4>
      <form action="{{ route('data_investasi.store') }}" method="post">
        @csrf
        <div class="form-group">
          <label for="id">Nomor ID</label>
          <input type="text" class="form-control" value="{{ $newId }}" disabled>
          <input type="hidden" name="id" value="{{ $newId }}">
        </div>

        <div class="form-group">
          <label for="tahun">Tahun</label>
          <input
            type="number"
            class="form-control @error('tahun') is-invalid @enderror" id="tahun" name="tahun" value="{{ old('tahun') }}"
            inputmode="numeric" pattern="^\d{4}$" min="2010" max="9999" maxlength="4" required>
          @error('tahun')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="form-group">
          <label for="periode">Periode</label>
          <select class="form-control" id="periode" name="periode" required>
            <option value="" disabled selected>Pilih Periode</option>
            <option value="Triwulan 1">Triwulan 1</option>
            <option value="Triwulan 2">Triwulan 2</option>
            <option value="Triwulan 3">Triwulan 3</option>
            <option value="Triwulan 4">Triwulan 4</option>
          </select>
        </div>

        <div class="form-group">
          <label for="status_penanaman_modal">Status Penanaman Modal</label>
          <select class="form-control" id="status_penanaman_modal" name="status_penanaman_modal" required>
            <option value="" disabled selected>Pilih Status</option>
            <option value="PMA">PMA</option>
            <option value="PMDN">PMDN</option>
          </select>
        </div>

        <div class="form-group">
          <label for="regional">Regional</label>
          <input type="text" class="form-control" id="regional" name="regional" required>
        </div>

        <div class="form-group">
          <label for="negara">Negara</label>
          <input type="text" class="form-control" id="negara" name="negara" required>
        </div>

        <div class="form-group">
          <label for="sektor_utama">Sektor Utama</label>
          <select class="form-control" id="sektor_utama" name="sektor_utama" required>
            <option value="" disabled selected>Pilih Sektor</option>
            <option value="Primer">Sektor Primer</option>
            <option value="Sekunder">Sektor Sekunder</option>
            <option value="Tersier">Sektor Tersier</option>
          </select>
        </div>

        <div class="form-group">
          <label for="nama_sektor">Nama Sektor</label>
          <input type="text" class="form-control" id="nama_sektor" name="nama_sektor" required>
        </div>

        <div class="form-group">
          <label for="deskripsi_kbli_2digit">Deskripsi KBLI 2 Digit</label>
          <input type="text" class="form-control" id="deskripsi_kbli_2digit" name="deskripsi_kbli_2digit" required>
        </div>

        <div class="form-group">
          <label for="provinsi">Provinsi</label>
          <input type="text" class="form-control" id="provinsi" name="provinsi" required>
        </div>

        <div class="form-group">
          <label for="kabupaten_kota">Kabupaten/Kota</label>
          <input type="text" class="form-control" id="kabupaten_kota" name="kabupaten_kota" required>
        </div>

        <div class="form-group">
          <label for="wilayah_jawa">Wilayah Jawa</label>
          <input type="text" class="form-control" id="wilayah_jawa" name="wilayah_jawa" required>
        </div>

        <div class="form-group">
          <label for="pulau">Pulau</label>
          <input type="text" class="form-control" id="pulau" name="pulau" required>
        </div>

        <div class="form-group">
          <label for="investasi_rp_juta">Investasi Rp Juta</label>
          <input type="text" class="form-control" id="investasi_rp_juta" name="investasi_rp_juta" inputmode="numeric" pattern="[0-9]*" required>
        </div>

        <div class="form-group">
          <label for="investasi_us_ribu">Investasi US Ribu</label>
          <input type="text" class="form-control" id="investasi_us_ribu" name="investasi_us_ribu" inputmode="numeric" pattern="[0-9]*"  required>
        </div>

        <div class="form-group">
          <label for="jumlah_tki">Jumlah TKI</label>
          <input type="text" class="form-control" id="jumlah_tki" name="jumlah_tki" inputmode="numeric" pattern="[0-9]*"  required>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary mr-2">Batal</a>
            <button type="submit" class="btn btn-success">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
