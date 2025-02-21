<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function Producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
