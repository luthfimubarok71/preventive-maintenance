<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Regional;
class UserController extends Controller
{
   public function index()
{
    $users = User::latest()->get();
    $regionals = Regional::all(); // 🔥 ini yang kurang

    return view('admin.index', compact('users', 'regionals'));
}

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required',
            'regional_id' => 'required|exists:regionals,id', 
        ]);

        try {
            User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
                'regional_id'  => $request->regional_id,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal membuat user: ' . $e->getMessage()]);
        }
    }

    public function edit(User $user)
    {
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'role'     => 'required',
        ]);

        $data = $request->only('username', 'email', 'role');

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        try {
            $user->update($data);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal update user: ' . $e->getMessage()]);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}