
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
            <button type="submit" name="download" value="1" class="btn btn-success">
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
</section>

@push('scripts')
<script>
$('#form-bagian2').on('submit', function(e) {
    // jika tombol download ditekan, biarkan submit normal
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

