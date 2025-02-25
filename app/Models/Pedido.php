<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'pedido';

    protected $fillable = [
        'users_id',
        'Cantidad'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function DetallePedido()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
