<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index_admin(){
        return response()->json([
            "status" => 1,
            "msg" => "Binvenido Administrador.",
            "data" => auth()->user()
        ]);
    }

    public function lista_usuarios(){
        $objeto_consulta = Admin::lista_usuarios();

        return response()->json([
            "data" => $objeto_consulta
        ]);
    }

    public function eliminar_usuario(Request $request)
    {
        $objeto_consulta = Admin::eliminar_usuario($request->id);

        return response()->json([
            "data" => $objeto_consulta
        ]);
        
    }

    public function lista_editar(Request $request)
    {
        $objeto_consulta = Admin::lista_editar($request->id);

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
        $objeto_consulta = Admin::actualizar_tabla($request);

        return response()->json([
            "data" => $objeto_consulta
        ]);
    }
}
