<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    /**
     * Handle an incoming request.
     * Check if the specific PRO feature is enabled in system settings.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        $setting = Setting::where('key', $featureKey)->first();

        // Jika fitur dinonaktifkan atau belum diset, kembalikan 404 (Not Found)
        // agar rute tidak terdeteksi oleh pihak luar / bot scanner
        if (! $setting || $setting->value !== '1') {
            abort(404, 'Fitur belum diaktifkan.');
        }

        return $next($request);
    }
}
