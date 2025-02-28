<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
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
        //Obtinene la suma del campo cantidad de la tabla Producto
        $totalCantidad = Producto::sum('Cantidad');

        //Junto a la tabla inventario con sus productos relacionados
        $inventario = Inventario::with('Producto')->get();

        return response()->json([
            "stock" => $inventario,
            "total_cantidad" => $totalCantidad,
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
        //utilizamos validator para validar los datos que se van a capturar
        $validator = Validator::make($request->all(), [
            "producto_id" => "required|integer|exists:productos,id",
            "users_id" => "required|integer|exists:users,id"
        ]);

        //si la validacion falla nos delvuelve el error que se esta presentando
        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        //Garantiza que las operaciones dentro del try se realicen correctamete, de lo contrario las borra
        DB::beginTransaction();

        try {
            
            //Busca si el producto ya existe en el inventario por medio del metodo where
            $inventario = Inventario::where('producto_id', $request->producto_id)->first();

            if (!$inventario) {

                //si no existe el producto en el inventario, se crea un registro 
                $inventario = Inventario::create([
                    "producto_id" => $request->producto_id,
                    "users_id" => $request->users_id,
                    "CantidadMax" => 100,  
                    "CantidadMin" => 10    

                ]);
            } else {

                $inventario->CantidadMax += 10;
                $inventario->CantidadMin += 5;
                $inventario->save();
            }

            //si todo sale bien se confirma la transicion
            DB::commit();

            //devuelve un response que contiene un json con la descripcion del inventario
            return response()->json([
                "message" => "Producto registrado o actualizado en el inventario.",
                "inventario" => $inventario,
                "status" => 201
            ], 201);

        //si hubo un error en el codigo, el catch evita que se realice la operacion ejecutando Rollback
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                "message" => "Error al registrar el producto en el inventario.",
                "error" => $e->getMessage(),
                "status" => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
