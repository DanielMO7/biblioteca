<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserController extends Controller
{
    /**
     * Valida que los datos ingresados por el usuario sean correctos, Crea el Usuario,
     * Hashea la contraseña y la almacena en la base de datos.
     *
     * @param Request $request | Trae todo los datos enviados por el usuario.
     * @return json | Respuesta de la funcion en Json.
     */
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


    /**
     * Trae los datos que se encuentran almacenados en el auth del usuario.
     *
     * @return json | Respuesta de la funcion en json.
     */
    public function perfil_usuario()
    {
        return response()->json([
            "status" => 0,
            "msg" => "Acerca del perfil del usuario",
            "data" => auth()->user()
        ]);
    }


    /**
     * Invoca la Funcion editar usuario y le envia como parametros el id que se encuentra en
     * el Request.
     *
     * @param Request $request
     * @return json | Array con los elementos almacenados del usuario.
     */
    public function editar_usuario(Request $request)
    {
        $objeto_consulta = User::editar_usuario($request->id);

        return response()->json([
            "data" => $objeto_consulta
        ]);
    }


    /**
     * Valida que los datos ingresados cumplan las condiciones y se Invoca la funcion que 
     * actualizar tabla se le envian como parametros los elementos para actualizar las 
     * base de datos.
     *
     * @param Request $request
     * @return json | Respuesta de la funcion en Json.
     */
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


    /**
     * Se verifica que todos los campos esten completos y se envian los parametros 
     * necesarios a la funcion verificar cambiar contraseña.
     *
     * @param Request $request
     * @return json | Respuesta de la funcion en Json.
     */
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
