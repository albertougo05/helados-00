<?php

namespace App\Http\Controllers\Productos;

use App\Models\Firma;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;


class ListasParaProducto
{
    /** 
     * Devuelve la lista de Proveedores (tabla Firmas)
     * (Para combobox)
     * 
     * @return array Lista de Firmas que son Proveedores
     */
    public static function listaProveedores()
    {
        return Firma::select('id', 'firma')
                    ->where([['proveedor', '=', 1],
                             ['estado', '=', 1]])
                    ->orderBy('firma')
                    ->get();
    }

    public static function listaProductoIndividual()
    {
        //return DB::select('call sp_prods_lista_indiv');

        return Producto::select('id', 'codigo', 'descripcion')  // Chequear qué tipos de productos !
            ->where('venta_publico', 1)
            ->where('estado', 1)
            ->whereNotIn('tipo_producto_id', [1])     // Tipo producto elaborado
            ->whereNotIn('grupo_id', [6, 9, 10, 11, 12, 13, 15, 16, 17])         // Grupos POSTRES, IMPULSIVOS x caja, ENVASES, DESCARTABLES, PANADERIA, CAFETERIA
            //->whereIn('grupo_id', [7, 8])         // Grupos POSTRES e IMPULSIVOS x unidad
            ->orderBy('descripcion')
            ->get();
    }

    public static function listaProductosReceta()
    {
        //return DB::select('call sp_lista_prods_receta');

        return Producto::select('id', 'codigo', 'descripcion', 'costo_x_unidad')
            ->where('estado', 1)
            ->where('apto_receta', 1)
            ->orderBy('descripcion')       // Para lista de producto individual
            ->get();
    }

    public static function listaArticulosPromo()
    {
        // $gru1 = 1; $gru2 = 4
        //return DB::select('call sp_lista_artic_promo($gru1, $gru2)', );

        return Producto::select('id', 'codigo', 'descripcion', 'costo_x_unidad', 'precio_lista_1')
            ->where('estado', 1)
            ->whereNotIn('grupo_id', [1, 5])
            ->orderBy('descripcion')
            ->get();
    }
}
