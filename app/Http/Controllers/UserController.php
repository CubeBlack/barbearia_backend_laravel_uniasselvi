<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        
        $users = User::all();

        return response()->json($users, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Armazena um novo usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Valida os dados de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Cria o usuário
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Hasheia a senha
        ]);

        // Retorna o usuário criado com status 201
        return response()->json([
            'message' => 'User created successfully.',
            'data' => $user,
        ], 201);
    }

    /**
     * Retorna um usuário específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Tenta encontrar o usuário pelo ID
        $user = User::find($id);

        // Se o usuário não for encontrado, retorna um erro 404
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        // Retorna os dados do usuário
        return response()->json([
            'data' => $user,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Atualiza os dados de um usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Busca o usuário pelo ID
        $user = User::find($id);

        // Verifica se o usuário existe
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        // Valida os dados enviados
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        // Atualiza os campos permitidos
        if (isset($validatedData['name'])) {
            $user->name = $validatedData['name'];
        }

        if (isset($validatedData['email'])) {
            $user->email = $validatedData['email'];
        }

        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        // Retorna o usuário atualizado
        return response()->json([
            'message' => 'User updated successfully.',
            'data' => $user,
        ], 200);
    }

    /**
     * Remove um usuário.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Busca o usuário pelo ID
        $user = User::find($id);

        // Verifica se o usuário existe
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        // Exclui o usuário
        $user->delete();

        // Retorna uma mensagem de sucesso
        return response()->json([
            'message' => 'User deleted successfully.',
        ], 200);
    }
}
