<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckServiceSuspension
{
    /**
     * Paths that remain accessible even when the system is suspended.
     *
     * @var array<int, string>
     */
    protected array $except = [
        'skillance-control*',
        'dokumen*',
        'build*',
        'css*',
        'js*',
        'images*',
        'storage*',
        'favicon.ico',
        'robots.txt',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isServiceSuspended()) {
            return $next($request);
        }

        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => false,
                'code' => 'SERVICE_SUSPENDED',
                'message' => 'Layanan SIMS ditangguhkan sementara dikarenakan tagihan operasional melewati batas jatuh tempo. Silakan hubungi pengelola sistem.',
            ], 503);
        }

        return response()->view('billing.suspended', [
            'invoiceNumber' => 'INV-2026/XI-SIMS/001',
            'contractNumber' => '01/SPK-SIMS/SMPN2/2026',
            'dueDate' => '05 November 2026',
            'bankName' => 'SeaBank',
            'accountNumber' => '901376460015',
            'accountName' => 'Muh Arsyad Ramsi',
            'nominal' => 'Rp 400.000,-',
            'whatsapp' => '082291700778',
            'whatsappUrl' => 'https://wa.me/6282291700778?text='.urlencode('Halo Pengembang SIMS (Skillance Cloud), kami dari SMPN 2 Tanggetada ingin melakukan konfirmasi penyelesaian pembayaran tagihan operasional sistem (INV-2026/XI-SIMS/001).'),
            'invoiceUrl' => url('/dokumen/invoice-smp2-tanggetada.html'),
        ], 503);
    }

    /**
     * Check if service is currently suspended due to overdue billing.
     */
    protected function isServiceSuspended(): bool
    {
        if (config('app.billing_suspended', false)) {
            return true;
        }

        try {
            return Setting::query()->where('key', 'billing_suspended')->value('value') === '1';
        } catch (\Throwable $e) {
            return false;
        }
    }
}
