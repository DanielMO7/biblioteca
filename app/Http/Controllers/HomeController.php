<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class HomeController extends Controller
{
    public function frases_home()
    {
        try {
            $sql_traer_frases_home = "SELECT * FROM fraces_home";
            $coneccion_traer_frases_home = DB::connection()->select(DB::raw($sql_traer_frases_home));

            return $coneccion_traer_frases_home;
        } catch (Throwable $e) {
            return "Error al traer frases" . $e;
        }
    }
}
