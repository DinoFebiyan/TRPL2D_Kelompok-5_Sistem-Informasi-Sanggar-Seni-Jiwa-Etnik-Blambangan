@extends('layouts.form')

@section('title', 'Tambah Berita — JEB')

@section('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        .page-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: #EAEAEA;
            /* Warna abu-abu muda sesuai desain */
            border-radius: 14px;
            border: 1px solid #dcdcdc;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
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
            /* Warna garis merah kecoklatan */
            border-radius: 30px;
            /* Membuat bentuk kapsul */
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            background: #fff;
            box-sizing: border-box;
            outline: none;
        }

        .form-group textarea {
            border-radius: 15px;
            /* Radius kotak khusus teks isi berita */
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

        .preview-img-box {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            border-radius: 8px;
            margin-top: 12px;
            border: 1px dashed #A87C7C;
            display: none;
        }

        /* Penyesuaian icon panah pada dropdown */
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

        .select-status:invalid {
            color: #b0b0b0;
        }

        /* Menyelaraskan box TinyMCE dengan tema form Anda */
        .tox-tinymce {
            border: 1px solid #A87C7C !important;
            /* Menggunakan warna border khas form Anda */
            border-radius: 14px !important;
            /* Membuat sudut melengkung halus */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            /* Memberi bayangan tipis */
            overflow: hidden;
        }

        /* Jarak vertikal antar input form group */
        .form-group {
            margin-bottom: 1.5rem;
        }
    </style>
{{-- Popup Server Error: muncul otomatis jika validasi gagal --}}
@if ($errors->any())
<div id="popup-server-inline" style="
    display: flex;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 24px;
    font-family: 'Plus Jakarta Sans', sans-serif;
">
    <div style="
        background: #FFF5F5;
        border-radius: 16px;
        padding: 36px 28px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        width: 100%;
        max-width: 420px;
        text-align: center;
    ">
        <div style="width:72px;height:72px;border-radius:50%;background:#FECACA;color:#E53935;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:28px;font-weight:700;">✕</div>
        <p style="font-size:11px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:#475569;margin-bottom:8px;">Server Error</p>
        <h2 style="font-size:18px;font-weight:800;color:#E53935;margin-bottom:10px;">Format Data Tidak Sesuai</h2>
        <p style="font-size:13.5px;color:#475569;line-height:1.65;margin-bottom:16px;">Periksa kembali data yang Anda masukkan:</p>
        <ul style="text-align:left;font-size:13px;color:#991b1b;margin-bottom:20px;padding-left:18px;">
            @foreach ($errors->all() as $error)
            <li style="margin-bottom:4px;">{{ $error }}</li>
            @endforeach
        </ul>
        <button onclick="document.getElementById('popup-server-inline').style.display='none'" style="display:inline-block;padding:12px 32px;border:none;border-radius:10px;background:#E53935;color:#fff;font-size:14.5px;font-weight:700;font-family:inherit;cursor:pointer;width:100%;">Coba Lagi</button>
    </div>
</div>
@endif
@endsection

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Tambah Berita</h1>
                <p>Kelola Berita</p>
            </div>
            <div class="header-actions">
                @if(auth()->user()->peran === 'superadmin')
                    <a href="{{ route('superadmin.kelola-berita') }}" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @elseif(auth()->user()->peran === 'admin')
                    <a href="{{ route('admin.kelola-berita') }}" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @endif

                <button type="submit" form="formTambahBerita" class="btn-simpan"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>

        <div class="form-body">
            <form id="formTambahBerita" action="{{ auth()->user()->peran === 'superadmin'
        ? route('superadmin.store-berita')
        : route('admin.store-berita') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if(session('success'))
                    <div
                        style="background:#dcfce7; color:#16a34a; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:0.85rem; border-left:4px solid #16a34a;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div
                        style="background:#fee2e2; color:#b91c1c; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:0.85rem; border-left:4px solid #dc2626;">
                        <i class="fas fa-exclamation-circle"></i> Harap perbaiki kesalahan pada form.
                        <ul style="margin: 5px 0 0 0; padding-left: 20px; color: #dc2626;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Foto Header --}}
                <div class="form-group">
                    <label>Foto Header (Bisa pilih lebih dari satu)</label>
                    <div class="custom-file-wrapper {{ $errors->has('foto') ? 'is-invalid' : '' }}">
                        <label for="foto" class="btn-pilih-file">Pilih File</label>
                        <span class="file-name-display" id="fileNameDisplay">Masukkan foto-foto baru...</span>

                        <input type="file" id="foto" name="foto[]" accept="image/jpeg,image/png,image/jpg"
                            style="display:none" onchange="updateFileNameAndPreview(this)" multiple>
                    </div>
                    <div class="form-helper">Format: JPG, PNG. Maks 2MB per file.</div>
                    <div id="imagePreviewContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px;">
                    </div>
                </div>

                {{-- Judul Berita --}}
                <div class="form-group">
                    <label>Judul Berita</label>
                    <input type="text" name="judul" value="{{ old('judul_berita') }}" placeholder="Masukkan judul berita..."
                        class="{{ $errors->has('judul_berita') ? 'is-invalid' : '' }}" required>
                </div>

                {{-- Isi Berita --}}
                <div class="form-group mb-4">
                    <label class="fw-semibold" style="font-family: 'Poppins', sans-serif; color: #333;">Isi Berita</label>
                    {{-- Menggunakan e(old('isi')) agar teks HTML aman jika validasi Laravel gagal --}}
                    <textarea name="isi" id="isi_berita_editor" rows="10">{!! old('isi') ? e(old('isi')) : '' !!}</textarea>

                    {{-- Menampilkan pesan error validasi di bawah kotak editor --}}
                    @error('isi')
                        <div style="color: #dc2626; font-size: 0.85rem; margin-top: 8px; font-family: 'Poppins', sans-serif;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Jenis Berita --}}
                <div class="form-group">
                    <label>Jenis Berita</label>
                    <select name="jenis_berita" id="jenis_berita"
                        class="select-status {{ $errors->has('jenis_berita') ? 'is-invalid' : '' }}" required>
                        <option value="" disabled selected hidden>Pilih jenis berita...</option>
                        <option value="Pengumuman" {{ old('jenis_berita') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman
                        </option>
                        <option value="Prestasi" {{ old('jenis_berita') == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                        <option value="Kolaborasi" {{ old('jenis_berita') == 'Kolaborasi' ? 'selected' : '' }}>Kolaborasi
                        </option>
                        <option value="Event" {{ old('jenis_berita') == 'Event' ? 'selected' : '' }}>Event</option>
                        <option value="Lainnya" {{ old('jenis_berita') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                {{-- Status Tayang --}}
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required class="select-status">
                        <option value="" disabled selected hidden>Masukkan status berita...</option>
                        <option value="tayang" {{ old('status') == 'tayang' ? 'selected' : '' }}>Tayang</option>
                        <option value="tidak ditayangkan" {{ old('status') == 'tidak ditayangkan' ? 'selected' : '' }}>Tidak
                            Ditayangkan</option>
                    </select>

                </div>
            </form>
        </div>
    </div>

    <script>
        // Sediakan sebuah wadah (array) global untuk menampung semua file biner gambar
        let kumpulanFileBerita = [];

        function updateFileNameAndPreview(input) {
            const display = document.getElementById('fileNameDisplay');
            const container = document.getElementById('imagePreviewContainer');

            if (input.files && input.files.length > 0) {
                // 1. Masukkan file yang baru dipilih ke dalam wadah array global kita
                for (let i = 0; i < input.files.length; i++) {
                    kumpulanFileBerita.push(input.files[i]);
                }

                // 2. Kosongkan container preview lama untuk digambar ulang semuanya
                container.innerHTML = '';

                // 3. Gunakan DataTransfer API untuk menggabungkan file ke dalam elemen input HTML asli
                const dataTransferObat = new DataTransfer();

                kumpulanFileBerita.forEach((file, index) => {
                    dataTransferObat.items.add(file);

                    // Render Preview Gambar masing-masing beserta tombol hapus satuan (opsional)
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const wrapper = document.createElement('div');
                        wrapper.style.position = 'relative';
                        wrapper.style.display = 'inline-block';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100px';
                        img.style.maxHeight = '100px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '6px';
                        img.style.border = '1px dashed #A87C7C';

                        wrapper.appendChild(img);
                        container.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });

                // 4. TIMPA file asli di dalam input HTML dengan daftar gabungan yang baru
                input.files = dataTransferObat.files;

                // Perbarui teks informasi total file terpilih
                display.textContent = kumpulanFileBerita.length + " file dipilih (terakumulasi)";
                display.style.color = "#333";
            }
        }
    </script>
    {{-- Memuat Library Resmi TinyMCE dari CDN Cloud --}}
    <script src="https://cdn.tiny.cloud/1/ug7pj020bty002h7qm1sroe2u6xjk3uz3u7kb0ctdgxrgysr/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: '#isi_berita_editor',

            height: 600,

            menubar: 'file edit view insert format table tools',

            plugins: [
                'advlist',
                'autolink',
                'lists',
                'charmap',
                'preview',
                'searchreplace',
                'visualblocks',
                'fullscreen',
                'insertdatetime',
                'wordcount'
            ],

            toolbar: [
                'undo redo | blocks fontfamily fontsize',
                '| bold italic underline strikethrough',
                '| forecolor backcolor',
                '| alignleft aligncenter alignright alignjustify',
                '| bullist numlist',
                '| outdent indent',
                '| table',
                '| charmap',
                '| removeformat',
                '| fullscreen preview'
            ].join(' '),

            font_family_formats:
                'Arial=arial,helvetica,sans-serif;' +
                'Calibri=calibri,sans-serif;' +
                'Cambria=cambria,serif;' +
                'Times New Roman=times new roman,times;' +
                'Georgia=georgia,serif;' +
                'Poppins=Poppins,sans-serif',

            fontsize_formats:
                '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt 72pt',

            toolbar_mode: 'sliding',

            branding: false,

            content_style:
                "body { font-family: Poppins, sans-serif; font-size:14px; line-height:1.8; }"
        });
    </script>
{{-- Popup Server Error: muncul otomatis jika validasi gagal --}}
@if ($errors->any())
<div id="popup-server-inline" style="
    display: flex;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 24px;
    font-family: 'Plus Jakarta Sans', sans-serif;
">
    <div style="
        background: #FFF5F5;
        border-radius: 16px;
        padding: 36px 28px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        width: 100%;
        max-width: 420px;
        text-align: center;
    ">
        <div style="width:72px;height:72px;border-radius:50%;background:#FECACA;color:#E53935;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:28px;font-weight:700;">✕</div>
        <p style="font-size:11px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:#475569;margin-bottom:8px;">Server Error</p>
        <h2 style="font-size:18px;font-weight:800;color:#E53935;margin-bottom:10px;">Format Data Tidak Sesuai</h2>
        <p style="font-size:13.5px;color:#475569;line-height:1.65;margin-bottom:16px;">Periksa kembali data yang Anda masukkan:</p>
        <ul style="text-align:left;font-size:13px;color:#991b1b;margin-bottom:20px;padding-left:18px;">
            @foreach ($errors->all() as $error)
            <li style="margin-bottom:4px;">{{ $error }}</li>
            @endforeach
        </ul>
        <button onclick="document.getElementById('popup-server-inline').style.display='none'" style="display:inline-block;padding:12px 32px;border:none;border-radius:10px;background:#E53935;color:#fff;font-size:14.5px;font-weight:700;font-family:inherit;cursor:pointer;width:100%;">Coba Lagi</button>
    </div>
</div>
@endif
@endsection