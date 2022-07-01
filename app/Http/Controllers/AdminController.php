<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Muestra la pagina princial del Perfil Administrador con sus datos un JSON
     *
     * @return json | Trae todos los datos del usuario.
     */
    public function index_admin(){
        return response()->json([
            "status" => 1,
            "msg" => "Binvenido Administrador.",
            "data" => auth()->user()
        ]);
    }


    /**
     * Invoca la Funcion de lista de usuarios del Administrador que trae todos los
     * datos de los usuarios registrados en el sistema.
     *
     * @return json | Trae los datos de todos los usuarios.
     */
    public function lista_usuarios(){
        $objeto_consulta = Admin::lista_usuarios();

        return response()->json([
            "data" => $objeto_consulta
        ]);
    }


    /**
     * Invoca la Funcion eliminar usuario y le pasa como parametros el id que se envia
     * por el metodo Request.
     *
     * @param Request $request | Se toma el id de la URL
     * @return json | Respuesta de la funcion en json. 
     */
    public function eliminar_usuario(Request $request)
    {
        $objeto_consulta = Admin::eliminar_usuario($request->id);

        return response()->json([
            "data" => $objeto_consulta
        ]);
        
    }


    /**
     * Invoca la Funcion lista editar le pasa como parametros el id que se envia
     * por el metodo Request.
     *
     * @param Request $request | Se toma el id de la URL
     * @return json | Con un array que contiene los datos del usuario que se van a editar.
     */
    public function lista_editar(Request $request)
    {
        $objeto_consulta = Admin::lista_editar($request->id);

        return response()->json([
            "data" => $objeto_consulta
        ]);
    }


    /**
     * Se validan los datos que se encuentran en el Request, Invoca la funcion actualizar tabla
     * y se le envian los parametros en el que se encuentran en el metodo Requets.
     *
     * @param Request $request  
     * @return json | Respuesta de la funcion en json.
     */
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
