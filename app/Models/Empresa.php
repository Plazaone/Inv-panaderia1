<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'empresa';


    /**
     *Los Atributops que son asignables en masa
     *
     *@var array<int, string>
     */
    protected $fillable = [
        'Nombre',
        'Telefono',
        'email',
        'direccion'
    ];


    /**
     * Obtiene la sucursal asociada con la Empresa
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Sucursals()
    {
        return $this->hasMany(Sucursal::class);
    }
}
