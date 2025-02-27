<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pedido = Pedido::with('detallePedido')->get();
        $data = [
            "pedido" => $pedido,
            "status" => 200
        ];
        return response()->json([
            "pedido" => Pedido::with('detallePedido')->get(),
            "status" => 200
        ], 200);
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
            "users_id" => "required|integer",
            "Cantidad" => "required|integer|min:1",
            "producto_id" => "required|integer|exists:productos,id",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }
         // Buscar el producto para obtener su precio
        $producto = Producto::find($request->producto_id);
        if (!$producto) {
            return response()->json([
                "message" => "Producto no encontrado",
                "status" => 404
            ], 404);
        }
    
         // Calcular el total del pedido
        $totalPedido = $request->Cantidad * $producto->PrecioUnidad;

        DB::beginTransaction();
        try {
             // Crear el pedido
            $pedido = Pedido::create([
                "users_id" => $request->users_id,
                "Cantidad" => $request->Cantidad,
            ]);

             // Crear el detalle del pedido
            $detallePedido = DetallePedido::create([
                "pedido_id" => $pedido->id,
                "producto_id" => $request->producto_id,
                "FechaPedido" => now(),
                "TotalPedido" => $totalPedido,
            ]);

            DB::commit();

            return response()->json([
                "message" => "Pedido creado correctamente",
                "pedido" => $pedido,
                "detalle" => $detallePedido,
                "status" => 201
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => "Error al crear el pedido",
                "error" => $e->getMessage(),
                "status" => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pedido = Pedido::with('detallePedido')->find($id);
        if (!$pedido) {
            return response()->json([
                "messege" => "pedido no encontrado",
                "status" => 404
            ], 404);
        }
        return response()->json([
            "pedido" => $pedido,
            "status" => 200
        ], 200);
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
            return response()->json([
                "message" => "Pedido no encontrado",
                "status" => 404
            ], 404);
        }

        // Validar los datos de la solicitud
        $validator = Validator::make($request->all(), [
            "users_id" => "required|integer",
            "Cantidad" => "required|integer|min:1",
            "producto_id" => "required|integer|exists:productos,id",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        // Iniciar transacción
        DB::beginTransaction();

        try {
            // Actualizar el pedido
            $pedido->update([
                "users_id" => $request->users_id,
                "Cantidad" => $request->Cantidad,
            ]);

            // Obtener el detalle del pedido asociado
            $detallePedido = $pedido->detallePedido()->first();

            if ($detallePedido) {
                // Calcular el nuevo total del pedido
                $producto = Producto::find($request->producto_id);
                $totalPedido = $request->Cantidad * $producto->PrecioUnidad;

                // Actualizar el detalle del pedido
                $detallePedido->update([
                    "producto_id" => $request->producto_id,
                    "FechaPedido" => now(),
                    "TotalPedido" => $totalPedido,
                ]);
            }

            // Confirmar transacción
            DB::commit();

            return response()->json([
                "message" => "Pedido actualizado correctamente",
                "pedido" => $pedido,
                "detalle" => $detallePedido,
                "status" => 200
            ], 200);

        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            return response()->json([
                "message" => "Error al actualizar el pedido",
                "error" => $e->getMessage(),
                "status" => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pedido = Pedido::with('detallePedido')->find($id);
        if (!$pedido) {
            return response()->json([
                "messege" => "pedido no encontrado",
                "status" => 404
            ], 404);
        }

        $pedido->delete();

        return response()->json([
            "messege" => "pedido eliminado",
            "status" => 200
        ], 200);
    }
}
