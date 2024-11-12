<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/clientes",
 *     summary="Listar todos os clientes",
 *     description="Retorna uma lista de todos os clientes cadastrados.",
 *     operationId="getClientes",
 *     tags={"Clientes"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de clientes",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="nome", type="string"),
 *                 @OA\Property(property="telefone", type="string"),
 *                 @OA\Property(property="email", type="string"),
 *                 @OA\Property(property="endereco", type="string")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro no servidor"
 *     )
 * )
 */
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes, 200);
    }

    // Método para mostrar um cliente específico
    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        return response()->json($cliente, 200);
    }

/**
 * @OA\Post(
 *     path="/api/clientes",
 *     summary="Criar um novo cliente",
 *     description="Cria um novo cliente com os dados fornecidos.",
 *     operationId="storeCliente",
 *     tags={"Clientes"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(property="nome", type="string"),
 *                 @OA\Property(property="telefone", type="string"),
 *                 @OA\Property(property="email", type="string"),
 *                 @OA\Property(property="endereco", type="string")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Cliente criado com sucesso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="nome", type="string"),
 *             @OA\Property(property="telefone", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="endereco", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Requisição inválida"
 *     )
 * )
 */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:clientes,email',
            'endereco' => 'nullable|string|max:255',
        ]);

        $cliente = Cliente::create($validatedData);

        return response()->json($cliente, 201);
    }

    // Método para atualizar um cliente específico
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        $validatedData = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'sometimes|required|email|unique:clientes,email,' . $id,
            'endereco' => 'nullable|string|max:255',
        ]);

        $cliente->update($validatedData);

        return response()->json($cliente, 200);
    }

    // Método para excluir um cliente específico
    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        $cliente->delete();

        return response()->json(['message' => 'Cliente excluído com sucesso'], 200);
    }
}
