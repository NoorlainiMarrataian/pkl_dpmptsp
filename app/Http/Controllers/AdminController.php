<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache; // ✅ untuk ambil total kunjungan
use App\Models\LogPengunduhan; // ✅ model log pengunduhan

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // Validasi format username
        $request->validate([
            'username' => ['required', 'regex:/^[a-zA-Z0-9_]+$/'],
            'password' => 'required',
        ], [
            'username.required' => 'Username harus diisi',
            'username.regex' => 'Gunakan format yang benar.',
            'password.required' => 'Password harus diisi',
        ]);

        $username = $request->username;
        $password = $request->password;

        // Fetch admin by username (DB may be case-insensitive). Enforce exact
        // case match in application logic to ensure 'admin' != 'ADMIN'. This
        // approach is portable across DB engines (avoids MySQL-specific BINARY).
        $admin = \App\Models\Admin::where('username', $username)->first();

        // Enforce exact-case username match and verify password
        if (! $admin || $admin->username !== $username || ! \Hash::check($password, $admin->password)) {
            return back()->withErrors([
                'username' => 'Username atau password salah',
            ]);
        }

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard');
    }



    public function dashboard()
    {
        // ✅ Ambil data log pengunduhan (jika mau ditampilkan di tabel)
        $downloads = LogPengunduhan::orderBy('waktu_download', 'desc')->get();

        // ✅ Ambil total kunjungan dari cache (diset di middleware CountVisitor)
        $totalVisits = Cache::get('total_visits', 0);

        return view('admin.dashboard', compact('downloads', 'totalVisits'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
