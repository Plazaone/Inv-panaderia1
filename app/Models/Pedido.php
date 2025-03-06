<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{

    use HasFactory;


    /**
     *Los Atributops que son asignables en masa
     *
     *@var array<int, string>
     */
    protected $fillable = [
        'users_id',
        'Cantidad'
    ];


    /**
     * Obtiene el Pedido asociada con el DetallePedido
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detallePedido()
    {
        return $this->hasMany(DetallePedido::class);
    }


    /**
     * Obtiene el Usuario Asociado con el Pedido
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function User()
    {
        return $this->belongsTo(User::class);
    }

}
