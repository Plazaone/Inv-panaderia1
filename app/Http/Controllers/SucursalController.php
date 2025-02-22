<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sucursal = Sucursal::all();

        $data = [
            "sucursal" => $sucursal,
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "NombreSucursal" => "required",
            "DireccionSucursal" => "required",
            "empresa_id" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        $sucursal = Sucursal::create($request->only(["NombreSucursal", "DireccionSucursal", "empresa_id"]));

        if (!$sucursal) {
            return response()->json([
                "message" => "Error al ingresar la sucursal",
                "status" => 500
            ], 500);
        }

        return response()->json([
            "sucursal" => $sucursal,
            "status" => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sucursal = Sucursal::find($id);
        if (!$sucursal) {
            $data = [
                "messege" => "Sucursal no Encontrada",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            "sucursal" => $sucursal,
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sucursal = Sucursal::find($id);
        if (!$sucursal) {
            $data = [
                "messege" => "Sucursal no encontrada",
                "status" => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            "NombreSucursal" => "required",
            "DireccionSucursal" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        $sucursal->NombreSucursal = $request->NombreSucursal;
        $sucursal->DireccionSucursal = $request->DireccionSucursal;

        $sucursal->save();

        $data = [
            "sucursal" => $sucursal,
            "status" => 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sucursal = Sucursal::find($id);
        if (!$sucursal) {
            $data = [
                "messege" => "Sucursal no encontrada",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $sucursal->delete();

        $data = [
            "messege" => "sucursal eliminada",
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $sucursal = Sucursal::find($id);

        if (!$sucursal) {
            $data = [
                "messege" => "Sucursal no encontrada",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            "NombreSucursal" => "max:100",
            "DireccionSucursal" => "max:200"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        if ($request->has('NombreSucursal')) {
            $sucursal->NombreSucursal = $request->NombreSucursal;
        }
        if ($request->has('DireccionSucursal')) {
            $sucursal->DireccionSucursal = $request->DireccionSucursal;
        }

        $sucursal->save();
        $data = [
            "Messege" => "Sucursal Actualizada",
            "empresa" => $sucursal,
            "status" => 200
        ];
        return response()->json($data, 200);
    }
}
