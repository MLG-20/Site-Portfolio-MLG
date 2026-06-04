<?php

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class ContactFormSpamTest extends TestCase
{
    use RefreshDatabase;

    /** Charge utile valide de base ; le timestamp est posé 5s dans le passé. */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name'    => 'Awa Diop',
            'email'   => 'awa@example.com',
            'phone'   => '771234567',
            'subject' => 'Demande de devis',
            'message' => 'Bonjour, je souhaiterais discuter d\'un projet web avec vous.',
            'form_ts' => Crypt::encryptString((string) (time() - 5)),
        ], $overrides);
    }

    public function test_un_message_legitime_est_enregistre(): void
    {
        $response = $this->post('/contact', $this->payload());

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('messages', 1);
    }

    public function test_le_honeypot_bloque_le_spam(): void
    {
        $response = $this->post('/contact', $this->payload(['website' => 'http://spam.example']));

        $response->assertSessionHasErrors('website');
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_le_second_honeypot_bloque_le_spam(): void
    {
        $response = $this->post('/contact', $this->payload(['url' => 'http://spam.example']));

        $response->assertSessionHasErrors('url');
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_une_soumission_instantanee_est_bloquee(): void
    {
        $response = $this->post('/contact', $this->payload([
            'form_ts' => Crypt::encryptString((string) time()),
        ]));

        $response->assertSessionHasErrors('form_ts');
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_un_jeton_temporel_absent_est_bloque(): void
    {
        $payload = $this->payload();
        unset($payload['form_ts']);

        $response = $this->post('/contact', $payload);

        $response->assertSessionHasErrors('form_ts');
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_un_message_avec_balise_bbcode_est_bloque(): void
    {
        $response = $this->post('/contact', $this->payload([
            'message' => 'Great site! [url=http://spam.example]click here[/url] best deal ever.',
        ]));

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_un_message_avec_trop_de_liens_est_bloque(): void
    {
        $response = $this->post('/contact', $this->payload([
            'message' => 'Visit http://a.example and http://b.example for more info please.',
        ]));

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_un_message_bourre_de_mots_cles_spam_est_bloque(): void
    {
        $response = $this->post('/contact', $this->payload([
            'subject' => 'Cheap SEO backlink advertising service',
            'message' => 'We offer cheap SEO backlink advertising to rank higher on google.',
        ]));

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_turnstile_ignore_si_non_configure(): void
    {
        // Aucune clé Turnstile en test → le captcha ne doit pas bloquer.
        config(['services.turnstile.secret' => null]);

        $response = $this->post('/contact', $this->payload());

        $response->assertSessionHas('success');
    }
}
