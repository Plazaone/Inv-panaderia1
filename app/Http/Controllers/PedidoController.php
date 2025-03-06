<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Inventario;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    /**
     * Listar todos los pedidos con sus detalles.
     */
    public function index()
    {
        $pedidos = Pedido::with('detallePedido')->get();

        return response()->json([
            "Pedidos" => $pedidos,
            "status" => 200
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "producto_id" => "required|integer|exists:productos,id",
            "users_id" => "required|integer|exists:users,id",
            "Cantidad" => "required|integer|min:1"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "Message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        DB::beginTransaction();

        try {
            $producto = Producto::find($request->producto_id);
            $cantidadSolicitada = $request->Cantidad;

            // Bloquear el registro del inventario para evitar condiciones de carrera
            $inventario = Inventario::where('producto_id', $producto->id)->lockForUpdate()->first();

            if (!$inventario) {
                return response()->json([
                    "Message" => "Producto no encontrado en el inventario",
                    "status" => 404
                ], 404);
            }

            if ($inventario->Stock < $cantidadSolicitada) {
                return response()->json([
                    "Message" => "Stock insuficiente",
                    "Stock_disponible" => $inventario->Stock,
                    "Status" => 400
                ], 400);
            }

            // Restar el stock del inventario
            $inventario->Stock -= $cantidadSolicitada;
            $inventario->save();

            // Registrar el pedido
            $pedido = Pedido::create([
                "users_id" => $request->users_id,
                "Cantidad" => $cantidadSolicitada
            ]);

            // Registrar el detalle del pedido
            DetallePedido::create([
                "pedido_id" => $pedido->id,
                "producto_id" => $producto->id,
                "FechaPedido" => now(),
                "TotalPedido" => $cantidadSolicitada * $producto->PrecioUnidad
            ]);

            DB::commit();

            return response()->json([
                "Message" => "Pedido Registrado Correctamente",
                "Pedido" => $pedido,
                "Inventario_Actualizado" => $inventario,
                "status" => 201
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en PedidoController@store: " . $e->getMessage());

            return response()->json([
                "Message" => "Error al registrar el Pedido",
                "Error" => $e->getMessage(),
                "Status" => 500
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
                "Message" => "Pedido no encontrado",
                "status" => 404
            ], 404);
        }

        return response()->json([
            "pedido" => $pedido,
            "status" => 200
        ], 200);
    }

    /**
     * Actualizar un pedido existente.
     */
    public function update(Request $request, $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json([
                "Message" => "Pedido no encontrado",
                "status" => 404,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
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

        DB::beginTransaction();

        try {
            $detallePedido = DetallePedido::where('pedido_id', $pedido->id)->first();

            if (!$detallePedido) {
                return response()->json([
                    "Message" => "Detalle del pedido no encontrado",
                    "Status" => 404
                ], 404);
            }

            $producto = Producto::find($request->producto_id);
            $inventario = Inventario::where('producto_id', $request->producto_id)->lockForUpdate()->first();

            if (!$producto || !$inventario) {
                return response()->json([
                    "Message" => "Producto o inventario no encontrado",
                    "Status" => 404
                ], 404);
            }

            // Calcular la diferencia entre la cantidad solicitada y la cantidad anterior
            $diferencia = $request->Cantidad - $pedido->Cantidad;

            if ($diferencia > 0) {
                // Si la cantidad solicitada es mayor, restar la diferencia del inventario
                if ($inventario->Stock < $diferencia) {
                    return response()->json([
                        "Message" => "Stock insuficiente, no puede llevarse tantos productos",
                        "Status" => 400
                    ], 400);
                }
                $inventario->Stock -= $diferencia;
            } else {
                // Si la cantidad solicitada es menor, sumar la diferencia al inventario
                $inventario->Stock += abs($diferencia);
            }
            $inventario->save();

            // Actualizar el pedido
            $pedido->update([
                "users_id" => $request->users_id,
                "Cantidad" => $request->Cantidad,
            ]);

            // Actualizar el detalle del pedido
            $detallePedido->update([
                "producto_id" => $request->producto_id,
                "FechaPedido" => now(),
                "TotalPedido" => $request->Cantidad * $producto->PrecioUnidad,
            ]);

            DB::commit();

            return response()->json([
                "Message" => "Pedido actualizado correctamente",
                "pedido" => $pedido,
                "detalle" => $detallePedido,
                "inventario" => $inventario,
                "status" => 200
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en PedidoController@update: " . $e->getMessage());

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
                "Message" => "Pedido No encontrado",
                "Status" => 404
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Obtener el detalle del pedido y el inventario
            $detallePedido = $pedido->detallePedido;
            $inventario = Inventario::where('producto_id', $detallePedido->producto_id)->lockForUpdate()->first();

            if ($inventario) {
                // Restaurar el stock al inventario
                $inventario->Stock += $pedido->Cantidad;
                $inventario->save();
            }

            // Eliminar el detalle del pedido
            $pedido->detallePedido()->delete();

            // Eliminar el pedido
            $pedido->delete();

            DB::commit();

            return response()->json([
                "Message" => "Pedido Eliminado Correctamente",
                "Status" => 200
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en PedidoController@destroy: " . $e->getMessage());

            return response()->json([
                "message" => "Error al eliminar el pedido",
                "error" => $e->getMessage(),
                "status" => 500
            ], 500);
        }
    }

    /**
     * Generar un reporte de pedidos.
     */
    public function reportePedidos()
    {
        try {
            $pedidos = Pedido::with('detallePedido')->get();

            $totalPedido = $pedidos->flatMap->detallePedido->sum('TotalPedido');

            return response()->json([
                "Pedidos" => $pedidos,
                "TotalPedido" => $totalPedido,
                "status" => 200
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error en PedidoController@reportePedidos: " . $e->getMessage());

            return response()->json([
                "message" => "Error al generar el informe de pedidos",
                "error" => $e->getMessage(),
                "status" => 500
            ], 500);
        }
    }
}
