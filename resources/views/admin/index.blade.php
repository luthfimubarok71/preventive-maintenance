@extends('layouts.bar')

@section('title', 'Manajemen User')

@push('style')
<style>
/* ================= STYLE LAMA (TIDAK DIUBAH) ================= */
body {
    background: linear-gradient(135deg, #6aa5ff, #3b82f6);
}

.user-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 80px 20px;
}

.user-card {
    width: 100%;
    max-width: 1000px;
    background: #ffffff;
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,.15);
}

.user-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.user-card h4 {
    font-weight: 600;
    margin-bottom: 20px;
}

.btn-primary {
    background: #2563eb;
    border: none;
    border-radius: 10px;
    padding: 8px 16px;
}

.btn-warning,
.btn-danger {
    border-radius: 8px;
    padding: 5px 12px;
}

.btn-add-user {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: #fff;
    font-weight: 600;
    border-radius: 12px;
    text-decoration: none;
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.35);
    transition: all 0.25s ease;
}

.btn-add-user span {
    font-size: 18px;
}

.btn-add-user:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(37, 99, 235, 0.45);
    color: #fff;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #f1f5f9;
}

th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #e5e7eb;
}

tbody tr:hover {
    background: #f8fafc;
}

.action-btns {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 10px;
    border: none;
    cursor: pointer;
}

.btn-edit {
    background: #e0f2fe;
    color: #0369a1;
}

.btn-delete {
    background: #fee2e2;
    color: #b91c1c;
}

/* ================= DARK MODE (TIDAK DIUBAH) ================= */
body.dark {
    background: linear-gradient(135deg, #243a6c, #091c70);
}

body.dark .user-card {
    background: #020617;
    color: #e5e7eb;
}

/* ================= STYLE BARU KHUSUS MODAL ================= */
/* === MODAL OVERLAY === */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(8px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

/* === MODAL BOX === */
.modal-box {
    width: 100%;
    max-width: 520px;
    background: #ffffff;
    border-radius: 26px;
    padding: 34px 32px 30px;
    position: relative;
    box-shadow:
        0 40px 80px rgba(0,0,0,.25),
        0 10px 25px rgba(0,0,0,.15);
    animation: modalPop .35s ease;
}

body.dark .modal-box {
    background: #020617;
    color: #e5e7eb;
}

/* === TITLE === */
.modal-box h4 {
    font-weight: 700;
    margin-bottom: 26px;
}

/* === INPUT & SELECT === */
.modal-box .form-control {
    border-radius: 14px;
    padding: 13px 16px;
    border: 1px solid #c7d2fe;
    margin-bottom: 14px;
}

.modal-box .form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.25);
}

/* === PRIMARY BUTTON === */
.modal-box .btn-save,
.modal-box .btn-update {
    width: 100%;
    padding: 14px;
    border-radius: 16px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border: none;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 12px 24px rgba(37,99,235,.45);
    transition: .25s ease;
}

.modal-box .btn-save:hover,
.modal-box .btn-update:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 36px rgba(37,99,235,.55);
}

/* === SECONDARY BUTTON === */
.modal-box .btn-secondary {
    width: 100%;
    margin-top: 10px;
    border-radius: 16px;
}

/* === CLOSE BUTTON === */
.modal-close {
    position: absolute;
    top: 16px;
    right: 18px;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #f1f5f9;
    border: none;
    font-size: 20px;
    font-weight: 700;
    cursor: pointer;
    transition: .25s ease;
}

.modal-close:hover {
    background: #e2e8f0;
    transform: rotate(90deg);
}

/* === ANIMATION === */
@keyframes modalPop {
    from {
        opacity: 0;
        transform: scale(.9) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>
@endpush

@section('content')
<div class="user-wrapper">
    <div class="user-card">

        <div class="user-header">
            <h4>Manajemen User</h4>
            <button type="button" class="btn-add-user" onclick="openCreateModal()">
                <span>＋</span> Tambah User
            </button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></td>
                    <td class="action-btns">
                        <button
                            type="button"
                            class="btn-action btn-edit"
                            onclick="openEditModal(this)"
                            data-id="{{ $user->id }}"
                            data-username="{{ $user->username }}"
                            data-email="{{ $user->email }}"
                            data-role="{{ $user->role }}">
                            ✏️ Edit
                        </button>

                        <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn-action btn-delete">🗑 Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div class="modal-overlay" id="createModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('createModal')">×</button>
        <h4 class="text-center">Tambah User</h4>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <input class="form-control" name="username" placeholder="Username" required>
            <input class="form-control" name="email" placeholder="Email" required>
            <input class="form-control" type="password" name="password" placeholder="Password" required>
            <input class="form-control" type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>

            <select class="form-control" name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="teknisi">Teknisi</option>
                <option value="kepala_ro">Kepala RO</option>
                <option value="pusat">Pusat</option>
            </select>

            <button class="btn-save">Simpan User</button>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('editModal')">×</button>
        <h4 class="text-center">Edit User</h4>

        <form method="POST" id="editForm">
            @csrf
            @method('PUT')

            <input class="form-control" id="editUsername" name="username" required>
            <input class="form-control" id="editEmail" name="email" required>
            <input class="form-control" type="password" name="password" placeholder="Password (opsional)">

            <select class="form-control" id="editRole" name="role" required>
                <option value="admin">Admin</option>
                <option value="teknisi">Teknisi</option>
                <option value="kepala_ro">Kepala RO</option>
                <option value="pusat">Pusat</option>
            </select>

            <button class="btn-update">Update User</button>
        </form>
    </div>
</div>
@endsection

@push('script')
<script>
function openCreateModal(){
    document.getElementById('createModal').style.display = 'flex';
}

function openEditModal(btn){
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('editForm').action = `/admin/users/${btn.dataset.id}`;
    document.getElementById('editUsername').value = btn.dataset.username;
    document.getElementById('editEmail').value = btn.dataset.email;
    document.getElementById('editRole').value = btn.dataset.role;
}

function closeModal(id){
    document.getElementById(id).style.display = 'none';
}
</script>
@endpush
