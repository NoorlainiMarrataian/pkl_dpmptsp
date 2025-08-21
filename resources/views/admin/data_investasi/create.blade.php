
<div class="container h-100 mt-5">
  <div class="row h-100 justify-content-center align-items-center">
    <div class="col-10 col-md-8 col-lg-6">
      <h3>Add a data</h3>
      <form action="{{ route('data_investasi.store') }}" method="post">
        @csrf
        <div class="form-group">
          <label for="tahun">Tahun</label>
          <input type="text" class="form-control" id="tahun" name="tahun" required>
        </div>
        <div class="form-group">
          <label for="periode">Periode</label>
          <input type="text" class="form-control" id="periode" name="periode" required>
        </div>
        <div class="form-group">
          <label for="status_penanaman_modal">Status Penanaman Modal</label>
          <input type="text" class="form-control" id="status_penanaman_modal" name="status_penanaman_modal" required>
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
          <input type="text" class="form-control" id="sektor_utama" name="sektor_utama" required>
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
          <input type="text" class="form-control" id="investasi_rp_juta" name="investasi_rp_juta" required>
        </div>
        <div class="form-group">
          <label for="investasi_us_ribu">Investasi US Ribu</label>
          <input type="text" class="form-control" id="investasi_us_ribu" name="investasi_us_ribu" required>
        </div>
        <div class="form-group">
          <label for="jumlah_tki">Jumlah TKI</label>
          <input type="text" class="form-control" id="jumlah_tki" name="jumlah_tki" required>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Create Data</button>
      </form>
    </div>
  </div>
</div>