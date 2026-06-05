{{-- Pop Up: Konfirmasi Batal --}}
{{-- Cara pakai: @include('popup.batal') di blade manapun --}}
{{-- Trigger: panggil showPopupBatal() --}}
{{-- Konfigurasi: set window.popupBatalUrl = '{{ route(...) }}' sebelum memanggil showPopupBatal() --}}

<div id="popup-batal" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 24px;
    font-family: 'Lato', sans-serif;
">
    <div style="
        background: #fff;
        border-radius: 20px;

        padding: 44px 36px 36px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18);
        width: 100%;
        max-width: 400px;
        text-align: center;
        animation: batalPopupIn 0.25s ease;
    ">
        {{-- Icon X --}}
        <div style="
            width: 80px; height: 80px;
            border-radius: 50%;
            background: #f0e8e8;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
        ">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                 stroke="#b08080" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </div>

        {{-- Judul --}}
        <h2 style="
            font-size: 22px; font-weight: 800;
            color: #111; margin-bottom: 14px;
            font-family: 'Lato', sans-serif;
        ">Yakin ingin membatalkan?</h2>

        {{-- Pesan --}}
        <p style="
            font-size: 13.5px; color: #666;
            line-height: 1.7; margin-bottom: 32px;
        ">Perubahan yang belum disimpan akan hilang.<br>Apakah kamu ingin membatalkan?</p>

        {{-- Tombol --}}
        <div style="display: flex; gap: 14px; justify-content: center;">
            {{-- Ya, batal --}}
            <button id="popup-batal-confirm" onclick="konfirmasiBatal()" style="
                padding: 13px 28px;
                background: #E53935; color: #fff;
                border: none; border-radius: 10px;
                font-size: 14.5px; font-weight: 700;
                font-family: inherit; cursor: pointer;
                min-width: 120px;
                transition: filter .2s, transform .1s;
            "
            onmouseover="this.style.filter='brightness(1.1)'"
            onmouseout="this.style.filter='brightness(1)'"
            onmousedown="this.style.transform='scale(.97)'"
            onmouseup="this.style.transform='scale(1)'">
                Ya, batal
            </button>

            {{-- Lanjutkan Perubahan --}}
            <button onclick="hidePopupBatal()" style="
                padding: 13px 28px;
                background: #555; color: #fff;
                border: none; border-radius: 10px;
                font-size: 14.5px; font-weight: 700;
                font-family: inherit; cursor: pointer;
                min-width: 160px;
                transition: filter .2s, transform .1s;
            "
            onmouseover="this.style.filter='brightness(1.15)'"
            onmouseout="this.style.filter='brightness(1)'"
            onmousedown="this.style.transform='scale(.97)'"
            onmouseup="this.style.transform='scale(1)'">
                Lanjutkan Perubahan
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // URL tujuan setelah batal dikonfirmasi (diset dari luar sebelum showPopupBatal dipanggil)
    window._popupBatalUrl = window._popupBatalUrl || null;

    function showPopupBatal(url) {
        if (url) window._popupBatalUrl = url;
        var el = document.getElementById('popup-batal');
        el.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hidePopupBatal() {
        var el = document.getElementById('popup-batal');
        el.style.display = 'none';
        document.body.style.overflow = '';
    }

    function konfirmasiBatal() {
        if (window._popupBatalUrl) {
            window.location.href = window._popupBatalUrl;
        } else {
            history.back();
        }
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
