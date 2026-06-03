@extends('layouts.form')

@section('title', 'Edit Berita — JEB')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        .page-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: #EAEAEA;
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

        /* Style khusus untuk tombol hapus foto lama */
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: 0.2s;
        }

        .btn-hapus-foto-lama:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        /* Menyelaraskan box TinyMCE dengan tema form Anda */
        .select-status {
            border: 1px solid #A87C7C;
            border-radius: 30px;
        }

        /* TAMBAHKAN KODE BARU INI DI SINI: */
        .tox-tinymce {
            border: 1px solid #A87C7C !important;
            /* Mengubah warna border luar TinyMCE */
            border-radius: 15px !important;
            /* Membuat sudut editor menjadi melengkung/modern */
        }
    </style>
@endsection

@section('content')
    <div class="page-container">
        <div class="form-header">
            <div>
                <h1>Edit Berita</h1>
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
                <button type="submit" form="formEditBerita" class="btn-simpan"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>

        <div class="form-body">
            <form id="formEditBerita"
                action="{{ auth()->user()->peran === 'superadmin' ? route('superadmin.update-berita', $berita->id) : route('admin.update-berita', $berita->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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

                {{-- Menampilkan Gambar Header Berita Saat Ini dari Database --}}
                @if($berita->galeri && $berita->galeri->isNotEmpty())
                    <div class="form-group">
                        <label>Gambar Header Berita Saat Ini (Klik tombol merah untuk menghapus)</label>
                        <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 10px;">
                            @foreach($berita->galeri as $foto)
                                <div id="foto-lama-box-{{ $foto->id }}" style="position: relative; display: inline-block;">
                                    <img src="{{ asset('storage/' . $foto->path) }}"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #c0c0c0;">
                                    <button type="button" class="btn-hapus-foto-lama" onclick="tandaiHapusFoto({{ $foto->id }})"
                                        title="Hapus gambar ini">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div id="wadahInputHapusFoto"></div>

                {{-- Tambah Foto Baru --}}
                <div class="form-group">
                    <label>Unggah Gambar Tambahan Baru (Bisa pilih lebih dari satu)</label>
                    <div class="custom-file-wrapper {{ $errors->has('foto') ? 'is-invalid' : '' }}">
                        <label for="foto" class="btn-pilih-file">Pilih File</label>
                        <span class="file-name-display" id="fileNameDisplay">Kosongkan jika tidak ingin menambah
                            gambar...</span>
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
                    <input type="text" name="judul" value="{{ old('judul', $berita->judul) }}"
                        placeholder="Masukkan judul berita..." required>
                </div>

                {{-- Isi Berita --}}
                <div class="form-group mb-4">
                    <label class="fw-semibold" style="font-family: 'Poppins', sans-serif; color: #333;">Isi Berita</label>
                    {{-- Jika ada input lama (old) gunakan itu, jika tidak ada, ambil data isi dari database --}}
                    <textarea name="isi" id="isi_berita_editor"
                        rows="10">{!! old('isi') ? e(old('isi')) : $berita->isi_berita !!}</textarea>

                    @error('isi')
                        <div style="color: #dc2626; font-size: 0.85rem; margin-top: 8px; font-family: 'Poppins', sans-serif;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Jenis Berita --}}
                <div class="form-group">
                    <label>Jenis Berita</label>
                    <select name="jenis_berita" id="jenis_berita" class="select-status" required>
                        <option value="Pengumuman" {{ old('jenis_berita', $berita->jenis_berita) == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="Prestasi" {{ old('jenis_berita', $berita->jenis_berita) == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                        <option value="Kolaborasi" {{ old('jenis_berita', $berita->jenis_berita) == 'Kolaborasi' ? 'selected' : '' }}>Kolaborasi</option>
                        <option value="Event" {{ old('jenis_berita', $berita->jenis_berita) == 'Event' ? 'selected' : '' }}>
                            Event</option>
                        <option value="Lainnya" {{ old('jenis_berita', $berita->jenis_berita) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                {{-- Status Tayang --}}
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required class="select-status">
                        <option value="tayang" {{ old('status', $berita->status) == 'tayang' ? 'selected' : '' }}>Tayang
                        </option>
                        <option value="tidak ditayangkan" {{ old('status', $berita->status) == 'tidak ditayangkan' ? 'selected' : '' }}>Tidak Ditayangkan</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <script>
        // FUNGSI BARU: Memasukkan ID foto ke input hidden dan menyembunyikannya dari UI
        function tandaiHapusFoto(idFoto) {
            if (confirm('Apakah Anda yakin ingin menghapus gambar ini dari berita?')) {
                const wadah = document.getElementById('wadahInputHapusFoto');

                // Buat elemen input hidden baru
                const inputHidden = document.createElement('input');
                inputHidden.type = 'hidden';
                inputHidden.name = 'hapus_foto[]';
                inputHidden.value = idFoto;
                wadah.appendChild(inputHidden);

                // Hilangkan box foto dari tampilan layar
                const boxFoto = document.getElementById('foto-lama-box-' + idFoto);
                if (boxFoto) {
                    boxFoto.remove();
                }
            }
        }

        // Penanganan Akumulasi Gambar Baru
        let kumpulanFileBerita = [];

        function updateFileNameAndPreview(input) {
            const display = document.getElementById('fileNameDisplay');
            const container = document.getElementById('imagePreviewContainer');

            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    kumpulanFileBerita.push(input.files[i]);
                }

                container.innerHTML = '';
                const dataTransferObat = new DataTransfer();

                kumpulanFileBerita.forEach((file) => {
                    dataTransferObat.items.add(file);

                    const reader = new FileReader();
                    reader.onload = function (e) {
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
                display.textContent = kumpulanFileBerita.length + " file baru dipilih (terakumulasi)";
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
                'table',
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

@endsection