<?php

namespace App\Http\Requests;

use App\Rules\NotSpam;
use App\Rules\Turnstile;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class StoreMessageRequest extends FormRequest
{
    /** Délai minimum (secondes) entre l'affichage du formulaire et l'envoi. */
    private const MIN_FILL_SECONDS = 3;

    /** Durée de validité du jeton temporel (secondes). */
    private const MAX_FILL_SECONDS = 7200; // 2h

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:100', new NotSpam],
            'email'   => ['required', 'email'],
            'phone'   => ['nullable', 'regex:/^[0-9\s\+\-\(\)]{7,20}$/'],
            'subject' => ['required', 'string', 'max:200', new NotSpam],
            'message' => ['required', 'string', 'min:10', 'max:2000', new NotSpam],

            // Honeypots — doivent rester vides (les bots les remplissent).
            'website' => ['prohibited'],
            'url'     => ['prohibited'],
        ];
    }

    /**
     * Contrôles anti-spam exécutés systématiquement (même si le champ est
     * absent de la requête, contrairement aux règles classiques) :
     *  - time-trap : un humain met plus de quelques secondes à remplir le
     *    formulaire, un bot le soumet quasi instantanément ;
     *  - Turnstile : captcha Cloudflare (ignoré si non configuré).
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $this->validateTimeTrap($validator);

            (new Turnstile)->validate(
                'cf-turnstile-response',
                $this->input('cf-turnstile-response'),
                fn ($message) => $validator->errors()->add('cf-turnstile-response', $message)
            );
        });
    }

    private function validateTimeTrap(Validator $validator): void
    {
        $token = $this->input('form_ts');

        if (empty($token)) {
            $validator->errors()->add('form_ts', 'Formulaire invalide. Veuillez recharger la page.');
            return;
        }

        try {
            $renderedAt = (int) Crypt::decryptString($token);
        } catch (\Throwable $e) {
            $validator->errors()->add('form_ts', 'Formulaire invalide. Veuillez recharger la page.');
            return;
        }

        $elapsed = time() - $renderedAt;

        if ($elapsed < self::MIN_FILL_SECONDS || $elapsed > self::MAX_FILL_SECONDS) {
            $validator->errors()->add('form_ts', 'Formulaire invalide. Veuillez recharger la page.');
        }
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Votre nom est requis.',
            'email.required'   => 'Votre email est requis.',
            'email.email'      => 'Veuillez entrer un email valide.',
            'phone.regex'      => 'Le numéro de téléphone n\'est pas valide.',
            'subject.required' => 'Le sujet est requis.',
            'message.required' => 'Le message est requis.',
            'message.min'      => 'Le message doit contenir au moins 10 caractères.',
            'message.max'      => 'Le message ne peut pas dépasser 2000 caractères.',
            'website.prohibited' => 'Formulaire invalide.',
            'url.prohibited'     => 'Formulaire invalide.',
        ];
    }

    /**
     * On ne stocke que les champs réels du message (pas les champs anti-spam).
     */
    public function validated($key = null, $default = null): array
    {
        return collect(parent::validated())
            ->only(['name', 'email', 'phone', 'subject', 'message'])
            ->all();
    }
}
