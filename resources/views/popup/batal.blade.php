{{-- =====================================================
     Pop Up: Konfirmasi Batal / Tinggalkan Form
     Taruh di: resources/views/popup/batal.blade.php
     Trigger: showPopupBatal() dari JS tombol back browser
     ===================================================== --}}

<div id="popup-batal" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 24px;
    font-family: 'Lato', sans-serif;
">
    <div style="
        background: #fff;
        border-radius: 20px;
        padding: 48px 40px 40px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.2);
        width: 100%;
        max-width: 420px;
        text-align: center;
        animation: batalPopupIn 0.25s ease;
    ">
        {{-- Icon peringatan --}}
        <div style="
            width: 100px; height: 100px;
            border-radius: 50%;
            background: #fff8ed;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 32px;
        ">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                 stroke="#d97706" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>

        {{-- Judul --}}
        <h2 style="
            font-size: 24px; font-weight: 800;
            color: #111; margin-bottom: 14px;
            font-family: 'Lato', sans-serif;
        ">Tinggalkan halaman ini?</h2>

        {{-- Pesan --}}
        <p style="
            font-size: 14px; color: #666;
            line-height: 1.75; margin-bottom: 36px;
        ">Data yang sudah Anda isi akan hilang<br>dan tidak dapat dikembalikan.<br>Apakah Anda yakin ingin membatalkan?</p>

        {{-- Tombol --}}
        <div style="display: flex; gap: 16px; justify-content: center;">
            {{-- Lanjutkan mengisi --}}
            <button onclick="hidePopupBatal()" style="
                padding: 14px 0;
                width: 160px;
                background: #f0f0f0; color: #333;
                border: none; border-radius: 10px;
                font-size: 15px; font-weight: 700;
                font-family: inherit; cursor: pointer;
                transition: filter .2s, transform .1s;
            "
            onmouseover="this.style.filter='brightness(.95)'"
            onmouseout="this.style.filter='brightness(1)'"
            onmousedown="this.style.transform='scale(.97)'"
            onmouseup="this.style.transform='scale(1)'">
                Lanjutkan Isi
            </button>

            {{-- Ya, batalkan --}}
            <button onclick="batalkanForm()" style="
                padding: 14px 0;
                width: 160px;
                background: #8B0000; color: #fff;
                border: none; border-radius: 10px;
                font-size: 15px; font-weight: 700;
                font-family: inherit; cursor: pointer;
                transition: filter .2s, transform .1s;
            "
            onmouseover="this.style.filter='brightness(1.1)'"
            onmouseout="this.style.filter='brightness(1)'"
            onmousedown="this.style.transform='scale(.97)'"
            onmouseup="this.style.transform='scale(1)'">
                Ya, Batalkan
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showPopupBatal() {
        var el = document.getElementById('popup-batal');
        el.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hidePopupBatal() {
        var el = document.getElementById('popup-batal');
        el.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Kembali ke halaman sebelumnya (benar-benar pergi)
    function batalkanForm() {
        // Lepas event popstate supaya tidak loop
        window.onpopstate = null;
        history.back();
    }

    // Tutup saat klik overlay
    document.getElementById('popup-batal').addEventListener('click', function(e) {
        if (e.target === this) hidePopupBatal();
    });
</script>
@endpush

@push('styles')
<style>
    @keyframes batalPopupIn {
        from { opacity: 0; transform: scale(0.92) translateY(16px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
</style>
@endpush
