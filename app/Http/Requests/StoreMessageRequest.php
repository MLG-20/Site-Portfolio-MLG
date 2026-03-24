<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email'],
            'phone'   => ['nullable', 'regex:/^[0-9\s\+\-\(\)]{7,20}$/'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'website' => ['prohibited'],  // honeypot — doit être vide (les bots le remplissent)
        ];
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
        ];
    }
}
