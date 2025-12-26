<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CountVisitor
{
    public function handle($request, Closure $next)
    {
        $visits = Cache::get('total_visits', 0);

        Cache::put('total_visits', $visits + 1);

        return $next($request);
    }
}
