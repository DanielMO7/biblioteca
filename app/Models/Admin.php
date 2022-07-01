<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class Admin extends Model
{
    use HasFactory;

    public static function lista_usuarios()
    {

        $sql = 'SELECT * FROM users WHERE estado = 1';
        $consulta = DB::connection()->select(DB::raw($sql));

        return [
            "status" => 1,
            "msg" => "Lista Usuarios",
            "data" => $consulta
        ];
    }

    public static function eliminar_usuario($id)
    {
        $sql = 'UPDATE users SET estado = 0 WHERE id =' . $id;
        $consulta = DB::connection()->select(DB::raw($sql));

        return [
            "status" => 1,
            "msg" => "Usuario eliminado correctamente",
        ];
    }

    public static function lista_editar($id)
    {
        $sql = 'SELECT * FROM users WHERE id =' . $id;
        $consulta = DB::connection()->select(DB::raw($sql));

        return [
            "status" => 1,
            "msg" => "Lista Usuarios",
            "data" => $consulta
        ];
    }

    public static function editar_usuario($request)
    {
        $sql = 'SELECT * FROM users WHERE id =' . $request->id;
        $objeto_consulta = DB::select($sql);

        return [
            "status" => 1,
            "msg" => "Datos del usuario!",
            "data" => $objeto_consulta
        ];
    }

    public static function actualizar_tabla($request){
        
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

            return "Cambios realizados correctamente";

        } catch (Throwable $e) {
            return "Error en database". $e;
        }
    }
}
