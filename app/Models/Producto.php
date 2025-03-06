<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'productos';

    /**
     *Los Atributops que son asignables en masa
     *
     *@var array<int, string>
     */
    protected $fillable = [
        'users_id',
        'NombreProducto',
        'Descripcion',
        'UnidadMedida',
        'PrecioUnidad'
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
     * Obtiene el Producto asociada con el DetallePedido
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function DetallePedido()
    {
        return $this->hasMany(DetallePedido::class);
    }


    /**
     * Obtiene el Producto asociada con el Inventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Inventario()
    {
        return $this->hasMany(Inventario::class);
    }
}
