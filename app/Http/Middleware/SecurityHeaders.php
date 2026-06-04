<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // HSTS — force le HTTPS côté navigateur (appliqué à toutes les réponses,
        // y compris /admin). Le site est déjà servi exclusivement en HTTPS.
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        if ($request->is('admin*')) {
            return $response;
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content-Security-Policy (site public uniquement — /admin est exclu plus haut).
        // 'unsafe-inline' requis : scripts/styles inline présents dans les vues.
        // Origines externes autorisées : Turnstile, Google Fonts/Analytics, FontAwesome (cdnjs),
        // jsDelivr, unpkg (scrollreveal). object-src 'self' pour l'aperçu PDF des certificats.
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://challenges.cloudflare.com https://www.googletagmanager.com https://unpkg.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: https:",
            "connect-src 'self' https://challenges.cloudflare.com https://www.googletagmanager.com https://www.google-analytics.com https://region1.google-analytics.com",
            "frame-src https://challenges.cloudflare.com",
            "object-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
