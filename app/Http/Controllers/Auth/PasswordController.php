<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    // Forgot password form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Handle forgot password submission
    public function sendResetLink(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required|string',
        ]);

        // Find by email or name
        $identifier = $data['identifier'];
        $user = str_contains($identifier, '@')
            ? User::where('email', $identifier)->first()
            : User::where('name', $identifier)->first();

        if (!$user) {
            return back()->withErrors(['identifier' => 'Akun tidak ditemukan.'])->withInput();
        }

        // Generate token using Laravel Password broker
        $token = Password::createToken($user);

        // Build reset link (we surface it di layar karena email belum dikonfigurasi)
        $resetLink = route('password.reset', ['token' => $token, 'email' => $user->email]);

        return back()->with('status', 'Link reset berhasil dibuat.')->with('reset_link', $resetLink);
    }

    // Show reset form
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // Handle reset
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:4|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil direset, silakan masuk.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    // Authenticated change password
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    public function change(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:4|confirmed',
        ]);

        $user = $request->user();
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Regenerate session
        Auth::login($user, true);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}

