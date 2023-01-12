<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post('insertar', [UserController::class, 'insertar']);
Route::post('validar-datos-registro', [UserController::class, 'validar_datos_registro']);
Route::post('ingresar', [AuthController::class, 'ingresar']);
Route::get('cerrar-sesion', [AuthController::class, 'cerrar_sesion']);

Route::middleware('auth:sanctum')->controller(UserController::class)->prefix('usuario')->group(function () {
    Route::get('perfil-usuario', 'perfil_usuario');
    Route::get('/editar-usuario/{id}', 'editar_usuario');
    Route::post('/editar-usuario/guardar', 'actualizar_tabla');
    Route::post('/cambiar-contrasena', 'verficiar_cambiar_contrasena');
});
Route::middleware('auth:sanctum')->controller(AdminController::class)->prefix('admin')->group(function () {
    Route::middleware('verificar_rol')->group(function () {
        Route::get('index-admin', 'index_admin');
        Route::get('lista-usuarios', 'lista_usuarios');
        Route::get('/lista-usuarios/{id}', 'lista_editar');
        Route::post('/lista-usuarios/guardar', 'actualizar_tabla');
        Route::get('/eliminar-usuario/{id}', 'eliminar_usuario');
    });
});
