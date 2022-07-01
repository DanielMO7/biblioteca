<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



// Ruta Registrar Usuario.
Route::post('insertar', [UserController::class, 'insertar']);

// Ruta Ingresar al Sistema.
Route::post('ingresar', [AuthController::class, 'ingresar']);

// Ruta Cerrar la Sesion del Usuario.
Route::get('cerrar-sesion', [AuthController::class, 'cerrar_sesion']);

// Rutas de Usuarios Registrados y Logueados Correctamente.
Route::middleware('auth:sanctum')->controller(UserController::class)->prefix('usuario')->group(function(){
    
    // Ruta Perfil del usuario.
    Route::get('perfil-usuario', 'perfil_usuario');

    // Ruta Editar los datos del usuario (Trae los datos del usuario).
    Route::get('/editar-usuario/{id}', 'editar_usuario');

    // Ruta Guarda los datos que el usuario quiere editar.
    Route::post('/editar-usuario/guardar', 'actualizar_tabla');

    // Ruta Cambiar contraseña del usuario.
    Route::post('/cambiar-contrasena', 'verficiar_cambiar_contrasena');

});

// Rutas de Usuarios Administradores Registrados y logueados.
Route::middleware('auth:sanctum')->controller(AdminController::class)->prefix('admin')->group(function(){
    Route::middleware('verificar_rol')->group(function(){

        // Ruta Vista Index del Administrador.
        Route::get('index-admin', 'index_admin');

        // Ruta Lista de Todos los Usuarios Registrado en el sistema.
        Route::get('lista-usuarios', 'lista_usuarios');

        // Ruta Datos del Usuario que se desea editar.
        Route::get('/lista-usuarios/{id}', 'lista_editar');

        // Ruta Guarda los Datos que el administrador desea cambiar del Usuario.
        Route::post('/lista-usuarios/guardar', 'actualizar_tabla');

        // Ruta Elimina al Usuario del Sistema (Cambia el estado).
        Route::get('/eliminar-usuario/{id}', 'eliminar_usuario');
    });
    
});
