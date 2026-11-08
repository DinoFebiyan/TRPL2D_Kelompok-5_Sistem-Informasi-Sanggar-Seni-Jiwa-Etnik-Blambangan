@extends('layouts.form')

@section('title', 'Tambah Pelatih — JEB')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        .page-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: #EAEAEA;
            border-radius: 14px;
            border: 1px solid #dcdcdc;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .form-header {
            background: #5B1A1A;
            padding: 1.2rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #E4C15A;
        }

        .form-header h1 {
            color: white;
            font-size: 1.8rem;
            margin: 0;
            font-family: 'Playfair Display', serif;
        }

        .form-header p {
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn-batal, .btn-simpan {
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
        }

        .btn-batal { background: white; color: #FF4D4D; border: 1px solid #FF4D4D; }
        .btn-simpan { background: white; color: #007BFF; border: 1px solid #007BFF; }

        .form-body { padding: 2.5rem; font-family: 'Poppins', sans-serif; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { font-weight: 500; color: #8A4B4B; display: block; margin-bottom: 8px; font-size: 1.05rem; }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 20px;
            border: 1px solid #A87C7C;
            border-radius: 30px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            background: #fff;
            box-sizing: border-box;
            outline: none;
        }

        .form-group input::placeholder { color: #b0b0b0; }

        .custom-file-wrapper {
            display: flex;
            border: 1px solid #A87C7C;
            border-radius: 30px;
            overflow: hidden;
            background: #fff;
            align-items: center;
        }

        .btn-pilih-file {
            background: #531919;
            color: white !important;
            padding: 12px 25px;
            cursor: pointer;
            border-right: 1px solid #A87C7C;
            margin-bottom: 0 !important;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .file-name-display { padding: 0 15px; display: flex; align-items: center; color: #b0b0b0; flex-grow: 1; font-size: 0.9rem; }
        .form-helper { font-size: 0.8rem; color: #888; margin-top: 6px; margin-left: 10px; }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%238A4B4B' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 20px) center;
        }
        .select-status { color: #333; }
        .select-status:invalid { color: #b0b0b0; }

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
        }
    </style>
@endsection

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Tambah Pelatih</h1>
                <p>Kelola Profil Sanggar</p>
            </div>
            <div class="header-actions">
                @if(auth()->user()->peran === 'superadmin')
                    <a href="#" onclick="showPopupBatal(); return false;" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @elseif(auth()->user()->peran === 'admin')
                    <a href="#" onclick="showPopupBatal(); return false;" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @endif
                <button type="button" onclick="handleSimpanPelatih('formTambahPelatih')" class="btn-simpan">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>

        <div class="form-body">
            <form id="formTambahPelatih" action="{{ auth()->user()->peran === 'superadmin' ? route('superadmin.store-pelatih') : route('admin.store-pelatih') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                    <div style="background:#fee2e2; color:#b91c1c; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:0.85rem; border-left:4px solid #dc2626;">
                        <i class="fas fa-exclamation-circle"></i> Harap perbaiki kesalahan pada form.
                        <ul style="margin: 5px 0 0 0; padding-left: 20px; color: #dc2626;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Foto Pelatih Multi-Upload --}}
                <div class="form-group">
                    <label>Foto Pelatih (Bisa pilih lebih dari satu)</label>
                    <div class="custom-file-wrapper {{ $errors->has('foto') ? 'is-invalid' : '' }}">
                        <label for="foto" class="btn-pilih-file">Pilih File</label>
                        <span class="file-name-display" id="fileNameDisplay">Masukkan foto pelatih...</span>
                        <input type="file" id="foto" name="foto[]" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="updateFileNameAndPreview(this)" multiple>
                    </div>
                    <div class="form-helper">Format: JPG, PNG. Maks 2MB per file.</div>
                    <div id="imagePreviewContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px;"></div>
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap pelatih..." required>
                </div>

                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <select id="jabatan" name="jabatan" class="select-status" required>
                        <option value="" disabled selected hidden>Pilih jabatan pelatih...</option>
                        <option value="Pelatih Tari" {{ old('jabatan') == 'Pelatih Tari' ? 'selected' : '' }}>Pelatih Tari</option>
                        <option value="Pelatih Musik/Gamelan" {{ old('jabatan') == 'Pelatih Musik/Gamelan' ? 'selected' : '' }}>Pelatih Musik/Gamelan</option>
                        <option value="Pelatih Koreografi" {{ old('jabatan') == 'Pelatih Koreografi' ? 'selected' : '' }}>Pelatih Koreografi</option>
                        <option value="Asisten Pelatih" {{ old('jabatan') == 'Asisten Pelatih' ? 'selected' : '' }}>Asisten Pelatih</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="instagram">Instagram</label>
                    <input type="text" id="instagram" name="instagram" value="{{ old('instagram') }}" placeholder="Contoh: @username atau link profil...">
                </div>

                <div class="form-group">
                    <label for="hp">No Handphone</label>
                    <input type="text" id="hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Masukkan nomor WhatsApp/HP aktif..." required>
                </div>
                                <div class="form-group">
                    <label for="nia">Nomor Induk Anggota (NIA)</label>
                    <input type="text" id="nia" name="nia" value="{{ old('nia', $pengurus->nia ?? $pelatih->nia ?? $anggota->nia ?? '') }}" placeholder="Masukkan NIA..." required>
                </div>

                <!-- Input Masa Bakti / Periode / Bergabung Sejak - Opsional -->
                <div class="form-group">
                    <label for="masa_bakti">Masa Bakti / Periode / Bergabung Sejak</label>
                    <input type="text" id="masa_bakti" name="masa_bakti" value="{{ old('masa_bakti', $pengurus->masa_bakti ?? $pelatih->masa_bakti ?? $anggota->masa_bakti ?? '') }}" placeholder="Contoh: 2023 - Sekarang atau Tahun 2024">
                </div>

                <!-- Input Deskripsi - Opsional -->
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Singkat</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" style="width: 100%; padding: 12px 20px; border: 1px solid #A87C7C; border-radius: 16px; font-family: 'Poppins', sans-serif;" placeholder="Masukkan deskripsi profil personel...">{{ old('deskripsi', $pengurus->deskripsi ?? $pelatih->deskripsi ?? $anggota->deskripsi ?? '') }}</textarea>
                </div>

                <!-- Input Prestasi - Opsional -->
                <div class="form-group">
                    <label for="prestasi">Prestasi</label>
                    <textarea id="prestasi" name="prestasi" rows="3" style="width: 100%; padding: 12px 20px; border: 1px solid #A87C7C; border-radius: 16px; font-family: 'Poppins', sans-serif;" placeholder="Masukkan daftar prestasi...">{{ old('prestasi', $pengurus->prestasi ?? $pelatih->prestasi ?? $anggota->prestasi ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="select-status" required>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <script>
        let kumpulanFilePelatih = [];

        function updateFileNameAndPreview(input) {
            const display = document.getElementById('fileNameDisplay');
            const container = document.getElementById('imagePreviewContainer');
            
            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    kumpulanFilePelatih.push(input.files[i]);
                }

                container.innerHTML = '';
                const dataTransferObat = new DataTransfer();
                
                kumpulanFilePelatih.forEach((file) => {
                    dataTransferObat.items.add(file);

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const wrapper = document.createElement('div');
                        wrapper.style.position = 'relative';
                        wrapper.style.display = 'inline-block';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '100px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '6px';
                        img.style.border = '1px dashed #A87C7C';

                        wrapper.appendChild(img);
                        container.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });

                input.files = dataTransferObat.files;
                display.textContent = kumpulanFilePelatih.length + " file dipilih (terakumulasi)";
                display.style.color = "#333";
            }
        }
    </script>

{{-- Popup --}}
@include('popup.validasi')
@include('popup.batal')
@include('popup.simpan')

{{-- =========================================================
     VALIDASI FORM SEBELUM SIMPAN (Pelatih)
     ========================================================= --}}
<script>
function validateFormPelatih() {
    const errors = [];

    // 1. Foto (multiple)
    const fotoInput = document.getElementById('foto');
    if (!fotoInput.files || fotoInput.files.length === 0) {
        errors.push('Foto pelatih belum dipilih');
    }

    // 2. Nama
    const nama = document.getElementById('nama');
    if (!nama.value.trim()) {
        errors.push('Nama lengkap belum diisi');
    } else if (nama.value.trim().length < 3) {
        errors.push('Nama lengkap minimal 3 karakter');
    }

    // 3. Jabatan
    const jabatan = document.getElementById('jabatan');
    if (!jabatan.value) {
        errors.push('Jabatan belum dipilih');
    }

    // 4. No Handphone
    const hp = document.getElementById('hp');
    if (!hp.value.trim()) {
        errors.push('No handphone belum diisi');
    } else if (!/^[0-9+\-\s]{8,15}$/.test(hp.value.trim())) {
        errors.push('No handphone tidak valid (8-15 digit angka)');
    }

    // 5. Status
    const status = document.getElementById('status');
    if (!status.value) {
        errors.push('Status belum dipilih');
    }

    return errors;
}

function handleSimpanPelatih(formId) {
    const errors = validateFormPelatih();

    if (errors.length > 0) {
        showPopupValidasi(errors);
        return;
    }

    showPopupSimpan(formId);
}
</script>

{{-- =========================================================
     OVERRIDE TOMBOL BATAL (redirect ke halaman kelola-profil)
     ========================================================= --}}
<script>
    window._urlBatalPelatih = '{{ auth()->user()->peran === "superadmin"
        ? route("superadmin.kelola-profil")
        : route("admin.kelola-profil") }}';

    window.batalkanForm = function () {
        if (typeof hidePopupBatal === 'function') {
            hidePopupBatal();
        }
        window.location.href = window._urlBatalPelatih;
    };
</script>

@if ($errors->any())
<div id="popup-server-inline" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;align-items:center;justify-content:center;padding:24px;font-family:'Plus Jakarta Sans',sans-serif;">
    <div style="background:#FFF5F5;border-radius:16px;padding:36px 28px;box-shadow:0 8px 32px rgba(0,0,0,0.18);width:100%;max-width:420px;text-align:center;">
        <div style="width:72px;height:72px;border-radius:50%;background:#FECACA;color:#E53935;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:28px;font-weight:700;">✕</div>
        <p style="font-size:11px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:#475569;margin-bottom:8px;">Server Error</p>
        <h2 style="font-size:18px;font-weight:800;color:#E53935;margin-bottom:10px;">Format Data Tidak Sesuai</h2>
        <p style="font-size:13.5px;color:#475569;line-height:1.65;margin-bottom:16px;">Periksa kembali data yang Anda masukkan:</p>
        <ul style="text-align:left;font-size:13px;color:#991b1b;margin-bottom:20px;padding-left:18px;">
            @foreach ($errors->all() as $error)
            <li style="margin-bottom:4px;">{{ $error }}</li>
            @endforeach
        </ul>
        <button onclick="document.getElementById('popup-server-inline').style.display='none';" style="display:inline-block;padding:12px 32px;border:none;border-radius:10px;background:#E53935;color:#fff;font-size:14.5px;font-weight:700;font-family:inherit;cursor:pointer;width:100%;">Coba Lagi</button>
    </div>
</div>
<script>
    window.addEventListener('load', function() {
        document.getElementById('popup-server-inline').style.display = 'flex';
    });
</script>
@endif
@endsection

@push('scripts')
<script>
    document.body.classList.add('page-form');
</script>
@endpush