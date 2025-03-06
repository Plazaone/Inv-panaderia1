<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;


    /**
     *Los Atributos que son asignables en masa
     *
     *@var array<int, string>
     */
    protected $table = 'sucursal';


    /**
     *Los Atributos que son asignables en masa
     *
     *@var array<int, string>
     */
    protected $fillable = [
        'NombreSucursal',
        'DireccionSucursal',
        'empresa_id'
    ];
    protected $guarded = [];


    /**
     * Obtiene la sucursal asociada con el usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function UsuarioSucursal()
    {
        return $this->hasMany(UsuarioSucursal::class);
    }


    /**
     * Obtiene la Sucursal Asociado con la Empresa
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function Empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
