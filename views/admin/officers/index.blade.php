@extends('layouts.admin')

@section('title', 'Kelola Petugas')
@section('header_title', 'Kelola Petugas')

@section('content')
    <div class="admin-users-page admin-officers-page" data-ajax-list>
        <form class="officer-facility-filterbar" action="{{ route('admin.officers.index') }}" method="GET">
            <label class="officer-facility-search">
                <span class="sr-only">Cari petugas</span>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari petugas...">
                <button type="submit" aria-label="Cari petugas">
                    <img src="{{ asset('images/officer/search.svg') }}" alt="" aria-hidden="true">
                </button>
            </label>

            <div class="officer-facility-filter-controls">
                <label class="officer-facility-select">
                    <span class="sr-only">Filter kategori akun</span>
                    <select name="kategori" onchange="this.form.requestSubmit()">
                        <option value="">Kategori</option>
                        <option value="Petugas" @selected(request('kategori') === 'Petugas')>Petugas</option>
                        <option value="Admin" @selected(request('kategori') === 'Admin')>Admin</option>
                    </select>
                    <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                </label>

                <label class="officer-facility-select">
                    <span class="sr-only">Filter status petugas</span>
                    <select name="status" onchange="this.form.requestSubmit()">
                        <option value="">Status</option>
                        <option value="verified" @selected(request('status') === 'verified')>Aktif</option>
                        <option value="suspended" @selected(request('status') === 'suspended')>Nonaktif</option>
                    </select>
                    <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                </label>

                <a href="{{ route('admin.officers.create') }}" class="admin-user-add-button">
                    <img src="{{ asset('images/admin/add-user.svg') }}" alt="" aria-hidden="true">
                    <span>Tambah Petugas</span>
                </a>
            </div>
        </form>

        <section class="admin-user-content-card" aria-label="Daftar petugas">
            <div class="admin-user-table-wrap">
                <table class="admin-user-table">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 20%">Nama</th>
                            <th scope="col" style="width: 18%">ID</th>
                            <th scope="col" style="width: 24%">Email</th>
                            <th scope="col" style="width: 15%">Kategori</th>
                            <th scope="col" style="width: 13%">Status</th>
                            <th scope="col" style="width: 10%" aria-label="Aksi"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($officers as $officer)
                            @php
                                $accountStatusLabel = in_array($officer->status_akun, ['verified', 'active'], true)
                                    ? 'Aktif'
                                    : 'Nonaktif';
                            @endphp
                            <tr>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->nomor_identitas ?? '—' }}</td>
                                <td>{{ $officer->email }}</td>
                                <td>{{ $officer->role === 'admin' ? 'Admin' : 'Petugas' }}</td>
                                <td><span class="admin-user-status">{{ $accountStatusLabel }}</span></td>
                                <td>
                                    <a href="{{ route('admin.officers.show', $officer->id) }}" class="admin-user-detail-button">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr class="admin-user-empty">
                                <td colspan="6">Tidak ada data yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="officer-facility-pagination admin-user-pagination">
                {{ $officers->links('vendor.pagination.admin-officers') }}
            </div>
        </section>
    </div>
@endsection
