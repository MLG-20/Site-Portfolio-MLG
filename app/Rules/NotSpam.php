<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Détecte le spam publicitaire typique des bots de formulaire :
 * trop de liens, balises de lien BBCode/HTML, ou accumulation de
 * mots-clés caractéristiques (SEO, crypto, advertising, etc.).
 */
class NotSpam implements ValidationRule
{
    /** Nombre d'URLs à partir duquel on considère le texte comme spam. */
    private const MAX_URLS = 2;

    /** Mots-clés de spam : 2 occurrences ou plus = rejet. */
    private const SPAM_KEYWORDS = [
        'seo', 'backlink', 'back link', 'ranking', 'advertising', 'crypto',
        'bitcoin', 'forex', 'casino', 'viagra', 'cialis', 'loan', 'payday',
        'cheap', 'discount', 'pharmacy', 'escort', 'porn', 'sex', 'guest post',
        'web traffic', 'increase traffic', 'rank higher', 'first page of google',
        'list of websites', 'b2b', 'leads', 'marketing services', 'click here',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            return;
        }

        $text = mb_strtolower($value);

        // 1. Balises de lien BBCode / HTML (signature quasi certaine de bot).
        if (preg_match('/\[(url|link)[=\]]/i', $value) || preg_match('/<a\s+href/i', $value)) {
            $fail('Votre message a été détecté comme spam.');
            return;
        }

        // 2. Trop d'URLs.
        $urlCount = preg_match_all('#https?://|www\.#i', $value);
        if ($urlCount >= self::MAX_URLS) {
            $fail('Votre message contient trop de liens.');
            return;
        }

        // 3. Accumulation de mots-clés de spam.
        $hits = 0;
        foreach (self::SPAM_KEYWORDS as $keyword) {
            if (str_contains($text, $keyword)) {
                $hits++;
            }
        }
        if ($hits >= 2) {
            $fail('Votre message a été détecté comme spam.');
        }
    }
}
