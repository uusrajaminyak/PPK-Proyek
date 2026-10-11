@extends('layouts.admin')

@section('title', 'Edit Fasilitas')
@section('header_title', 'Kelola Fasilitas')

@section('content')
    @php
        $imagePath = $facility->foto_path ?? $facility->foto ?? null;
        $imageUrl = $imagePath
            ? (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://']) ? $imagePath : asset('storage/' . $imagePath))
            : null;
    @endphp

    <div class="admin-facility-edit-page">
        <section class="admin-facility-edit-gallery" aria-label="Foto fasilitas">
            <div class="officer-facility-detail-main-photo" id="facility-image-preview">
                @if ($imageUrl)
                    <img src="{{ $imageUrl }}" alt="Foto {{ $facility->nama_fasilitas }}">
                @endif
            </div>

            <div class="officer-facility-detail-thumbnails" aria-hidden="true">
                @for ($thumbnail = 0; $thumbnail < 3; $thumbnail++)
                    <div class="officer-facility-detail-thumbnail {{ $thumbnail === 0 && $imageUrl ? 'has-image' : '' }}" data-facility-thumbnail>
                        @if ($thumbnail === 0 && $imageUrl)
                            <img src="{{ $imageUrl }}" alt="">
                        @endif
                    </div>
                @endfor
            </div>

            <label class="admin-facility-upload-button officer-facility-detail-button" for="facility-photo-input">Upload Gambar</label>
            <input class="admin-facility-photo-input" id="facility-photo-input" type="file" name="foto" form="facility-edit-form" accept=".jpg,.jpeg,.png,.webp">
            <p class="admin-facility-upload-hint">JPG, PNG, atau WEBP (maks. 2 MB)</p>
        </section>

        <div class="officer-detail-divider" aria-hidden="true"></div>

        <section class="admin-facility-edit-information" aria-labelledby="admin-facility-edit-title">
            <h2 id="admin-facility-edit-title">Edit Fasilitas</h2>

            @if ($errors->any())
                <div class="officer-detail-alert is-error" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="facility-edit-form" class="admin-facility-edit-form" action="{{ route('admin.facilities.update', $facility) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="status_fasilitas" value="{{ old('status_fasilitas', $facility->status_fasilitas) }}">
                <input type="hidden" name="kategori" value="{{ old('kategori', $facility->tipe) }}">

                <label class="admin-facility-field admin-facility-field-full">
                    <span>Nama Fasilitas <b>*</b></span>
                    <input type="text" name="nama_fasilitas" value="{{ old('nama_fasilitas', $facility->nama_fasilitas) }}" placeholder="Masukkan nama fasilitas" required>
                </label>

                <div class="admin-facility-edit-row">
                    <label class="admin-facility-field">
                        <span>Lokasi <b>*</b></span>
                        <input type="text" name="lokasi" value="{{ old('lokasi', $facility->lokasi) }}" placeholder="Masukkan lokasi fasilitas" required>
                    </label>
                    <label class="admin-facility-field">
                        <span>Kapasitas <b>*</b></span>
                        <input type="number" name="kapasitas" value="{{ old('kapasitas', $facility->kapasitas) }}" min="0" placeholder="Masukkan kapasitas" required>
                    </label>
                </div>

                <label class="admin-facility-field admin-facility-field-full">
                    <span>Fasilitas <b>*</b></span>
                    <textarea name="deskripsi" rows="4" placeholder="Jelaskan fasilitas dan aturan penggunaan" required>{{ old('deskripsi', $facility->deskripsi) }}</textarea>
                </label>

                <div class="admin-facility-edit-actions">
                    <a class="admin-facility-cancel-button" href="{{ route('admin.facilities.show', $facility) }}">Batal</a>
                    <button class="officer-facility-detail-button" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </section>
    </div>

    <script>
        document.getElementById('facility-photo-input').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) {
                return;
            }

            const imageUrl = URL.createObjectURL(file);
            const preview = document.getElementById('facility-image-preview');
            preview.replaceChildren(Object.assign(document.createElement('img'), {
                src: imageUrl,
                alt: `Pratinjau foto ${file.name}`,
            }));

            const firstThumbnail = document.querySelector('[data-facility-thumbnail]');
            firstThumbnail.replaceChildren(Object.assign(document.createElement('img'), {
                src: imageUrl,
                alt: '',
            }));
            firstThumbnail.classList.add('has-image');
        });
    </script>
@endsection
