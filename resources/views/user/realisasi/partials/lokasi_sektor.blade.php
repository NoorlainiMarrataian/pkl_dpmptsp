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
        <button type="button" name="jenisBagian2" value="5 Realisasi Investasi Terbesar Berdasarkan Kab Kota" class="btn btn-secondary">
            5 Realisasi Investasi Terbesar Berdasarkan Kab Kota
        </button>
        <button type="button" name="jenisBagian2" value="5 Proyek Terbesar Berdasarkan Kab Kota" class="btn btn-secondary">
            5 Proyek Terbesar Berdasarkan Kab Kota
        </button>
        <button type="button" name="jenisBagian2" value="sektor" class="btn btn-secondary">
            Berdasarkan Sektor
        </button>

        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
        <input type="hidden" name="triwulan" value="{{ request('triwulan') }}">
        <input type="hidden" name="jenis" value="{{ request('jenis') }}">

    </form>

    <div id="bagian2-result">
        <div id="print-area">
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
                        @if(isset($triwulan2) && $triwulan2 !== 'Tahun')
                            <th>Periode</th>
                        @endif
                        <th>Proyek (PMDN)</th>
                        <th>Total Investasi RP (PMDN)</th>
                        <th>Proyek (PMA)</th>
                        <th>Total Investasi RP (PMA)</th>
                        <th>Total Investasi US (PMA)</th>
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
                            <td>{{ $data->proyek_pma }}</td>
                            <td>{{ number_format($data->total_investasi_rp_pma, 0, ',', '.') }}</td>
                            <td>{{ number_format($data->total_investasi_us_pma, 0, ',', '.') }}</td>
                            <td>{{ $data->total_proyek }}</td>
                            <td>{{ number_format($data->total_investasi_rp_all, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (isset($triwulan2) && $triwulan2 !== 'Tahun') ? 9 : 8 }}">Tidak ada data sektor.</td>
                        </tr>
                    @endforelse

                    {{-- Baris Total --}}
                    @if(count($sektor) > 0)
                        <tr style="font-weight:bold; background:#f2f2f2;">
                            <td>Total</td>
                            @if(isset($triwulan2) && $triwulan2 !== 'Tahun')
                                <td></td>
                            @endif
                            <td>{{ $sektor->sum('proyek_pmdn') }}</td>
                            <td>{{ number_format($sektor->sum('total_investasi_rp_pmdn'), 0, ',', '.') }}</td>
                            <td>{{ $sektor->sum('proyek_pma') }}</td>
                            <td>{{ number_format($sektor->sum('total_investasi_rp_pma'), 0, ',', '.') }}</td>
                            <td>{{ number_format($sektor->sum('total_investasi_us_pma'), 0, ',', '.') }}</td>
                            <td>{{ $sektor->sum('total_proyek') }}</td>
                            <td>{{ number_format($sektor->sum('total_investasi_rp_all'), 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif
        </div>
    </div>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" defer></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    // === Filter AJAX untuk bagian2 ===
    $(document).on('click', '#bagian2-content button[name="jenisBagian2"]', function(e) {
        e.preventDefault();
        let url = $('#form-bagian2').attr('action');
        let data = $('#form-bagian2').serialize();
        data += '&jenisBagian2=' + encodeURIComponent($(this).val());

        $.get(url, data, function(response) {
            let html = $("<div>").html(response);
            let newResult = html.find("#bagian2-result").html();
            if (newResult !== undefined) {
                $("#bagian2-result").html(newResult);
            }
        });
    });

});
</script>
@endpush
