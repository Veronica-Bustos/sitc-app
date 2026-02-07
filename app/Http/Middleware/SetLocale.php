<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            // Auto-detect from browser or use default
            $locale = $request->getPreferredLanguage(['en', 'es']) ?: config('app.locale');
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }

        return $next($request);
    }
}
