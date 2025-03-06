<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        $data = [
            "Usuario" => $user,
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "Nombre1" => "required",
            "Nombre2" => "required",
            "Apellido1" => "required",
            "Apellido2" => "required",
            "email" => "required|email|unique:users",
            "Telefono" => "required",
            "Direccion" => "required",
            "Rol" => "required",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        $user = User::create($request->only([
            "Nombre1", "Nombre2",
            "Apellido1", "Apellido2",
            "email",
            "Telefono",
            "Direccion",
            "Rol",
            "password"
        ]));

        if (!$user) {
            return response()->json([
                "message" => "Error al ingresar al usuario",
                "status" => 500
            ], 500);
        }

        return response()->json([
            "usuario" => $user,
            "status" => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            $data = [
                "messege" => "Usuario no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            "usuario" => $user,
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            $data = [
                "messege" => "Usuario no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            "Nombre1" => "required",
            "Nombre2" => "required",
            "Apellido1" => "required",
            "Apellido2" => "required",
            "email" => "required|email|unique:users",
            "Telefono" => "required",
            "Direccion" => "required",
            "Rol" => "required",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        $user->update($request->only([
            "Nombre1",
            "Nombre2",
            "Apellido1",
            "Apellido2",
            'email' => 'required|email|unique:users,email,' . $id,
            "Telefono",
            "Direccion",
            "Rol",
            "password"
        ]));

        $user->save();

        $data = [
            "Usuario" => $user,
            "status" => 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            $data = [
                "messege" => "Usuario no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $user->delete();

        $data = [
            "Messege" => "Usuario Eliminado",
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            $data = [
                "messege" => "  Usuario no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            "Nombre1" => "max:25",
            "Nombre2" => "max:25",
            "Apellido1" => "max:25",
            "Apellido2" => "max:25",
            "email" => "max:255",
            "Telefono" => "max:12",
            "Direccion" => "max:100",
            "Rol" => "max:30",
            "password" => "max:12"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        if ($request->has('Nombre1')) {
            $user->Nombre1 = $request->Nombre1;
        }
        if ($request->has('Nombre2')) {
            $user->Nombre2 = $request->Nombre2;
        }
        if ($request->has('Apellido1')) {
            $user->Apellido1 = $request->Apellido1;
        }
        if ($request->has('Apellido2')) {
            $user->Apellido2 = $request->Apellido2;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('Telefono')) {
            $user->Telefono = $request->Telefono;
        }
        if ($request->has('Direccion')) {
            $user->Direccion = $request->Direccion;
        }
        if ($request->has('Rol')) {
            $user->Rol = $request->Rol;
        }
        if ($request->has('password')) {
            $user->password = $request->password;
        }

        $user->save();
        $data = [
            "Messege" => "Campo del Usuario actualizado",
            "empresa" => $user,
            "status" => 200
        ];
        return response()->json($data, 200);
    }
}
