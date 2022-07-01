<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class Admin extends Model
{
    use HasFactory;


    /**
     * Selecciona de la tabla users todos los elementos que su valor en estado sea igual a 1
     *
     * @return json | Retorna un json con un status un msg y una data con todos los datos 
     * consultado.
     */
    public static function lista_usuarios()
    {
        try {
            $sql = 'SELECT * FROM users WHERE estado = 1';
            $consulta = DB::connection()->select(DB::raw($sql));

            return [
                "status" => 1,
                "msg" => "Lista Usuarios",
                "data" => $consulta
            ];
        } catch (Throwable $e) {
            return "Error en database" . $e;
        }
    }


    /**
     * Cambia de la tabla users el estado de la fila cuyo id sea igual al id que recibe como
     * parametro.
     *
     * @param int $id
     * @return json | Mensaje que especifica si fue correcto el proceso.
     * @return catch | Error en la base de datos.
     */
    public static function eliminar_usuario($id)
    {
        try {
            $sql = 'UPDATE users SET estado = 0 WHERE id =' . $id;
            $consulta = DB::connection()->select(DB::raw($sql));
    
            return [
                "status" => 1,
                "msg" => "Usuario eliminado correctamente",
            ];
        } catch (Throwable $e) {
            return "Error en database" . $e;
        }
    }
    

    /**
     * Selecciona de la tabla users los datos de la fila cuyo a id sea igual al id recibido como pametro.
     * 
     *
     * @param int $id
     * @return json | Mensaje que especifica si fue correcto el proceso. Envia un array con los datos del usuario.
     * @return catch | Error en la base de datos.
     */
    public static function lista_editar($id)
    {
        try {
            $sql = 'SELECT * FROM users WHERE id =' . $id;
            $consulta = DB::connection()->select(DB::raw($sql));
    
            return [
                "status" => 1,
                "msg" => "Lista Usuarios",
                "data" => $consulta
            ];
        } catch (Throwable $e) {
            return "Error en database" . $e;
        }
    }


    /**
     * Verifica en la base de datos que los elementos email y documento no se encuentren registrados
     * por otro usuario en la base de datos. Si las condiciones se cumplen actualiza los datos de este usuario.
     *
     * @param mixed $request
     * @return string | String que especifica algun inconveniente.
     * @return catch | Error en la base de datos.
     * 
     */
    public static function actualizar_tabla($request)
    {
        $sql = 'SELECT id FROM users WHERE email= ?';
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
            return "Error en database" . $e;
        }
    }
}
