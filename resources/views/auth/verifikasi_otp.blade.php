<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi Kode – Sanggar Tari JEB</title>
    {{-- 🟢 KODE FAVICON DINAMIS (DB & ASSET) --}}
    @php
        $globalProfil = \App\Models\ProfilSanggar::with('logo')->first();
        $faviconUrl = ($globalProfil && $globalProfil->logo && $globalProfil->logo->path) 
            ? asset('storage/' . $globalProfil->logo->path) 
            : asset('img/images-landing-page/galeri/logo-jeb.jpg');
    @endphp
    
<link rel="icon" type="image/jpeg" href="{{ $faviconUrl }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing:border-box;margin:0;padding:0; }
        :root {
            --red:#C0272D; --red-dark:#9B1B20; --gold:#dfb15b;
            --cream:#FFF8F8; --text:#2C1A1A; --muted:#9A8080; --border:#F0DADA;
        }
        html, body { height:100%;font-family:'Nunito',sans-serif;background:var(--cream);overflow:hidden; }
        .wrapper { display:flex;height:100vh;width:100vw; }
        .panel-left {
            width:42%;background:var(--red);position:relative;
            display:flex;flex-direction:column;align-items:center;
            justify-content:center;padding:3rem 2.5rem;overflow:hidden;
        }
        .panel-left::before {
            content:'';position:absolute;inset:0;
            background-image: repeating-linear-gradient(45deg,rgba(255,255,255,.07) 0px,rgba(255,255,255,.07) 1px,transparent 1px,transparent 28px),
                repeating-linear-gradient(-45deg,rgba(255,255,255,.07) 0px,rgba(255,255,255,.07) 1px,transparent 1px,transparent 28px);
        }
        .logo-ring { position:relative;z-index:1;width:130px;height:130px;border-radius:50%;background:#fff;border:5px solid rgba(255,255,255,.4);box-shadow:0 8px 32px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;margin-bottom:0.5rem; }
        .brand-name { position:relative;z-index:1;color:#fff;font-family:'Playfair Display',serif;font-size:50px;font-weight:700;text-align:center;line-height:1.1; }
        .brand-name em { font-style:italic;color:var(--gold);display:block; }
        .brand-sub { position:relative;z-index:1;color:rgba(255,255,255,.8);font-size:0.9rem;margin-top:0.8rem;text-transform:uppercase;letter-spacing:1px; }
        .brand-slogan { position:relative;z-index:1;margin-top:2.5rem;text-align:center;color:rgba(255,255,255,.6);font-size:.85rem;font-style:italic;line-height:1.6;max-width:250px;border-top:1px solid rgba(255,255,255,.2);padding-top:1.2rem; }
        .panel-right { flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem 3.5rem;background:#fff;position:relative; }
        .back-link { position:absolute;top:2rem;left:2rem;text-decoration:none;color:var(--red);font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.5rem; }
        .form-box { width:100%;max-width:400px;animation:fadeUp .6s ease both; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        .title-big { font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:700;color:var(--text);margin-bottom:.35rem;text-align:center; }
        .title-big em { font-style:italic;color:var(--red); }
        .title-desc { font-size:.85rem;color:var(--muted);margin-bottom:2rem;line-height:1.5;text-align:center; }
        .email-badge { background:#FFF5F5;border:1px solid var(--border);border-radius:8px;padding:.5rem 1rem;text-align:center;font-size:.85rem;color:var(--text);font-weight:600;margin-bottom:1.5rem; }
        .field-label { display:block;font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text);margin-bottom:1rem;text-align:center;letter-spacing:2px; }
        .otp-container { display:flex;gap:10px;justify-content:center;margin-bottom:.75rem; }
        .otp-input { width:48px;height:48px;border-radius:50%;border:2px solid var(--border);background:#FFF8F8;text-align:center;font-size:1.4rem;font-weight:800;color:var(--red);outline:none;transition:all 0.3s ease;font-family:'Nunito',sans-serif; }
        .otp-input:focus { border-color:var(--gold);background:#fff;box-shadow:0 0 10px rgba(223,177,91,0.3); }
        .otp-input.filled { border-color:var(--red);background:#FFF5F5; }
        .error-msg { color:var(--red);font-size:.8rem;text-align:center;margin-bottom:1rem; }
        .success-msg { background:#f0fff4;border:1px solid #68d391;color:#276749;padding:.6rem 1rem;border-radius:8px;font-size:.82rem;text-align:center;margin-bottom:1rem; }
        /* Input tersembunyi untuk submit nilai OTP gabungan */
        #otp-hidden { display:none; }
        .btn-kirim { width:100%;padding:1rem;background:var(--red);color:#fff;border:none;border-radius:10px;font-size:0.95rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;cursor:pointer;box-shadow:0 4px 12px rgba(192,39,45,0.2);transition:all 0.2s;margin-bottom:1.5rem; }
        .btn-kirim:hover { background:var(--red-dark);transform:translateY(-2px); }
        .resend-area { text-align:center;font-size:0.85rem;color:var(--muted);line-height:1.6; }
        .resend-area a { color:var(--red);font-weight:800;text-decoration:none;border-bottom:1.5px solid var(--red); }
        .resend-area a:hover { opacity:.8; }
        .timer { color:var(--red);font-weight:700; }
        @media (max-width:768px) { .panel-left{display:none;} }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="panel-left">
        <div class="logo-ring">
            <img src="{{ asset('img/logo-jeb.jpg') }}" alt="Logo" style="width:100%;height:100%;border-radius:50%;object-fit:cover;" />
        </div>
        <div class="brand-name">Sanggar Tari <em>JEB</em></div>
        <div class="brand-sub">Singojuruh – Banyuwangi</div>
        <p class="brand-slogan">"Melestarikan Budaya, Menggerakkan Jiwa, Mewujudkan Karya"</p>
    </div>

    <div class="panel-right">
        <a href="{{ route('otp.forgot.form') }}" class="back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>

        <div class="form-box">
            <div class="title-big">Verifikasi <em>Kode</em></div>
            <p class="title-desc">Masukkan kode 6 digit yang telah dikirim ke email Anda.</p>

            {{-- Tampilkan email tujuan --}}
            @if(session('otp_email'))
                <div class="email-badge">📧 {{ session('otp_email') }}</div>
            @endif

            {{-- Pesan sukses / error --}}
            @if(session('success'))
                <div class="success-msg">{{ session('success') }}</div>
            @endif
            @error('otp')
                <div class="error-msg">{{ $message }}</div>
            @enderror

            <form method="POST" action="{{ route('otp.verify') }}" id="otp-form">
                @csrf
                <input type="hidden" name="otp" id="otp-hidden">

                <label class="field-label">KODE VERIFIKASI</label>
                <div class="otp-container">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                </div>

                <button type="submit" class="btn-kirim" id="btn-submit">VERIFIKASI KODE</button>
            </form>

            <div class="resend-area">
                Belum menerima kode? <br>
                <span id="countdown-wrap">Kirim ulang dalam <span class="timer" id="countdown">10:00</span></span>
                <span id="resend-wrap" style="display:none;">
                    <form method="POST" action="{{ route('otp.resend') }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--red);font-weight:800;font-size:0.85rem;border-bottom:1.5px solid var(--red);padding:0;font-family:'Nunito',sans-serif;">KIRIM ULANG</button>
                    </form>
                </span>
            </div>
        </div>
    </div>
</div>

<script>
    // ── Auto-tab & navigasi OTP ──
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otp-hidden');
    const form = document.getElementById('otp-form');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            // Pastikan hanya angka
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value) {
                input.classList.add('filled');
                if (index < inputs.length - 1) inputs[index + 1].focus();
            } else {
                input.classList.remove('filled');
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].classList.remove('filled');
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
            pasteData.split('').forEach((char, i) => {
                if (inputs[i]) {
                    inputs[i].value = char;
                    inputs[i].classList.add('filled');
                }
            });
            if (inputs[pasteData.length - 1]) inputs[pasteData.length - 1].focus();
        });
    });

    // Gabungkan nilai saat submit
    form.addEventListener('submit', (e) => {
        const otp = Array.from(inputs).map(i => i.value).join('');
        if (otp.length < 6) {
            e.preventDefault();
            alert('Masukkan semua 6 digit kode OTP.');
            return;
        }
        hiddenInput.value = otp;
    });

    // ── Countdown Timer 10 menit ──
    let seconds = 600;
    const countdownEl = document.getElementById('countdown');
    const countdownWrap = document.getElementById('countdown-wrap');
    const resendWrap = document.getElementById('resend-wrap');

    const timer = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(timer);
            countdownWrap.style.display = 'none';
            resendWrap.style.display = 'inline';
        } else {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            countdownEl.textContent = `${m}:${s}`;
        }
    }, 1000);

    // Fokus ke kotak pertama saat halaman dimuat
    if (inputs[0]) inputs[0].focus();
</script>
</body>
</html>

