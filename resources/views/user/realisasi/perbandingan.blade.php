@extends('layouts.app')

@section('content')
<section class="perbandingan-investasi container mt-4">

    <h2 class="mb-4">PERBANDINGAN REALISASI INVESTASI</h2>

    {{-- ===== BAGIAN 1: PERBANDINGAN PERTAHUN ===== --}}
    <div class="card mb-5">
        <h3 class="mt-4">Perbandingan Pertahun</h3>
            {{-- Filter --}}
            <form id ="form-perbandingan1" action="{{ route('realisasi.perbandingan') }}" method="GET" class="filter-bar">

                {{-- Jenis Investasi --}}
                <div class="filter-item">
                    <select name="jenis" id="jenis" class="form-select">
                        <option value="">Pilih Jenis</option>
                        <option value="PMA" {{ request('jenis') == 'PMA' ? 'selected' : '' }}>PMA</option>
                        <option value="PMDN" {{ request('jenis') == 'PMDN' ? 'selected' : '' }}>PMDN</option>
                        <option value="PMA+PMDN" {{ request('jenis') == 'PMA+PMDN' ? 'selected' : '' }}>PMA + PMDN</option>
                    </select>
                </div>

                {{-- Tahun --}}
                <div class="filter-item tahun-group">
                    <div class="tahun-selects">
                        <select name="tahun1" id="tahun1" class="form-select">
                            <option value="">Tahun 1</option>
                            @foreach(range(date('Y'), 2010) as $th)
                                <option value="{{ $th }}" {{ request('tahun1') == $th ? 'selected' : '' }}>{{ $th }}</option>
                            @endforeach
                        </select>
                        <span class="dash">-</span>
                        <select name="tahun2" id="tahun2" class="form-select">
                            <option value="">Tahun 2</option>
                            @foreach(range(date('Y'), 2010) as $th)
                                <option value="{{ $th }}" {{ request('tahun2') == $th ? 'selected' : '' }}>{{ $th }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tombol --}}
                <div>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <a href="#" class="download-btn" id="openPopupBagian1">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
                
            </form>

            {{-- Tabel Perbandingkan 1--}}
            <div id="tabel-perbandingan1">
                @include('user.realisasi.partials.tabel_perbandingan1')
            </div>
        </div>
    </div>


        <div class="card shadow-sm p-4 mt-5">
            {{-- ====================== PERBANDINGAN 2: PETRIWULAN ====================== --}}
            <h3 class="mt-5">Perbandingan Petriwulan</h3>
            {{-- Filter --}}                <form id="form-perbandingan2" action="{{ route('realisasi.perbandingan2') }}" method="GET" class="filter-bar mb-3">
                    {{-- Jenis Investasi --}}
                    <div class="filter-item">
                        <select name="jenis" id="jenis_2" class="form-select">
                            <option value="">Pilih Jenis</option>
                            <option value="PMA" {{ request('jenis') == 'PMA' ? 'selected' : '' }}>PMA</option>
                            <option value="PMDN" {{ request('jenis') == 'PMDN' ? 'selected' : '' }}>PMDN</option>
                            <option value="PMA+PMDN" {{ request('jenis') == 'PMA+PMDN' ? 'selected' : '' }}>PMA + PMDN</option>
                        </select>
                    </div>

                    {{-- Tahun dan Triwulan--}}
                    <div class="filter-item tahun-group">
                        <div class="tahun-selects">
                            {{-- Tahun 1 --}}
                            <select name="tahun1" id="tahun1_2" class="form-select">
                                <option value="">Tahun 1</option>
                                @foreach(range(date('Y'), 2010) as $th)
                                    <option value="{{ $th }}" {{ request('tahun1') == $th ? 'selected' : '' }}>{{ $th }}</option>
                                @endforeach
                            </select>
                            {{-- Periode 1 --}}
                            <select class="form-select" name="periode1" id="periode1">
                                <option value="">Pilih Periode</option>
                                <option value="Triwulan 1" {{ request('periode1') == 'Triwulan 1' ? 'selected' : '' }}>Triwulan 1</option>
                                <option value="Triwulan 2" {{ request('periode1') == 'Triwulan 2' ? 'selected' : '' }}>Triwulan 2</option>
                                <option value="Triwulan 3" {{ request('periode1') == 'Triwulan 3' ? 'selected' : '' }}>Triwulan 3</option>
                                <option value="Triwulan 4" {{ request('periode1') == 'Triwulan 4' ? 'selected' : '' }}>Triwulan 4</option>
                            </select>
                            <span class="dash">-</span>
                            {{-- Tahun 2 --}}
                            <select name="tahun2" id="tahun2_2" class="form-select">
                                <option value="">Tahun 2</option>
                                @foreach(range(date('Y'), 2010) as $th)
                                    <option value="{{ $th }}" {{ request('tahun2') == $th ? 'selected' : '' }}>{{ $th }}</option>
                                @endforeach
                            </select>
                            {{-- Periode 2 --}}
                            <select class="form-select" name="periode2" id="periode2">
                                <option value="">Pilih Periode</option>
                                <option value="Triwulan 1" {{ request('periode2') == 'Triwulan 1' ? 'selected' : '' }}>Triwulan 1</option>
                                <option value="Triwulan 2" {{ request('periode2') == 'Triwulan 2' ? 'selected' : '' }}>Triwulan 2</option>
                                <option value="Triwulan 3" {{ request('periode2') == 'Triwulan 3' ? 'selected' : '' }}>Triwulan 3</option>
                                <option value="Triwulan 4" {{ request('periode2') == 'Triwulan 4' ? 'selected' : '' }}>Triwulan 4</option>
                            </select>
                        </div>
                    </div>          

                    {{-- Tombol --}}
                    <div>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <a href="#" class="download-btn" id="openPopupBagian2">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                </form>

                {{-- Tabel Perbandingkan 2--}}
                <div id="tabel-perbandingan2">
                    @include('user.realisasi.partials.tabel_perbandingan2')
                </div>
            </div>
        </div>
    </section>

    {{-- Popup Bagian 1 --}}
    <div id="popupBagian1" class="popup-overlay">
        <div class="popup-content">
            <h2>Data Diri - Bagian 1</h2>
            <div class="warning-icon">
                <i class="fas fa-exclamation"></i>
            </div>
            <p>Silahkan isi formulir untuk mengunduh Bagian 1</p>

            <form class="downloadForm" data-bagian="Bagian 1" method="POST" action="{{ route('log_pengunduhan.store') }}">
                @csrf
                <div class="checkbox-group horizontal">
                    <label><input type="radio" name="kategori_pengunduh" value="Individu" required> Individu</label>
                    <label><input type="radio" name="kategori_pengunduh" value="Perusahaan"> Perusahaan</label>
                    <label><input type="radio" name="kategori_pengunduh" value="Lainnya"> Lainnya</label>
                </div>
                <input type="text" name="nama_instansi" placeholder="Nama Lengkap/Instansi" required>
                <input type="email" name="email_pengunduh" placeholder="Email" required>
                <input type="tel" name="telpon" placeholder="Telpon" pattern="[0-9]+" inputmode="numeric" required>
                <textarea name="keperluan" placeholder="Keperluan" required></textarea>
                <div class="checkbox-group">
                    <label><input type="checkbox" required> Anda setuju bertanggung jawab atas data yang diunduh</label>
                    <label><input type="checkbox" required> Pihak DPMPTSP tidak bertanggung jawab atas dampak penggunaan data</label>
                </div>
                <div class="popup-buttons">
                    <button type="submit" class="btn-blue">Unduh</button>
                    <button type="button" class="btn-red closePopup">Batalkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Popup Bagian 2 --}}
    <div id="popupBagian2" class="popup-overlay">
        <div class="popup-content">
            <h2>Data Diri - Bagian 2</h2>
            <div class="warning-icon">
                <i class="fas fa-exclamation"></i>
            </div>
            <p>Silahkan isi formulir untuk mengunduh Bagian 2</p>

            <form class="downloadForm" data-bagian="Bagian 2" method="POST" action="{{ route('log_pengunduhan.store') }}">
                @csrf
                <div class="checkbox-group horizontal">
                    <label><input type="radio" name="kategori_pengunduh" value="Individu" required> Individu</label>
                    <label><input type="radio" name="kategori_pengunduh" value="Perusahaan"> Perusahaan</label>
                    <label><input type="radio" name="kategori_pengunduh" value="Lainnya"> Lainnya</label>
                </div>
                <input type="text" name="nama_instansi" placeholder="Nama Lengkap/Instansi" required>
                <input type="email" name="email_pengunduh" placeholder="Email" required>
                <input type="tel" name="telpon" placeholder="Telpon" pattern="[0-9]+" inputmode="numeric" required>
                <textarea name="keperluan" placeholder="Keperluan" required></textarea>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="persetujuan_tanggung_jawab" value="1" required > Anda setuju bertanggung jawab atas data yang diunduh</label>
                    <label><input type="checkbox" name="persetujuan_dpmptsp" value="1" required > Pihak DPMPTSP tidak bertanggung jawab atas dampak penggunaan data</label>
                </div>
                <div class="popup-buttons">
                    <button type="submit" class="btn-blue">Unduh</button>
                    <button type="button" class="btn-red closePopup">Batalkan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
