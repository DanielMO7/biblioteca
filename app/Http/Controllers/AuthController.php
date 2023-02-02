<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function ingresar(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where("email", "=", $request->email)->first();

        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken("auth_token")->plainTextToken;

                return response()->json([
                    "status" => 1,
                    "msg" => "Usuario logueado exitosamente.",
                    "access_token"  => $token,
                    "rol" => $user->rol
                ], 200);
            } else {
                // 401 No Autorizado
                return response()->json([
                    "status" => 0,
                    "msg" => "La contraseña es incorrecta!",
                ], 401);
            }
        } else {
            // 403 El acceso a ese recurso está prohibido
            return response()->json([
                "status" => 2,
                "msg" => "Usuario no registrado!",
            ], 403);
        }
    }
    public function cerrar_sesion(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "status" => 1,
            "msg" => "Cierre de sesion"
        ], 200);
    }
}
