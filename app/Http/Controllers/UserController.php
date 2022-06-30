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

    public function editar_usuario(Request $request, $id)
    {
        $sql = 'SELECT * FROM users WHERE id =' . $id;
        $objeto_consulta = DB::select($sql);


        return response()->json([
            "data" => $objeto_consulta
        ]);
    }

    public function actualizar_tabla(Request $request)
    {
        if (auth('sanctum')->user()->id != $request->id) {
            return 'No autorizado.';
        }

        // Query que toma valor segun condiciones especificas.
        $sql = 'SELECT id FROM users WHERE email= ?';
        //Trae el id del email que sea igual al que desea actualizar el usuario.
        $consulta = DB::connection()->select(DB::raw($sql), [$request->email]);

        $emailExistencia = $consulta;

        /**
         * Valida si encontro algo, si es asi compara el id de ese email con el id que tiene el usuario.
         * Si el id del usuario es diferente al id que tiene ese email retorna retorna un string que dice
         * que el email ya esta en uso.
         */
        if (count($emailExistencia) > 0) {
            if ($emailExistencia[0]->id != $request->id) {
                return 'email_existente';
            }
        }

        // Trae el id del email que sea igual al que desea actualizar el usuario.

        $sql_consulta2 = 'SELECT id FROM users WHERE documento= ?';
        $consulta2 = DB::connection()->select(DB::raw($sql_consulta2), [
            $request->documento
        ]);
        $documentoExistencia = $consulta2;

        /**
         * Valida si encontro algo, si es asi compara el id de ese documento con el id que tiene el usuario.
         * Si el id del usuario es diferente al id que tiene ese documento retorna un string que dice que el
         * documento ya esta en uso.
         */
        if (count($documentoExistencia) > 0) {
            if ($documentoExistencia[0]->id != $request->id) {
                return 'documento_existente';
            }
        }
        /**
         * Valida las diferentes condiciones que se den para actualizar la query, enviara la consulta sql
         * correcta del dato que desea cambiar el usuario.
         *
         * Si el usuario solo desea cambiar su nombre, se actualizara la tabla con la query especifica que
         * realizara esa accion.
         */

        try {
            $sql = 'UPDATE users SET users.name = ?, email = ? , documento = ? WHERE id = ?';
            $sentencia = DB::connection()->select(DB::raw($sql), [
                $request->name,
                $request->email,
                $request->documento,
                $request->id,
            ]);

            return response()->json([
                "respuesta" => "Cambios realizados correctamente"
            ]);
        } catch (Throwable $e) {
            return response()->json([
                "respuesta" => "Error en database",
                "error" => $e
            ]);
        }
    }

    public function verficiar_cambiar_contrasena(Request $request)
    {
        if (auth('sanctum')->user()->id != $request->id) {
            return 'No autorizado.';
        }
        if ($request->contrasena_nueva == $request->contrasena_verificar) {
            $sql = "SELECT * FROM users WHERE id = ?";
            $sentencia = DB::connection()->select(DB::raw($sql), [
                $request->id,
            ]);
            try{
                foreach ($sentencia as $usuario) {
                    if (Hash::check($request->contrasena_anterior, $usuario->password)) {
                        $sql = "UPDATE users SET users.password = ? WHERE id = ?";
                        $contrasena_has = Hash::make($request->contrasena_nueva);
                        $sentencia = DB::connection()->select(DB::raw($sql), [
                            $contrasena_has,
                            $request->id,
                        ]);
                        return response()->json([
                            "status" => 1,
                            "msg" => "Contraseña actualizada correctamente!",
                        ]);
                    } else {
                        return response()->json([
                            "status" => 0,
                            "msg" => "Contraseña incorrecta!",
                        ]);
                    }
                }
            }catch(Throwable $e){
                return response()->json([
                    "status" => 0,
                    "msg" => "Error en database!",
                ]);
            }
        } else {
            return response()->json([
                "status" => 0,
                "msg" => "Las contraseñas no son iguales",
            ]);
        }
    }
}
