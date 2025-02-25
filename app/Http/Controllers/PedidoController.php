<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pedido = Pedido::all();

        $data = [
            "pedido" => $pedido,
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
            "users_id" => "required",
            "Cantidad" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        $pedido = Pedido::create($request->only(["users_id", "Cantidad"]));

        if (!$pedido) {
            return response()->json([
                "message" => "Error al ingresar el pedido",
                "status" => 500
            ], 500);
        }

        return response()->json([
            "pedido" => $pedido,
            "status" => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            $data = [
                "messege" => "Pedido no Encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            "Pedido" => $pedido,
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
        $pedido = Pedido::find($id);
        if (!$pedido) {
            $data = [
                "messege" => "Pedido no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            "users_id" => "required",
            "Cantidad" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        $pedido->users_id = $request->users_id;
        $pedido->Cantidad = $request->Cantidad;

        $pedido->save();

        $data = [
            "Pedido" => $pedido,
            "status" => 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            $data = [
                "messege" => "Pedido no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $pedido->delete();

        $data = [
            "messege" => "Pedido eliminado",
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            $data = [
                "messege" => "Pedido no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            "users_id" => "max:20",
            "Cantidad" => "max:20"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        if ($request->has('users_id')) {
            $pedido->users_id = $request->users_id;
        }
        if ($request->has('Cantidad')) {
            $pedido->Cantidad = $request->Cantidad;
        }

        $pedido->save();
        $data = [
            "Messege" => "Pedido actualizado",
            "pedido" => $pedido,
            "status" => 200
        ];
        return response()->json($data, 200);
    }
}
