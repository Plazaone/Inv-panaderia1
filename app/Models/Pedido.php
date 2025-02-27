<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    
    use HasFactory;
    
    protected $fillable = [
        'users_id',
        'Cantidad'
    ];

    public function detallePedido()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

}