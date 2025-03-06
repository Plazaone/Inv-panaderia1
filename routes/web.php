<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

// Ruta para mostrar el formulario de login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// Ruta para procesar el formulario de login
Route::post('login', [LoginController::class, 'login']);

// Ruta para cerrar sesión
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
