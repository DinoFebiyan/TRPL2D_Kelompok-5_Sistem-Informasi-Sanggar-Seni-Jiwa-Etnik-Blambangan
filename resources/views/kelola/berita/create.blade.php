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

        .select-status:invalid {
            color: #b0b0b0;
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
            gap: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            white-space: nowrap;
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

        .ai-generate-wrapper {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
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
        }

        .prompt-input:focus {
            border-color: #B08A2D;
            box-shadow: 0 0 0 2px rgba(212, 160, 23, 0.2);
        }

        .btn-generate-ai .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
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
                    <a href="#" onclick="showPopupBatal('{{ route('superadmin.kelola-berita') }}'); return false;"
                        class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @elseif(auth()->user()->peran === 'admin')
                    <a href="#" onclick="showPopupBatal('{{ route('admin.kelola-berita') }}'); return false;" class="btn-batal">
                        <i class="fas fa-times"></i> Batal
                    </a>
                @endif

                <button type="button" onclick="showPopupSimpan('formTambahBerita')" form="formTambahBerita"
                    class="btn-simpan"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>

        <div class="form-body">
            <form id="formTambahBerita" 
            action="{{ auth()->user()->peran == 'superadmin'
                ? route('superadmin.store-berita')
                : route('admin.store-berita') }}" 
                method="POST"
                enctype="multipart/form-data">
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
                    <div id="imagePreviewContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px;"></div>
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
                        <option value="Pengumuman" {{ old('jenis_berita') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="Prestasi" {{ old('jenis_berita') == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                        <option value="Kolaborasi" {{ old('jenis_berita') == 'Kolaborasi' ? 'selected' : '' }}>Kolaborasi</option>
                        <option value="Event" {{ old('jenis_berita') == 'Event' ? 'selected' : '' }}>Event</option>
                        <option value="Lainnya" {{ old('jenis_berita') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    
                    <label>Isi Berita</label>
                    <div class="ai-generate-wrapper">
        
                        <input type="text" id="prompt-ai" class="prompt-input" 
                               placeholder="Ketik instruksi/prompt untuk AI di sini..." 
                               style="display: none;">
                        
                        <button type="button" id="btn-generate-ai" class="btn-generate-ai">
                            <img src="https://www.gstatic.com/lamda/images/gemini_sparkle_v002_d4735304ff6292a690345.svg" 
                                alt="Gemini" 
                                style="width: 16px; height: 16px; object-fit: contain;"> 
                            <span id="btn-text">Generate dengan AI</span>
                        </button>
                    </div> 

                    <textarea id="isi_berita_editor" name="isi" rows="6"
                        placeholder="Masukkan isi berita..." required>{{ old('isi_berita') }}</textarea>
                </div>

                
                

                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required class="select-status">
                        <option value="" disabled selected hidden>Masukkan status berita...</option>
                        <option value="tayang" {{ old('status') == 'tayang' ? 'selected' : '' }}>Tayang</option>
                        <option value="tidak ditayangkan" {{ old('status') == 'tidak ditayangkan' ? 'selected' : '' }}>Tidak Ditayangkan</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
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

<script src="https://cdn.tiny.cloud/1/ug7pj020bty002h7qm1sroe2u6xjk3uz3u7kb0ctdgxrgysr/tinymce/7/tinymce.min.js"
    referrerpolicy="origin"></script>

<script>
    tinymce.init({
        selector: '#isi_berita_editor',
        height: 600,
        menubar: 'file edit view insert format table tools',
        plugins: [
            'advlist','autolink','lists','link','image','media','table','charmap','preview',
            'searchreplace','visualblocks','fullscreen','insertdatetime','wordcount'
        ],
        toolbar: [
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

        // TAMBAHKAN DUA BARIS INI:
    setup: function (editor) {
        editor.on('change keyup NodeChange', function () {
            editor.save(); // Menyinkronkan konten TinyMCE ke textarea asli
        });
    },

        images_upload_url: '/upload-gambar-berita',
        automatic_uploads: true,
        image_title: true,
        images_upload_handler: (blobInfo) => new Promise((resolve, reject) => {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/upload-gambar-berita');
            xhr.setRequestHeader('X-CSRF-TOKEN',
                document.querySelector('meta[name="csrf-token"]').content);

            xhr.onload = function () {
                if (xhr.status !== 200) return reject('HTTP Error: ' + xhr.status);
                let json = JSON.parse(xhr.responseText);
                if (!json.location) return reject('Upload gagal');
                resolve(json.location);
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
        content_style: "body { font-family: Poppins, sans-serif; font-size:14px; line-height:1.8; }"
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnGenerate   = document.getElementById('btn-generate-ai');
    const promptInput   = document.getElementById('prompt-ai');
    const promptContainer = document.getElementById('prompt-extra-container'); // Container form prompt tambahan
    const fileInput     = document.getElementById('foto');
    const btnText       = document.getElementById('btn-text'); // Span/teks di dalam tombol
    
    if (!btnGenerate) return;

    const API_URL = '{{ route("generate.ai.berita") }}'; 

    btnGenerate.addEventListener('click', async function () {
        const judul = document.getElementById('judul_berita').value.trim();
        const jenis = document.getElementById('jenis_berita').value || '';
        const promptText = promptInput ? promptInput.value.trim() : '';

        // 1. Siapkan FormData untuk request ke Laravel
        const formData = new FormData();
        formData.append('judul', judul);
        formData.append('jenis_berita', jenis);
        formData.append('prompt', promptText);

        if (fileInput && fileInput.files.length > 0) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append('foto[]', fileInput.files[i]);
            }
        }

        // 2. Tampilan Loading State
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

            // 3. Masukkan Hasil ke TinyMCE / Textarea
            if (window.tinymce && tinymce.get('isi_berita_editor')) {
                tinymce.get('isi_berita_editor').setContent(data.content);
            } else {
                document.getElementById('isi_berita_editor').value = data.content;
            }

            // 4. MUNCULKAN FORM PROMPT TAMBAHAN & UBAH TEKS TOMBOL
            if (promptContainer) {
                promptContainer.style.display = 'block'; // Tampilkan container prompt tambahan
            }
            if (promptInput) {
                promptInput.style.display = 'block';
            }
            
            // Ubah teks tombol menjadi Re-generate Berita
            if (btnText) {
                btnText.innerText = 'Re-generate Berita';
            } else {
                btnGenerate.innerHTML = '<i class="fas fa-sync-alt"></i> Re-generate Berita';
            }

        } catch (err) {
            console.error(err);
            alert('Terjadi kesalahan: ' + err.message);
            // Kembalikan teks jika gagal
            if (btnText) btnText.innerText = originalText;
        } finally {
            btnGenerate.disabled = false;
        }
    });
});
</script>
@endpush