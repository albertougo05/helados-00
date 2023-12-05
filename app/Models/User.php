<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nombre_usuario',
        'email',
        'password',
        'empresa_id',
        'sucursal_id',
        'empleado_id',
        'perfil_id',
        'estado',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** 
     * Devuelve la relación de los roles del usuario
     * 
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /** 
     * Asigna un rol al usuario
     * 
     * @parameter string $role
     * or
     * @parameter model Role $role
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::whereName($role)->firstOrFail();
        }

        # Esto (sync), graba el registro, por mas que ya tiene las mismas claves de Role y Ability
        $this->roles()->sync($role, false);
    }

    /**
     * Devuelve array con los permisos (abilities)
     * 
     * @return 
     */
    public function abilities()
    {
        return $this->roles->map->abilities->flatten()->pluck('name')->unique();
    }

    
}
