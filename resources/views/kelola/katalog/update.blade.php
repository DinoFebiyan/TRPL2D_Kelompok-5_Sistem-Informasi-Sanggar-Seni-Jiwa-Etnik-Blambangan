@extends('layouts.form')

@section('title', 'Edit Katalog — JEB')

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
        .form-group select,
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
            width: 100%;
            padding: 12px 20px;
            border-radius: 30px;
            border: 1px solid #A87C7C;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            background: #fff;
            color: #333;
        }

        .btn-hapus-foto-lama {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: 0.2s;
        }
        .btn-hapus-foto-lama:hover {
            background: #dc2626;
            transform: scale(1.1);
        }
    </style>
@endsection

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Edit Katalog</h1>
                <p>Kelola Katalog</p>
            </div>
            <div class="header-actions">
                <a href="#" onclick="showPopupBatal('{{ route('superadmin.kelola-katalog') }}'); return false;" class="btn-batal"><i class="fas fa-times"></i> Batal</a>
                <button type="button" onclick="showPopupSimpan('formEditKatalog')" form="formEditKatalog" class="btn-simpan"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>

        <div class="form-body">
            <form id="formEditKatalog" action="{{ route('superadmin.update-katalog', $katalog->id) }}" method="POST" enctype="multipart/form-data">
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

                {{-- Menampilkan Gambar Katalog Saat Ini (Eager Loaded dari model Galeri) --}}
                @if($katalog->galeri && $katalog->galeri->isNotEmpty())
                <div class="form-group">
                    <label>Gambar Produk Saat Ini (Klik tombol merah untuk menghapus)</label>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 10px;">
                        @foreach($katalog->galeri as $foto)
                            <div id="foto-lama-box-{{ $foto->id }}" style="position: relative; display: inline-block;">
                                <img src="{{ asset('storage/' . $foto->path) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #c0c0c0;">
                                <button type="button" class="btn-hapus-foto-lama" onclick="tandaiHapusFoto({{ $foto->id }})" title="Hapus gambar ini">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Hidden input tracker pelacak gambar terhapus --}}
                <div id="wadahInputHapusFoto"></div>

                {{-- Tambah Foto Tambahan Baru --}}
                <div class="form-group">
                    <label>Unggah Gambar Tambahan Baru (Bisa pilih lebih dari satu)</label>
                    <div class="custom-file-wrapper {{ $errors->has('foto') ? 'is-invalid' : '' }}">
                        <label for="foto" class="btn-pilih-file">Pilih File</label>
                        <span class="file-name-display" id="fileNameDisplay">Kosongkan jika tidak ingin menambah gambar...</span>
                        <input type="file" id="foto" name="foto[]" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="updateFileNameAndPreview(this)" multiple>
                    </div>
                    <div class="form-helper">Format: JPG, PNG. Maks 2MB per file.</div>
                    <div id="imagePreviewContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px;"></div>
                </div>

                {{-- Nama Katalog --}}
                <div class="form-group">
                    <label>Nama Katalog</label>
                    <input type="text" name="nama_katalog" value="{{ old('nama_katalog', $katalog->nama_katalog) }}" placeholder="Masukkan nama katalog..." required>
                </div>

                {{-- Kategori --}}
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" class="select-status" required>
                        <option value="Kostum" {{ old('kategori', $katalog->kategori) == 'Kostum' ? 'selected' : '' }}>Kostum</option>
                        <option value="Properti" {{ old('kategori', $katalog->kategori) == 'Properti' ? 'selected' : '' }}>Properti</option>
                        <option value="Alat Musik" {{ old('kategori', $katalog->kategori) == 'Alat Musik' ? 'selected' : '' }}>Alat Musik</option>
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label>Deskripsi Katalog</label>
                    <textarea name="deskripsi" rows="6" placeholder="Masukkan deskripsi katalog..." required>{{ old('deskripsi', $katalog->deskripsi) }}</textarea>
                </div>

                {{-- Stok --}}
                <div class="form-group">
                    <label>Masukkan Stok Barang</label>
                    <input type="number" name="stok" value="{{ old('stok', $katalog->stok) }}" placeholder="Masukkan stok..." required>
                </div>

                {{-- Status Ketersediaan --}}
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="select-status" required>
                        <option value="tersedia" {{ old('status', $katalog->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="tidak tersedia" {{ old('status', $katalog->status) == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <script>
        function tandaiHapusFoto(idFoto) {
            if(confirm('Apakah Anda yakin ingin menghapus gambar ini dari katalog?')) {
                const wadah = document.getElementById('wadahInputHapusFoto');
                
                const inputHidden = document.createElement('input');
                inputHidden.type = 'hidden';
                inputHidden.name = 'hapus_foto[]';
                inputHidden.value = idFoto;
                wadah.appendChild(inputHidden);

                const boxFoto = document.getElementById('foto-lama-box-' + idFoto);
                if(boxFoto) {
                    boxFoto.remove();
                }
            }
        }

        let kumpulanFileKatalog = [];

        function updateFileNameAndPreview(input) {
            const display = document.getElementById('fileNameDisplay');
            const container = document.getElementById('imagePreviewContainer');
            
            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    kumpulanFileKatalog.push(input.files[i]);
                }

                container.innerHTML = '';
                const dataTransferObat = new DataTransfer();
                
                kumpulanFileKatalog.forEach((file) => {
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
                display.textContent = kumpulanFileKatalog.length + " file baru dipilih (terakumulasi)";
                display.style.color = "#333";
            }
        }
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

{{-- Popup Batal & Simpan --}}
@include('popup.batal')
@include('popup.simpan')

@push('scripts')
<script>
    document.body.classList.add('page-form');
</script>
@endpush