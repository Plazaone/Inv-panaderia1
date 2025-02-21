<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Illuminate\Log\log;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresa = Empresa::all();

        $data = [
            "empresa" => $empresa,
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
            "Nombre" => "required",
            "Telefono" => "required",
            "email" => "required|email|unique:empresa",
            "direccion" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        $empresa = Empresa::create($request->only(["Nombre", "Telefono", "email", "direccion"]));

        if (!$empresa) {
            return response()->json([
                "message" => "Error al ingresar la empresa",
                "status" => 500
            ], 500);
        }

        return response()->json([
            "empresa" => $empresa,
            "status" => 201
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa) {
            $data = [
                "messege" => "Empresa no encontrada",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            "empresa" => $empresa,
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
        $empresa = Empresa::find($id);
        if (!$empresa) {
            $data = [
                "messege" => "Empresa no encontrada",
                "status" => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            "Nombre" => "required",
            "Telefono" => "required",
            "email" => "required|email|unique:empresa",
            "direccion" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        $empresa->Nombre = $request->Nombre;
        $empresa->Telefono = $request->Telefono;
        $empresa->email = $request->email;
        $empresa->direccion = $request->direccion;

        $empresa->save();

        $data = [
            "empresa" => $empresa,
            "status" => 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa) {
            $data = [
                "messege" => "Empresa no encontrada",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $empresa->delete();

        $data = [
            "Messege" => "Empresa Eliminada",
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $empresa = Empresa::find($id);

        if (!$empresa) {
            $data = [
                "messege" => "Empresa no encontrada",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            "Nombre" => "max:255",
            "Telefono" => "digits:12",
            "email" => "required|email|unique:empresa",
            "direccion" => "max:255"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        if ($request->has('Nombre')) {
            $empresa->Nombre = $request->Nombre;
        }
        if ($request->has('Telefono')) {
            $empresa->Telefono = $request->Telefono;
        }
        if ($request->has('email')) {
            $empresa->email = $request->email;
        }
        if ($request->has('direccion')) {
            $empresa->direccion = $request->direccion;
        }

        $empresa->save();
        $data = [
            "Messege" => "Empresa Actualizada",
            "empresa" => $empresa,
            "status" => 200
        ];
        return response()->json($data, 200);
    }
}
