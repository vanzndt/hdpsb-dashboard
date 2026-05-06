<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Daftar user & password.
     * Untuk keamanan lebih baik, pindah ke database atau .env
     */
    private array $users = [
        'ADMIN'    => ['password' => 'vanzndt28', 'role' => 'ADMIN'],
        'ANJAS'    => ['password' => 'hdpsb', 'role' => 'PIC'],
        'JHON'     => ['password' => 'hdpsb', 'role' => 'PIC'],
        'LINA'     => ['password' => 'hdpsb', 'role' => 'PIC'],
        'NANDA'    => ['password' => 'hdpsb', 'role' => 'PIC'],
        'PUTRI'    => ['password' => 'hdpsb', 'role' => 'PIC'],
        'TAUFIQ'   => ['password' => 'hdpsb', 'role' => 'PIC'],
        'TIKA'     => ['password' => 'hdpsb', 'role' => 'PIC'],
        'IRVAN'    => ['password' => 'hdpsb', 'role' => 'PIC'],
        'JULIARDI' => ['password' => 'hdpsb', 'role' => 'PIC'],
        'RITA'     => ['password' => 'hdpsb', 'role' => 'PIC'],
    ];

    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->has('helpdesk_user')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = strtoupper(trim($request->input('username')));
        $password = $request->input('password');

        if (!isset($this->users[$username])) {
            return back()->withErrors(['login' => 'Akun tidak ditemukan.'])->withInput(['username' => $username]);
        }

        if ($this->users[$username]['password'] !== $password) {
            return back()->withErrors(['login' => 'Password salah!'])->withInput(['username' => $username]);
        }

        // Simpan session
        $request->session()->put('helpdesk_user', [
            'name' => $username,
            'role' => $this->users[$username]['role'],
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('helpdesk_user');
        return redirect()->route('login');
    }
}
