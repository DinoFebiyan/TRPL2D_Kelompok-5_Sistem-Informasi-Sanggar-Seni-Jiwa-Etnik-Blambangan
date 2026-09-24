<div id="popup-validasi" style="
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
        max-width: 440px;
        text-align: center;
        animation: validasiPopupIn 0.25s ease;
    ">
        {{-- Icon Warning --}}
        <div style="
            width: 80px; height: 80px;
            border-radius: 50%;
            background: #fdf3e7;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
        ">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                 stroke="#d99a2b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>

        {{-- Judul --}}
        <h2 style="
            font-size: 22px; font-weight: 800;
            color: #111; margin-bottom: 14px;
            font-family: 'Lato', sans-serif;
        ">Form Belum Lengkap</h2>

        <p style="
            font-size: 13.5px; color: #666;
            line-height: 1.7; margin-bottom: 20px;
        ">Mohon lengkapi data berikut sebelum menyimpan:</p>

        {{-- List Field --}}
        <ul id="popup-validasi-list" style="
            list-style: none;
            padding: 14px 18px;
            margin: 0 0 30px;
            background: #fdf6f6;
            border-radius: 12px;
            border-left: 3px solid #d9534f;
            text-align: left;
            max-height: 220px;
            overflow-y: auto;
        "></ul>

        {{-- Tombol --}}
        <div style="display: flex; justify-content: center;">
            <button onclick="hidePopupValidasi()" style="
                padding: 13px 40px;
                background: #00BCD4; color: #fff;
                border: none; border-radius: 10px;
                font-size: 14.5px; font-weight: 700;
                font-family: inherit; cursor: pointer;
                min-width: 160px;
                transition: filter .2s, transform .1s;
            "
            onmouseover="this.style.filter='brightness(1.1)'"
            onmouseout="this.style.filter='brightness(1)'"
            onmousedown="this.style.transform='scale(.97)'"
            onmouseup="this.style.transform='scale(1)'">
                Oke, Mengerti
            </button>
        </div>
    </div>
</div>

{{-- Style inline (tanpa @push) --}}
<style>
    @keyframes validasiPopupIn {
        from { opacity: 0; transform: scale(0.92) translateY(16px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
</style>

{{-- Script inline (tanpa @push) --}}
<script>
    function showPopupValidasi(listPesan) {
        var listEl = document.getElementById('popup-validasi-list');
        if (!listEl) return;
        listEl.innerHTML = '';

        (listPesan || []).forEach(function (pesan) {
            var li = document.createElement('li');
            li.style.cssText = 'color:#8a2a2a; font-size:13.5px; line-height:1.6; padding:5px 0; display:flex; align-items:flex-start; gap:8px;';
            li.innerHTML = '<span style="color:#d9534f; font-weight:700;">•</span> <span>' + pesan + '</span>';
            listEl.appendChild(li);
        });

        var el = document.getElementById('popup-validasi');
        el.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hidePopupValidasi() {
        var el = document.getElementById('popup-validasi');
        if (!el) return;
        el.style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('popup-validasi');
        if (el && !el.dataset.listenerAttached) {
            el.dataset.listenerAttached = '1';
            el.addEventListener('click', function (e) {
                if (e.target === this) hidePopupValidasi();
            });
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') hidePopupValidasi();
        });
    });
</script>