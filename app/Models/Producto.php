<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relación uno a uno con tabla firma (proveedor)
     */
    public function proveedor()
    {
        return $this->hasOne(Firma::class, 'id', 'proveedor_id');
    }
}
