@extends('layouts.admin')

@section('title', 'Daftarkan Pengguna')
@section('header_title', 'Kelola Pengguna')

@section('content')
    <section class="admin-user-register-page" aria-labelledby="admin-user-register-title">
        <div class="admin-user-register-form-wrap">
            <h2 id="admin-user-register-title">Daftar Pengguna Baru</h2>

            @if ($errors->any())
                <div class="admin-user-register-errors" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST" class="admin-user-register-form">
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
                        <select name="kategori" id="kategori-select" required>
                            <option value="" disabled @selected(!old('kategori'))>Pilih kategori</option>
                            @foreach ($kategoriOptions as $kategori)
                                <option value="{{ $kategori }}" @selected(old('kategori') === $kategori)>{{ $kategori }}</option>
                            @endforeach
                        </select>
                        <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                    </span>
                </label>

                @php($isStaf = old('kategori') === 'Staf')
                <label class="admin-user-register-field admin-user-prodi-field {{ $isStaf ? 'is-disabled' : '' }}" id="prodi-field" aria-disabled="{{ $isStaf ? 'true' : 'false' }}">
                    <span>Prodi <b>*</b></span>
                    <span class="admin-user-register-select">
                        <select name="program_studi" id="prodi-select" @disabled($isStaf) @required(!$isStaf)>
                            <option value="" disabled @selected(!old('program_studi'))>Pilih prodi</option>
                            @foreach ($prodiOptions as $prodi)
                                <option value="{{ $prodi }}" @selected(old('program_studi') === $prodi)>{{ $prodi }}</option>
                            @endforeach
                        </select>
                        <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                    </span>
                </label>

                @php($isMahasiswa = old('kategori') === 'Mahasiswa')
                <label class="admin-user-register-field admin-user-angkatan-field {{ $isMahasiswa ? '' : 'is-disabled' }}" id="angkatan-field" aria-disabled="{{ $isMahasiswa ? 'false' : 'true' }}">
                    <span>Angkatan <b>*</b></span>
                    <span class="admin-user-register-select">
                        <select name="angkatan" id="angkatan-select" @disabled(!$isMahasiswa) @required($isMahasiswa)>
                            <option value="" disabled @selected(!old('angkatan'))>Pilih angkatan</option>
                            @foreach ($angkatanOptions as $angkatan)
                                <option value="{{ $angkatan }}" @selected((string) old('angkatan') === (string) $angkatan)>{{ $angkatan }}</option>
                            @endforeach
                        </select>
                        <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                    </span>
                </label>

                <label class="admin-user-register-field">
                    <span>NIM/NIP <b>*</b></span>
                    <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas') }}" placeholder="Masukkan NIM/NIP" required>
                </label>

                <label class="admin-user-register-field">
                    <span>No. WhatsApp Aktif <b>*</b></span>
                    <input type="tel" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Masukkan no. WhatsApp aktif" required>
                </label>

                <div class="admin-user-register-actions">
                    <button type="submit" class="admin-user-register-submit">Daftarkan Pengguna</button>
                    <a href="{{ route('admin.users.index') }}" class="admin-user-register-cancel">Batal</a>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.getElementById('kategori-select');
            const angkatanField = document.getElementById('angkatan-field');
            const angkatanSelect = document.getElementById('angkatan-select');
            const prodiField = document.getElementById('prodi-field');
            const prodiSelect = document.getElementById('prodi-select');

            function updateCategoryFields() {
                const isMahasiswa = categorySelect.value === 'Mahasiswa';
                const isStaf = categorySelect.value === 'Staf';

                angkatanSelect.disabled = !isMahasiswa;
                angkatanSelect.required = isMahasiswa;
                angkatanField.classList.toggle('is-disabled', !isMahasiswa);
                angkatanField.setAttribute('aria-disabled', String(!isMahasiswa));

                if (!isMahasiswa) {
                    angkatanSelect.value = '';
                }

                prodiSelect.disabled = isStaf;
                prodiSelect.required = !isStaf;
                prodiField.classList.toggle('is-disabled', isStaf);
                prodiField.setAttribute('aria-disabled', String(isStaf));

                if (isStaf) {
                    prodiSelect.value = '';
                }
            }

            categorySelect.addEventListener('change', updateCategoryFields);
            updateCategoryFields();
        });
    </script>
@endsection
