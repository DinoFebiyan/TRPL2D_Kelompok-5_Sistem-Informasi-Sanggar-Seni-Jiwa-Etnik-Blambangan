{{-- Pop Up: Halaman Tidak Ditemukan (404) --}}
{{-- Cara pakai: tambahkan @include('popups.path') di blade manapun --}}
{{-- Trigger tampil: panggil showPopupPath() --}}

<!-- Overlay Pop Up 404 Not Found -->
<div id="popup-path" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 24px;
    font-family: 'Plus Jakarta Sans', sans-serif;
">
    <div style="
        background: #F0EBFF;
        border-radius: 16px;
        padding: 36px 28px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        width: 100%;
        max-width: 420px;
        text-align: center;
        animation: popupIn 0.25s ease;
    ">
        {{-- Icon dengan spark --}}
        <div style="position: relative; display: inline-block; margin-bottom: 20px;">
            <div style="
                width: 80px; height: 80px;
                border-radius: 50%;
                background: #DDD6FE;
                display: flex; align-items: center; justify-content: center;
                font-size: 36px;
            ">📄</div>
            <span style="position:absolute; top:-4px;  left:-14px;  font-size:16px; color:#A78BFA; opacity:.6; font-weight:300;">+</span>
            <span style="position:absolute; top:-4px;  right:-14px; font-size:16px; color:#A78BFA; opacity:.6; font-weight:300;">+</span>
            <span style="position:absolute; bottom:-4px; left:-14px;  font-size:16px; color:#A78BFA; opacity:.6; font-weight:300;">+</span>
            <span style="position:absolute; bottom:-4px; right:-14px; font-size:16px; color:#A78BFA; opacity:.6; font-weight:300;">+</span>
        </div>

        {{-- Label --}}
        <p style="
            font-size: 11px; font-weight: 800;
            letter-spacing: .14em; text-transform: uppercase;
            color: #475569; margin-bottom: 8px;
        ">Hal Tidak Ditemukan (404)</p>

        {{-- Judul --}}
        <h2 style="
            font-size: 18px; font-weight: 800;
            color: #6C3AE0; margin-bottom: 10px;
        ">Halaman Tidak Ditemukan</h2>

        {{-- Pesan --}}
        <p style="
            font-size: 13.5px; color: #475569;
            line-height: 1.65; margin-bottom: 24px;
        ">Halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>

        {{-- Tombol --}}
        <button onclick="hidePopupPath()" style="
            display: inline-block;
            padding: 12px 28px;
            border: 2px solid #6C3AE0;
            border-radius: 10px;
            background: transparent; color: #6C3AE0;
            font-size: 14.5px; font-weight: 700;
            font-family: inherit; cursor: pointer;
            transition: background .2s, transform .1s;
        "
        onmouseover="this.style.background='#EDE9FE'"
        onmouseout="this.style.background='transparent'"
        onmousedown="this.style.transform='scale(.97)'"
        onmouseup="this.style.transform='scale(1)'">
            Kembali ke Beranda
        </button>
    </div>
</div>

@push('scripts')
<script>
    function showPopupPath() {
        var el = document.getElementById('popup-path');
        el.style.display = 'flex';
    }
    function hidePopupPath() {
        var el = document.getElementById('popup-path');
        el.style.display = 'none';
    }
    // Tutup saat klik overlay
    document.getElementById('popup-path').addEventListener('click', function(e) {
        if (e.target === this) hidePopupPath();
    });
</script>
@endpush

@push('styles')
<style>
    @keyframes popupIn {
        from { opacity: 0; transform: scale(0.92) translateY(16px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
</style>
@endpush