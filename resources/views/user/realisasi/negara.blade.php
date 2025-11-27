@extends('layouts.app')

@section('content')
<section class="negara-investor">
    <h2>NEGARA INVESTOR</h2>

    {{-- Filter Tahun & Periode --}}
    <form class="filter-bar" action="{{ route('realisasi.negara') }}" method="GET">
        <select class="dropdown-tahun" name="tahun" id="tahunSelect">
            <option value="">Pilih Tahun</option>
            @foreach(range(date('Y'), 2010) as $th)
                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
            @endforeach
        </select>
        <button type="submit" name="triwulan" value="Tahun" class="btn-periode" disabled>1 Tahun</button>
        <button type="submit" name="triwulan" value="Triwulan 1" class="btn-periode" disabled>Triwulan 1</button>
        <button type="submit" name="triwulan" value="Triwulan 2" class="btn-periode" disabled>Triwulan 2</button>
        <button type="submit" name="triwulan" value="Triwulan 3" class="btn-periode" disabled>Triwulan 3</button>
        <button type="submit" name="triwulan" value="Triwulan 4" class="btn-periode" disabled>Triwulan 4</button>

        <a href="#" class="btn-download" id="openPopup">
            <i class="bi bi-download"></i>
        </a>
    </form>

    {{-- Grafik --}}
    @if($data_investasi->isNotEmpty())
        <div class="grafik-card">
            <canvas id="chartNegara" width="1000" height="400"></canvas>
        </div>
    @endif

    {{-- Tabel --}}
    @if($data_investasi->isNotEmpty())
        <div class="tabel-card">
            <h3 class="judul-tabel">PMA - {{ $tahun }}</h3>
            <table class="tabel-negara">
                <thead>
                    <tr>
                        <th>Negara</th>
                        <th>Proyek</th>
                        <th>Periode</th>
                        <th>Tambahan Investasi (US$ Ribu)</th>
                        <th>Tambahan Investasi (Rp Juta)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_investasi as $data)
                    <tr>
                        <td>{{ $data->negara ?? '-' }}</td>
                        <td>{{ $data->jumlah_pma ?? '-' }}</td> 
                        <td>{{ $data->periode ?? '-' }}</td>
                        <td>{{ isset($data->total_investasi_us_ribu) ? number_format($data->total_investasi_us_ribu, 2, ',', '.') : '-' }}</td>
                        <td>{{ isset($data->total_investasi_rp_juta) ? number_format($data->total_investasi_rp_juta, 2, ',', '.') : '-' }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="1"><strong>Total</strong></td>
                        <td><strong>{{ $total['jumlah_pma'] ?? '-' }}</strong></td>
                        <td colspan="1"></td>
                        <td><strong>{{ isset($total['total_investasi_us_ribu']) ? number_format($total['total_investasi_us_ribu'], 2, ',', '.') : '-' }}</strong></td>
                        <td><strong>{{ isset($total['total_investasi_rp_juta']) ? number_format($total['total_investasi_rp_juta'], 2, ',', '.') : '-' }}</strong></td>
                </tbody>
            </table>
        </div>
    @endif

    {{-- Pesan kosong --}}
    @if($data_investasi->isEmpty())
        @if(!request('tahun') || !request('triwulan'))
            <p class="text-center" style="margin-top:20px; font-style:italic; color:#777;">
                Silahkan Pilih Tahun dan Periode Untuk Melihat Data
            </p>
        @else
            <p class="text-center" style="margin-top:20px; font-style:italic; color:#777;">
                Data belum ada
            </p>
        @endif
    @endif
</section>

{{-- Popup Modal --}}
<div id="popupForm" class="popup-overlay">
    <div class="popup-content">
        <h2>Data Diri</h2>
        <div class="warning-icon">
            <i class="fas fa-exclamation"></i>
        </div>
        <p>Silahkan isi formulir untuk mengunduh file ini</p>

        <form id="downloadForm" method="POST" action="{{ route('log_pengunduhan.store') }}">
            @csrf
            <div class="checkbox-group horizontal">
                <label><input type="radio" name="kategori_pengunduh" value="Individu" required> Individu</label>
                <label><input type="radio" name="kategori_pengunduh" value="Perusahaan"> Perusahaan</label>
                <label><input type="radio" name="kategori_pengunduh" value="Lainnya"> Lainnya</label>
            </div>
            <div style="position: relative;">
                <input type="text" name="nama_instansi" id="namaInstansi" placeholder="Nama Lengkap/Instansi" maxlength="100" required>
                <small id="charCount" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #999; font-size: 12px;">0/100</small>
            </div>
            <span id="charWarning" style="color: red; font-size: 12px; display: none; margin-top: 5px;">Maaf, karakter yang anda masukkan lebih 100</span>
            <input type="email" name="email_pengunduh" placeholder="Email" required>
            <div style="position: relative;">
                <input type="tel" name="telpon" id="telponInput" placeholder="Telpon" pattern="[0-9]+" inputmode="numeric" maxlength="20" required>
            </div>
            <div style="margin-top:6px;">
                <span id="telWarning" style="color: red; font-size:12px; display:none;">Mohon masukkan nomor telepon dengan 5–20 digit.</span>
            </div>
            <textarea name="keperluan" placeholder="Keperluan" required></textarea>
            <div class="checkbox-group">
                <label><input type="checkbox" required> Anda setuju bertanggung jawab atas data yang diunduh</label>
                <label><input type="checkbox" required> Pihak DPMPTSP tidak bertanggung jawab atas dampak penggunaan data</label>
            </div>
            <div class="popup-buttons">
                <button type="submit" class="btn-blue">Unduh</button>
                <button type="button" id="closePopup" class="btn-red">Batalkan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/negara.css') }}">
<link rel="stylesheet" href="{{ asset('css/popup.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Enable tombol periode setelah pilih tahun
    const tahunSelect = document.getElementById("tahunSelect");
    const periodeButtons = document.querySelectorAll(".btn-periode");
    tahunSelect.addEventListener("change", function(){
        periodeButtons.forEach(btn => {
            btn.disabled = (this.value === "");
        });
    });
    // Jalankan sekali saat load halaman
    periodeButtons.forEach(btn => {
        btn.disabled = (tahunSelect.value === "");
    });

    // Popup
    document.getElementById("openPopup").addEventListener("click", function(e){
        e.preventDefault();
        document.getElementById("popupForm").style.display = "flex";
    });
    document.getElementById("closePopup").addEventListener("click", function(){
        document.getElementById("popupForm").style.display = "none";
    });

    // Validasi karakter input nama_instansi
    const namaInstansiInput = document.getElementById('namaInstansi');
    const charCount = document.getElementById('charCount');
    const charWarning = document.getElementById('charWarning');
    
    if (namaInstansiInput) {
        namaInstansiInput.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length + '/100';
            
            if (length >= 100) {
                charWarning.style.display = 'block';
            } else {
                charWarning.style.display = 'none';
            }
        });
    }

    // Validasi input telpon (real-time) - hanya angka, dengan satu peringatan
    const telInput = document.getElementById('telponInput');
    const telWarning = document.getElementById('telWarning');
    if (telInput && telWarning) {
        // Prevent non-digit key presses (allow control/navigation keys)
        telInput.addEventListener('keydown', function(e) {
            const allowedKeys = [8,9,13,27,35,36,37,38,39,40,46]; // backspace, tab, enter, esc, home,end,arrows,delete
            if (allowedKeys.includes(e.keyCode) || e.ctrlKey || e.metaKey) return;
            // Allow digits only
            if (/^[0-9]$/.test(e.key)) return;
            e.preventDefault();
        });

        // On input: sanitize (remove non-digits) and show/hide warning
        telInput.addEventListener('input', function() {
            const onlyDigits = this.value.replace(/\D/g, '');
            if (this.value !== onlyDigits) this.value = onlyDigits;
            if (onlyDigits.length < 5 || onlyDigits.length > 20) {
                telWarning.style.display = 'inline-block';
            } else {
                telWarning.style.display = 'none';
            }
        });

        // On paste: sanitize pasted content to digits only
        telInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const digits = paste.replace(/\D/g, '');
            // Insert digits at cursor position
            const start = this.selectionStart || 0;
            const end = this.selectionEnd || 0;
            const before = this.value.slice(0, start);
            const after = this.value.slice(end);
            let newVal = before + digits + after;
            newVal = newVal.replace(/\D/g, '').slice(0, 20); // enforce maxlength
            this.value = newVal;
            if (newVal.length < 5 || newVal.length > 20) telWarning.style.display = 'inline-block'; else telWarning.style.display = 'none';
        });
    }

    // Download
    document.getElementById('downloadForm').addEventListener('submit', function(e){
        e.preventDefault();
        var form = this;

        // 1) Pastikan user sudah memilih Tahun dan Periode sebelum mengunduh
        var tahunVal = document.getElementById('tahunSelect') ? document.getElementById('tahunSelect').value : '';
        var selectedTriwulanBtn = document.querySelector('.btn-periode.active');
        if (!tahunVal || !selectedTriwulanBtn) {
            alert('Silakan pilih tahun dan periode terlebih dahulu sebelum mengunduh data.');
            return;
        }

        // 2) Jika sudah memilih tetapi tidak ada data, tampilkan pesan "Data belum ada"
        var tabelCard = document.querySelector('.tabel-card');
        if (!tabelCard) {
            alert('Tidak ada data yang diunduh. Silahkan pilih tahun dan periode valid');
            return;
        }

        // 3) Validasi panjang telepon sebelum submit (digit only)
        var telEl = document.getElementById('telponInput');
        if (telEl) {
            var telDigits = telEl.value.replace(/\D/g, '');
            if (telDigits.length < 5 || telDigits.length > 20) {
                document.getElementById('telWarning').style.display = 'inline-block';
                telEl.focus();
                return; // hentikan submit
            }
        }

        // ✅ Tambahkan blok validasi emoji di sini
        const emojiRegex = /([\u203C-\u3299]|\ud83c[\ud000-\udfff]|\ud83d[\ud000-\udfff]|\ud83e[\ud000-\udfff])/g;

        let hasEmoji = false;
        form.querySelectorAll('input[type=text], input[type=email], input[type=tel], textarea').forEach(el => {
            if (emojiRegex.test(el.value)) {
                hasEmoji = true;
            }
        });

        if (hasEmoji) {
            alert("Input tidak boleh mengandung emoji.");
            return; // 🚫 hentikan proses unduh
        }

        var formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                setTimeout(function() {
                    var element = document.querySelector('.tabel-card');
                    html2pdf().set({
                        margin: 10,
                        filename: 'tabel-negara-investor.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2, useCORS: true },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    }).from(element).save();
                    document.getElementById("popupForm").style.display = "none";
                }, 300);
            } else {
                alert('Gagal menyimpan data.');
            }
        });
    });

    @if($data_investasi->isNotEmpty())
    // Grafik Chart.js
    const ctx = document.getElementById('chartNegara');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($data_investasi->pluck('negara')),
            datasets: [
                {
                    label: 'Investasi Rp',
                    data: @json($data_investasi->pluck('total_investasi_rp_juta')),
                    backgroundColor: 'rgba(255, 0, 0, 1)',
                    borderColor: 'rgba(255, 0, 0, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: { 
            responsive: true, 
            scales: { y: { beginAtZero: true } } 
        }
    });
    @endif

    document.addEventListener("DOMContentLoaded", function () {
    const periodeButtons = document.querySelectorAll(".btn-periode");

    // Klik tombol: hanya set class active — biarkan form submit secara normal
    periodeButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            periodeButtons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            // jangan call e.preventDefault() => biarkan form submit
        });
    });

    // (Opsional) Kalau mau tombol tetap tampak active setelah reload,
    // tambahkan class berdasarkan request param (blade)
    const selectedTriwulan = @json(request('triwulan'));
    if (selectedTriwulan) {
        const activeBtn = Array.from(periodeButtons).find(b => b.value === selectedTriwulan);
        if (activeBtn) {
            periodeButtons.forEach(b => b.classList.remove("active"));
            activeBtn.classList.add("active");
        }
    }
});
</script>
@endpush
