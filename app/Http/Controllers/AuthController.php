<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');
    $user = \App\Models\User::where('email', $credentials['email'])->first();

    if (!$user) {
        return response()->json(['message' => 'Email não encontrado'], 404);
    }

    if (!Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Senha incorreta'], 401);
    }

    if (Auth::attempt($credentials)) {
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    return response()->json(['message' => 'Erro de autenticação desconhecido'], 401);
}


    public function me()
    {
        return response()->json(Auth::user());
    }

    public function logout()
    {
        $user = Auth::user();
        
        $user->tokens()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function refresh(Request $request)
    {
        $user = Auth::user();
        
        $request->user()->currentAccessToken()->delete();
        
        $newToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $newToken,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

}