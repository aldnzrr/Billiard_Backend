<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login dan apakah role-nya ada di dalam parameter
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return response()->json(['message' => 'Akses ditolak! Role tidak sesuai.'], 403);
        }

        return $next($request);
    }
}