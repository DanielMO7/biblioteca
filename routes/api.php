<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post('insertar', [UserController::class, 'insertar']);

Route::post('ingresar', [AuthController::class, 'ingresar']);
Route::get('cerrar-sesion', [AuthController::class, 'cerrar_sesion']);

Route::middleware('auth:sanctum')->controller(UserController::class)->prefix('usuario')->group(function(){
    Route::get('perfil-usuario', 'perfil_usuario');
    Route::get('/editar-usuario/{id}', 'editar_usuario');

    Route::post('/editar-usuario/guardar', 'actualizar_tabla');
    Route::post('/cambiar-contrasena', 'verficiar_cambiar_contrasena');
});
