<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario';

    protected $guarded = [];

    protected $fillable = [
        'users_id',
        'producto_id',
        'Cantidad',
        'CantidadMax',
        'Stock',
        'CantidadMin'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function Producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
