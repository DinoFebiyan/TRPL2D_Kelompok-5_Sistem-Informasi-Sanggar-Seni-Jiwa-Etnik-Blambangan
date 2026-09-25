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

        /* Memastikan dialog TinyMCE selalu di atas elemen lain */
        .tox-tinymce-aux {
            z-index: 99999 !important;
        }

        .tox .tox-dialog-wrap {
            z-index: 99999 !important;
        }

        /* ==========================================
           ✅ FIX: TINYMCE RESPONSIVE + SCROLL
           ========================================== */
        .tox-tinymce {
            max-width: 100% !important;
            border-radius: 15px !important;
            box-sizing: border-box !important;
            overflow: visible !important;
        }

        .tox .tox-edit-area,
        .tox .tox-edit-area__iframe {
            overflow: visible !important;
        }

        .tox .tox-edit-area__iframe {
            min-height: 300px !important;
        }

        .tox .tox-toolbar-overlord,
        .tox .tox-toolbar__overflow,
        .tox .tox-toolbar__primary {
            max-width: 100% !important;
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

            /* ✅ Kecilkan tombol pilih file di mobile */
            .form-group .btn-pilih-file {
                padding: 0 16px !important;
                font-size: 0.8rem !important;
            }
            .file-name-display {
                font-size: 0.8rem !important;
                padding: 10px 12px !important;
                line-height: 1.3 !important;
            }

            .tox-tinymce {
                width: 100% !important;
                margin: 0 auto !important;
            }
            .tox .tox-toolbar-overlord {
                overflow-x: auto !important;
            }
            .tox .tox-toolbar__primary {
                flex-wrap: nowrap !important;
            }
            .tox .tox-edit-area__iframe {
                width: 100% !important;
            }
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
                    <a href="#" onclick="showPopupBatal(); return false;" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @elseif(auth()->user()->peran === 'admin')
                    <a href="#" onclick="showPopupBatal(); return false;" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @endif
                <button type="button" onclick="handleSimpanEditBerita('formEditBerita')" class="btn-simpan">
                    <i class="fas fa-save"></i> Simpan
                </button>
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
                        <span class="file-name-display" id="fileNameDisplay">Kosongkan jika tidak ingin menambah gambar...</span>
                        <input type="file" id="foto" name="foto[]" accept="image/jpeg,image/png,image/jpg"
                            style="display:none" onchange="updateFileNameAndPreview(this)" multiple>
                    </div>
                    <div class="form-helper">Format: JPG, PNG. Maks 2MB per file.</div>
                    <div id="imagePreviewContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px;"></div>
                </div>

                {{-- Judul Berita --}}
                <div class="form-group">
                    <label>Judul Berita</label>
                    <input type="text" name="judul" id="edit_judul_berita" value="{{ old('judul', $berita->judul) }}"
                        placeholder="Masukkan judul berita..." required>
                </div>

                {{-- Isi Berita --}}
                <div class="form-group">
                    <label>Isi Berita</label>
                    <textarea id="isi_berita_editor" name="isi" rows="6" placeholder="Masukkan isi berita..." required>{{ old('isi', $berita->isi_berita) }}</textarea>
                </div>

                {{-- Jenis Berita --}}
                <div class="form-group">
                    <label>Jenis Berita</label>
                    <select name="jenis_berita" id="edit_jenis_berita" class="select-status" required>
                        <option value="Pengumuman" {{ old('jenis_berita', $berita->jenis_berita) == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="Prestasi" {{ old('jenis_berita', $berita->jenis_berita) == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                        <option value="Kolaborasi" {{ old('jenis_berita', $berita->jenis_berita) == 'Kolaborasi' ? 'selected' : '' }}>Kolaborasi</option>
                        <option value="Event" {{ old('jenis_berita', $berita->jenis_berita) == 'Event' ? 'selected' : '' }}>Event</option>
                        <option value="Lainnya" {{ old('jenis_berita', $berita->jenis_berita) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                {{-- Status Tayang --}}
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="edit_status_berita" required class="select-status">
                        <option value="tayang" {{ old('status', $berita->status) == 'tayang' ? 'selected' : '' }}>Tayang</option>
                        <option value="tidak ditayangkan" {{ old('status', $berita->status) == 'tidak ditayangkan' ? 'selected' : '' }}>Tidak Ditayangkan</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- Popup --}}
    @include('popup.validasi')
    @include('popup.batal')
    @include('popup.simpan')

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
        // ✅ Deteksi mobile untuk atur tinggi editor
        const isMobile = window.matchMedia('(max-width: 640px)').matches;

        tinymce.init({
            selector: '#isi_berita_editor',
            ui_container: 'body',
            height: isMobile ? 500 : 600,
            min_height: 350,
            resize: true,
            menubar: isMobile ? false : 'file edit view insert format table tools',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'media', 'table', 'charmap', 'preview',
                'searchreplace', 'visualblocks', 'fullscreen', 'insertdatetime', 'wordcount'
            ],
            toolbar: isMobile
                ? 'undo redo | bold italic underline | bullist numlist | link image | removeformat'
                : [
                    'undo redo | blocks fontfamily fontsize',
                    '| bold italic underline strikethrough',
                    '| forecolor backcolor',
                    '| alignleft aligncenter alignright alignjustify',
                    '| bullist numlist',
                    '| outdent indent',
                    '| link image media',
                    '| table',
                    '| charmap',
                    '| removeformat',
                    '| fullscreen preview'
                ].join(' '),

            setup: function (editor) {
                editor.on('change keyup NodeChange', function () {
                    editor.save();
                });
            },

            automatic_uploads: true,
            image_title: true,

            images_upload_handler: (blobInfo) => new Promise((resolve, reject) => {
                let xhr = new XMLHttpRequest();
                let targetUrl = '{{ auth()->user()->peran === "superadmin" ? route("superadmin.berita.upload-gambar") : route("admin.berita.upload-gambar") }}';
                xhr.open('POST', targetUrl);

                let metaToken = document.querySelector('meta[name="csrf-token"]');
                if (metaToken) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', metaToken.content);
                } else {
                    return reject('Meta tag CSRF token tidak ditemukan!');
                }

                xhr.onload = function () {
                    if (xhr.status < 200 || xhr.status >= 300) {
                        return reject('HTTP Error: ' + xhr.status);
                    }
                    try {
                        let json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location !== 'string') {
                            return reject('Response JSON tidak valid');
                        }
                        resolve(json.location);
                    } catch (e) {
                        reject('Gagal menguraikan JSON dari server');
                    }
                };

                xhr.onerror = function () {
                    reject('Gagal mengunggah gambar karena kesalahan koneksi.');
                };

                let formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            }),

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
            content_style: "html, body { font-family: Poppins, sans-serif; font-size: 14px; line-height: 1.8; overflow-y: auto !important; }",
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            valid_elements: '*[*]',
            extended_valid_elements: 'img[class|src|border=0|alt|title|width|height|style|data-mce-src|data-mce-json]',
            image_advtab: true
        });
    </script>

{{-- =========================================================
     VALIDASI FORM SEBELUM SIMPAN (Edit Berita)
     ========================================================= --}}
<script>
function validateFormEditBerita() {
    const errors = [];

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

    const judul = document.getElementById('edit_judul_berita');
    if (!judul.value.trim()) {
        errors.push('Judul berita belum diisi');
    } else if (judul.value.trim().length < 5) {
        errors.push('Judul berita minimal 5 karakter');
    }

    let isiContent = '';
    if (window.tinymce && tinymce.get('isi_berita_editor')) {
        isiContent = tinymce.get('isi_berita_editor').getContent({ format: 'text' }).trim();
    } else {
        isiContent = document.getElementById('isi_berita_editor').value.trim();
    }
    if (!isiContent) {
        errors.push('Isi berita belum diisi');
    }

    const jenis = document.getElementById('edit_jenis_berita');
    if (!jenis.value) {
        errors.push('Jenis berita belum dipilih');
    }

    const status = document.getElementById('edit_status_berita');
    if (!status.value) {
        errors.push('Status berita belum dipilih');
    }

    return errors;
}

function handleSimpanEditBerita(formId) {
    const errors = validateFormEditBerita();

    if (errors.length > 0) {
        showPopupValidasi(errors);
        return;
    }

    showPopupSimpan(formId);
}
</script>


<script>
    window._urlBatalEditBerita = '{{ auth()->user()->peran === "superadmin"
        ? route("superadmin.kelola-berita")
        : route("admin.kelola-berita") }}';

    window.batalkanForm = function () {
        if (typeof hidePopupBatal === 'function') {
            hidePopupBatal();
        }
        window.location.href = window._urlBatalEditBerita;   // ✅ FIX
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