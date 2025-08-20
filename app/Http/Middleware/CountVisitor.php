<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CountVisitor
{
    public function handle($request, Closure $next)
    {
        // Ambil total kunjungan saat ini dari cache
        $visits = Cache::get('total_visits', 0);

        // Tambah 1 setiap ada request masuk
        Cache::put('total_visits', $visits + 1);

        return $next($request);
    }
}
