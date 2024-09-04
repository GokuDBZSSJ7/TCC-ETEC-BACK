<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Get the authenticated user.
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * Log the user out and delete all tokens.
     */
    public function logout(Request $request)
    {
        // Verifica se o usuário está autenticado
        $user = Auth::user();
    
        if ($user) {
            // Exclui todos os tokens do usuário
            $user->tokens()->delete();
            
            // Responde com uma mensagem de sucesso
            return response()->json(['message' => 'Logout realizado com sucesso']);
        } else {
            // Retorna um erro se o usuário não estiver autenticado
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }
    }

    /**
     * Refresh the authentication token.
     */
    public function refresh()
    {
        $user = Auth::user();
        $user->tokens()->where('id', '!=', Auth::user()->currentAccessToken()->id)->delete();

        $newToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $newToken,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Validate login request data.
     */
    protected function validateLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Erro de validação', 'errors' => $validator->errors()], 422);
        }
    }
}
