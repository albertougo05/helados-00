<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relación de muchos a muchos con Ability (permisos)
     * 
     */
    public function abilities()
    {
        return $this->belongsToMany(Ability::class)->withTimestamps();
    }

    /**
     * Asigna un permiso a un rol (ability to role)
     * 
     * @parameters string $ability (permiso)
     * or
     * @parameters Model Ability $ability (permiso)
     */
    public function allowTo($ability)
    {
        if (is_string($ability)) {
            $ability = Ability::whereName($ability)->firstOrFail();
        }

        $this->abilities()->sync($ability, false);
    }

}
