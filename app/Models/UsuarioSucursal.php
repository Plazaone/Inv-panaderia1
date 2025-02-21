<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioSucursal extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
