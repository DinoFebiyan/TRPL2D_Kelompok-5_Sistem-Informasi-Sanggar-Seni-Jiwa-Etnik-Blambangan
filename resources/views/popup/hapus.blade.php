<div id="popup-hapus" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 99999;
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
        animation: hapusPopupIn 0.25s ease;
    ">
        {{-- Icon Hapus (trash) --}}
        <div style="
            width: 80px; height: 80px;
            border-radius: 50%;
            background: #fdecec;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
        ">
            <svg width="38" height="38" viewBox="0 0 24 24" fill="none"
                 stroke="#c0272d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6"/>
                <path d="M14 11v6"/>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
        </div>

        {{-- Judul --}}
        <h2 style="
            font-size: 22px; font-weight: 800;
            color: #111; margin-bottom: 14px;
            font-family: 'Lato', sans-serif;
        ">Yakin ingin menghapus?</h2>

        {{-- Pesan --}}
        <p style="
            font-size: 13.5px; color: #666;
            line-height: 1.7; margin-bottom: 32px;
        ">Data yang dihapus tidak dapat dikembalikan.<br>Pastikan Anda memilih data yang benar sebelum melanjutkan.</p>

        {{-- Tombol --}}
        <div style="display: flex; gap: 14px; justify-content: center;">
            {{-- Batal --}}
            <button onclick="hidePopupHapus()" style="
                padding: 13px 28px;
                background: #555; color: #fff;
                border: none; border-radius: 10px;
                font-size: 14.5px; font-weight: 700;
                font-family: inherit; cursor: pointer;
                min-width: 120px;
                transition: filter .2s, transform .1s;
            "
            onmouseover="this.style.filter='brightness(1.15)'"
            onmouseout="this.style.filter='brightness(1)'"
            onmousedown="this.style.transform='scale(.97)'"
            onmouseup="this.style.transform='scale(1)'">
                Batal
            </button>

            {{-- Ya, hapus --}}
            <button id="popup-hapus-confirm" onclick="konfirmasiHapus()" style="
                padding: 13px 28px;
                background: #d9534f; color: #fff;
                border: none; border-radius: 10px;
                font-size: 14.5px; font-weight: 700;
                font-family: inherit; cursor: pointer;
                min-width: 130px;
                transition: filter .2s, transform .1s;
            "
            onmouseover="this.style.filter='brightness(1.1)'"
            onmouseout="this.style.filter='brightness(1)'"
            onmousedown="this.style.transform='scale(.97)'"
            onmouseup="this.style.transform='scale(1)'">
                Ya, hapus
            </button>
        </div>
    </div>
</div>

{{-- Style inline (tanpa @push, biar aman di layout apapun) --}}
<style>
    @keyframes hapusPopupIn {
        from { opacity: 0; transform: scale(0.92) translateY(16px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
</style>

{{-- Script inline (tanpa @push, biar aman di layout apapun) --}}
<script>
    window._popupHapusFormId   = window._popupHapusFormId   || null;
    window._popupHapusCallback = window._popupHapusCallback || null;

    /**
     * Tampilkan popup konfirmasi hapus.
     *   1. showPopupHapus('id-form')      -> submit form setelah konfirmasi
     *   2. showPopupHapus(null, callback) -> jalankan callback setelah konfirmasi
     */
    function showPopupHapus(formId, callback) {
        window._popupHapusFormId   = formId   || null;
        window._popupHapusCallback = callback || null;

        var el = document.getElementById('popup-hapus');
        if (!el) return;
        el.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hidePopupHapus() {
        var el = document.getElementById('popup-hapus');
        if (!el) return;
        el.style.display = 'none';
        document.body.style.overflow = '';
    }

    function konfirmasiHapus() {
        hidePopupHapus();

        if (typeof window._popupHapusCallback === 'function') {
            window._popupHapusCallback();
            return;
        }

        if (window._popupHapusFormId) {
            var form = document.getElementById(window._popupHapusFormId);
            if (form) { form.submit(); return; }
        }

        // Fallback
        var forms = document.querySelectorAll('form');
        if (forms.length > 0) forms[0].submit();
    }

    // Pasang listener klik overlay (hanya sekali)
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('popup-hapus');
        if (el && !el.dataset.listenerAttached) {
            el.dataset.listenerAttached = '1';
            el.addEventListener('click', function (e) {
                if (e.target === this) hidePopupHapus();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') hidePopupHapus();
        });
    });
</script>