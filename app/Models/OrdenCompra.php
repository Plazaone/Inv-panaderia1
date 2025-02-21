<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function Insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
}
