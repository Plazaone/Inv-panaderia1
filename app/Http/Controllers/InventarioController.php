<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventarioController extends Controller
{
    /**
     * Listar el inventario con la suma total de cantidades.
     */
    public function index()
    {
        $totalCantidad = Inventario::sum('Cantidad');
        $inventario = Inventario::with('Producto')->get();

        return response()->json([
            "Inventario" => $inventario,
            "Stock Disponible" => $totalCantidad,
            "Status" => 200
        ], 200);
    }

    /**
     * Agregar un producto al inventario o actualizarlo.
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
            $inventario = Inventario::where('producto_id', $request->producto_id)->first();

            if (!$inventario) {
                $inventario = Inventario::create([
                    "producto_id" => $request->producto_id,
                    "users_id" => $request->users_id,
                    "Cantidad" => $request->Cantidad,
                    "CantidadMax" => 100,
                    "CantidadMin" => 10,
                    "Stock" => $request->Cantidad
                ]);
            } else {
                $inventario->Cantidad += $request->Cantidad;
                $inventario->Stock = $inventario->Cantidad;
                $inventario->save();
            }

            DB::commit();

            return response()->json([
                "Message" => "Producto registrado o actualizado en el inventario.",
                "Inventario" => $inventario,
                "status" => 201
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en InventarioController@store: " . $e->getMessage());

            return response()->json([
                "Message" => "Error al registrar el producto en el inventario.",
                "Error" => $e->getMessage(),
                "status" => 500
            ], 500);
        }
    }
}
