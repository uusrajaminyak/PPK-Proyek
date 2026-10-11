@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('header_title', 'Kelola Pengguna')

@section('content')
    <div class="admin-users-page">
        <form id="users-filter-form" class="officer-facility-filterbar" action="{{ route('admin.users.index') }}" method="GET">
            <label class="officer-facility-search">
                <span class="sr-only">Cari pengguna</span>
                <input type="search" id="search-input" name="search" value="{{ request('search') }}" placeholder="Cari pengguna...">
                <button type="submit" aria-label="Cari pengguna">
                    <img src="{{ asset('images/officer/search.svg') }}" alt="" aria-hidden="true">
                </button>
            </label>

            <div class="officer-facility-filter-controls">
                <label class="officer-facility-select">
                    <span class="sr-only">Filter kategori pengguna</span>
                    <select name="kategori" id="kategori-select" onchange="this.form.requestSubmit()">
                        <option value="Semua">Kategori</option>
                        <option value="Mahasiswa" @selected(request('kategori') === 'Mahasiswa')>Mahasiswa</option>
                        <option value="Dosen" @selected(request('kategori') === 'Dosen')>Dosen</option>
                        <option value="Staf" @selected(request('kategori') === 'Staf')>Staf</option>
                    </select>
                    <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                </label>

                <label class="officer-facility-select">
                    <span class="sr-only">Filter status pengguna</span>
                    <select name="status" id="status-select" onchange="this.form.requestSubmit()">
                        <option value="Semua">Status</option>
                        <option value="verified" @selected(request('status') === 'verified')>Aktif</option>
                        <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                        <option value="suspended" @selected(request('status') === 'suspended')>Nonaktif</option>
                    </select>
                    <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                </label>

                <a href="{{ route('admin.users.create') }}" class="admin-user-add-button">
                    <img src="{{ asset('images/admin/add-user.svg') }}" alt="" aria-hidden="true">
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </form>

        <section class="admin-user-content-card" aria-label="Daftar pengguna">
            <div id="users-table-grid" class="admin-user-table-wrap">
                <table class="admin-user-table">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 22%">Nama</th>
                            <th scope="col" style="width: 23%">NIM/NIP/ID</th>
                            <th scope="col" style="width: 23%">Kategori</th>
                            <th scope="col" style="width: 17%">Status</th>
                            <th scope="col" style="width: 15%" aria-label="Aksi"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php
                                [$accountStatusLabel, $accountStatusClass] = match (true) {
                                    in_array($user->status_akun, ['verified', 'active'], true) => ['Aktif', 'is-active'],
                                    $user->status_akun === 'pending' => ['Menunggu', 'is-pending'],
                                    default => ['Nonaktif', 'is-inactive'],
                                };
                            @endphp
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->nomor_identitas ?? '—' }}</td>
                                <td>{{ $user->kategori ?? ucfirst($user->role) }}</td>
                                <td><span class="admin-user-status {{ $accountStatusClass }}">{{ $accountStatusLabel }}</span></td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="admin-user-detail-button">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr class="admin-user-empty">
                                <td colspan="5">Tidak ada data pengguna yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="pagination-container" class="officer-facility-pagination admin-user-pagination">
                {{ $users->links('vendor.pagination.admin-users') }}
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('users-filter-form');
            const searchInput = document.getElementById('search-input');
            const categorySelect = document.getElementById('kategori-select');
            const statusSelect = document.getElementById('status-select');

            function fetchFilteredUsers(targetUrl) {
                let url = targetUrl;

                if (!url) {
                    const params = new URLSearchParams();
                    if (searchInput.value.trim() !== '') {
                        params.set('search', searchInput.value.trim());
                    }
                    if (categorySelect.value !== 'Semua') {
                        params.set('kategori', categorySelect.value);
                    }
                    if (statusSelect.value !== 'Semua') {
                        params.set('status', statusSelect.value);
                    }
                    url = "{{ route('admin.users.index') }}" + '?' + params.toString();
                }

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('HTTP Error ' + response.status);
                        }
                        return response.text();
                    })
                    .then((html) => {
                        const documentFragment = new DOMParser().parseFromString(html, 'text/html');
                        const updatedTable = documentFragment.getElementById('users-table-grid');
                        const updatedPagination = documentFragment.getElementById('pagination-container');

                        if (updatedTable) {
                            document.getElementById('users-table-grid').innerHTML = updatedTable.innerHTML;
                        }
                        if (updatedPagination) {
                            document.getElementById('pagination-container').innerHTML = updatedPagination.innerHTML;
                        }

                        window.history.pushState({}, '', url);
                        bindPaginationLinks();
                    })
                    .catch((error) => console.error('Error fetching user data:', error));
            }

            function bindPaginationLinks() {
                document.querySelectorAll('.admin-user-pagination a').forEach((link) => {
                    link.onclick = function (event) {
                        event.preventDefault();
                        fetchFilteredUsers(this.href);
                    };
                });
            }

            filterForm.addEventListener('submit', function (event) {
                event.preventDefault();
                fetchFilteredUsers();
            });

            let debounceTimer;
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchFilteredUsers(), 300);
            });

            bindPaginationLinks();
        });
    </script>
@endsection
