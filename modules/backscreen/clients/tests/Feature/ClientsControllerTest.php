<?php

namespace Backscreen\Clients\Tests\Feature;

use Backscreen\Clients\Models\Client;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ClientsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test listing all clients (index method).
     */
    public function test_can_list_clients()
    {
        Client::factory()->count(5)->create();

        $response = $this->getJson('/api/clients');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    /**
     * Test creating a client (store method).
     */
    public function test_can_create_client()
    {
        $clientData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
        ];

        $response = $this->postJson('/api/clients', $clientData);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                         'name' => $clientData['name'],
                         'email' => $clientData['email'],
                         'phone' => $clientData['phone'],
                     ]
                 ]);

        $this->assertDatabaseHas('clients', $clientData);
    }

    /**
     * Test retrieving a specific client (show method).
     */
    public function test_can_show_client()
    {
        $client = Client::factory()->create();

        $response = $this->getJson("/api/clients/{$client->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $client->id,
                         'name' => $client->name,
                         'email' => $client->email,
                     ]
                 ]);
    }

    /**
     * Test updating a client (update method).
     */
    public function test_can_update_client()
    {
        $client = Client::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updatedemail@example.com',
            'phone' => '123-456-7890',
        ];

        $response = $this->putJson("/api/clients/{$client->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'name' => 'Updated Name',
                         'email' => 'updatedemail@example.com',
                         'phone' => '123-456-7890',
                     ]
                 ]);

        $this->assertDatabaseHas('clients', $updateData);
    }

    /**
     * Test deleting a client (destroy method).
     */
    public function test_can_delete_client()
    {
        $client = Client::factory()->create();

        $response = $this->deleteJson("/api/clients/{$client->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}
