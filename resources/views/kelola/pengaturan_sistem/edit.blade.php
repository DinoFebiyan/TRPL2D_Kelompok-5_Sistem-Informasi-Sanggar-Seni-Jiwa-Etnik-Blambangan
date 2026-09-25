@extends('layouts.form')

@section('title', 'Edit Pengaturan Sistem — JEB')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        .page-container { max-width: 1000px; margin: 2rem auto; background: #EAEAEA; border-radius: 14px; border: 1px solid #dcdcdc; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .form-header { background: #5B1A1A; padding: 1.2rem 2.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid #E4C15A; }
        .form-header h1 { color: white; font-size: 1.8rem; margin: 0; font-family: 'Playfair Display', serif; }
        .form-header p { color: rgba(255, 255, 255, 0.8); margin: 0; font-family: 'Poppins', sans-serif; font-size: 0.85rem; }
        .header-actions { display: flex; gap: 12px; }
        .btn-batal, .btn-simpan { padding: 8px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; border: none; font-family: 'Poppins', sans-serif; font-size: 0.9rem; }
        .btn-batal { background: white; color: #FF4D4D; border: 1px solid #FF4D4D; }
        .btn-simpan { background: white; color: #007BFF; border: 1px solid #007BFF; }
        .form-body { padding: 2.5rem; font-family: 'Poppins', sans-serif; }
        .form-group { margin-bottom: 2rem; background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #dbdbdb; }
        .form-group label { font-weight: 600; color: #5B1A1A; display: block; margin-bottom: 10px; font-size: 1rem; }
        .form-group input { width: 100%; padding: 12px 20px; border: 1px solid #A87C7C; border-radius: 30px; font-family: 'Poppins', sans-serif; font-size: 0.9rem; background: #fff; box-sizing: border-box; outline: none; }
        .custom-file-wrapper { display: flex; border: 1px solid #A87C7C; border-radius: 30px; overflow: hidden; background: #fff; align-items: center; }
        .btn-pilih-file { background: #531919; color: white !important; padding: 12px 25px; cursor: pointer; border-right: 1px solid #A87C7C; margin-bottom: 0 !important; font-weight: 500; font-size: 0.9rem; }
        .file-name-display { padding: 0 15px; display: flex; align-items: center; color: #b0b0b0; flex-grow: 1; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .btn-hapus-foto-lama { position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; cursor: pointer; }

        /* ==========================================
           ✅ FIX: RESPONSIVE HEADER FORM DI MOBILE
           ========================================== */
        @media (max-width: 640px) {
            .form-header {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 15px !important;
                padding: 1.2rem 1.5rem !important;
            }
            
            .form-header h1 {
                font-size: 1.4rem !important;
            }
            
            .form-header p {
                font-size: 0.78rem !important;
            }
            
            .header-actions {
                width: 100% !important;
                display: flex !important;
                gap: 10px !important;
            }
            
            .btn-batal,
            .btn-simpan {
                flex: 1 !important;
                justify-content: center !important;
                padding: 10px 14px !important;
                font-size: 0.85rem !important;
            }

            .form-body {
                padding: 1.5rem !important;
            }

            .form-group {
                padding: 15px !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Edit Pengaturan Sistem</h1>
                <p>Kelola Slider Slideshow & Integrasi Aplikasi Media Sosial</p>
            </div>
            <div class="header-actions">
                <a href="#" onclick="showPopupBatal(); return false;" class="btn-batal">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="button" onclick="handleSimpanSistem('formEditSistem')" class="btn-simpan">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>

        <div class="form-body">
            <form id="formEditSistem" action="{{ route('superadmin.pengaturan-sistem.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- MANAGER HEADER FILE MULTIPLE --}}
                <div class="form-group">
                    <label>Gambar Banner Slideshow Saat Ini</label>
                    @if($sistem->headers && $sistem->headers->isNotEmpty())
                        <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 15px;">
                            @foreach($sistem->headers as $foto)
                                <div id="foto-lama-box-{{ $foto->id }}" style="position: relative; display: inline-block;">
                                    <img src="{{ asset('storage/' . $foto->path) }}" style="width: 150px; height: 90px; object-fit: cover; border-radius: 8px; border: 1px solid #c0c0c0;">
                                    <button type="button" class="btn-hapus-foto-lama" onclick="tandaiHapusHeader({{ $foto->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="font-size: 0.85rem; color: #888; font-style: italic;">Belum ada foto banner header.</p>
                    @endif

                    <label style="margin-top: 15px;">Unggah Banner Slideshow Baru</label>
                    <div class="custom-file-wrapper">
                        <label for="header_files" class="btn-pilih-file">Pilih Berkas</label>
                        <span class="file-name-display" id="headerNameDisplay">Tambah foto slideshow...</span>
                        <input type="file" id="header_files" name="header_files[]" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="updateHeaderPreview(this)" multiple>
                    </div>
                    <div id="headerPreviewContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px;"></div>
                </div>
                <div id="wadahInputHapusHeader"></div>

                {{-- SOCIAL LINKS --}}
                <div class="form-group">
                    <label>Link Google Maps Embed</label>
                    <input type="text" name="link_maps" id="link_maps" value="{{ old('link_maps', $sistem->link_maps) }}" placeholder="https://www.google.com/maps/embed?...">
                </div>
                <div class="form-group">
                    <label>Nomor WhatsApp Admin (Format: 628xxx)</label>
                    <input type="text" name="link_wa" id="link_wa" value="{{ old('link_wa', $sistem->link_wa) }}" placeholder="628123456789">
                </div>
                <div class="form-group">
                    <label>Email Korespondensi resmi</label>
                    <input type="email" name="link_email" id="link_email" value="{{ old('link_email', $sistem->link_email) }}" placeholder="info@jeb.id">
                </div>
                <div class="form-group">
                    <label>Link Instagram Profil</label>
                    <input type="url" name="link_instagram" id="link_instagram" value="{{ old('link_instagram', $sistem->link_instagram) }}" placeholder="https://instagram.com/username">
                </div>
                <div class="form-group">
                    <label>Link TikTok Profil</label>
                    <input type="url" name="link_tiktok" id="link_tiktok" value="{{ old('link_tiktok', $sistem->link_tiktok) }}" placeholder="https://tiktok.com/@username">
                </div>
            </form>
        </div>
    </div>

    <script>
        function tandaiHapusHeader(idFoto) {
            if(confirm('Hapus gambar ini dari slider?')) {
                const wadah = document.getElementById('wadahInputHapusHeader');
                const inputHidden = document.createElement('input');
                inputHidden.type = 'hidden';
                inputHidden.name = 'hapus_header[]';
                inputHidden.value = idFoto;
                wadah.appendChild(inputHidden);
                document.getElementById('foto-lama-box-' + idFoto).remove();
            }
        }

        let tumpukanHeader = [];
        function updateHeaderPreview(input) {
            const display = document.getElementById('headerNameDisplay');
            const container = document.getElementById('headerPreviewContainer');
            if (input.files) {
                for (let file of input.files) { tumpukanHeader.push(file); }
                container.innerHTML = '';
                const dt = new DataTransfer();
                tumpukanHeader.forEach((file) => {
                    dt.items.add(file);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style = 'width:120px; height:75px; object-fit:cover; border-radius:6px;';
                        container.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
                input.files = dt.files;
                display.textContent = tumpukanHeader.length + " file dipilih";
            }
        }
    </script>

{{-- Popup --}}
@include('popup.validasi')
@include('popup.batal')
@include('popup.simpan')

{{-- =========================================================
     VALIDASI FORM SEBELUM SIMPAN (Pengaturan Sistem)
     ========================================================= --}}
<script>
function validateFormSistem() {
    const errors = [];

    // 1. Google Maps
    const maps = document.getElementById('link_maps');
    if (maps.value.trim()) {
        // kalau diisi, harus mengandung karakter URL yang wajar
        if (!/^https?:\/\//i.test(maps.value.trim()) && !maps.value.includes('<iframe')) {
            errors.push('Link Google Maps harus berupa URL atau embed code');
        }
    }

    // 2. WhatsApp
    const wa = document.getElementById('link_wa');
    if (wa.value.trim()) {
        // format: 628xxx, boleh + atau - atau spasi
        if (!/^[0-9+\-\s]{8,15}$/.test(wa.value.trim())) {
            errors.push('Nomor WhatsApp tidak valid (8-15 digit angka)');
        }
    }

    // 3. Email
    const email = document.getElementById('link_email');
    if (email.value.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            errors.push('Format email tidak valid');
        }
    }

    // 4. Instagram
    const ig = document.getElementById('link_instagram');
    if (ig.value.trim()) {
        try {
            new URL(ig.value.trim());
        } catch (e) {
            errors.push('Link Instagram harus berupa URL yang valid (contoh: https://instagram.com/...)');
        }
    }

    // 5. TikTok
    const tiktok = document.getElementById('link_tiktok');
    if (tiktok.value.trim()) {
        try {
            new URL(tiktok.value.trim());
        } catch (e) {
            errors.push('Link TikTok harus berupa URL yang valid (contoh: https://tiktok.com/@...)');
        }
    }

    return errors;
}

function handleSimpanSistem(formId) {
    const errors = validateFormSistem();

    if (errors.length > 0) {
        showPopupValidasi(errors);
        return;
    }

    showPopupSimpan(formId);
}
</script>

{{-- =========================================================
     OVERRIDE TOMBOL BATAL (redirect ke pengaturan-sistem.index)
     ========================================================= --}}
<script>
    window._urlBatalSistem = '{{ route("superadmin.pengaturan-sistem.index") }}';

    window.batalkanForm = function () {
        if (typeof hidePopupBatal === 'function') {
            hidePopupBatal();
        }
        window.location.href = window._urlBatalSistem;
    };
</script>
@endsection

@push('scripts')
<script>
    document.body.classList.add('page-form');
</script>
@endpush