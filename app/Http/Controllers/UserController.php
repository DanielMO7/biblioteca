<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserController extends Controller
{
    public function insertar(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'documento' => 'required|integer|unique:users',
            'rol' => 'required',
            'password' => 'required'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->documento = $request->documento;
        $user->rol = $request->rol;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            "status" => 1,
            "msg" => "Registro de usuario exitoso!",
        ]);
    }
    public function perfil_usuario()
    {
        return response()->json([
            "status" => 0,
            "msg" => "Acerca del perfil del usuario",
            "data" => auth()->user()
        ]);
    }

    public function editar_usuario(Request $request)
    {
        $objeto_consulta = User::editar_usuario($request->id);

        return response()->json([
            "data" => $objeto_consulta
        ]);
    }

    public function actualizar_tabla(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'documento' => 'required|integer',
        ]);

        $objeto_consulta = User::actualizar_tabla($request);
        
        return response()->json([
            "data" => $objeto_consulta
        ]);
    }

    public function verficiar_cambiar_contrasena(Request $request)
    {
        $request->validate([
            'contrasena_nueva' => 'required',
            'contrasena_verificar' => 'required',
            'contrasena_anterior' => 'required',
        ]);
        $objeto_consulta = User::verficiar_cambiar_contrasena($request);
        
        return response()->json([
            "data" => $objeto_consulta
        ]);
    }
}
