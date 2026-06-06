<div id="popup-batal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;padding:24px;">
    <div style="background:#fff;border-radius:16px;padding:40px 32px 32px;width:100%;max-width:420px;text-align:center;box-shadow:0 8px 40px rgba(0,0,0,0.15);">

        {{-- Icon X --}}
        <div style="width:80px;height:80px;border-radius:50%;background:#f5e8e8;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#c0272d" stroke-width="2" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </div>

        <h2 style="font-size:22px;font-weight:800;color:#111;margin-bottom:12px;">Yakin ingin membatalkan?</h2>
        <p style="font-size:14px;color:#888;line-height:1.6;margin-bottom:32px;">Perubahan yang belum disimpan akan hilang.<br>Apakah kamu ingin membatalkan?</p>

        <div style="display:flex;gap:12px;justify-content:center;">
            <button onclick="batalkanForm()" style="padding:12px 32px;background:#C0272D;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">Ya, batal</button>
            <button onclick="hidePopupBatal()" style="padding:12px 32px;background:#888;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">Lanjutkan Perubahan</button>
        </div>
    </div>
</div>

<style>
    #popup-batal { display: none; }
</style>

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
    function batalkanForm() {
        window.onpopstate = null;
        hidePopupBatal();
        history.go(-2);
    }
    document.getElementById('popup-batal').addEventListener('click', function(e) {
        if (e.target === this) hidePopupBatal();
    });
</script>
