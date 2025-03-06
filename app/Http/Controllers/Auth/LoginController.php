<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


    /**
     * Muestra el Formulario del Login
     */
    public function showLoginForm()
    {
        return view('/login');
    }


    /**
     *Creacion del Metodo Login que autentica al usuario
     */
    public function Login(Request $request)
    {
        $request->validate([
            'email' => "required|email",
            "password" => "required"
        ]);


        if (Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ])) {
            return redirect()->intended('/principal');
        }

        return back()->withErrors([
            "email" => "Error: El correo no es valido con nuestros registos",
            "password" => "Error en la Contraseña"
        ]);
    }


    /**
     *Creacion del Metodo Logout que cierra la sesion
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión
        $request->session()->invalidate(); // Invalida la sesión
        $request->session()->regenerateToken(); // Regenera el token de sesión
        return redirect('/login'); // Redirige a la página principal
    }
}
