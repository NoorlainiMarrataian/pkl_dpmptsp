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
        $credentials = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah',
        ]);
    }

    public function dashboard()
    {
        // ✅ Ambil data log pengunduhan (jika mau ditampilkan di tabel)
        $downloads = LogPengunduhan::orderBy('waktu_download', 'desc')->get();

        // ✅ Ambil total kunjungan dari cache (diset di middleware CountVisitor)
        $totalVisits = Cache::get('total_visits', 0);

        return view('admin.dashboard', compact('downloads', 'totalVisits'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
