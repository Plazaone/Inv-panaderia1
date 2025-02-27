<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedidos';
    
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'FechaPedido',
        'TotalPedido'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function Producto()
    {
        return $this->belongsTo(Producto::class);
    }
    
}
