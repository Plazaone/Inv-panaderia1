<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtiene la suma total de las cantidades en inventario
        $totalCantidad = Inventario::sum('Cantidad');
        // Obtiene todos los registros de Inventario con su producto asociado
        $inventario = Inventario::with('Producto')->get();

        return response()->json([
            "Inventario" => $inventario,
            "Stock Disponible" => $totalCantidad,
            "Status" => 200
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
                "Message" => "Error en la validacion de los datos",
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
                $inventario->Stock = $inventario->Cantidad;//Stock y cantidad siempre deben ser iguales
                $inventario->save();
            }


            DB::commit();


            return response()->json([
                "Message" => "Producto registrado o actualizado en el inventario.",
                "inventario" => $inventario,
                "status" => 201
            ], 201);


        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                "message" => "Error al registrar el producto en el inventario.",
                "error" => $e->getMessage(),
                "status" => 500
            ], 500);
        }
    }

}
