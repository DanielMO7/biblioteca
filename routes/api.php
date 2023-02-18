<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post('insertar', [UserController::class, 'insertar']);
Route::post('validar-datos-registro', [UserController::class, 'validar_datos_registro']);
Route::post('ingresar', [AuthController::class, 'ingresar']);

Route::controller(HomeController::class)->prefix('home')->group(function () {
    Route::get('frases-home', 'frases_home');
});

Route::middleware('auth:sanctum')->controller(AuthController::class)->group(function () {
    Route::post('validar-token', 'validar_token');
    Route::post('cerrar-sesion', 'cerrar_sesion');
});

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
