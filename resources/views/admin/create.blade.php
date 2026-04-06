@extends('layouts.bar')

@section('title', 'Tambah User')

@push('style')
    <style>
        /* ================= PAGE WRAPPER ================= */
        .page-wrapper {
            min-height: 100vh;
            background: linear-gradient(135deg, #6aa5ff, #3b82f6);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 80px 20px;
            transition: 0.4s ease;
        }

        body.dark .page-wrapper {
            background: linear-gradient(135deg, #0f172a, #020617);
        }

        /* ================= CARD ================= */
        .card-custom {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .15);
            transition: 0.3s ease;
        }

        body.dark .card-custom {
            background: #020617;
            color: #e5e7eb;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .6);
        }

        /* ================= TITLE ================= */
        .card-custom h4 {
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }

        /* ================= FORM CONTROL ================= */
        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid #dbeafe;
            transition: 0.25s ease;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .25);
        }

        body.dark .form-control {
            background: #020617;
            border-color: #1e293b;
            color: #e5e7eb;
        }

        body.dark .form-control:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, .25);
        }

        /* ================= BUTTON ================= */
        .btn-save {
            margin-top: 10px;
            width: 100%;
            padding: 12px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            border: none;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 12px 24px rgba(37, 99, 235, .45);
            transition: 0.25s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 36px rgba(37, 99, 235, .55);
        }

        .btn-save:active {
            transform: translateY(0);
        }

        /* ================= BACK LINK ================= */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }

        body.dark .back-link {
            color: #93c5fd;
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper">
        <div class="card-custom">

            <h4>Tambah User</h4>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <input class="form-control mb-3" name="username" value="{{ old('username') }}" placeholder="Username" required>
                @error('username')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror


                <input class="form-control mb-3" name="email" value="{{ old('email') }}" placeholder="Email" required>
                @error('email')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror

                <input class="form-control mb-3" name="password" type="password" placeholder="Password" required>
                @error('password')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror

                <input class="form-control mb-3" name="password_confirmation" type="password"
                    placeholder="Konfirmasi Password" required>
                @error('password_confirmation')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror

                <select class="form-control mb-4" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="teknisi" {{ old('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                    <option value="kepala_ro" {{ old('role') == 'kepala_ro' ? 'selected' : '' }}>Kepala RO</option>
                    <option value="pusat" {{ old('role') == 'pusat' ? 'selected' : '' }}>Pusat</option>
                </select>
                @error('role')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror
                <select class="form-control mb-4" name="regional_id" required>
                    <option value="">-- Pilih Regional --</option>
                    @foreach ($regionals as $regional)
                        <option value="{{ $regional->id }}">
                            {{ $regional->nama_regional }}
                        </option>
                    @endforeach
                </select>

                <button class="btn-save" type="submit">Simpan User</button>
            </form>


            <a href="{{ route('admin.users.index') }}" class="back-link">
                ← Kembali ke Manajemen User
            </a>

        </div>
    </div>
@endsection
