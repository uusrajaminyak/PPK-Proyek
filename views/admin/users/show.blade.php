@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('header_title', 'Kelola Pengguna')

@section('content')
    <section class="admin-officer-detail-page" aria-labelledby="admin-user-detail-title">
        <a class="officer-facility-detail-back" href="{{ route('admin.users.index') }}">
            <img src="{{ asset('images/officer/return.svg') }}" alt="" aria-hidden="true">
            Kembali
        </a>

        @if (session('success'))
            <div class="officer-detail-alert is-success admin-officer-detail-alert" role="status">{{ session('success') }}</div>
        @endif

        <div class="admin-officer-detail-card admin-user-detail-card">
            <h2 id="admin-user-detail-title">Detail Pengguna</h2>

            <div class="admin-officer-detail-avatar" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.5" />
                    <circle cx="24" cy="17" r="7" stroke="currentColor" stroke-width="1.5" />
                    <path d="M11 38c1.7-6.1 6.4-9.5 13-9.5s11.3 3.4 13 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>

            <h3>{{ $user->name }}</h3>
            <p class="admin-officer-detail-email">{{ $user->email }}</p>

            <dl class="admin-officer-detail-data">
                <div>
                    <dt>NIM/NIP/ID</dt>
                    <dd>{{ $user->nomor_identitas ?: '—' }}</dd>
                </div>
                <div>
                    <dt>Kategori</dt>
                    <dd>{{ $user->kategori ?: '—' }}</dd>
                </div>
                <div>
                    <dt>No. WhatsApp</dt>
                    <dd>{{ $user->no_telepon ?: '—' }}</dd>
                </div>
                <div>
                    <dt>Program Studi</dt>
                    <dd>{{ $user->program_studi ?: '—' }}</dd>
                </div>
                <div>
                    <dt>Terdaftar Sejak</dt>
                    <dd>{{ $user->created_at?->format('d M Y') ?? '—' }}</dd>
                </div>
            </dl>

            <div class="admin-officer-detail-actions admin-user-detail-actions {{ $user->status_akun === 'pending' ? 'is-pending' : '' }}">
                @if ($user->status_akun === 'pending')
                    <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_akun" value="verified">
                        <button class="is-activate" type="submit">Verifikasi Akun</button>
                    </form>
                    <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_akun" value="rejected">
                        <button class="is-suspend" type="submit">Tolak Registrasi</button>
                    </form>
                @elseif (in_array($user->status_akun, ['verified', 'active'], true))
                    <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_akun" value="suspended">
                        <button class="is-suspend" type="submit">Suspend Akun</button>
                    </form>
                @else
                    <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_akun" value="verified">
                        <button class="is-activate" type="submit">Aktifkan Akun</button>
                    </form>
                @endif

                <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" onsubmit="return confirm('Reset password akun ini?');">
                    @csrf
                    <button class="is-reset" type="submit">Reset Password</button>
                </form>
            </div>
        </div>
    </section>
@endsection
