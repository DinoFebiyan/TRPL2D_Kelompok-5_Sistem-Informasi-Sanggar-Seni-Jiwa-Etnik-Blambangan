@extends('layouts.form')

@section('title', 'Edit Profil Sanggar — JEB')

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
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { font-weight: 500; color: #8A4B4B; display: block; margin-bottom: 8px; font-size: 1.05rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px 20px; border: 1px solid #A87C7C; border-radius: 30px; font-family: 'Poppins', sans-serif; font-size: 0.9rem; background: #fff; box-sizing: border-box; outline: none; }
        .form-group textarea { border-radius: 15px; resize: vertical; }
        .custom-file-wrapper { display: flex; border: 1px solid #A87C7C; border-radius: 30px; overflow: hidden; background: #fff; align-items: center; }
        .btn-pilih-file { background: #531919; color: white !important; padding: 12px 25px; cursor: pointer; border-right: 1px solid #A87C7C; margin-bottom: 0 !important; font-weight: 500; font-size: 0.9rem; }
        .file-name-display { padding: 0 15px; display: flex; align-items: center; color: #b0b0b0; flex-grow: 1; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .form-helper { font-size: 0.8rem; color: #888; margin-top: 6px; margin-left: 10px; }
        .btn-hapus-foto-lama { position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: 0.2s; }
        .btn-hapus-foto-lama:hover { background: #dc2626; transform: scale(1.1); }
        
        /* Preview item khusus upload tunggal */
        .single-preview-box { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px dashed #A87C7C; margin-top: 12px; display: none; }
    </style>
@endsection

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Edit Profil Sanggar</h1>
                <p>Kelola Informasi Utama & Gambar Header</p>
            </div>
            <div class="header-actions">
                @if(auth()->user()->peran === 'superadmin')
                    <a href="#" onclick="showPopupBatal('{{ route('superadmin.kelola-profil') }}'); return false;" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @elseif(auth()->user()->peran === 'admin')
                    <a href="#" onclick="showPopupBatal('{{ route('admin.kelola-profil') }}'); return false;" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @endif
                <button type="button" onclick="showPopupSimpan('formEditProfil')" form="formEditProfil" class="btn-simpan"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>

        <div class="form-body">
            <form id="formEditProfil" action="{{ route('superadmin.update-profil') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @csrf
                @method('PUT')

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

                {{-- ==================== SEKSI HEADER (MULTI-FOTO SLIDESHOW) ==================== --}}
                @if($profil->headers && $profil->headers->isNotEmpty())
                <div class="form-group">
                    <label>Gambar Header Utama Saat Ini (Klik tombol merah untuk menghapus)</label>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 10px;">
                        @foreach($profil->headers as $foto)
                            <div id="foto-lama-box-{{ $foto->id }}" style="position: relative; display: inline-block;">
                                <img src="{{ asset('storage/' . $foto->path) }}" style="width: 150px; height: 90px; object-fit: cover; border-radius: 8px; border: 1px solid #c0c0c0;">
                                <button type="button" class="btn-hapus-foto-lama" onclick="tandaiHapusHeader({{ $foto->id }})" title="Hapus header ini">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Input tracker penampung id header terhapus --}}
                <div id="wadahInputHapusHeader"></div>

                <div class="form-group">
                    <label>Unggah Gambar Header Slideshow Baru (Bisa pilih lebih dari satu)</label>
                    <div class="custom-file-wrapper">
                        <label for="header_files" class="btn-pilih-file">Pilih File</label>
                        <span class="file-name-display" id="headerNameDisplay">Kosongkan jika tidak ingin menambah foto header...</span>
                        <input type="file" id="header_files" name="header_files[]" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="updateHeaderPreview(this)" multiple>
                    </div>
                    <div class="form-helper">Format: JPG, PNG. Gambar akan disimpan dalam folder 'header'. Maks 2MB per file.</div>
                    <div id="headerPreviewContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px;"></div>
                </div>

                {{-- ==================== SEKSI LOGO SANGGAR (SINGLE FOTO) ==================== --}}
                <div class="form-group">
                    <label>Ubah Logo Sanggar</label>
                    <div class="custom-file-wrapper">
                        <label for="logo" class="btn-pilih-file">Pilih Logo</label>
                        <span class="file-name-display" id="logoNameDisplay">Pilih jika ingin mengganti logo...</span>
                        <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="previewSingleFile(this, 'logoPreview', 'logoNameDisplay')">
                    </div>
                    <div class="form-helper">Format: JPG, PNG. Maks 4MB.</div>
                    <img id="logoPreview" class="single-preview-box" alt="Pratinjau Logo">
                </div>

                {{-- ==================== SEKSI FOTO PEMBINA (SINGLE FOTO) ==================== --}}
                <div class="form-group">
                    <label>Ubah Foto Pembina</label>
                    <div class="custom-file-wrapper">
                        <label for="foto_pembina" class="btn-pilih-file">Pilih Foto</label>
                        <span class="file-name-display" id="pembinaNameDisplay">Pilih jika ingin mengganti foto pembina...</span>
                        <input type="file" id="foto_pembina" name="foto_pembina" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="previewSingleFile(this, 'pembinaPreview', 'pembinaNameDisplay')">
                    </div>
                    <div class="form-helper">Format: JPG, PNG. Maks 4MB.</div>
                    <img id="pembinaPreview" class="single-preview-box" alt="Pratinjau Pembina">
                </div>

                {{-- ==================== KONTEN TEKS PROFIL SANGGAR ==================== --}}
                <div class="form-group">
                    <label>Deskripsi Sanggar</label>
                    <textarea name="deskripsi" rows="4" required>{{ old('deskripsi', $profil->deskripsi) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Visi</label>
                    <textarea name="visi" rows="3" required>{{ old('visi', $profil->visi) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Misi</label>
                    <textarea name="misi" rows="4" required>{{ old('misi', $profil->misi) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Sejarah Singkat</label>
                    <textarea name="sejarah" rows="5" required>{{ old('sejarah', $profil->sejarah) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Sambutan Pembina</label>
                    <textarea name="sambutan_pembina" rows="4" required>{{ old('sambutan_pembina', $profil->sambutan_pembina) }}</textarea>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Logika hapus foto header lama (Client-side effect)
        function tandaiHapusHeader(idFoto) {
            if(confirm('Apakah Anda yakin ingin menghapus gambar ini dari slideshow header utama?')) {
                const wadah = document.getElementById('wadahInputHapusHeader');
                const inputHidden = document.createElement('input');
                inputHidden.type = 'hidden';
                inputHidden.name = 'hapus_header[]';
                inputHidden.value = idFoto;
                wadah.appendChild(inputHidden);

                const boxFoto = document.getElementById('foto-lama-box-' + idFoto);
                if(boxFoto) { boxFoto.remove(); }
            }
        }

        // Live preview penumpukan file baru dinamis (Multi-Upload)
        let tumpukanHeaderBaru = [];
        function updateHeaderPreview(input) {
            const display = document.getElementById('headerNameDisplay');
            const container = document.getElementById('headerPreviewContainer');
            
            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    tumpukanHeaderBaru.push(input.files[i]);
                }

                container.innerHTML = '';
                const dt = new DataTransfer();
                
                tumpukanHeaderBaru.forEach((file) => {
                    dt.items.add(file);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const wrapper = document.createElement('div');
                        wrapper.style.position = 'relative';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '120px';
                        img.style.height = '75px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '6px';
                        img.style.border = '1px dashed #A87C7C';

                        wrapper.appendChild(img);
                        container.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });

                input.files = dt.files;
                display.textContent = tumpukanHeaderBaru.length + " file header baru dipilih (terakumulasi)";
                display.style.color = "#333";
            }
        }

        // Fitur Baru: Live preview fungsional untuk Single File Upload (Logo & Pembina)
        function previewSingleFile(input, previewImageId, displayNameId) {
            const previewImage = document.getElementById(previewImageId);
            const nameDisplay = document.getElementById(displayNameId);

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block'; // Memunculkan element gambar preview
                }

                reader.readAsDataURL(file);
                nameDisplay.textContent = file.name; // Mengubah label display menjadi nama file asli
                nameDisplay.style.color = "#333";
            } else {
                previewImage.src = '';
                previewImage.style.display = 'none';
                nameDisplay.textContent = "Pilih file kembali...";
                nameDisplay.style.color = "#b0b0b0";
            }
        }
    </script>
@endsection


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

{{-- Popup Batal & Simpan --}}
@include('popup.batal')
@include('popup.simpan')
