<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;

class AgendamentoController extends Controller
{
    // Listar todos os agendamentos
    public function index()
    {
        return Agendamento::all();
    }

    // Criar um novo agendamento
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'data' => 'required|date',
            'hora_inicio' => 'required',
            'status' => 'nullable|string',
            'observacoes' => 'nullable|string'
        ]);

        $agendamento = Agendamento::create($request->all());

        return response()->json($agendamento, 201);
    }

    // Exibir um agendamento específico
    public function show($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        return response()->json($agendamento);
    }

    // Atualizar um agendamento
    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id' => 'exists:clientes,id',
            'data' => 'date',
            'hora_inicio' => 'nullable',
            'hora_fim' => 'nullable',
            'status' => 'nullable|string',
            'observacoes' => 'nullable|string'
        ]);

        $agendamento = Agendamento::findOrFail($id);
        $agendamento->update($request->all());

        return response()->json($agendamento);
    }

    // Deletar um agendamento
    public function destroy($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->delete();

        return response()->json(null, 204);
    }
}
