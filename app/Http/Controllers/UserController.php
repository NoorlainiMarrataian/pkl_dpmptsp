<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        // arahkan ke view dashboard user
        return view('user.dashboard.index');
    }
}
