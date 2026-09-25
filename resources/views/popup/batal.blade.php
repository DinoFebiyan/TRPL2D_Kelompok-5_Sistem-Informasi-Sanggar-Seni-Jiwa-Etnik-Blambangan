<div id="popup-batal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;padding:16px;">
    <div class="popup-batal-box" style="background:#fff;border-radius:16px;padding:40px 32px 32px;width:100%;max-width:420px;text-align:center;box-shadow:0 8px 40px rgba(0,0,0,0.15);box-sizing:border-box;">

        {{-- Icon X --}}
        <div class="popup-batal-icon" style="width:80px;height:80px;border-radius:50%;background:#f5e8e8;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#c0272d" stroke-width="2" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </div>

        <h2 class="popup-batal-title" style="font-size:22px;font-weight:800;color:#111;margin:0 0 12px 0;line-height:1.3;">Yakin ingin membatalkan?</h2>
        <p class="popup-batal-text" style="font-size:14px;color:#888;line-height:1.6;margin:0 0 32px 0;">Perubahan yang belum disimpan akan hilang.<br>Apakah kamu ingin membatalkan?</p>

        <div class="popup-batal-actions" style="display:flex;gap:12px;justify-content:center;">
            <button class="popup-batal-btn popup-batal-btn-ya" onclick="batalkanForm()" style="padding:12px 32px;background:#C0272D;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;transition:filter .2s, transform .1s;white-space:nowrap;">Ya, batal</button>
            <button class="popup-batal-btn popup-batal-btn-lanjut" onclick="hidePopupBatal()" style="padding:12px 32px;background:#888;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;transition:filter .2s, transform .1s;white-space:nowrap;">Lanjutkan Perubahan</button>
        </div>
    </div>
</div>

<style>
    #popup-batal { display: none; }

    /* Hover effect untuk tombol */
    .popup-batal-btn:hover {
        filter: brightness(1.1);
    }
    .popup-batal-btn:active {
        transform: scale(0.97);
    }

    /* ==========================================
       ✅ RESPONSIVE — Mobile & Tablet
       ========================================== */
    @media (max-width: 480px) {
        /* Padding container lebih kecil */
        #popup-batal {
            padding: 12px !important;
        }

        /* Box popup lebih compact */
        .popup-batal-box {
            padding: 28px 20px 20px !important;
            border-radius: 14px !important;
            max-width: 100% !important;
        }

        /* Icon lebih kecil */
        .popup-batal-icon {
            width: 64px !important;
            height: 64px !important;
            margin-bottom: 18px !important;
        }
        .popup-batal-icon svg {
            width: 26px !important;
            height: 26px !important;
        }

        /* Judul lebih kecil */
        .popup-batal-title {
            font-size: 18px !important;
            margin-bottom: 10px !important;
        }

        /* Text lebih kecil */
        .popup-batal-text {
            font-size: 13px !important;
            margin-bottom: 24px !important;
        }

        /* Tombol jadi stack vertikal full width */
        .popup-batal-actions {
            flex-direction: column !important;
            gap: 10px !important;
        }
        .popup-batal-btn {
            width: 100% !important;
            padding: 12px 16px !important;
            font-size: 13.5px !important;
            box-sizing: border-box !important;
        }
    }

    /* Untuk layar sangat sempit (< 360px) */
    @media (max-width: 360px) {
        .popup-batal-box {
            padding: 22px 16px 16px !important;
        }
        .popup-batal-title {
            font-size: 16px !important;
        }
        .popup-batal-text {
            font-size: 12px !important;
        }
        .popup-batal-btn {
            font-size: 12.5px !important;
            padding: 10px 14px !important;
        }
    }
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
        // ✅ Pakai callback dinamis jika ada (dari blade)
        if (typeof window.batalkanFormOverride === 'function') {
            window.batalkanFormOverride();
            return;
        }

        // Fallback lama (untuk halaman yang tidak override)
        window.onpopstate = null;
        hidePopupBatal();
        history.go(-2);
    }
    document.getElementById('popup-batal').addEventListener('click', function(e) {
        if (e.target === this) hidePopupBatal();
    });
</script>