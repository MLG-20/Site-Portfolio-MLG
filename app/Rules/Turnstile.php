<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Vérifie le jeton Cloudflare Turnstile.
 *
 * Si aucune clé secrète n'est configurée (dev/local), la règle laisse
 * passer pour ne pas bloquer le formulaire.
 */
class Turnstile implements ValidationRule
{
    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.turnstile.secret');

        // Turnstile non configuré → on ne bloque pas.
        if (empty($secret)) {
            return;
        }

        if (empty($value) || ! is_string($value)) {
            $fail('Veuillez confirmer que vous n\'êtes pas un robot.');
            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post(self::VERIFY_URL, [
                    'secret'   => $secret,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

            if (! $response->successful() || ! ($response->json('success') === true)) {
                $fail('La vérification anti-robot a échoué. Veuillez réessayer.');
            }
        } catch (\Throwable $e) {
            // En cas d'indisponibilité de Cloudflare, on logue mais on laisse passer
            // pour ne pas pénaliser un vrai visiteur.
            Log::warning('Turnstile injoignable : ' . $e->getMessage());
        }
    }
}
