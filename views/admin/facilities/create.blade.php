@extends('layouts.admin')

@section('title', 'Tambah Fasilitas Baru')
@section('header_title', 'Kelola Fasilitas')

@section('content')
<section class="admin-user-register-page" aria-labelledby="admin-facility-register-title">
    <div class="admin-user-register-form-wrap">
        <h2 id="admin-facility-register-title">Tambah Fasilitas Baru</h2>

        @if ($errors->any())
            <div class="admin-user-register-errors" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data" class="admin-user-register-form admin-facility-register-form">
            @csrf

            <label class="admin-user-register-field admin-facility-field-full">
                <span>Nama Fasilitas <b>*</b></span>
                <input type="text" name="nama_fasilitas" value="{{ old('nama_fasilitas') }}" placeholder="Masukkan nama fasilitas" required>
            </label>

            <label class="admin-user-register-field">
                <span>Tipe Fasilitas <b>*</b></span>
                <span class="admin-user-register-select">
                    <select name="tipe" required>
                        <option value="" disabled @selected(!old('tipe'))>Pilih tipe fasilitas</option>
                        @foreach ($tipeOptions as $tipe)
                            <option value="{{ $tipe }}" @selected(old('tipe') === $tipe)>{{ $tipe }}</option>
                        @endforeach
                    </select>
                    <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                </span>
            </label>

            <label class="admin-user-register-field">
                <span>Lokasi <b>*</b></span>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Masukkan lokasi fasilitas" required>
            </label>

            <label class="admin-user-register-field">
                <span>Kapasitas <b>*</b></span>
                <input type="number" name="kapasitas" value="{{ old('kapasitas') }}" min="0" placeholder="Masukkan kapasitas" required>
            </label>

            <label class="admin-user-register-field admin-facility-field-full">
                <span>Deskripsi</span>
                <textarea name="deskripsi" rows="4" placeholder="Jelaskan fasilitas dan aturan penggunaan">{{ old('deskripsi') }}</textarea>
            </label>

            <label class="admin-user-register-field admin-facility-field-full">
                <span>Foto Fasilitas</span>
                <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp">
                <small>Format JPG, PNG, atau WEBP. Maksimal 2 MB.</small>
            </label>

            <label class="admin-user-register-field admin-facility-field-full">
                <span>Status Fasilitas <b>*</b></span>
                <span class="admin-user-register-select">
                    <select name="status_fasilitas" required>
                        <option value="active" @selected(old('status_fasilitas', 'active') === 'active')>Tersedia</option>
                        <option value="inactive" @selected(old('status_fasilitas') === 'inactive')>Tidak Tersedia</option>
                    </select>
                    <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                </span>
            </label>

            <div class="admin-user-register-actions">
                <button type="submit" class="admin-user-register-submit">Simpan Fasilitas</button>
                <a href="{{ route('admin.facilities.index') }}" class="admin-user-register-cancel">Batal</a>
            </div>
        </form>
    </div>
</section>
@endsection
