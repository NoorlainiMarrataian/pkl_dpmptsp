@extends('layouts.app')

@section('content')
<section class="lokasi-investasi container mt-4">

    <h2 class="mb-4">LOKASI</h2>

    {{-- ===== BAGIAN 1: Kab/Kota (grafik + tabel) ===== --}}
    <div class="card mb-5">
        <div class="card-body">
            @include('user.realisasi.partials.lokasi_kabkota')
        </div>
    </div>  

    {{-- ===== BAGIAN 2: Sektor, dll ===== --}}
    <div class="card" id="bagian2-content">
        <div class="card-body">
            @include('user.realisasi.partials.lokasi_sektor')
        </div>
    </div>

</section>

{{-- Popup Modal --}}
<div id="popupForm" class="popup-overlay" aria-hidden="true">
    <div class="popup-content" role="dialog" aria-modal="true" aria-labelledby="popupTitle">
        <h2 id="popupTitle">Data Diri</h2>
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
                <button type="button" id="closePopup" class="btn-red">Batalkan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/lokasi.css') }}">
<link rel="stylesheet" href="{{ asset('css/popup.css') }}">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var openBtn = document.getElementById('openPopup') || document.querySelector('.btn-download');
    var popup = document.getElementById('popupForm');
    var closeBtn = document.getElementById('closePopup');
    var downloadForm = document.getElementById('downloadForm');

    function showPopup() {
        if (!popup) return;
        popup.style.display = 'flex';
        popup.setAttribute('aria-hidden', 'false');
    }
    function hidePopup() {
        if (!popup) return;
        popup.style.display = 'none';
        popup.setAttribute('aria-hidden', 'true');
    }

    if (openBtn) {
        openBtn.addEventListener('click', function (e) {
            if (e) e.preventDefault();
            showPopup();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            hidePopup();
        });
    }

    if (popup) {
        popup.addEventListener('click', function (e) {
            if (e.target === popup) hidePopup();
        });
    }

    if (downloadForm) {
        downloadForm.addEventListener('submit', function (e) {
            e.preventDefault();

            var form = this;
            var formData = new FormData(form);
            var csrfToken = form.querySelector('input[name="_token"]') ? form.querySelector('input[name="_token"]').value : '';

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(function(response) {
                var ct = response.headers.get('content-type') || '';
                if (ct.indexOf('application/json') !== -1) {
                    return response.json();
                }
                return response.text().then(function(text){
                    throw new Error('Server returned non-JSON response. Preview: ' + text.slice(0,200));
                });
            })
            .then(function(data) {
                if (data && data.success) {
                    var element = document.getElementById('exportArea'); 
                    if (!element) {
                        console.warn('[popup] #exportArea tidak ditemukan.');
                        hidePopup();
                        return;
                    }

                    try {
                        var opt = {
                            margin: [10,10,10,10],
                            filename: 'grafik-dan-tabel-lokasi.pdf',
                            image: { type: 'jpeg', quality: 0.98 },
                            html2canvas: { scale: 3, useCORS: true, scrollY: 0 },
                            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
                        };

                        var pdfTask = html2pdf().set(opt).from(element).save();

                        if (pdfTask && typeof pdfTask.then === 'function') {
                            pdfTask.then(function(){ hidePopup(); }).catch(function(err){
                                console.error('html2pdf error:', err);
                                hidePopup();
                            });
                        } else {
                            setTimeout(function(){ hidePopup(); }, 800);
                        }
                    } catch (err) {
                        console.error('html2pdf exception:', err);
                        hidePopup();
                    }
                } else {
                    alert('Gagal menyimpan data.');
                }
            })
            .catch(function(err){
                console.error('downloadForm error:', err);
                alert('Terjadi error saat pengiriman data. Silakan cek console untuk detail.');
            });
        });
    }
});
</script>
@endpush