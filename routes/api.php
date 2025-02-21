<?php

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UserController; // Asegúrate de tener un controlador para User
use Illuminate\Support\Facades\Route;

//Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');

// Ruta para Empresa
Route::get('/empresa', [EmpresaController::class, 'index']);

Route::get('/empresa/{id}', [EmpresaController::class, 'show']);

Route::post('/empresa',[EmpresaController::class, 'store']);

Route::delete('/empresa/{id}', [EmpresaController::class, 'destroy']);

Route::put('/empresa/{id}', [EmpresaController::class, 'update']);

Route::patch('/empresa/{id}', [EmpresaController::class, 'updatePartial']);



