<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;


class BuscarProducto extends Controller
{
    /**
     * Búsqueda de producto, en input que busca producto. 
     * Por codigo de barra, por código, por nombre
     * Name: producto.buscar
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response Json
     */
    public function __invoke(Request $request)
    {
        $buscar = $request->input('buscar');

        // Buscar por codigo de barras (cuando esté disponible)

        // Buscar por codigo
        $result = Producto::select('id', 'descripcion', 'codigo', 'costo')
                    ->where("codigo", $buscar)
                    ->where("estado", 1)
                    ->where("con_receta", 0)
                    ->where("generico", 0)
                    ->get();

        if ($result->isEmpty()) {       // Si no encontró por código busca por descripcion

            if ($buscar === "") {       // Si es vacio el buscar, devuelve todos los productos
                $result = Producto::select('id', 'descripcion', 'codigo', 'costo')
                    ->where("estado", 1)
                    ->where("con_receta", 0)
                    ->where("generico", 0)
                    ->orderBy('descripcion')
                    ->get();
            } else {
                $result = Producto::select('id', 'descripcion', 'codigo', 'costo')
                    ->where("descripcion", "like", $buscar . "%")
                    ->where("estado", 1)
                    ->where("con_receta", 0)
                    ->where("generico", 0)
                    ->orderBy('descripcion')
                    ->limit(15)
                    ->get();
            }
        }

        return response()->json($result->toArray());
    }
}
