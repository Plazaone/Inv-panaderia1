<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Inventario;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Registrar un nuevo pedido y su detalle.
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
            $inventario = Inventario::where('producto_id', $producto->id)->first();

            if (!$inventario || $inventario->Stock < $cantidadSolicitada) {
                return response()->json([
                    "Message" => "Stock insuficiente",
                    "Stock_disponible" => $inventario ? $inventario->Stock : 0, //
                    "Status" => 400
                ], 400);
            }


            $pedido = Pedido::create([
                "users_id" => $request->users_id,
                "Cantidad" => $cantidadSolicitada
            ]);

            $detallePedido = DetallePedido::create([
                "pedido_id" => $pedido->id,
                "producto_id" => $producto->id,
                "FechaPedido" => now(),
                "TotalPedido" => $cantidadSolicitada * $producto->PrecioUnidad
            ]);

            $inventario->Cantidad -= $cantidadSolicitada;
            $inventario->Stock = max($inventario->Cantidad, 0); //
            $inventario->save();

            DB::commit();

            return response()->json([
                "Message" => "Pedido Registrado Corecctamente",
                "Pedido" => $pedido,
                "Detalle_Pedido" => $detallePedido,
                "Inventario_Actualizado" => $inventario,
                "status" => 201
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                "Message" => "Error al regsitrar el Pedido",
                "Error" => $e->getMessage(),
                "Status" => 500
            ], 500);
        }
    }

    /**
     * Mostrar un pedido por ID con su detalle.
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
            $inventario = Inventario::where('producto_id', $request->producto_id)->first();

            if (!$producto || !$inventario) {
                return response()->json([
                    "Message" => "Producto o inventario no encontrado",
                    "Status" => 404
                ], 404);
            }

            $diferencia = $request->Cantidad - $pedido->Cantidad;

            if ($diferencia > 0 && $inventario->Stock < $diferencia) {
                return response()->json([
                    "Message" => "Stock insuficiente, no puede llevarse tantos productos",
                    "Status" => 400
                ], 400);
            }   

            $inventario->Stock -= $diferencia;//
            $inventario->Cantidad -= $diferencia;
            $inventario->save();

            $pedido->update([
                "users_id" => $request->users_id,
                "Cantidad" => $request->Cantidad,
            ]);

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

            return response()->json([
                "message" => "Error al actualizar el pedido",
                "error" => $e->getMessage(),
                "status" => 500
            ], 500);
        }
    }


    /**
     * Eliminar un pedido y su detalle.
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
            $pedido->detallePedido()->delete();
            
            $pedido->delete();

            DB::commit();

            return response()->json([
                "Message" => "Pedido Eliminado Correctamente",
                "Status" => 200
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                "message" => "Error al eliminar el pedido",
                "error" => $e->getMessage(),
                "status" => 500
            ], 500);
        }
    }
}
