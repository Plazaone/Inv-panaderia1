<?php

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

//Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');

/* Rutas API REST para crud de Empresa*/

Route::get('/empresa', [EmpresaController::class, 'index']);

Route::get('/empresa/{id}', [EmpresaController::class, 'show']);

Route::post('/empresa',[EmpresaController::class, 'store']);

Route::delete('/empresa/{id}', [EmpresaController::class, 'destroy']);

Route::put('/empresa/{id}', [EmpresaController::class, 'update']);

Route::patch('/empresa/{id}', [EmpresaController::class, 'updatePartial']);

/* Rutas API REST para crud de Sucursal*/

Route::get('/sucursal',[SucursalController::class, 'index']);

Route::get('/sucursal/{id}', [SucursalController::class, 'show']);

Route::post('/sucursal',[SucursalController::class, 'store']);

Route::put('/sucursal/{id}', [SucursalController::class, 'update']);

Route::delete('/sucursal/{id}', [SucursalController::class, 'destroy']);

Route::patch('/sucursal/{id}', [SucursalController::class, 'updatePartial']);

/*Routas API REST para crud de Usuario*/ 

Route::get('/user',[UserController::class, 'index']);

Route::get('/user/{id}', [UserController::class, 'show']);

Route::post('/user',[UserController::class, 'store']);

Route::put('/user/{id}', [UserController::class, 'update']);

Route::delete('/user/{id}', [UserController::class, 'destroy']);

Route::patch('/user/{id}', [UserController::class, 'updatePartial']);



