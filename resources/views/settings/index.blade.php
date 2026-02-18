@extends('layouts.bar')

@section('title', 'Pengaturan Akun')

@push('style')
<style>
    .settings-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 80px 20px;
    }

    .settings-card {
        width: 100%;
        max-width: 600px;
        background: #ffffff;
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,.15);
    }

    body.dark .settings-card {
        background: #020617;
        color: #e5e7eb;
    }

    .settings-card h4 {
        font-weight: 600;
        margin-bottom: 20px;
        color: #2563eb;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-update {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .password-section {
        border-top: 1px solid #e5e7eb;
        padding-top: 20px;
        margin-top: 20px;
    }

    .password-section h5 {
        font-weight: 600;
        color: #374151;
        margin-bottom: 15px;
    }

    body.dark .password-section h5 {
        color: #e5e7eb;
    }
</style>
@endpush

@section('content')
<div class="settings-wrapper">
    <div class="settings-card">
        <h4><i class="bi bi-gear"></i> Pengaturan Akun</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <input type="text" class="form-control" id="role" name="role" value="{{ ucfirst($user->role) }}" readonly>
                <small class="text-muted">Role tidak dapat diubah melalui pengaturan akun</small>
            </div>

            <div class="password-section">
                <h5><i class="bi bi-shield-lock"></i> Ubah Password</h5>

                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                </div>

                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                </div>
            </div>

            <button type="submit" class="btn-update">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </form>

        @if(Auth::user()->role === 'admin')
        <div class="settings-card" style="margin-top: 30px;">
            <h4><i class="bi bi-person-plus"></i> Tambah User Baru</h4>

            <form method="POST" action="{{ route('settings.store') }}">
                @csrf

                <div class="form-group">
                    <label for="new_username">Username</label>
                    <input type="text" class="form-control" id="new_username" name="new_username" value="{{ old('new_username') }}" required>
                    @error('new_username') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="new_email">Email</label>
                    <input type="email" class="form-control" id="new_email" name="new_email" value="{{ old('new_email') }}" required>
                    @error('new_email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                    @error('new_password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    @error('new_password_confirmation') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="new_role">Role</label>
                    <select class="form-control" id="new_role" name="new_role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('new_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="teknisi" {{ old('new_role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="kepala_ro" {{ old('new_role') == 'kepala_ro' ? 'selected' : '' }}>Kepala RO</option>
                        <option value="pusat" {{ old('new_role') == 'pusat' ? 'selected' : '' }}>Pusat</option>
                    </select>
                    @error('new_role') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn-update">
                    <i class="bi bi-person-plus"></i> Tambah User
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
</create_file>
