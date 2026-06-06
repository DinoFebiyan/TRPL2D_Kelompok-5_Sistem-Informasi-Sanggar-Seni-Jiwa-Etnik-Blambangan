@extends('layouts.form')

@section('title', 'Tambah Event — JEB')

@section('styles')
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
            margin-bottom: 1.8rem;
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
            border-radius: 20px;
            resize: vertical;
            min-height: 120px;
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
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%238A4B4B' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 20px) center;
        }
        
        input[type="date"], input[type="time"] {
            appearance: none;
            -webkit-appearance: none;
            position: relative;
        }
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }
        
        .date-input-wrapper, .time-input-wrapper {
            position: relative;
        }
        .date-input-wrapper::after {
            content: "\f133";
            font-family: "Font Awesome 5 Free";
            font-weight: 400;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #000;
            pointer-events: none;
            font-size: 1.1rem;
        }
        .time-input-wrapper::after {
            content: "\f017";
            font-family: "Font Awesome 5 Free";
            font-weight: 400;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #000;
            pointer-events: none;
            font-size: 1.1rem;
        }
    </style>

    {{-- Popup Server Error: Muncul otomatis jika validasi gagal --}}
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

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Tambah Event</h1>
                <p>Kelola Event</p>
            </div>
            <div class="header-actions">
                   @if(auth()->user()->peran === 'superadmin')
                    <a href="#" onclick="showPopupBatal('{{ route('superadmin.kelola-event') }}'); return false;" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @elseif(auth()->user()->peran === 'admin')
                    <a href="#" onclick="showPopupBatal('{{ route('admin.kelola-event') }}'); return false;" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @endif
                <button type="button" onclick="showPopupSimpan('formTambahEvent')" form="formTambahEvent" class="btn-simpan"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
        <div class="form-body">
            <form id="formTambahEvent" action="{{ auth()->user()->peran === 'superadmin' ? route('superadmin.store-event') : route('admin.store-event') }}" method="POST" enctype="multipart/form-data">
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

                {{-- PERBAIKAN MULTIPLE PHOTO: Menggunakan nama array foto[] dan multiple picker --}}
                <div class="form-group">
                    <label>Foto Event (Bisa pilih lebih dari satu)</label>
                    <div class="custom-file-wrapper">
                        <label for="foto" class="btn-pilih-file">Pilih File</label>
                        <span class="file-name-display" id="fileNameDisplay">Masukkan foto-foto event...</span>
                        <input type="file" id="foto" name="foto[]" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="updateFileNameAndPreview(this)" multiple>
                    </div>
                    <div class="form-helper">Format: JPG, PNG. Maks 2MB per file.</div>
                    <div id="imagePreviewContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px;"></div>
                </div>
                
                <div class="form-group">
                    <label>Nama Event</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama event..." required>
                </div>
                
                <div class="form-group">
                    <label>Deskripsi Event</label>
                    <textarea name="deskripsi" placeholder="Masukkan deskripsi event..." required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Kategori Event</label>
                    <select name="kategori" required>
                        <option value="" disabled selected hidden>Pilih Kategori Event</option>
                        <option value="Pertunjukan" {{ old('kategori') == 'Pertunjukan' ? 'selected' : '' }}>Pertunjukan</option>
                        <option value="Latihan" {{ old('kategori') == 'Latihan' ? 'selected' : '' }}>Latihan</option>
                        <option value="Pelatihan" {{ old('kategori') == 'Pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                        <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Tanggal</label>
                    <div class="date-input-wrapper">
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Jam Pelaksanaan</label>
                    <div class="time-input-wrapper">
                        <input type="time" name="jam" value="{{ old('jam') }}" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Lokasi Event</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Masukkan lokasi Event ..." required>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="" disabled selected hidden>Pilih status event ...</option> 
                        <option value="belum selesai" {{ old('status') == 'belum selesai' ? 'selected' : '' }}>Belum Selesai</option>
                        <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // JS Akumulasi Foto Baru Sesuai Pola Sukses Berita
        let kumpulanFileEvent = [];

        function updateFileNameAndPreview(input) {
            const display = document.getElementById('fileNameDisplay');
            const container = document.getElementById('imagePreviewContainer');
            
            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    kumpulanFileEvent.push(input.files[i]);
                }

                container.innerHTML = '';
                const dataTransferObat = new DataTransfer();
                
                kumpulanFileEvent.forEach((file) => {
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
                display.textContent = kumpulanFileEvent.length + " file baru dipilih (terakumulasi)";
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