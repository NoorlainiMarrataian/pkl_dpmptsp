<div id="bagian2-content" class="card-section">
    <h2 class="judul-lokasi">Data Realisasi Investasi Kalimantan</h2>

    {{-- Filter Tahun, Jenis Data, dan Periode --}}
    <form id="form-bagian2" class="filter-bar" action="{{ route('realisasi.lokasi') }}" method="GET">
            
        {{-- Tahun --}}
        <select class="dropdown-tahun" name="tahun2" >
            <option value="">Pilih Tahun</option>
            @foreach(range(date('Y'), 2010) as $th)
            <option value="{{ $th }}" {{ request('tahun2') == $th ? 'selected' : '' }}>{{ $th }}</option>
            @endforeach
        </select>

        {{-- Periode --}}
        <select name="triwulan2" class="dropdown-triwulan">
            {{-- Default sebelum ada request --}}
            <option value="" {{ request('triwulan2') == '' ? 'selected' : '' }}>Pilih Periode</option>
            <option value="Tahun" {{ request('triwulan2') == 'Tahun' ? 'selected' : '' }}>1 Tahun</option>
            <option value="Triwulan 1" {{ request('triwulan2') == 'Triwulan 1' ? 'selected' : '' }}>Triwulan 1</option>
            <option value="Triwulan 2" {{ request('triwulan2') == 'Triwulan 2' ? 'selected' : '' }}>Triwulan 2</option>
            <option value="Triwulan 3" {{ request('triwulan2') == 'Triwulan 3' ? 'selected' : '' }}>Triwulan 3</option>
            <option value="Triwulan 4" {{ request('triwulan2') == 'Triwulan 4' ? 'selected' : '' }}>Triwulan 4</option>
        </select>
            
        {{-- Tombol filter berdasarkan jenis --}}
        <button type="submit" name="jenisBagian2" value="5 Realisasi Investasi Terbesar Berdasarkan Kab Kota" class="btn btn-secondary">
            5 Realisasi Investasi Terbesar Berdasarkan Kab Kota
        </button>
        <button type="submit" name="jenisBagian2" value="5 Proyek Terbesar Berdasarkan Kab Kota" class="btn btn-secondary">
            5 Proyek Terbesar Berdasarkan Kab Kota
        </button>
        <button type="submit" name="jenisBagian2" value="sektor" class="btn btn-secondary">
            Berdasarkan Sektor
        </button>

        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
        <input type="hidden" name="triwulan" value="{{ request('triwulan') }}">
        <input type="hidden" name="jenis" value="{{ request('jenis') }}">


        {{-- Tombol download --}}
        <button type="button" id="openPopupLokasi" class="btn btn-success">
            <i class="fas fa-download"></i> Download
        </button>

    </form>


    {{-- ===== Top 5 Investasi PMA & PMDN ===== --}}
    @if($jenisBagian2 == '5 Realisasi Investasi Terbesar Berdasarkan Kab Kota')
        {{-- Tabel PMA --}}
        <h3>PMA - 5 Realisasi Investasi Terbesar</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kab/Kota</th>
                    <th>Status Penanaman Modal</th>
                    @if($triwulan2 && $triwulan2 != 'Tahun')
                        <th>Periode</th>
                    @endif
                    <th>Investasi (Rp Juta)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topPMA as $data)
                    <tr>
                        <td>{{ $data->kabupaten_kota }}</td>
                        <td>{{ $data->status_penanaman_modal }}</td>
                        @if($triwulan2 && $triwulan2 != 'Tahun')
                            <td>{{ $data->periode }}</td>
                        @endif
                        <td>{{ number_format($data->total_investasi, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Tidak ada data PMA.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Tabel PMDN --}}
        <h3>PMDN - 5 Realisasi Investasi Terbesar</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kab/Kota</th>
                    <th>Status Penanaman Modal</th>
                    @if($triwulan2 && $triwulan2 != 'Tahun')
                        <th>Periode</th>
                    @endif
                    <th>Investasi (Rp Juta)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topPMDN as $data)
                    <tr>
                        <td>{{ $data->kabupaten_kota }}</td>
                        <td>{{ $data->status_penanaman_modal }}</td>
                        @if($triwulan2 && $triwulan2 != 'Tahun')
                            <td>{{ $data->periode }}</td>
                        @endif
                        <td>{{ number_format($data->total_investasi, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Tidak ada data PMDN.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    {{-- ===== Top 5 Proyek PMA & PMDN ===== --}}
    @if($jenisBagian2 === '5 Proyek Terbesar Berdasarkan Kab Kota')
        {{-- Tabel PMA --}}
        <h3>PMA - 5 Proyek Terbesar</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kab/Kota</th>
                    <th>Status Penanaman Modal</th>
                    @if($triwulan2 && $triwulan2 != 'Tahun')
                        <th>Periode</th>
                    @endif
                    <th>Proyek</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topPMA as $data)
                    <tr>
                        <td>{{ $data->kabupaten_kota }}</td>
                        <td>{{ $data->status_penanaman_modal }}</td>
                        @if($triwulan2 && $triwulan2 != 'Tahun')
                            <td>{{ $data->periode }}</td>
                        @endif
                        <td>{{ $data->proyekpma }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $triwulan2 != 'Tahun' ? 4 : 3 }}">Tidak ada data PMA.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Tabel PMDN --}}
        <h3>PMDN - 5 Proyek Terbesar</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kab/Kota</th>
                    <th>Status Penanaman Modal</th>
                    @if($triwulan2 && $triwulan2 != 'Tahun')
                        <th>Periode</th>
                    @endif
                    <th>Proyek</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topPMDN as $data)
                    <tr>
                        <td>{{ $data->kabupaten_kota }}</td>
                        <td>{{ $data->status_penanaman_modal }}</td>
                        @if($triwulan2 && $triwulan2 != 'Tahun')
                            <td>{{ $data->periode }}</td>
                        @endif
                        <td>{{ $data->proyekpmdn }}</td>
                    </tr>
                @empty
                   <tr><td colspan="{{ $triwulan2 != 'Tahun' ? 4 : 3 }}">Tidak ada data PMDN.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    @if($jenisBagian2 === 'sektor')
        <h2 class="judul-lokasi">Data Berdasarkan Sektor</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Sektor</th>
                    {{-- tampilkan kolom periode hanya kalau bukan filter Tahun --}}
                    @if(!isset($triwulan2) || $triwulan2 !== 'Tahun')
                        <th>Periode</th>
                    @endif
                    <th>Proyek (PMDN)</th>
                    <th>Total Investasi RP (PMDN)</th>

                    {{-- PMA --}}
                    <th>Proyek (PMA)</th>
                    <th>Total Investasi RP (PMA)</th>
                    <th>Total Investasi US (PMA)</th>

                    {{-- Gabungan --}}
                    <th>Total Proyek (PMDN + PMA)</th>
                    <th>Total Investasi RP (ALL)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sektor as $data)
                    <tr>
                        <td>{{ $data->nama_sektor }}</td>
                        @if(isset($triwulan2) && $triwulan2 !== 'Tahun')
                            <td>{{ $data->periode }}</td>
                        @endif
                        <td>{{ $data->proyek_pmdn }}</td>
                        <td>{{ number_format($data->total_investasi_rp_pmdn, 0, ',', '.') }}</td>

                        {{-- PMA --}}
                        <td>{{ $data->proyek_pma }}</td>
                        <td>{{ number_format($data->total_investasi_rp_pma, 0, ',', '.') }}</td>
                        <td>{{ number_format($data->total_investasi_us_pma, 0, ',', '.') }}</td>

                        {{-- Gabungan --}}
                        <td>{{ $data->total_proyek }}</td>
                        <td>{{ number_format($data->total_investasi_rp_all, 0, ',', '.') }}</td>

                    </tr>
                @empty
                    <tr><td colspan="10">Tidak ada data sektor.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>

