<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;


    /**
     *Los Atributops que son asignables en masa
     *
     *@var array<int, string>
     */
    protected $table = 'inventario';

    protected $guarded = [];


    /**
     *Los Atributops que son asignables en masa
     *
     *@var array<int, string>
     */
    protected $fillable = [
        'users_id',
        'producto_id',
        'Cantidad',
        'CantidadMax',
        'Stock',
        'CantidadMin'
    ];


    /**
     * Obtiene el Usuario Asociado con el Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function User()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Obtiene el inventario asociado con el Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
