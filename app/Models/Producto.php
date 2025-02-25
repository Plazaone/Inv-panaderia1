<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'producto';

    protected $fillable = [
        'users_id',
        'NombreProducto',
        'Descripcion',
        'UnidadMedida',
        'PrecioUnidad'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function DetallePedido()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function Inventario()
    {
        return $this->hasMany(Inventario::class);
    }
}
