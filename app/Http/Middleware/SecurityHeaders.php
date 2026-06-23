<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // Generar nonce criptografico para CSP (evita unsafe-inline)
        $nonce = base64_encode(random_bytes(16));
        app()->instance('csp-nonce', $nonce);

        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Server', 'webserver');

        $isLogin   = $request->routeIs('login.custom', 'login.api');
        $apiHost   = config('services.autentificatic.host', 'autentificaticapi.carabineros.cl');

        $connectSrc = $isLogin
            ? "'self' http://{$apiHost} https://{$apiHost}"
            : "'self' https://{$apiHost}";

        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'nonce-{$nonce}'; " .
            "style-src 'self' 'unsafe-inline'; " .
            "font-src 'self' data:; " .
            "img-src 'self' data:; " .
            "connect-src {$connectSrc}; " .
            "form-action 'self'; " .
            "frame-ancestors 'none';"
        );

        return $response;
    }
}
