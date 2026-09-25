@extends('layouts.form')

@section('title', 'Edit Event — JEB')

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

        /* ==========================================
           ✅ CUSTOM FILE WRAPPER
           ========================================== */
        .custom-file-wrapper {
            display: flex;
            border: 1px solid #A87C7C;
            border-radius: 30px;
            overflow: hidden;
            background: #fff;
            align-items: stretch;
            min-height: 48px;
        }

        /* ==========================================
           ✅ TOMBOL PILIH FILE — specificity tinggi
           ========================================== */
        .form-group .btn-pilih-file {
            background: #531919 !important;
            color: white !important;
            padding: 0 25px !important;
            cursor: pointer;
            border-right: 1px solid #A87C7C;
            margin: 0 !important;
            font-weight: 500;
            font-size: 0.9rem;
            white-space: nowrap;
            flex-shrink: 0;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            align-self: stretch !important;
            line-height: 1 !important;
            text-align: center;
            box-sizing: border-box;
            min-height: 46px;
            border-radius: 0;
        }

        .file-name-display {
            padding: 12px 15px;
            display: flex;
            align-items: center;
            color: #b0b0b0;
            flex-grow: 1;
            font-size: 0.9rem;
            min-width: 0;
            line-height: 1.3;
            word-break: break-word;
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

            .form-group .btn-pilih-file {
                padding: 0 16px !important;
                font-size: 0.8rem !important;
            }
            .file-name-display {
                font-size: 0.8rem !important;
                padding: 10px 12px !important;
                line-height: 1.3 !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Edit Event</h1>
                <p>Kelola Event</p>
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
                <button type="button" onclick="handleSimpanEditEvent('formEditEvent')" class="btn-simpan">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>

        <div class="form-body">
            <form id="formEditEvent" action="{{ auth()->user()->peran === 'superadmin' ? route('superadmin.update-event', $event->id) : route('admin.update-event', $event->id) }}" method="POST" enctype="multipart/form-data">
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

                {{-- SINKRONISASI: Menampilkan multi-foto event lama beserta tombol hapus satuan --}}
                @if($event->galeri && $event->galeri->isNotEmpty())
                <div class="form-group">
                    <label>Foto Event Saat Ini (Klik tombol merah untuk menghapus)</label>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 10px;">
                        @foreach($event->galeri as $foto)
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

                <div id="wadahInputHapusFoto"></div>

                {{-- Tambah Foto Tambahan Baru --}}
                <div class="form-group">
                    <label>Unggah Gambar Tambahan Baru (Bisa pilih lebih dari satu)</label>
                    <div class="custom-file-wrapper">
                        <label for="foto" class="btn-pilih-file">Pilih File</label>
                        <span class="file-name-display" id="fileNameDisplay">Kosongkan jika tidak ingin menambah gambar...</span>
                        <input type="file" id="foto" name="foto[]" accept="image/jpeg,image/png,image/jpg" style="display:none" onchange="updateFileNameAndPreview(this)" multiple>
                    </div>
                    <div class="form-helper">Format: JPG, PNG. Maks 2MB per file.</div>
                    <div id="imagePreviewContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px;"></div>
                </div>

                <div class="form-group">
                    <label>Nama Event</label>
                    <input type="text" name="nama" id="edit_nama_event" value="{{ old('nama', $event->nama) }}" placeholder="Masukkan nama event..." required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Event</label>
                    <textarea name="deskripsi" id="edit_deskripsi_event" rows="6" placeholder="Masukkan deskripsi event..." required>{{ old('deskripsi', $event->deskripsi) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Kategori Event</label>
                    <select name="kategori" id="edit_kategori_event" required>
                        <option value="Pertunjukan" {{ old('kategori', $event->kategori) == 'Pertunjukan' ? 'selected' : '' }}>Pertunjukan</option>
                        <option value="Latihan" {{ old('kategori', $event->kategori) == 'Latihan' ? 'selected' : '' }}>Latihan</option>
                        <option value="Pelatihan" {{ old('kategori', $event->kategori) == 'Pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                        <option value="Lainnya" {{ old('kategori', $event->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <div class="date-input-wrapper">
                        <input type="date" id="edit_tanggal_event" name="tanggal" value="{{ old('tanggal', $event->tanggal ? $event->tanggal->format('Y-m-d') : '') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Jam Pelaksanaan</label>
                    <div class="time-input-wrapper">
                        <input type="time" id="edit_jam_event" name="jam" value="{{ old('jam', $event->jam) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" id="edit_lokasi_event" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}" placeholder="Masukkan lokasi Event ..." required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="edit_status_event" required>
                        <option value="belum selesai" {{ old('status', strtolower($event->status)) == 'belum selesai' ? 'selected' : '' }}>Belum Selesai</option>
                        <option value="selesai" {{ old('status', strtolower($event->status)) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi Penanda Hapus Foto Sesuai Modul Berita
        function tandaiHapusFoto(idFoto) {
            if(confirm('Apakah Anda yakin ingin menghapus gambar ini dari event?')) {
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

        // Penanganan Akumulasi File Baru Instan
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

{{-- Popup --}}
@include('popup.validasi')
@include('popup.batal')
@include('popup.simpan')

{{-- =========================================================
     VALIDASI FORM SEBELUM SIMPAN (Edit Event)
     ========================================================= --}}
<script>
function validateFormEditEvent() {
    const errors = [];

    // 1. Foto (opsional — hanya cek kalau ada file baru dipilih)
    const fotoInput = document.getElementById('foto');
    if (fotoInput.files.length > 0) {
        for (let i = 0; i < fotoInput.files.length; i++) {
            const file = fotoInput.files[i];
            const allowed = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowed.includes(file.type)) {
                errors.push('Format foto harus JPG atau PNG');
                break;
            }
            if (file.size > 2 * 1024 * 1024) {
                errors.push('Ukuran foto maksimal 2MB');
                break;
            }
        }
    }

    // 2. Nama Event
    const nama = document.getElementById('edit_nama_event');
    if (!nama.value.trim()) {
        errors.push('Nama event belum diisi');
    } else if (nama.value.trim().length < 3) {
        errors.push('Nama event minimal 3 karakter');
    }

    // 3. Deskripsi
    const deskripsi = document.getElementById('edit_deskripsi_event');
    if (!deskripsi.value.trim()) {
        errors.push('Deskripsi event belum diisi');
    }

    // 4. Kategori
    const kategori = document.getElementById('edit_kategori_event');
    if (!kategori.value) {
        errors.push('Kategori event belum dipilih');
    }

    // 5. Tanggal
    const tanggal = document.getElementById('edit_tanggal_event');
    if (!tanggal.value) {
        errors.push('Tanggal event belum diisi');
    }

    // 6. Jam
    const jam = document.getElementById('edit_jam_event');
    if (!jam.value) {
        errors.push('Jam pelaksanaan belum diisi');
    }

    // 7. Lokasi
    const lokasi = document.getElementById('edit_lokasi_event');
    if (!lokasi.value.trim()) {
        errors.push('Lokasi event belum diisi');
    }

    // 8. Status
    const status = document.getElementById('edit_status_event');
    if (!status.value) {
        errors.push('Status event belum dipilih');
    }

    return errors;
}

function handleSimpanEditEvent(formId) {
    const errors = validateFormEditEvent();

    if (errors.length > 0) {
        showPopupValidasi(errors);
        return;
    }

    showPopupSimpan(formId);
}
</script>

{{-- =========================================================
     OVERRIDE TOMBOL BATAL (redirect ke halaman kelola-event)
     ========================================================= --}}
<script>
    window._urlBatalEditEvent = '{{ auth()->user()->peran === "superadmin"
        ? route("superadmin.kelola-event")
        : route("admin.kelola-event") }}';

    window.batalkanForm = function () {
        if (typeof hidePopupBatal === 'function') {
            hidePopupBatal();
        }
        window.location.href = window._urlBatalEditEvent;
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