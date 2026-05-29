@extends('layouts.form')

@section('title', 'Edit Profil Sanggar — JEB')

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

        .btn-batal,
        .btn-simpan {
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

        .btn-batal {
            background: white;
            color: #FF4D4D;
            border: 1px solid #FF4D4D;
        }

        .btn-simpan {
            background: white;
            color: #007BFF;
            border: 1px solid #007BFF;
        }

        .form-body {
            padding: 2.5rem;
            font-family: 'Poppins', sans-serif;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 500;
            color: #8A4B4B;
            display: block;
            margin-bottom: 8px;
            font-size: 1.05rem;
        }

        .form-group input,
        .form-group textarea {
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

        .form-group textarea {
            border-radius: 15px;
            resize: vertical;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #b0b0b0;
        }

        .custom-file-wrapper {
            display: flex;
            border: 1px solid #A87C7C;
            border-radius: 30px;
            overflow: hidden;
            background: #fff;
            align-items: center;
        }

        .btn-pilih-file {
            background: #8A4B4B;
            color: white !important;
            padding: 12px 25px;
            cursor: pointer;
            border-right: 1px solid #A87C7C;
            margin-bottom: 0 !important;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .file-name-display {
            padding: 0 15px;
            display: flex;
            align-items: center;
            color: #b0b0b0;
            flex-grow: 1;
            font-size: 0.9rem;
        }

        .form-helper {
            font-size: 0.8rem;
            color: #888;
            margin-top: 6px;
            margin-left: 10px;
        }

        .photo-edit-row {
            display: flex;
            gap: 20px;
            margin-bottom: 1.5rem;
        }

        .photo-edit-card {
            flex: 1;
            background: #fdfdfd;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #dcdcdc;
        }

        .current-photo-preview {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
            padding: 10px;
            background: #f5ebeb;
            border-radius: 8px;
            border: 1px solid #e8dede;
        }

        .current-photo-preview img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #c0c0c0;
        }

        .current-photo-preview span {
            font-size: 0.8rem;
            color: #5b1a1a;
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Edit Profil Sanggar</h1>
                <p>Kelola Informasi Utama Sanggar</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('superadmin.kelola-profil') }}" class="btn-batal"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" form="formEditProfil" class="btn-simpan"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
        
        <div class="form-body">
            <form id="formEditProfil" action="{{ route('superadmin.update-profil', $profil->id ?? 1) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') 
                
                @if($errors->any())
                    <div style="background:#fee2e2; padding:12px 16px; border-radius:8px; margin-bottom:15px; border-left:4px solid #dc2626;">
                        <ul style="margin:0; color:#dc2626; padding-left:16px; font-size: 0.85rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- PROSES EDIT GAMBAR (LOGO & PEMBINA SEJAJAR) --}}
                <div class="photo-edit-row">
                    {{-- Blok Kiri: Logo Sanggar --}}
                    <div class="photo-edit-card">
                        <label style="font-weight: 500; color: #8A4B4B; display: block; margin-bottom: 8px; font-size: 1.05rem;">Logo Sanggar</label>
                        
                        @if($profil && $profil->logo && $profil->logo->file_blob)
                            <div class="current-photo-preview">
                                <img src="data:image/jpeg;base64,{{ base64_encode($profil->logo->file_blob) }}" alt="Logo">
                                <span><i class="fas fa-check-circle"></i> Logo terpasang di sistem</span>
                            </div>
                        @endif

                        <div class="custom-file-wrapper">
                            <label for="logo" class="btn-pilih-file">Pilih Logo</label>
                            <span class="file-name-display" id="logoNameDisplay">Ganti logo sanggar...</span>
                            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="previewMediaInstan(this, 'logoNameDisplay', 'logoPreviewBox')">
                        </div>
                        <div class="form-helper">Maks 2MB (JPG/PNG).</div>
                        <div id="logoPreviewBox" style="margin-top: 10px; display: flex; justify-content: flex-start;"></div>
                    </div>

                    {{-- Blok Kanan: Foto Pembina --}}
                    <div class="photo-edit-card">
                        <label style="font-weight: 500; color: #8A4B4B; display: block; margin-bottom: 8px; font-size: 1.05rem;">Foto Pembina</label>
                        
                        @if($profil && $profil->fotoPembina && $profil->fotoPembina->file_blob)
                            <div class="current-photo-preview">
                                <img src="data:image/jpeg;base64,{{ base64_encode($profil->fotoPembina->file_blob) }}" alt="Foto Pembina">
                                <span><i class="fas fa-check-circle"></i> Foto pembina aktif</span>
                            </div>
                        @endif

                        <div class="custom-file-wrapper">
                            <label for="foto_pembina" class="btn-pilih-file">Pilih Foto</label>
                            <span class="file-name-display" id="pembinaNameDisplay">Ganti foto pembina...</span>
                            <input type="file" id="foto_pembina" name="foto_pembina" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="previewMediaInstan(this, 'pembinaNameDisplay', 'pembinaPreviewBox')">
                        </div>
                        <div class="form-helper">Maks 2MB (JPG/PNG).</div>
                        <div id="pembinaPreviewBox" style="margin-top: 10px; display: flex; justify-content: flex-start;"></div>
                    </div>
                </div>
                
                {{-- FIELD TEKS UTAMA --}}
                <div class="form-group">
                    <label>Deskripsi Sanggar</label>
                    <textarea name="deskripsi" rows="4" placeholder="Masukkan deskripsi ringkas tentang sanggar..." required>{{ old('deskripsi', $profil->deskripsi ?? '') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label>Visi Sanggar</label>
                    <textarea name="visi" rows="3" placeholder="Masukkan visi sanggar..." required>{{ old('visi', $profil->visi ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Misi Sanggar</label>
                    <textarea name="misi" rows="4" placeholder="Masukkan misi sanggar (gunakan baris baru untuk poin-poin)..." required>{{ old('misi', $profil->misi ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Sejarah Sanggar</label>
                    <textarea name="sejarah" rows="6" placeholder="Ceritakan sejarah berdirinya sanggar seni..." required>{{ old('sejarah', $profil->sejarah ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Sambutan Pembina Sanggar</label>
                    <textarea name="sambutan_pembina" rows="5" placeholder="Masukkan kalimat sambutan hangat dari kepala pembina..." required>{{ old('sambutan_pembina', $profil->sambutan_pembina ?? '') }}</textarea>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Fungsi pembaca file biner instan untuk Logo dan Foto Pembina
        function previewMediaInstan(input, displayId, containerId) {
            const display = document.getElementById(displayId);
            const container = document.getElementById(containerId);
            
            if (input.files && input.files.length > 0) {
                display.textContent = input.files[0].name;
                display.style.color = "#333";
                container.innerHTML = '';

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '80px';
                    img.style.height = '80px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '8px';
                    img.style.border = '2px dashed #A87C7C';
                    container.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                display.textContent = "Pilih file...";
                display.style.color = "#b0b0b0";
                container.innerHTML = '';
            }
        }
    </script>
@endsection