$(document).ready(function(){
    let chart1 = null;

    // ===== PERBANDINGAN 1 =====
    $('#form-perbandingan1').submit(function(e){
        e.preventDefault();

        // VALIDASI FILTER JENIS
        let jenis = $('#jenis').val();
        let tahun1 = $('#tahun1').val();
        let tahun2 = $('#tahun2').val();

        if(jenis === ""){
            alert("Harap pilih filter Jenis.");
            return;
        }

        if(tahun1 === ""){
            alert("Harap pilih Tahun 1.");
            return;
        }

        if(tahun2 === ""){
            alert("Harap pilih Tahun 2.");
            return;
        }


        $.ajax({
            url: "{{ route('realisasi.perbandingan') }}",
            type: "GET",
            data: $(this).serialize(),

            success: function(response){
                $('#tabel-perbandingan1').html(response.html);

                if (chart1) chart1.destroy();
                let ctx = document.getElementById('chartPerbandingan1').getContext('2d');
                chart1 = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: response.chartLabels,
                        datasets: [{
                            label: 'Total Investasi Rp',
                            data: response.chartData1.concat(response.chartData2),
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(153, 102, 255, 0.5)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            },

            error: function(xhr){
                if(xhr.responseJSON && xhr.responseJSON.error){
                    alert(xhr.responseJSON.error);
                } else {
                    alert("Terjadi kesalahan pada server.");
                }
            }

        });
    });

    // ===== PERBANDINGAN 2 =====
    let chart2 = null;
    $('#form-perbandingan2').submit(function(e){
        e.preventDefault();

        // ========== VALIDASI PERBANDINGAN 2 ==========
        let jenis = $('#jenis_2').val();
        let tahun1 = $('#tahun1_2').val();
        let tahun2 = $('#tahun2_2').val();
        let periode1 = $('#periode1').val();
        let periode2 = $('#periode2').val();

        if(jenis === ""){
            alert("Harap pilih Jenis Investasi.");
            return;
        }

        if(tahun1 === ""){
            alert("Harap pilih Tahun 1.");
            return;
        }

        if(periode1 === ""){
            alert("Harap pilih Periode 1.");
            return;
        }

        if(tahun2 === ""){
            alert("Harap pilih Tahun 2.");
            return;
        }

        if(periode2 === ""){
            alert("Harap pilih Periode 2.");
            return;
        }

        // ========== JIKA LOLOS VALIDASI → KIRIM AJAX ==========
        $.ajax({
            url: "{{ route('realisasi.perbandingan2') }}",
            type: "GET",
            data: $(this).serialize(),
            success: function(response){
                $('#tabel-perbandingan2').html(response.html);

                if (chart2) chart2.destroy();
                let ctx = document.getElementById('chartPerbandingan2').getContext('2d');
                chart2 = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: response.chartLabels,
                        datasets: [{
                            label: 'Total Investasi (Rp Juta)',
                            data: response.chartData1.concat(response.chartData2)
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        });
    });

});

$(document).ready(function(){
    // Membuka popup
    $('#openPopupBagian1').click(function(e){
        e.preventDefault();
        $('#popupBagian1').fadeIn();
    });

    $('#openPopupBagian2').click(function(e){
        e.preventDefault();
        $('#popupBagian2').fadeIn();
    });

    // Menutup popup
    $('.closePopup').click(function(){
        $(this).closest('.popup-overlay').fadeOut();
    });

    // Tutup popup saat klik di luar konten
    $('.popup-overlay').click(function(e){
        if(e.target == this) $(this).fadeOut();
    });

    // Submit form unduh PDF
    $('.downloadForm').submit(function(e){
        e.preventDefault();

        const emojiRegex = /([\u203C-\u3299]|\ud83c[\ud000-\udfff]|\ud83d[\ud000-\udfff]|\ud83e[\ud000-\udfff])/g;
        let hasEmoji = false;

        $(this).find('input[type=text], input[type=email], input[type=tel], textarea').each(function(){
            if (emojiRegex.test($(this).val())) {
                hasEmoji = true;
            }
        });

        if (hasEmoji) {
            alert("Input tidak boleh mengandung emoji.");
            return;
        }



        const bagian = $(this).data('bagian');

        // Kirim log pengunduhan terlebih dahulu
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(){ console.log('Log pengunduhan tersimpan'); }
        });

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const pageHeight = doc.internal.pageSize.height; // tinggi halaman
        let startY = 20;

        let containerSelector = '';
        let chartCanvas = null;

        if(bagian === 'Bagian 1'){
            containerSelector = '#tabel-perbandingan1';
            chartCanvas = document.getElementById('chartPerbandingan1');
        } else if(bagian === 'Bagian 2'){
            containerSelector = '#tabel-perbandingan2';
            chartCanvas = document.getElementById('chartPerbandingan2');
        }

        // Loop semua tabel di dalam container
        document.querySelectorAll(`${containerSelector} table`).forEach((tbl) => {
            if(tbl){
                doc.autoTable({
                    html: tbl,
                    startY: startY,
                    styles: { fontSize: 8 },
                    didDrawPage: function(data){
                        startY = data.cursor.y + 10;
                    }
                });
                startY = doc.lastAutoTable.finalY + 10;

                // Cek jika sudah mendekati halaman bawah
                if(startY + 110 > pageHeight){ // 110mm untuk chart
                    doc.addPage();
                    startY = 20;
                }
            }
        });

        // Tambahkan chart
        if(chartCanvas){
            // Jika chart tidak muat di halaman saat ini, buat halaman baru
            if(startY + 100 > pageHeight){
                doc.addPage();
                startY = 20;
            }
            const chartImage = chartCanvas.toDataURL('image/jpeg', 1.0);
            doc.addImage(chartImage, 'JPEG', 10, startY, 180, 100);
        }

        doc.save(`${bagian}.pdf`);
        $(this).closest('.popup-overlay').fadeOut();
    });

});
</script>
@endpush


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/perbandingan.css') }}">
    <link rel="stylesheet" href="{{ asset('css/popup.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

@endpush