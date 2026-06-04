<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_endpoint_refuse_les_visiteurs_non_authentifies(): void
    {
        // Un message existe : on ne doit RIEN en révéler à un non-authentifié.
        Message::create([
            'name'    => 'Visiteur',
            'email'   => 'visiteur@example.com',
            'subject' => 'Privé',
            'message' => 'Contenu confidentiel du visiteur.',
        ]);

        $response = $this->getJson('/api/check-messages');

        $response->assertStatus(401);
        $response->assertJsonMissingPath('lastMessage');
        $response->assertDontSee('visiteur@example.com');
    }

    public function test_endpoint_repond_a_un_admin_authentifie(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->getJson('/api/check-messages');

        $response->assertOk();
        $response->assertJsonStructure(['hasNewMessages', 'unreadCount', 'lastMessage']);
    }
}
