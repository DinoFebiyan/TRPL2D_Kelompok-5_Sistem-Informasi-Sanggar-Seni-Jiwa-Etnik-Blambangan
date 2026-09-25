@extends('layouts.admin')

@section('title', 'Pengaturan — JEB')
@section('header_title', 'Pengaturan')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .settings-wrapper {
        width: 100%;
        font-family: 'Poppins', sans-serif;
        color: #333;
    }

    /* ══ KARTU PENGATURAN (SERASI DENGAN KELOLA PROFIL) ══ */
    .settings-card {
        background: #fff;
        border-radius: 12px;
        padding: 25px; 
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        margin-bottom: 28px;
    }

    .settings-card .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .settings-card h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
    }

    .settings-card h3 i {
        color: #5B1A1A;
    }

    /* ══ SEKSI FOTO PROFIL AVATAR ══ */
    .avatar-row {
        display: flex;
        gap: 20px;
        align-items: center;
        background: #fffbfb;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e8dede;
        margin-bottom: 25px;
    }

    .avatar-preview-frame {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        overflow: hidden;
        border: 1px solid #e8dede;
        background: #f3f4f6;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-preview-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-input-wrapper {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex-grow: 1;
        min-width: 0;
    }

    .avatar-input-wrapper label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #b07d7d;
        letter-spacing: 0.5px;
    }

    /* ══ GRID & COMPONENT ENTRY FORM ══ */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .form-group label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #b07d7d;
        letter-spacing: 0.5px;
    }
    
    .form-group input {
        font-size: 0.85rem;
        color: #333;
        background: #fffbfb;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #e8dede;
        font-family: 'Poppins', sans-serif;
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s;
    }

    .form-group input:focus {
        border-color: #b07d7d;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(176, 125, 125, 0.1);
    }
    
    .full-width {
        grid-column: span 2;
    }

    /* ==========================================
       ✅ STYLING FILE UPLOAD KAPSUL — FIXED
       ========================================== */
    .custom-file-wrapper {
        display: flex;
        align-items: stretch;
        border: 1px solid #e8dede;
        border-radius: 8px;
        background: #ffffff;
        overflow: hidden;
        width: 100%;
        max-width: 450px;
        min-height: 44px;
        box-sizing: border-box;
    }

    /* ✅ Tombol pilih berkas — lebih panjang & center vertikal */
    .custom-file-wrapper .btn-pilih-file,
    .form-group .btn-pilih-file {
        background-color: #e2c0c0 !important;
        color: #5a1a1a !important;
        padding: 0 40px !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        cursor: pointer;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        align-self: stretch !important;
        border-right: 1px solid #e8dede;
        white-space: nowrap !important;
        flex-shrink: 0;
        line-height: 1 !important;
        text-align: center;
        box-sizing: border-box;
        min-height: 42px;
        border-radius: 0;
    }

    /* ✅ File name display */
    .custom-file-wrapper .file-name-display {
        padding: 10px 15px;
        color: #BDBDBD;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        flex-grow: 1;
        min-width: 0;
        line-height: 1.3;
        word-break: break-word;
        box-sizing: border-box;
    }

    /* ══ DESIGN BUTTONS SIMPAN ══ */
    .btn-save-settings {
        background: #e2c0c0;
        color: #5a1a1a;
        border: 1px solid #e8dede;
        border-radius: 12px;
        padding: 8px 20px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        transition: 0.2s;
        text-decoration: none;
        white-space: nowrap;
    }
    
    .btn-save-settings:hover {
        background: #d4aeae;
    }

    /* ==========================================
       ✅ RESPONSIVE — Mobile & Tablet
       (Disamakan dengan versi superadmin)
       ========================================== */
    @media (max-width: 768px) {
        .form-grid { 
            grid-template-columns: 1fr !important; 
        }
        .full-width { 
            grid-column: span 1 !important; 
        }
        .avatar-row { 
            flex-direction: column !important; 
            align-items: flex-start !important; 
            padding: 15px !important;
        }

        /* ✅ Header card: title di atas, tombol di bawah rata kiri */
        .settings-card .card-header-flex { 
            flex-direction: column !important; 
            align-items: flex-start !important; 
            gap: 10px !important; 
        }

        .settings-card {
            padding: 18px 15px !important;
        }

        /* ✅ File picker — full width di mobile, tombol lebih compact */
        .custom-file-wrapper {
            max-width: 100% !important;
            width: 100% !important;
        }
        .custom-file-wrapper .btn-pilih-file,
        .form-group .btn-pilih-file {
            padding: 0 24px !important;
            font-size: 0.75rem !important;
        }
        .custom-file-wrapper .file-name-display {
            font-size: 0.78rem !important;
            padding: 10px 12px !important;
        }
    }

    @media (max-width: 480px) {
        .settings-card {
            padding: 15px 12px !important;
        }
        .avatar-preview-frame {
            width: 55px !important;
            height: 55px !important;
        }
        .avatar-row {
            padding: 12px !important;
        }
        .custom-file-wrapper .btn-pilih-file,
        .form-group .btn-pilih-file {
            padding: 0 20px !important;
            font-size: 0.72rem !important;
        }
        .custom-file-wrapper .file-name-display {
            font-size: 0.75rem !important;
        }
    }
