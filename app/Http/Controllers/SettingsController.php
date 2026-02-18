<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => [
                'nullable',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ]);

        // Update basic info
        $user->username = $request->username;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Pengaturan akun berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'new_username' => 'required|string|max:255|unique:users,username',
            'new_email' => 'required|string|email|max:255|unique:users,email',
            'new_password' => 'required|min:6|confirmed',
            'new_role' => 'required|in:admin,teknisi,kepala_ro,pusat',
        ]);

        User::create([
            'username' => $request->new_username,
            'email' => $request->new_email,
            'password' => Hash::make($request->new_password),
            'role' => $request->new_role,
        ]);

        return back()->with('success', 'User baru berhasil ditambahkan.');
    }
}