<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddHeadMetaTags
{
    /**
     * Ajouter les meta tags viewport et theme-color au dashboard Filament
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Ajouter les meta tags si la réponse est HTML
        if ($response->headers->get('content-type') && str_contains($response->headers->get('content-type'), 'text/html')) {
            $metaTags = <<<'HTML'
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1f242d">
    <style>
        /* Fix safe-area-inset pour notch/status bar */
        body {
            padding-top: env(safe-area-inset-top);
        }
        [class*="fi-header"],
        [class*="fi-page"] {
            padding-top: max(env(safe-area-inset-top), 0.5rem);
        }
    </style>
</head>
HTML;

            $content = $response->getContent();
            $content = str_replace('</head>', $metaTags, $content);
            $response->setContent($content);
        }

        return $response;
    }
}
