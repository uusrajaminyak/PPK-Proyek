@extends('layouts.admin')

@section('title', 'Detail Petugas/Admin')
@section('header_title', 'Kelola Petugas')

@section('content')
    <section class="admin-officer-detail-page" aria-labelledby="admin-officer-detail-title">
        <a class="officer-facility-detail-back" href="{{ route('admin.officers.index') }}">
            <img src="{{ asset('images/officer/return.svg') }}" alt="" aria-hidden="true">
            Kembali
        </a>

        @if (session('success'))
            <div class="officer-detail-alert is-success admin-officer-detail-alert" role="status">{{ session('success') }}</div>
        @endif

        <div class="admin-officer-detail-card">
            <h2 id="admin-officer-detail-title">Detail Petugas</h2>

            <div class="admin-officer-detail-avatar" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.5" />
                    <circle cx="24" cy="17" r="7" stroke="currentColor" stroke-width="1.5" />
                    <path d="M11 38c1.7-6.1 6.4-9.5 13-9.5s11.3 3.4 13 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>

            <h3>{{ $officer->name }}</h3>
            <p class="admin-officer-detail-email">{{ $officer->email }}</p>

            <dl class="admin-officer-detail-data">
                <div>
                    <dt>NIM/NIP/ID</dt>
                    <dd>{{ $officer->nomor_identitas ?: '—' }}</dd>
                </div>
                <div>
                    <dt>Kategori</dt>
                    <dd>{{ $officer->role === 'admin' ? 'Admin' : 'Petugas' }}</dd>
                </div>
                <div>
                    <dt>No. WhatsApp</dt>
                    <dd>{{ $officer->no_telepon ?: '—' }}</dd>
                </div>
                <div>
                    <dt>Terdaftar Sejak</dt>
                    <dd>{{ $officer->created_at?->format('d M Y') ?? '—' }}</dd>
                </div>
            </dl>

            <div class="admin-officer-detail-actions">
                <form action="{{ route('admin.officers.update-status', $officer) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_akun" value="{{ in_array($officer->status_akun, ['verified', 'active'], true) ? 'suspended' : 'verified' }}">
                    <button class="{{ in_array($officer->status_akun, ['verified', 'active'], true) ? 'is-suspend' : 'is-activate' }}" type="submit">
                        {{ in_array($officer->status_akun, ['verified', 'active'], true) ? 'Suspend Akun' : 'Aktifkan Akun' }}
                    </button>
                </form>

                <form action="{{ route('admin.officers.reset-password', $officer) }}" method="POST" onsubmit="return confirm('Reset password akun ini?');">
                    @csrf
                    <button class="is-reset" type="submit">Reset Password</button>
                </form>
            </div>
        </div>
    </section>
@endsection