</style>

<div class="settings-wrapper">

    {{-- ALERT PESAN SISTEM --}}
    @if(session('success'))
        <div style="background:#dcfce7; color:#16a34a; padding:12px 16px; border-radius:12px; margin-bottom:24px; font-size:0.85rem; border:1px solid #bbf7d0;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fee2e2; color:#ef4444; padding:12px 16px; border-radius:12px; margin-bottom:24px; font-size:0.85rem; border:1px solid #fecaca;">
            <i class="fas fa-exclamation-circle"></i> <strong>Gagal memperbarui data:</strong>
            <ul style="margin: 5px 0 0 0; padding-left: 20px; color: #ef4444;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CARD 1: FORM UPDATE DATA PROFIL --}}
    <div class="settings-card">
        <form method="POST" action="{{ route('admin.pengaturan.update-profil') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="card-header-flex">
                <h3><i class="fas fa-user-cog"></i> Profil Akun Admin</h3>
                <button type="submit" class="btn-save-settings">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
            
            {{-- Bagian Upload Avatar --}}
            <div class="avatar-row">
                <div class="avatar-preview-frame">
                    @if(auth()->user()->galeri && auth()->user()->galeri->path)
                        <img id="avatarPreview" src="{{ asset('storage/' . auth()->user()->galeri->path) }}" alt="Foto Profil">
                    @else
                        <img id="avatarPreview" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=5B1A1A&color=fff" alt="Avatar">
                    @endif
                </div>
                <div class="avatar-input-wrapper">
                    <label>Foto Profil Akun</label>
                    <div class="custom-file-wrapper">
                        <label for="foto_profil" class="btn-pilih-file">Pilih Berkas</label>
                        <span class="file-name-display" id="avatarNameDisplay">Ubah foto profil...</span>
                        <input type="file" id="foto_profil" name="foto_profil" style="display:none" accept="image/jpeg,image/png,image/jpg" onchange="previewAvatar(this)">
                    </div>
                    <span style="font-size: 0.75rem; color: #888;">Format: JPG, JPEG, PNG. Maks 2MB.</span>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Masukkan nama lengkap..." required>
                </div>
                
                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" placeholder="Masukkan alamat email..." required>
                </div>
                
                <div class="form-group full-width">
                    <label>No. Handphone / WhatsApp</label>
                    <input type="text" name="no_handphone" value="{{ old('no_handphone', auth()->user()->no_handphone ?? '') }}" placeholder="Contoh: 08XXXXXXXXXX">
                </div>
            </div>
        </form>
    </div>

    {{-- CARD 2: FORM UPDATE PASSWORD --}}
    <div class="settings-card">
        <form method="POST" action="{{ route('admin.pengaturan.update-password') }}">
            @csrf
            @method('PUT')

            <div class="card-header-flex">
                <h3><i class="fas fa-lock"></i> Ubah Password</h3>
                <button type="submit" class="btn-save-settings">
                    <i class="fas fa-key"></i> Perbarui Kata Sandi
                </button>
            </div>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Password Saat Ini</label>
                    <input type="password" name="current_password" placeholder="Masukkan password yang digunakan saat ini..." required>
                </div>
                
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" placeholder="Buat password baru..." required minlength="6">
                    <span style="font-size: 0.75rem; color: #888; margin-top: 4px;"><i class="fas fa-info-circle"></i> Minimal panjang kata sandi adalah 6 karakter.</span>
                </div>
                
                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi input password baru..." required>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewAvatar(input) {
        const label = document.getElementById('avatarNameDisplay');
        const preview = document.getElementById('avatarPreview');
        
        if (input.files && input.files[0]) {
            label.textContent = input.files[0].name;
            label.style.color = "#333";
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection