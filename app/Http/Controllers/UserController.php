<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->paginate(12);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:users,name',
            'email' => 'required|email|max:150|unique:users,email',
            'role' => 'required|in:admin,petugas',
            'password' => 'required|string|min:4|confirmed',
            'photo' => 'nullable|image|mimes:'.implode(',', config('bpip.user_photo_mimes')).'|max:'.(int) config('bpip.user_photo_max_kb'),
        ]);

        // Create user (explicitly hash password)
        $payload = [
            'name' => trim($data['name']),
            'email' => strtolower($data['email']),
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ];

        if ($request->hasFile('photo')) {
            $payload['photo'] = $request->file('photo')->store('avatars', 'public');
        }

        User::create($payload);

        return redirect()->route('users.index')->with('success', 'Anggota berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => "required|string|max:100|unique:users,name,{$user->id}",
            'email' => "required|email|max:150|unique:users,email,{$user->id}",
            'role' => 'required|in:admin,petugas',
            'password' => 'nullable|string|min:4|confirmed',
            'photo' => 'nullable|image|mimes:'.implode(',', config('bpip.user_photo_mimes')).'|max:'.(int) config('bpip.user_photo_max_kb'),
        ]);

        $user->name = trim($data['name']);
        $user->email = strtolower($data['email']);
        $user->role = $data['role'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        if ($request->hasFile('photo')) {
            // delete old if exists
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('avatars', 'public');
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'Data anggota diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Anggota berhasil dihapus.');
    }

    public function resetPassword(User $user)
    {
        $new = Str::random(10);
        $user->password = Hash::make($new);
        $user->save();
        return back()->with('info', 'Password baru untuk '.$user->name.': '.$new);
    }

    public function destroyPhoto(User $user)
    {
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            $user->photo = null;
            $user->save();
        }
        return back()->with('success', 'Foto anggota telah dihapus.');
    }
}
