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
            'username.regex' => 'Gunakan format yang benar',
        ]);

        $username = $request->username;
        $password = $request->password;

        // Case-sensitive login
        $admin = \App\Models\Admin::whereRaw('BINARY username = ?', [$username])->first();

        if (! $admin || ! \Hash::check($password, $admin->password)) {
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
