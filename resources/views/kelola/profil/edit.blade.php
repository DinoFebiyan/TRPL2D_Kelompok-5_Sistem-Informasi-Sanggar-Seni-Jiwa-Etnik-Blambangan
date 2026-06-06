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
        .form-group { margin-bottom: 2rem; background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #dbdbdb; }
        .form-group label { font-weight: 600; color: #5B1A1A; display: block; margin-bottom: 10px; font-size: 1rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px 20px; border: 1px solid #A87C7C; border-radius: 30px; font-family: 'Poppins', sans-serif; font-size: 0.9rem; background: #fff; box-sizing: border-box; outline: none; }
        .form-group textarea { border-radius: 15px; resize: vertical; }
        .custom-file-wrapper { display: flex; border: 1px solid #A87C7C; border-radius: 30px; overflow: hidden; background: #fff; align-items: center; }
        .btn-pilih-file { background: #531919; color: white !important; padding: 12px 25px; cursor: pointer; border-right: 1px solid #A87C7C; margin-bottom: 0 !important; font-weight: 500; font-size: 0.9rem; }
        .file-name-display { padding: 0 15px; display: flex; align-items: center; color: #b0b0b0; flex-grow: 1; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .single-preview-box { width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 1px dashed #A87C7C; margin-top: 12px; display: none; }
        .current-photo-box { width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #5B1A1A; margin-bottom: 10px; }
    </style>
@endsection

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Edit Profil Sanggar</h1>
                <p>Kelola Informasi Teks Utama, Visi Misi & Dokumen Identitas Foto Sanggar</p>
            </div>
            <div class="header-actions">
                <a href="#" onclick="showPopupBatal('{{ auth()->user()->peran === 'superadmin' ? route('superadmin.kelola-profil') : route('admin.kelola-profil') }}'); return false;" class="btn-batal">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="button" onclick="showPopupSimpan('formEditProfil')" form="formEditProfil" class="btn-simpan"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>

        <div class="form-body">
            <form id="formEditProfil" action="{{ auth()->user()->peran === 'superadmin' ? route('superadmin.update-profil') : route('admin.update-profil') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- LOGO --}}
                <div class="form-group">
                    <label>Logo Sanggar Saat Ini</label>
                    @if($profil && $profil->logo && $profil->logo->path)
                        <img src="{{ asset('storage/' . $profil->logo->path) }}" class="current-photo-box">
                    @endif
                    <div class="custom-file-wrapper">
                        <label for="logo" class="btn-pilih-file">Pilih Logo</label>
                        <span class="file-name-display" id="logoNameDisplay">Ganti logo...</span>
                        <input type="file" id="logo" name="logo" accept="image/*" style="display:none" onchange="previewFile(this, 'logoPreview', 'logoNameDisplay')">
                    </div>
                    <img id="logoPreview" class="single-preview-box">
                </div>

                {{-- FOTO PEMBINA --}}
                <div class="form-group">
                    <label>Foto Pembina Saat Ini</label>
                    @if($profil && $profil->fotoPembina && $profil->fotoPembina->path)
                        <img src="{{ asset('storage/' . $profil->fotoPembina->path) }}" class="current-photo-box">
                    @endif
                    <div class="custom-file-wrapper">
                        <label for="foto_pembina" class="btn-pilih-file">Pilih Foto</label>
                        <span class="file-name-display" id="pembinaNameDisplay">Ganti foto pembina...</span>
                        <input type="file" id="foto_pembina" name="foto_pembina" accept="image/*" style="display:none" onchange="previewFile(this, 'pembinaPreview', 'pembinaNameDisplay')">
                    </div>
                    <img id="pembinaPreview" class="single-preview-box">
                </div>

                {{-- TEXT INPUTS --}}
                <div class="form-group"><label>Deskripsi Sanggar</label><textarea name="deskripsi" rows="4" required>{{ old('deskripsi', $profil->deskripsi) }}</textarea></div>
                <div class="form-group"><label>Visi</label><textarea name="visi" rows="3" required>{{ old('visi', $profil->visi) }}</textarea></div>
                <div class="form-group"><label>Misi</label><textarea name="misi" rows="4" required>{{ old('misi', $profil->misi) }}</textarea></div>
                <div class="form-group"><label>Sejarah Singkat</label><textarea name="sejarah" rows="5" required>{{ old('sejarah', $profil->sejarah) }}</textarea></div>
                <div class="form-group"><label>Sambutan Pembina</label><textarea name="sambutan_pembina" rows="4" required>{{ old('sambutan_pembina', $profil->sambutan_pembina) }}</textarea></div>
            </form>
        </div>
    </div>

    <script>
        function previewFile(input, previewId, displayId) {
            const preview = document.getElementById(previewId);
            const text = document.getElementById(displayId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) { preview.src = e.target.result; preview.style.display = 'block'; }
                reader.readAsDataURL(input.files[0]);
                text.textContent = input.files[0].name;
            }
        }
    </script>
    @include('popup.batal')
    @include('popup.simpan')
@endsection
@push('scripts')
<script>
    document.body.classList.add('page-form');
</script>
@endpush