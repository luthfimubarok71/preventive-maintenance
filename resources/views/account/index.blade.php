@extends('layouts.bar')

@section('title', 'Account Settings')

@push('style')
<style>
    .account-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 80px 20px;
    }

    .account-card {
        width: 100%;
        max-width: 600px;
        background: #ffffff;
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,.15);
    }

    body.dark .account-card {
        background: #020617;
        color: #e5e7eb;
    }

    .account-card h4 {
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
<div class="account-wrapper">
    <div class="account-card">
        <h4><i class="bi bi-person-gear"></i> Account Settings</h4>

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

        <form method="POST" action="{{ route('account.update') }}">
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
                <small class="text-muted">Role cannot be changed through account settings</small>
            </div>

            <div class="password-section">
                <h5><i class="bi bi-shield-lock"></i> Change Password</h5>

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                </div>
            </div>

            <button type="submit" class="btn-update">
                <i class="bi bi-check-circle"></i> Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
