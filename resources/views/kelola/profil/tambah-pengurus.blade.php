@extends('layouts.form')

@section('title', 'Tambah Pengurus — JEB')

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
        .form-group select {
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

        .form-group input::placeholder {
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
            background: #531919;
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

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%238A4B4B' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 20px) center;
        }

        .select-status {
            color: #333;
        }
        
        .select-status:invalid {
            color: #b0b0b0;
        }
    </style>
@endsection

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Tambah Pengurus</h1>
                <p>Kelola Profil Sanggar</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('superadmin.kelola-profil') }}" class="btn-batal"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" form="formTambahPengurus" class="btn-simpan"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>

        <div class="form-body">
            <form id="formTambahPengurus" action="{{ route('superadmin.store-pengurus') }}" method="POST" enctype="multipart/form-data">
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

                {{-- Foto Pengurus (Mendukung Multi-Upload Akumulatif) --}}
                <div class="form-group">
                    <label>Foto Kegiatan / Profil Pengurus (Bisa pilih lebih dari satu)</label>
                    <div class="custom-file-wrapper {{ $errors->has('foto') ? 'is-invalid' : '' }}">
                        <label for="foto" class="btn-pilih-file">Pilih File</label>
                        <span class="file-name-display" id="fileNameDisplay">Masukkan foto pengurus...</span>
                        <input type="file" id="foto" name="foto[]" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="updateFileNameAndPreview(this)" multiple>
                    </div>
                    <div class="form-helper">Format: JPG, PNG. Maks 2MB per file.</div>
                    <div id="imagePreviewContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px;"></div>
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap pengurus..." required>
                </div>

                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <select id="jabatan" name="jabatan" class="select-status" required>
                        <option value="" disabled selected hidden>Pilih jabatan pengurus...</option>
                        <option value="Ketua" {{ old('jabatan') == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                        <option value="Sekretaris" {{ old('jabatan') == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                        <option value="Bendahara" {{ old('jabatan') == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="instagram">Instagram</label>
                    <input type="text" id="instagram" name="instagram" value="{{ old('instagram') }}" placeholder="Masukkan link atau username instagram pengurus...">
                </div>

                <div class="form-group">
                    <label for="hp">No Handphone</label>
                    <input type="text" id="hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Masukkan no handphone pengurus..." required>
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
        let kumpulanFilePengurus = [];

        function updateFileNameAndPreview(input) {
            const display = document.getElementById('fileNameDisplay');
            const container = document.getElementById('imagePreviewContainer');
            
            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    kumpulanFilePengurus.push(input.files[i]);
                }

                container.innerHTML = '';
                const dataTransferObat = new DataTransfer();
                
                kumpulanFilePengurus.forEach((file) => {
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
                display.textContent = kumpulanFilePengurus.length + " file dipilih (terakumulasi)";
                display.style.color = "#333";
            }
        }
    </script>
@endsection