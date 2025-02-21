<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function UsuarioSucursal()
    {
        return $this->hasMany(UsuarioSucursal::class);
    }

    public function Empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
