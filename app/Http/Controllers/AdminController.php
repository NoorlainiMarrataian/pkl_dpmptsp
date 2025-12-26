<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\LogPengunduhan;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
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

        $admin = \App\Models\Admin::where('username', $username)->first();

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
        $downloads = LogPengunduhan::orderBy('waktu_download', 'desc')->get();

        $totalVisits = Cache::get('total_visits', 0);

        return view('admin.dashboard', compact('downloads', 'totalVisits'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
