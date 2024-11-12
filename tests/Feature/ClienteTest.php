<?php
namespace Tests\Feature;

use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    // Testa o método index (listagem de clientes)
    public function test_cliente_index_retorna_lista_de_clientes()
    {
        Cliente::factory()->count(3)->create();

        $response = $this->getJson('/api/clientes');

        $response->assertStatus(200)
                 ->assertJsonCount(3); // Confirma que há 3 clientes na resposta
    }

    // Testa o método store (criação de cliente)
    public function test_cliente_store_cria_novo_cliente()
    {
        $dados = [
            'nome' => 'João Silva',
            'telefone' => '123456789',
            'email' => 'joao@example.com',
            'endereco' => 'Rua Exemplo, 123',
        ];

        $response = $this->postJson('/api/clientes', $dados);

        $response->assertStatus(201)
                 ->assertJsonFragment($dados);

        $this->assertDatabaseHas('clientes', $dados);
    }

    // Testa o método show (exibição de um cliente específico)
    public function test_cliente_show_retorna_cliente_especifico()
    {
        $cliente = Cliente::factory()->create();

        $response = $this->getJson("/api/clientes/{$cliente->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nome' => $cliente->nome,
                     'email' => $cliente->email,
                 ]);
    }

    // Testa o método update (atualização de cliente)
    public function test_cliente_update_atualiza_cliente_existente()
    {
        $cliente = Cliente::factory()->create();

        $dadosAtualizados = [
            'nome' => 'João Atualizado',
            'telefone' => '987654321',
            'email' => 'joao.atualizado@example.com',
            'endereco' => 'Avenida Atualizada, 456',
        ];

        $response = $this->putJson("/api/clientes/{$cliente->id}", $dadosAtualizados);

        $response->assertStatus(200)
                 ->assertJsonFragment($dadosAtualizados);

        $this->assertDatabaseHas('clientes', $dadosAtualizados);
    }

    // Testa o método destroy (exclusão de cliente)
    public function test_cliente_destroy_exclui_cliente_existente()
    {
        $cliente = Cliente::factory()->create();

        $response = $this->deleteJson("/api/clientes/{$cliente->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Cliente excluído com sucesso']);

        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    }
}
