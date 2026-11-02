<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and attach robust security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Cegah serangan Clickjacking (tidak bisa di-embed di iframe situs berbahaya)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Cegah MIME-type sniffing (mencegah file gambar disusupi skrip berbahaya)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Proteksi Cross-Site Scripting (XSS) untuk browser modern
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Batasi kebocoran URL asal referer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Batasi izin sensor & hardware (izinkan kamera hanya untuk scan QR presensi)
        $response->headers->set('Permissions-Policy', 'camera=(self), microphone=(), geolocation=(self)');

        return $response;
    }
}
