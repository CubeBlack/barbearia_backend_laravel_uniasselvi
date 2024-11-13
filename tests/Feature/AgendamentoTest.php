<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendamentoTest extends TestCase
{
    use RefreshDatabase;

    // Teste para listar todos os agendamentos
    public function test_index()
    {
        Agendamento::factory()->count(3)->create();

        $response = $this->getJson('/api/agendamentos');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    // Teste para criar um agendamento
    public function test_store()
    {
        $cliente = Cliente::factory()->create();

        $data = [
            'cliente_id' => $cliente->id,
            'data' => '2024-11-15',
            'hora_inicio' => '14:00:00',
            'hora_fim' => '15:00:00',
            'status' => 'pendente',
            'observacoes' => 'Observação de teste'
        ];

        $response = $this->postJson('/api/agendamentos', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment($data);
    }

    // Teste para exibir um agendamento específico
    public function test_show()
    {
        $agendamento = Agendamento::factory()->create();

        $response = $this->getJson("/api/agendamentos/{$agendamento->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $agendamento->id,
            'cliente_id' => $agendamento->cliente_id,
        ]);
    }

    // Teste para atualizar um agendamento
    public function test_update()
    {
        $agendamento = Agendamento::factory()->create();

        $data = [
            'status' => 'concluido',
            'observacoes' => 'Atualizado'
        ];

        $response = $this->putJson("/api/agendamentos/{$agendamento->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment($data);
    }

    // Teste para deletar um agendamento
    public function test_destroy()
    {
        $agendamento = Agendamento::factory()->create();

        $response = $this->deleteJson("/api/agendamentos/{$agendamento->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('agendamentos', ['id' => $agendamento->id]);
    }
}
