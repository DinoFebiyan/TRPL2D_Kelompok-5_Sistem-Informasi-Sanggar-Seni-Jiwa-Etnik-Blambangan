{{-- Pop Up: Konfirmasi Simpan --}}
{{-- Cara pakai: @include('popup.simpan') di blade manapun --}}
{{-- Trigger: panggil showPopupSimpan('id-form') --}}

<div id="popup-simpan" style="
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
        animation: simpanPopupIn 0.25s ease;
    ">
        {{-- Icon Simpan (floppy disk) --}}
        <div style="
            width: 80px; height: 80px;
            border-radius: 50%;
            background: #f0ede8;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
        ">
            <svg width="38" height="38" viewBox="0 0 24 24" fill="none"
                 stroke="#a09080" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
            </svg>
        </div>

        {{-- Judul --}}
        <h2 style="
            font-size: 22px; font-weight: 800;
            color: #111; margin-bottom: 14px;
            font-family: 'Lato', sans-serif;
        ">Yakin ingin menyimpan?</h2>

        {{-- Pesan --}}
        <p style="
            font-size: 13.5px; color: #666;
            line-height: 1.7; margin-bottom: 32px;
        ">Data akan disimpan ke sistem.<br>Pastikan semua input sudah benar sebelum melanjutkan.</p>

        {{-- Tombol --}}
        <div style="display: flex; gap: 14px; justify-content: center;">
            {{-- Batal --}}
            <button onclick="hidePopupSimpan()" style="
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

            {{-- Ya, simpan --}}
            <button id="popup-simpan-confirm" onclick="konfirmasiSimpan()" style="
                padding: 13px 28px;
                background: #00BCD4; color: #fff;
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
                Ya, simpan
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ID form yang akan di-submit setelah konfirmasi
    window._popupSimpanFormId = window._popupSimpanFormId || null;

    function showPopupSimpan(formId) {
        if (formId) window._popupSimpanFormId = formId;
        var el = document.getElementById('popup-simpan');
        el.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hidePopupSimpan() {
        var el = document.getElementById('popup-simpan');
        el.style.display = 'none';
        document.body.style.overflow = '';
    }

    function konfirmasiSimpan() {
        hidePopupSimpan();
        if (window._popupSimpanFormId) {
            var form = document.getElementById(window._popupSimpanFormId);
            if (form) {
                form.submit();
                return;
            }
        }
        // Fallback: cari form pertama di halaman
        var forms = document.querySelectorAll('form');
        if (forms.length > 0) forms[0].submit();
    }

    // Tutup saat klik overlay
    document.getElementById('popup-simpan').addEventListener('click', function(e) {
        if (e.target === this) hidePopupSimpan();
    });
</script>
@endpush

@push('styles')
<style>
    @keyframes simpanPopupIn {
        from { opacity: 0; transform: scale(0.92) translateY(16px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
</style>
@endpush
