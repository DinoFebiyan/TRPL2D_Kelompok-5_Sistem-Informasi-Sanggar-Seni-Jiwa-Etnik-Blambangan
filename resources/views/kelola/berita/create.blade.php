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

        .select-status:invalid {
            color: #b0b0b0;
        }

        /* ==========================================
           ✅ AI GENERATE WRAPPER
           Desktop: button natural width, rata kanan
           Mobile: full width
           ========================================== */
        .ai-generate-wrapper {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 15px;
            width: 100%;
            align-items: flex-end;      /* ✅ Desktop: rata kanan */
        }

        .prompt-input {
            padding: 10px 20px;
            border: 1px solid #D4A017;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            width: 100%;
            outline: none;
            transition: all 0.3s ease;
            background: #fff;
            box-sizing: border-box;
            align-self: stretch;        /* ✅ Prompt full width kalau muncul */
        }

        .prompt-input:focus {
            border-color: #B08A2D;
            box-shadow: 0 0 0 2px rgba(212, 160, 23, 0.2);
        }

        .btn-generate-ai {
            background: #FEF9E7;
            border: 1px dashed #D4A017;
            color: #B08A2D;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            white-space: nowrap;
            width: auto;                /* ✅ Desktop: natural width */
            max-width: 100%;
            box-sizing: border-box;
        }

        .btn-generate-ai:hover {
            background: #E4C15A;
            color: #fff;
            border-style: solid;
        }

        .btn-generate-ai:disabled {
            opacity: 0.75;
            cursor: not-allowed;
        }

        .btn-generate-ai .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* ==========================================
           ✅ FIX: TINYMCE RESPONSIVE
           ========================================== */
        .tox-tinymce {
            max-width: 100% !important;
            border-radius: 15px !important;
            box-sizing: border-box !important;
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

            /* ✅ Generate AI button full width di mobile */
            .ai-generate-wrapper {
                align-items: stretch !important;    /* ✅ Full width di mobile */
            }
            .btn-generate-ai {
                width: 100% !important;
                justify-content: center !important;
            }

            /* ✅ TinyMCE: full width di mobile */
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
                <h1>Tambah Berita</h1>
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

                <button type="button" onclick="handleSimpanBerita('formTambahBerita')" class="btn-simpan">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>

        <div class="form-body">
            <form id="formTambahBerita" action="{{ auth()->user()->peran == 'superadmin'
        ? route('superadmin.store-berita')
        : route('admin.store-berita') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Foto Header (Bisa pilih lebih dari satu)</label>
                    <div class="custom-file-wrapper">
                        <label for="foto" class="btn-pilih-file">Pilih File</label>
                        <span class="file-name-display" id="fileNameDisplay">Masukkan header berita...</span>
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
                    <input type="text" name="judul" id="judul_berita" value="{{ old('judul_berita') }}"
                        placeholder="Masukkan judul berita..." required>
                </div>

                <div class="form-group">
                    <label>Jenis Berita</label>
                    <select name="jenis_berita" id="jenis_berita" class="select-status" required>
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

                <div class="form-group">

                    <label>Isi Berita</label>
                    <div class="ai-generate-wrapper">

                        <input type="text" id="prompt-ai" class="prompt-input"
                            placeholder="Ketik instruksi/prompt untuk AI di sini..." style="display: none;">

                        <button type="button" id="btn-generate-ai" class="btn-generate-ai">
                            <img src="https://www.gstatic.com/lamda/images/gemini_sparkle_v002_d4735304ff6292a690345.svg"
                                alt="Gemini" style="width: 16px; height: 16px; object-fit: contain;">
                            <span id="btn-text">Generate dengan AI</span>
                        </button>
                    </div>

                    <textarea id="isi_berita_editor" name="isi" rows="6"
                        placeholder="Masukkan isi berita...">{{ old('isi_berita') }}</textarea>
                </div>

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

    {{-- Popup --}}
    @include('popup.validasi')
    @include('popup.batal')
    @include('popup.simpan')
@endsection

@push('scripts')
    <script>
        document.body.classList.add('page-form');

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

                input.files = dataTransferObat.files;
                display.textContent = kumpulanFileBerita.length + " file dipilih (terakumulasi)";
                display.style.color = "#333";
            }
        }
    </script>

   <script src="https://cdn.tiny.cloud/1/bno1xwfvj9ifgx0new4nfkoog2p1u3wspabzqvi32jmqbwdh/tinymce/8/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        // ✅ Deteksi mobile untuk atur tinggi editor
        const isMobile = window.matchMedia('(max-width: 640px)').matches;

        tinymce.init({
            selector: '#isi_berita_editor',
            ui_container: 'body',
            height: isMobile ? 420 : 500,   // ✅ Desktop 500px (dari 600, biar tidak terlalu panjang)
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
            content_style: "body { font-family: Poppins, sans-serif; font-size:14px; line-height:1.8; }",
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            valid_elements: '*[*]',
            extended_valid_elements: 'img[class|src|border=0|alt|title|width|height|style|data-mce-src|data-mce-json]',
            image_advtab: true
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnGenerate = document.getElementById('btn-generate-ai');
            const promptInput = document.getElementById('prompt-ai');
            const promptContainer = document.getElementById('prompt-extra-container');
            const fileInput = document.getElementById('foto');
            const btnText = document.getElementById('btn-text');

            if (!btnGenerate) return;

            const API_URL = '{{ route("generate.ai.berita") }}';

            btnGenerate.addEventListener('click', async function () {
                const judul = document.getElementById('judul_berita').value.trim();
                const jenis = document.getElementById('jenis_berita').value || '';
                const promptText = promptInput ? promptInput.value.trim() : '';

                const formData = new FormData();
                formData.append('judul', judul);
                formData.append('jenis_berita', jenis);
                formData.append('prompt', promptText);

                if (fileInput && fileInput.files.length > 0) {
                    for (let i = 0; i < fileInput.files.length; i++) {
                        formData.append('foto[]', fileInput.files[i]);
                    }
                }

                btnGenerate.disabled = true;
                const originalText = btnText ? btnText.innerText : btnGenerate.innerHTML;
                if (btnText) {
                    btnText.innerText = 'Memproses...';
                } else {
                    btnGenerate.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                }

                try {
                    const response = await fetch(API_URL, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        let errorMessage = `Server Error (${response.status})`;
                        try {
                            const errorJson = JSON.parse(errorText);
                            errorMessage = errorJson.message || errorMessage;
                        } catch (e) {
                            console.error("HTML Error Response:", errorText);
                        }
                        throw new Error(errorMessage);
                    }

                    const data = await response.json();

                    if (!data.success || !data.content) {
                        throw new Error(data.message || 'Gagal generate dari server.');
                    }

                    if (window.tinymce && tinymce.get('isi_berita_editor')) {
                        tinymce.get('isi_berita_editor').setContent(data.content);
                        tinymce.get('isi_berita_editor').save();
                    } else {
                        document.getElementById('isi_berita_editor').value = data.content;
                    }

                    if (promptContainer) {
                        promptContainer.style.display = 'block';
                    }
                    if (promptInput) {
                        promptInput.style.display = 'block';
                    }

                    if (btnText) {
                        btnText.innerText = 'Re-generate Berita';
                    } else {
                        btnGenerate.innerHTML = '<i class="fas fa-sync-alt"></i> Re-generate Berita';
                    }

                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan: ' + err.message);
                    if (btnText) btnText.innerText = originalText;
                } finally {
                    btnGenerate.disabled = false;
                }
            });
        });
    </script>


    <script>
        function validateFormBerita() {
            const errors = [];

            const fotoInput = document.getElementById('foto');
            if (!fotoInput.files || fotoInput.files.length === 0) {
                errors.push('Foto header berita belum dipilih');
            }

            const judul = document.getElementById('judul_berita');
            if (!judul.value.trim()) {
                errors.push('Judul berita belum diisi');
            } else if (judul.value.trim().length < 5) {
                errors.push('Judul berita minimal 5 karakter');
            }

            const jenis = document.getElementById('jenis_berita');
            if (!jenis.value) {
                errors.push('Jenis berita belum dipilih');
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

            const status = document.querySelector('select[name="status"]');
            if (!status.value) {
                errors.push('Status berita belum dipilih');
            }

            return errors;
        }

        function handleSimpanBerita(formId) {
            const errors = validateFormBerita();

            if (errors.length > 0) {
                showPopupValidasi(errors);
                return;
            }

            showPopupSimpan(formId);
        }
    </script>


    <script>
        window._urlBatalBerita = '{{ auth()->user()->peran === "superadmin"
        ? route("superadmin.kelola-berita")
        : route("admin.kelola-berita") }}';

        window.batalkanForm = function () {
            if (typeof hidePopupBatal === 'function') {
                hidePopupBatal();
            }
            window.location.href = window._urlBatalBerita;
        };
    </script>
@endpush