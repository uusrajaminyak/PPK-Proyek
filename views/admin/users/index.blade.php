@extends('layouts.admin')

@section('header_title', 'Kelola Pengguna')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<!-- Master Content Outer Container -->
<div id="users-master-container" class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-3 shadow-sm flex flex-col justify-between overflow-hidden font-jakarta text-[#001248]">

    <!-- Top Toolbar -->
    <form id="users-filter-form" action="{{ route('admin.users.index') }}" method="GET" class="flex items-center justify-between w-full px-0 shrink-0 mb-1.5 pt-1 gap-3">
        <!-- Search Bar -->
        <div class="flex-1 relative">
            <input type="text"
                id="search-input"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari pengguna"
                class="w-full bg-white border border-gray-300 rounded-full py-1.5 pl-5 pr-10 text-xs font-semibold text-[#001248] placeholder-[#001248]/50 focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] transition-colors shadow-none">
            <button type="submit" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#001248]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <!-- Kategori Filter -->
            <select name="kategori" id="kategori-select" class="bg-white border border-gray-300 rounded px-3 py-1.5 text-xs font-bold text-[#001248] focus:outline-none focus:ring-1 focus:ring-[#001248] shadow-none cursor-pointer">
                <option value="Semua">Semua Kategori</option>
                <option value="Mahasiswa" {{ request('kategori') === 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="Dosen" {{ request('kategori') === 'Dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="Petugas" {{ request('kategori') === 'Petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="Admin" {{ request('kategori') === 'Admin' ? 'selected' : '' }}>Admin</option>
            </select>

            <!-- Status Filter -->
            <select name="status" id="status-select" class="bg-white border border-gray-300 rounded px-3 py-1.5 text-xs font-bold text-[#001248] focus:outline-none focus:ring-1 focus:ring-[#001248] shadow-none cursor-pointer">
                <option value="Semua">Semua Status</option>
                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Aktif</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <!-- Tambah Pengguna Button -->
            <a href="{{ route('admin.users.create') }}" class="bg-[#001248] hover:bg-[#001a66] text-white px-4 py-1.5 rounded-full text-xs font-bold flex items-center space-x-2 transition-colors shadow-none shrink-0">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <line x1="19" y1="8" x2="19" y2="14"></line>
                    <line x1="16" y1="11" x2="22" y2="11"></line>
                </svg>
                <span>Tambah Pengguna</span>
            </a>
        </div>
    </form>

    <!-- Data Table Container (Matching Design Mockup) -->
    <div id="users-table-grid" class="flex-1 bg-white border border-gray-200 rounded-lg overflow-hidden shadow-none flex flex-col justify-between min-h-0 mb-1 px-5 py-1">
        <div class="overflow-hidden flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#001248] text-xs font-extrabold text-[#001248] bg-white sticky top-0 z-10">
                        <th class="py-1.5 px-3">Nama</th>
                        <th class="py-1.5 px-3">NIM/NIP/ID</th>
                        <th class="py-1.5 px-3">Kategori</th>
                        <th class="py-1.5 px-3">Status</th>
                        <th class="py-1.5 px-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-xs text-[#001248] font-normal">
                    @forelse($users as $user)
                    @php
                    $statusRaw = $user->status_akun;
                    $isVerified = $statusRaw === 'verified' || $statusRaw === 'active';
                    $isPending = $statusRaw === 'pending';
                    @endphp
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-1.5 px-3 text-[#001248] font-normal align-middle">{{ $user->name }}</td>
                        <td class="py-1.5 px-3 text-[#001248] font-normal align-middle">{{ $user->nomor_identitas ?? '-' }}</td>
                        <td class="py-1.5 px-3 text-[#001248] font-normal align-middle">
                            {{ $user->kategori ?? ucfirst($user->role) }}
                        </td>
                        <td class="py-1.5 px-3 text-[#001248] font-normal align-middle">
                            @if($isVerified)
                            Aktif
                            @elseif($isPending)
                            Menunggu
                            @else
                            Nonaktif
                            @endif
                        </td>
                        <td class="py-1.5 px-3 text-right align-middle">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="inline-block bg-[#001248] hover:bg-[#001a66] text-white px-3.5 py-1 rounded text-xs font-normal transition-colors shadow-none">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-400 text-xs font-normal">
                            Tidak ada data pengguna yang ditemukan dalam database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bottom Dynamic Pagination Footer -->
    <div id="pagination-container" class="relative flex flex-col sm:flex-row items-center justify-between pt-1 border-t border-gray-100 shrink-0 mt-0.5 min-h-[32px] px-1">
        <!-- Item Summary Counter -->
        <span class="text-xs font-medium text-gray-400 text-left sm:absolute sm:left-1">
            Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
        </span>

        <!-- Numbered Pagination Controls -->
        <div class="flex items-center space-x-1.5 mx-auto my-0.5 sm:my-0 z-10">
            @if ($users->hasPages())
            @if ($users->onFirstPage())
            <span class="w-7 h-7 rounded-lg border border-gray-300 text-gray-300 flex items-center justify-center text-xs font-medium bg-white cursor-not-allowed">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </span>
            @else
            <a href="{{ $users->previousPageUrl() }}" class="pagination-link w-7 h-7 rounded-lg border border-gray-300 text-[#001248] hover:bg-[#001248] hover:text-white transition-colors flex items-center justify-center text-xs font-medium bg-white">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </a>
            @endif

            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
            @if ($page == $users->currentPage())
            <span class="w-7 h-7 rounded-lg bg-[#001248] text-white flex items-center justify-center text-xs font-extrabold shadow-none">
                {{ $page }}
            </span>
            @else
            <a href="{{ $url }}" class="pagination-link w-7 h-7 rounded-lg border border-[#001248] text-[#001248] hover:bg-[#001248] hover:text-white flex items-center justify-center text-xs font-bold bg-white transition-colors">
                {{ $page }}
            </a>
            @endif
            @endforeach

            @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" class="pagination-link w-7 h-7 rounded-lg bg-[#001248] text-white hover:bg-[#001a66] transition-colors flex items-center justify-center text-xs font-bold shadow-none">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
            @else
            <span class="w-7 h-7 rounded-lg border border-gray-200 text-gray-300 flex items-center justify-center text-xs font-medium bg-white cursor-not-allowed">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </span>
            @endif
            @endif
        </div>
    </div>

</div>

<!-- Vanilla JavaScript AJAX Engine -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('users-filter-form');
        const searchInput = document.getElementById('search-input');
        const kategoriSelect = document.getElementById('kategori-select');
        const statusSelect = document.getElementById('status-select');

        function fetchFilteredUsers(targetUrl) {
            let url = targetUrl;

            if (!url) {
                const baseUrl = "{{ route('admin.users.index') }}";
                const params = new URLSearchParams();

                if (searchInput && searchInput.value.trim() !== '') {
                    params.set('search', searchInput.value.trim());
                }
                if (kategoriSelect && kategoriSelect.value !== 'Semua') {
                    params.set('kategori', kategoriSelect.value);
                }
                if (statusSelect && statusSelect.value !== 'Semua') {
                    params.set('status', statusSelect.value);
                }

                url = baseUrl + '?' + params.toString();
            }

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('HTTP Error ' + response.status);
                    return response.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newTable = doc.getElementById('users-table-grid');
                    const newPagination = doc.getElementById('pagination-container');

                    if (newTable) {
                        document.getElementById('users-table-grid').innerHTML = newTable.innerHTML;
                    }
                    if (newPagination) {
                        document.getElementById('pagination-container').innerHTML = newPagination.innerHTML;
                    }

                    window.history.pushState({}, '', url);
                    bindPaginationLinks();
                })
                .catch(error => console.error('Error fetching user data:', error));
        }

        function bindPaginationLinks() {
            document.querySelectorAll('.pagination-link').forEach(link => {
                link.onclick = function(e) {
                    e.preventDefault();
                    fetchFilteredUsers(this.getAttribute('href'));
                };
            });
        }

        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', () => fetchFilteredUsers());
        }
        if (statusSelect) {
            statusSelect.addEventListener('change', () => fetchFilteredUsers());
        }

        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchFilteredUsers();
            });
        }

        let debounceTimer;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchFilteredUsers(), 300);
            });
        }

        bindPaginationLinks();
    });
</script>
@endsection