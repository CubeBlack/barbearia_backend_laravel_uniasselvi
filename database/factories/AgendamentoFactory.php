<?php

namespace Database\Factories;

use App\Models\Agendamento;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendamentoFactory extends Factory
{
    protected $model = Agendamento::class;

    public function definition()
    {
        return [
            'cliente_id' => Cliente::factory(),  // Gera um cliente automaticamente para o agendamento
            'data' => $this->faker->date(),
            'hora_inicio' => $this->faker->time(),
            'hora_fim' => $this->faker->optional()->time(),
            'status' => $this->faker->randomElement(['pendente', 'concluido', 'cancelado']),
            'observacoes' => $this->faker->sentence(),
        ];
    }
}
