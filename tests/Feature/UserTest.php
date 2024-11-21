<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o índice de usuários.
     */
    public function test_index_returns_all_users()
    {
        // Cria alguns usuários
        User::factory()->count(3)->create();

        // Faz uma requisição GET para /users
        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonCount(3); // Verifica se retornou 3 usuários
    }

    /**
     * Testa a criação de um usuário.
     */
    public function test_store_creates_a_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123', // A senha será hashada
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(201) // Código HTTP de criação
                 ->assertJsonFragment([
                     'name' => 'John Doe',
                     'email' => 'john.doe@example.com',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
        ]);
    }

    /**
     * Testa a exibição de um usuário.
     */
    public function test_show_returns_a_specific_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $user->id,
                     'name' => $user->name,
                     'email' => $user->email,
                 ]);
    }

    /**
     * Testa a atualização de um usuário.
     */
    public function test_update_modifies_a_user()
    {
        $user = User::factory()->create();

        $updatedData = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('users', $updatedData);
    }

    /**
     * Testa a exclusão de um usuário.
     */
    public function test_destroy_deletes_a_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204); // Sem conteúdo após exclusão

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
