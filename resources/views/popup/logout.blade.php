{{-- Pop Up: Konfirmasi Logout --}}
{{-- Cara pakai: @include('popup.logout') di blade manapun --}}
{{-- Trigger: panggil showPopupLogout() dari tombol logout --}}

<div id="popup-logout" style="
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
        animation: logoutPopupIn 0.25s ease;
    ">
        {{-- Icon logout --}}
        <div style="
            width: 100px; height: 100px;
            border-radius: 50%;
            background: #f5eded;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 32px;
        ">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                 stroke="#c07070" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
        </div>

        {{-- Judul --}}
        <h2 style="
            font-size: 26px; font-weight: 800;
            color: #111; margin-bottom: 16px;
            font-family: 'Lato', sans-serif;
        ">Yakin ingin keluar?</h2>

        {{-- Pesan --}}
        <p style="
            font-size: 14px; color: #666;
            line-height: 1.75; margin-bottom: 36px;
        ">Anda akan keluar dari sesi dan memerlukan login<br>untuk masuk kembali.<br>Pastikan seluruh perubahan sudah disimpan<br>sebelum keluar.</p>

        {{-- Tombol --}}
        <div style="display: flex; gap: 16px; justify-content: center;">
            {{-- Batal --}}
            <button onclick="hidePopupLogout()" style="
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
                Batal
            </button>

            {{-- Ya, keluar --}}
            <button onclick="document.getElementById('form-logout').submit()" style="
                padding: 14px 0;
                width: 160px;
                background: #E05555; color: #fff;
                border: none; border-radius: 10px;
                font-size: 15px; font-weight: 700;
                font-family: inherit; cursor: pointer;
                transition: filter .2s, transform .1s;
            "
            onmouseover="this.style.filter='brightness(1.08)'"
            onmouseout="this.style.filter='brightness(1)'"
            onmousedown="this.style.transform='scale(.97)'"
            onmouseup="this.style.transform='scale(1)'">
                ya, keluar
            </button>
        </div>
    </div>
</div>

{{-- Form logout tersembunyi --}}
<form id="form-logout" method="POST" action="{{ route('logout') }}" style="display:none;">
    @csrf
</form>

@push('scripts')
<script>
    function showPopupLogout() {
        var el = document.getElementById('popup-logout');
        el.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hidePopupLogout() {
        var el = document.getElementById('popup-logout');
        el.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Tutup saat klik overlay
    document.getElementById('popup-logout').addEventListener('click', function(e) {
        if (e.target === this) hidePopupLogout();
    });
</script>
@endpush

@push('styles')
<style>
    @keyframes logoutPopupIn {
        from { opacity: 0; transform: scale(0.92) translateY(16px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
</style>
@endpush
