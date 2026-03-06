<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\AuthRedirect;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function show()
    {
        return inertia('Auth/Login');
    }

    /**
     * Handle the incoming login request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
                'remember' => 'nullable|boolean',
            ],
            [
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Password wajib diisi',
            ],
        );

        if (!Auth::attempt($request->only('email', 'password'), (bool) $request->boolean('remember'))) {
            return back()->with('error', 'Login gagal! Email atau password tidak sesuai.');
        }

        $request->session()->regenerate();

        $user = $request->user();
        $redirectRoute = AuthRedirect::routeName($user);

        if (!$redirectRoute) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda belum memiliki hak akses ke dashboard mana pun.');
        }

        return redirect()->route($redirectRoute)->with('success', 'Login berhasil. Selamat datang, ' . $user->name . '!');
    }
}
