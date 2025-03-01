<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $producto = Producto::all();

        $data = [
            "producto" => $producto,
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
            "NombreProducto" => "required",
            "Descripcion" => "required",
            "UnidadMedida" => "required",
            "PrecioUnidad" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        $producto = Producto::create($request->only(["users_id", "NombreProducto", "Descripcion", "UnidadMedida", "PrecioUnidad"]));

        if (!$producto) {
            return response()->json([
                "message" => "Error al ingresar el Producto",
                "status" => 500
            ], 500);
        }

        return response()->json([
            "Producto" => $producto,
            "status" => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            $data = [
                "messege" => "Producto no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            "Producto" => $producto,
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
        $producto = Producto::find($id);
        if (!$producto) {
            $data = [
                "messege" => "Producto no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            "users_id" => "required",
            "NombreProducto" => "required",
            "Descripcion" => "required",
            "UnidadMedida" => "required",
            "PrecioUnidad" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        $producto->users_id = $request->users_id;
        $producto->NombreProducto = $request->NombreProducto;
        $producto->Descripcion = $request->Descripcion;
        $producto->UnidadMedida = $request->UnidadMedida;
        $producto->PrecioUnidad = $request->PrecioUnidad;

        $producto->save();

        $data = [
            "Producto" => $producto,
            "status" => 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            $data = [
                "messege" => "Producto no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $producto->delete();

        $data = [
            "messege" => "Producto eliminado",
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            $data = [
                "messege" => "Producto no Encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            "users_id" => "max:20",
            "NombreProducto" => "max:80",
            "Descripcion" => "max:100",
            "UnidadMedida" => "max:11",
            "PrecioUnidad" => "max:12"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }

        if ($request->has('users_id')) {
            $producto->users_id = $request->users_id;
        }
        if ($request->has('NombreProducto')) {
            $producto->NombreProducto = $request->NombreProducto;
        }
        if ($request->has('Descripcion')) {
            $producto->Descripcion = $request->Descripcion;
        }
        if ($request->has('UnidadMedida')) {
            $producto->UnidadMedida = $request->UnidadMedida;
        }
        if ($request->has('PrecioUnidad')) {
            $producto->PrecioUnidad = $request->PrecioUnidad;
        }

        $producto->save();
        $data = [
            "Messege" => "Producto Actualizado",
            "pedido" => $producto,
            "status" => 200
        ];
        return response()->json($data, 200);
    }
}
