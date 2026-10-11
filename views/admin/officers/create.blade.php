@extends('layouts.admin')

@section('title', 'Daftarkan Petugas/Admin')
@section('header_title', 'Kelola Petugas')

@section('content')
    <section class="admin-user-register-page" aria-labelledby="admin-officer-register-title">
        <div class="admin-user-register-form-wrap">
            <h2 id="admin-officer-register-title">Daftar Petugas/Admin Baru</h2>

            @if ($errors->any())
                <div class="admin-user-register-errors" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.officers.store') }}" method="POST" class="admin-user-register-form admin-officer-register-form">
                @csrf
                <input type="hidden" name="status_aktif" value="1">

                <label class="admin-user-register-field">
                    <span>Nama Depan <b>*</b></span>
                    <input type="text" name="nama_depan" value="{{ old('nama_depan') }}" placeholder="Masukkan nama depan" required>
                </label>

                <label class="admin-user-register-field">
                    <span>Nama Belakang <b>*</b></span>
                    <input type="text" name="nama_belakang" value="{{ old('nama_belakang') }}" placeholder="Masukkan nama belakang" required>
                </label>

                <label class="admin-user-register-field">
                    <span>Email <b>*</b></span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
                </label>

                <label class="admin-user-register-field">
                    <span>Kategori <b>*</b></span>
                    <span class="admin-user-register-select">
                        <select name="kategori" required>
                            <option value="" disabled @selected(!in_array(old('kategori'), ['Petugas', 'Admin'], true))>Pilih kategori</option>
                            <option value="Petugas" @selected(old('kategori') === 'Petugas')>Petugas</option>
                            <option value="Admin" @selected(old('kategori') === 'Admin')>Admin</option>
                        </select>
                        <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                    </span>
                </label>

                <label class="admin-user-register-field admin-officer-register-phone">
                    <span>No. WhatsApp Aktif <b>*</b></span>
                    <input type="tel" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Masukkan no. WA aktif" required>
                </label>

                <div class="admin-user-register-actions">
                    <button type="submit" class="admin-user-register-submit">Daftarkan Petugas</button>
                    <a href="{{ route('admin.officers.index') }}" class="admin-user-register-cancel">Batal</a>
                </div>
            </form>
        </div>
    </section>
@endsection
