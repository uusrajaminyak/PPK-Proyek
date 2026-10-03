<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Cek status akun verified
            if (!$user->isVerified()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Akun belum diverifikasi. Hubungi administrator.',
                ]);
            }

            $welcomeMessage = 'Selamat datang, ' . $user->name . '!';

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', $welcomeMessage);
            }

            return redirect()->intended(route('home'))->with('success', $welcomeMessage);
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Berhasil logout.');
    }
}
