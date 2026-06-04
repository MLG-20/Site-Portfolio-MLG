<?php

namespace Tests\Unit;

use App\Rules\NotSpam;
use PHPUnit\Framework\TestCase;

class NotSpamRuleTest extends TestCase
{
    /** Exécute la règle et renvoie le message d'erreur, ou null si validé. */
    private function check(string $value): ?string
    {
        $error = null;
        (new NotSpam)->validate('message', $value, function ($message) use (&$error) {
            $error = $message;
        });

        return $error;
    }

    public function test_un_message_normal_passe(): void
    {
        $this->assertNull($this->check(
            "Bonjour, je suis intéressé par vos services pour créer un site vitrine. Pouvez-vous me rappeler ?"
        ));
    }

    public function test_un_message_avec_un_seul_lien_passe(): void
    {
        $this->assertNull($this->check(
            "Voici mon profil LinkedIn https://linkedin.com/in/awa, dites-moi si ça vous intéresse."
        ));
    }

    public function test_balise_bbcode_bloquee(): void
    {
        $this->assertNotNull($this->check('[url=http://spam.example]click[/url]'));
    }

    public function test_balise_html_lien_bloquee(): void
    {
        $this->assertNotNull($this->check('Check <a href="http://spam.example">here</a>'));
    }

    public function test_deux_liens_bloques(): void
    {
        $this->assertNotNull($this->check('http://a.example et http://b.example'));
    }

    public function test_accumulation_mots_cles_bloquee(): void
    {
        $this->assertNotNull($this->check('Cheap SEO backlink service to rank higher.'));
    }

    public function test_un_seul_mot_cle_passe(): void
    {
        // « marketing » seul ne doit pas suffire (évite les faux positifs).
        $this->assertNull($this->check(
            "Je travaille dans le marketing et je cherche un développeur freelance."
        ));
    }
}