{{-- Popup Modal Unduh --}}
<div id="popupFormLokasi" class="popup-overlay">
    <div class="popup-content">
        <h2>Data Diri</h2>
        <div class="warning-icon"><i class="fas fa-exclamation"></i></div>
        <p>Silahkan isi formulir untuk mengunduh file ini</p>

        <form id="downloadFormLokasi" method="POST" action="{{ route('log_pengunduhan.store') }}">
            @csrf
            <div class="checkbox-group horizontal">
                <label><input type="radio" name="kategori_pengunduh" value="Individu" required> Individu</label>
                <label><input type="radio" name="kategori_pengunduh" value="Perusahaan"> Perusahaan</label>
                <label><input type="radio" name="kategori_pengunduh" value="Lainnya"> Lainnya</label>
            </div>
            <input type="text" name="nama_instansi" placeholder="Nama Lengkap/Instansi" required>
            <input type="email" name="email_pengunduh" placeholder="Email" required>
            <input type="text" name="telpon" placeholder="Telpon">
            <textarea name="keperluan" placeholder="Keperluan"></textarea>
            <div class="checkbox-group">
                <label><input type="checkbox" required> Anda setuju bertanggung jawab atas data yang diunduh</label>
                <label><input type="checkbox" required> Pihak DPMPTSP tidak bertanggung jawab atas dampak penggunaan data</label>
            </div>
            <div class="popup-buttons">
                <button type="submit" class="btn-blue">Unduh</button>
                <button type="button" id="closePopupLokasi" class="btn-red">Batalkan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // Event delegation biar tidak hilang meski #bagian2-content di-reload
    $(document).on("click", "#openPopupLokasi", function(e) {
        e.preventDefault();
        $("#popupFormLokasi").css("display", "flex");
    });

    $(document).on("click", "#closePopupLokasi", function() {
        $("#popupFormLokasi").hide();
    });

    // Handle submit form popup download
    $(document).on("submit", "#downloadFormLokasi", function(e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(form);

        fetch(form.action, {
            method:'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                setTimeout(function() {
                    var element = document.querySelector('#bagian2-content');
                    html2pdf().set({
                        margin: 10,
                        filename: 'data_lokasi_investasi.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2, useCORS: true },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    }).from(element).save();

                    $("#popupFormLokasi").hide();
                }, 1000);
            } else {
                alert('Gagal menyimpan data pengunduhan');
            }
        });
    });

    // Filter AJAX untuk bagian2
    $('#form-bagian2').on('submit', function(e) {
        if($(document.activeElement).attr('name') === 'download') return;

        e.preventDefault();
        let url = $(this).attr('action');
        let data = $(this).serialize();

        $.get(url, data, function(response) {
            $('#bagian2-content').html(response);
        });
    });
</script>
@endpush
