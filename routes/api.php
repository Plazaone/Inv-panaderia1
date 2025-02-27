<?php

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\UserController;
use App\Models\Pedido;
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

/*Rutas api rest para crud de Pedido*/

Route::get('/pedido', [PedidoController::class, 'index']);

Route::get('/pedido/{id}', [PedidoController::class, 'show']);

Route::post('/pedido', [PedidoController::class, 'store']);

Route::put('/pedido/{id}', [PedidoController::class, 'update']);

Route::delete('/pedido/{id}', [PedidoController::class, 'destroy']);

/*Rutas api rest para crud de la tabla Producto*/

Route::get('/producto', [ProductoController::class, 'index']);

Route::get('/producto/{id}', [ProductoController::class, 'show']);

Route::post('/producto', [ProductoController::class, 'store']);

Route::put('/producto/{id}', [ProductoController::class, 'update']);

Route::delete('/producto/{id}',[ProductoController::class, 'destroy']);

Route::patch('/producto/{id}', [ProductoController::class, 'updatePartial']);



