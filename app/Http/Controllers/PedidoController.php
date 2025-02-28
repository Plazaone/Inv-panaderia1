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
     * Display a listing of the resource.
     */
    public function index()
    {
        //Junto a la tabla Pedido con su detalle relacionado
        $pedido = Pedido::with('detallePedido')->get();

        //Delvuelve un response con formato json y todos los pedidos junto con sus detalles correspondientes
        return response()->json([

            "Detalle_pedido" => Pedido::with('detallePedido')->get(),
            "pedido" => $pedido,
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
            "users_id" => "required|integer",
            "Cantidad" => "required|integer|min:1",
            "producto_id" => "required|integer|exists:productos,id",
        ]);

        ///si la validacion falla nos delvuelve el error que se esta presentando
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

            // Obtener el producto utilizando el metodo find que recibe como parametro el id del producto PK
            $producto = Producto::find($request->producto_id);

            //si no encuentra un producto devuelve un response con un mensaje de que no se encontro
            if (!$producto) {
                return response()->json([
                    "message" => "Producto no encontrado",
                    "status" => 404
                ], 404);
            }

            // Verificar si hay suficiente stock
            if ($producto->Cantidad < $request->Cantidad) {
                return response()->json([
                    "message" => "Stock insuficiente para este producto",
                    "status" => 400
                ], 400);
            }

            // Restar la cantidad solicitada del stock del producto
            $producto->Cantidad -= $request->Cantidad;
            $producto->save();

            //Obtener el inventario asociado al producto utilizando el metodo where de eloquent y recibiendo a PK
            $inventario = Inventario::where('producto_id', $request->producto_id)->first();

            //si exite un inventario
            if ($inventario) {
                // Restar la cantidad del inventario también
                $inventario->CantidadMax -= $request->Cantidad;
                //se ajusta el minimo de ser necesario
                $inventario->CantidadMin = min($inventario->CantidadMin, $inventario->CantidadMax);
                $inventario->save();
            }

            //Calcular el total del pedido
            $totalPedido = $request->Cantidad * $producto->PrecioUnidad;

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

            //si todo salio bien cierra la transicion
            DB::commit();

            //Devuelve un response con un json con el pedido y su respectivo detalle
            return response()->json([
                "message" => "Pedido creado correctamente",
                "pedido" => $pedido,
                "detalle" => $detallePedido,
                "status" => 201
            ], 201);
            
        //En caso de un error en la seccion catch
        } catch (\Exception $e) {

            //Se ejecuta el metodo rollback que elimna o revierte las operaciones
            DB::rollBack();

            //devuelve un response con un json que contiene el mensaje de error
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
         //Junto a la tabla Pedido con su detalle relacionado y lo busco por su id con el metodo find
        $pedido = Pedido::with('detallePedido')->find($id);

        //Si no existe un pedido devuelve un Json que no encuentra el pedido
        if (!$pedido) {
            return response()->json([
                "messege" => "pedido no encontrado",
                "status" => 404
            ], 404);
        }

        //de lo contrario devuelve un response con el objeto pedido
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
        //Buscamos el objeto pedido por medio de su ID (PK)
        $pedido = Pedido::find($id);
        
        //si no exite devolvemos un mensaje en jsn que no lo encontro
        if (!$pedido) {
            return response()->json([
                "message" => "Pedido no encontrado",
                "status" => 404
            ], 404);
        }
        
        //el objeto validator valida que la actualizacion tenga los datos correspondientes
        $validator = Validator::make($request->all(), [
            "users_id" => "required|integer",
            "Cantidad" => "required|integer|min:1",
            "producto_id" => "required|integer|exists:productos,id",
        ]);
        
        //si validator falla manda un mensaje con el error de la validacion
        if ($validator->fails()) {
            return response()->json([
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ], 400);
        }
        
        /*Se inicia la transicion que Garantiza que las operaciones dentro 
        del try se realicen correctamete, de lo contrario las borra*/
        DB::beginTransaction();
        try {
            // Obtener el detalle del pedido asociado con el id de pedido correspondiente
            $detallePedido = DetallePedido::where('pedido_id', $pedido->id)->first();
            
            //Si no existe un detalle de pedido devuelve un response que no lo encuentra
            if (!$detallePedido) {
                return response()->json([
                    "message" => "Detalle de pedido no encontrado",
                    "status" => 404
                ], 404);
            }
    
            /*El objeto producto utiliza a la clase producto que llama al 
            metodo find y con ello recibe como parametro el id del producto*/

            $producto = Producto::find($request->producto_id);

            //si no existe devuelve un mensaje de que no lo encuentra
            if (!$producto) {
                return response()->json([
                    "message" => "Producto no encontrado",
                    "status" => 404
                ], 404);
            }
    
            // Obtener el inventario del producto
            $inventario = Inventario::where('producto_id', $request->producto_id)->first();
    
            // Calcular la diferencia en cantidad
            $diferencia = $request->Cantidad - $pedido->Cantidad;
    
            if ($diferencia > 0) {
                // Se está aumentando la cantidad del pedido
                if ($producto->Cantidad < $diferencia) {
                    return response()->json([
                        "message" => "Stock insuficiente para aumentar la cantidad",
                        "status" => 400
                    ], 400);
                }
    
                // Restar la diferencia del stock del producto
                $producto->Cantidad -= $diferencia;
                if ($inventario) {
                    $inventario->CantidadMax -= $diferencia;
                    $inventario->CantidadMin = min($inventario->CantidadMin, $inventario->CantidadMax);
                    $inventario->save();
                }
            } elseif ($diferencia < 0) {
                // Se está reduciendo la cantidad del pedido
                $producto->Cantidad += abs($diferencia); // Devolver la cantidad al stock
                if ($inventario) {
                    $inventario->CantidadMax += abs($diferencia);
                    $inventario->save();
                }
            }
    
            $producto->save();
    
            // Recalcular el total del pedido
            $totalPedido = $request->Cantidad * $producto->PrecioUnidad;
    
            // Actualizar el pedido
            $pedido->update([
                "users_id" => $request->users_id,
                "Cantidad" => $request->Cantidad,
            ]);
    
            // Actualizar el detalle del pedido
            $detallePedido->update([
                "producto_id" => $request->producto_id,
                "FechaPedido" => now(),
                "TotalPedido" => $totalPedido,
            ]);
            

            //si todo sale bien se confirma la transicion
            DB::commit();
            
            //devuelve un response con su pedido actualizado y su detalle de pedido actualizado
            return response()->json([
                "message" => "Pedido actualizado correctamente",
                "pedido" => $pedido,
                "detalle" => $detallePedido,
                "status" => 200
            ], 200);

        //se ejecuta la seccion catch
        } catch (\Exception $e) {
            
            //Ejecuta el metodo rollbaack que revierte y elimina los datos
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
        ////Junto a la tabla Pedido con su detalle relacion
        $pedido = Pedido::with('detallePedido')->find($id);

        //si no existe un pedido devuelve un mensaje
        if (!$pedido) {
            return response()->json([
                "messege" => "pedido no encontrado",
                "status" => 404
            ], 404);
        }

        //el objeto pedido llama al metodo delete y elimina esta informacion en cascada
        $pedido->delete();

        //devuelve un json con un mensaje
        return response()->json([
            "messege" => "pedido eliminado",
            "status" => 200
        ], 200);
    }
}
