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
        'ANJAS'    => ['password' => '40990230', 'role' => 'PIC'],
        'JHON'     => ['password' => '18940320', 'role' => 'PIC'],
        'LINA'     => ['password' => '19710013', 'role' => 'PIC'],
        'NANDA'    => ['password' => '18990011', 'role' => 'PIC'],
        'PUTRI'    => ['password' => 'vanzndt28', 'role' => 'PIC'],
        'TAUFIQ'   => ['password' => '20981045', 'role' => 'PIC'],
        'TIKA'     => ['password' => 'vanzndt28', 'role' => 'PIC'],
        'IRVAN'    => ['password' => 'vanzndt28', 'role' => 'PIC'],
        'JULIARDI' => ['password' => 'vanzndt28', 'role' => 'PIC'],
        'RITA'     => ['password' => 'vanzndt28', 'role' => 'PIC'],
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
