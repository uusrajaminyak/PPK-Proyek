<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f5f5">
    <title>Lapor Kendala/Kerusakan Fasilitas - Fasilita UNDIP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body class="report-page">

    {{-- Watermark Siluet Kiri --}}
    <img class="page-watermark" src="{{ asset('images/landing/undip-watermark.png') }}" alt="" aria-hidden="true">

    {{-- Navbar Utama --}}
    @include('partials.navbar')

    {{-- Main Container --}}
    <main class="report-main">
        {{-- Header Judul --}}
        <div class="report-header">
            <h1 class="report-title">Lapor Kendala/Kerusakan Fasilitas</h1>
            <p class="report-subtitle">Laporkan kendala atau kerusakan fasilitas kampus Universitas Diponegoro untuk penanganan cepat oleh petugas.</p>
        </div>

        {{-- Card Form --}}
        <div class="report-card">
            @if ($errors->any())
                <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1.5rem;font-size:0.85rem;color:#991b1b;">
                    @foreach ($errors->all() as $error)
                        <p style="margin:0.15rem 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="report-form" id="reportForm">
                @csrf
                <input type="hidden" name="kategori_laporan" value="Fasilitas Umum">

                {{-- Tanggal --}}
                <div class="report-form-group">
                    <label class="report-label" for="tanggal_lapor">
                        Tanggal<span class="required-mark">*</span>
                    </label>
                    <div class="report-input-wrapper">
                        <div class="report-input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <input type="text" id="tanggal_lapor" name="tanggal_display" class="report-input has-icon" 
                               value="{{ now()->format('d/m/Y') }}" readonly required>
                    </div>
                </div>

                {{-- Grid 2 Kolom: Nama Pelapor & No WhatsApp --}}
                <div class="report-grid-2">
                    <div class="report-form-group">
                        <label class="report-label" for="nama_pelapor">
                            Nama Pelapor<span class="required-mark">*</span>
                        </label>
                        <input type="text" id="nama_pelapor" name="nama_pelapor" class="report-input" 
                            value="{{ Auth::user()?->name }}" 
                            readonly aria-readonly="true"
                            style="background: #f9fafb; color: #6b7280; cursor: not-allowed;">
                    </div>

                    <div class="report-form-group">
                        <label class="report-label" for="no_whatsapp">
                            No. WhatsApp Aktif<span class="required-mark">*</span>
                        </label>
                        <input type="tel" id="no_whatsapp" name="no_whatsapp" class="report-input" 
                               value="{{ old('no_whatsapp') }}" 
                               placeholder="08XXXXXXXXXX" required>
                    </div>
                </div>

                {{-- Lokasi / Fasilitas --}}
                <div class="report-form-group">
                    <label class="report-label" for="facility_id">
                        Lokasi<span class="required-mark">*</span>
                    </label>
                    <div class="report-select-wrapper">
                        <select name="facility_id" id="facility_id" class="report-select" required>
                            <option value="">Pilih lokasi</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->nama_fasilitas }} &bull; {{ $facility->lokasi }}
                                </option>
                            @endforeach
                        </select>
                        <div class="report-select-arrow">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Kendala/Kerusakan --}}
                <div class="report-form-group">
                    <label class="report-label" for="deskripsi">
                        Deskripsi Kendala/Kerusakan<span class="required-mark">*</span>
                    </label>
                    <textarea name="deskripsi" id="deskripsi" class="report-textarea" 
                              placeholder="Informasi detail kendala/kerusakan fasilitas..." required>{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Upload Foto --}}
                <div class="report-form-group">
                    <label class="report-label">
                        Upload Foto<span class="required-mark">*</span>
                    </label>
                    <input type="file" id="fotos" name="fotos[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" 
                           style="display: none;" onchange="handleFileSelect(this)">
                    
                    <div class="report-dropzone" id="dropzone" onclick="document.getElementById('fotos').click()">
                        <div class="dropzone-icon-box">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="12" y1="18" x2="12" y2="12"></line>
                                <polyline points="9 15 12 12 15 15"></polyline>
                            </svg>
                        </div>
                        <p class="dropzone-text" id="dropzoneText">Klik/drag gambar ke sini untuk upload</p>
                    </div>

                    {{-- Image Preview Grid --}}
                    <div class="report-preview-grid" id="previewGrid" style="display: none;"></div>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit" class="btn-submit-report" id="btnSubmitReport">
                    Buat Laporan
                </button>
            </form>
        </div>
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Script Dropzone Drag & Drop dan Preview Foto --}}
    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fotos');
        const previewGrid = document.getElementById('previewGrid');

        // Drag & Drop effects
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('drag-over');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('drag-over');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFileSelect(fileInput);
        });

        function handleFileSelect(input) {
            previewGrid.innerHTML = '';
            if (input.files && input.files.length > 0) {
                previewGrid.style.display = 'grid';
                Array.from(input.files).forEach(file => {
                    if (!file.type.startsWith('image/')) return;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const box = document.createElement('div');
                        box.className = 'preview-thumb-box';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        box.appendChild(img);
                        previewGrid.appendChild(box);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                previewGrid.style.display = 'none';
            }
        }
    </script>
</body>
</html>