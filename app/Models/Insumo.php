<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Fabricacion()
    {
        return $this->hasMany(Fabricacion::class);
    }

    public function OrdenCompra(){
        return $this->hasMany(OrdenCompra::class);
    }
}
