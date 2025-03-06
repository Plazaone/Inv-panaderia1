<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';

    /**
     *Los Atributops que son asignables en masa
     *
     *@var array<int, string>
     */
    protected $fillable = [
        'Nombre1',
        'Nombre2',
        'Apellido1',
        'Apellido2',
        'email',
        'Telefono',
        'Direccion',
        'Rol',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


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
     * Obtiene el pedido asociado con el usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Pedido()
    {
        return $this->hasMany(Pedido::class);
    }


    /**
     * Obtiene el producto asociado con el usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Producto()
    {
        return $this->hasMany(Producto::class);
    }


    /**
     * Obtiene el Inventario asociado con el usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Inventario()
    {
        return $this->hasMany(Inventario::class);
    }
}
