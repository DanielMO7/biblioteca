<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Throwable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','documento', 'rol', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Se valida que el id que sen encuentra guardado en al auth sanctum sea igual al id que se 
     * desea moficar. Luego se seleccionan todos los datos de la tabla users cuya fila tenga
     * el mismo id que se recibe como parametro. 
     *
     * @param int $id
     * @return json | Array con los datos del usuario a actualizar.
     */
    public static function editar_usuario($id)
    {   
        if (auth('sanctum')->user()->id != $id) {
            return 'No autorizado.';
        }
        try {
            $sql = 'SELECT * FROM users WHERE id =' . $id;
            $objeto_consulta = DB::select($sql);
    
            return [
                "status" => 1,
                "msg" => "Datos del usuario!",
                "data" => $objeto_consulta
            ];
        } catch (Throwable $e) {
            return "Error en database" . $e;
        }
    }


    /**
     * Se valida que el id que sen encuentra guardado en al auth sanctum sea igual al id que se 
     * desea moficar. Verifica en la base de datos que los elementos email y documento no se encuentren
     * registrados por otro usuario. Si las condiciones se cumplen actualiza los datos de este usuario.
     *
     * @param mixed $request
     * @return json | Retorna un mensaje especificando un inconveniente o aprobacion de la accion.
     */
    public static function actualizar_tabla($request){

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
         * 
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


    /**
     * Se valida que el id que se en encuentra guardado en al auth sanctum sea igual al id que se 
     * desea moficar. Se comparan que contraseña nueva y su validacion sean iguales, luego traen de la
     * tabla users todos los datos que sean igual al id recibigo. Se verifica que la contraseña en la db
     * sea igual a la que envia el usuario y se actualiza.
     *
     * @param mixed $request
     * @return json | Mensaje con los posibles errores o proceso coorecto.
     */
    public static function verficiar_cambiar_contrasena($request)
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
                        return [
                            "status" => 1,
                            "msg" => "Contraseña actualizada correctamente!",
                        ];
                    } else {
                        return [
                            "status" => 0,
                            "msg" => "Contraseña incorrecta!",
                        ];
                    }
                }
            }catch(Throwable $e){
                return [
                    "status" => 0,
                    "msg" => "Error en database!",
                ];
            }
        } else {
            return [
                "status" => 0,
                "msg" => "Las contraseñas no son iguales",
            ];
        }
    }

}
