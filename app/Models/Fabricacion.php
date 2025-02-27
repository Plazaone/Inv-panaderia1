<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabricacion extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
    public function Producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
