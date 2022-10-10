<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function ingresar(Request $request)
    {
        // Validar
        $credenciales = $request->validate([
           "email" => "required|email",
           "password" => "required"
        ]);

        // Verificar
        if (!Auth::attempt($credenciales)) {
            return response()->json([
                "mensaje" => "No Autorizado",
                "error" => true
            ], 401);
        }

        // Generar Token Sanctum
        $user = $request->user();
        $tokenResult = $user->createToken('Token Personal');
        $token = $tokenResult->plainTextToken;

        // Responder
        return response()->json([
           "access_token" => $token,
           "token_type" => "Bearer",
           "usuario" => $user
        ]);
    }

    public function registrar(Request $request)
    {
        // validar
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required"
        ]);

        // Crear nuevo usuario
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        // Respuesta
        if ($user->save()) {
            return response()->json([
                "mensaje" => "Usuario Registrado",
                "error" => false
            ], 201);
        } else {
            return response()->json([
               "mensaje" => "Error al registrar el usuario",
               "error" => true
            ], 422);
        }
    }

    public function perfil()
    {
        return Auth::user();
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(["mensaje" => "Log Out"]);
    }
}
