<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relación de muchos a muchos con Roles
     * 
     * @return object relationship
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
}
