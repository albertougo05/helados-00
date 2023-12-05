<?php

namespace App\Http\Controllers\Productos;

use App\Models\ProductoSucursal;


class ProductosDeSucursal
{
    /**
     * Devuelve los productos de la sucursal, para ventaen mostrador. 
     * Por codigo de barra, por código, por nombre
     * Name: producto.sucursal
     *
     * @return array
     */
    public function getProductosSucursal($sucursal_id)
    {

        return $this->_productosDeLaSucursal($sucursal_id);
    }

    /**
     * LOS PRODUCTOS SERAN LOS QUE TENGA CADA SUCURSAL
     * 
     */
    private function _productosDeLaSucursal($sucursal_id)
    {
        $productos = ProductoSucursal::select(
                'productos_sucursal.sucursal_id',
                'productos_sucursal.producto_id',
                'productos_sucursal.precio_vta',
                'productos.id',
                'productos.codigo',
                'productos.grupo_id',
                'productos.descripcion',
                'productos.unidades_x_caja',
                'productos.con_receta',
                'productos.costo_x_unidad',
                'productos.peso_materia_prima',
                'productos.estado',
            )
            ->where('productos_sucursal.sucursal_id', $sucursal_id)
            ->where('productos_sucursal.precio_vta', '>', 0)
            ->where('productos.estado', '!=', 0)
            ->leftJoin('productos', 'productos_sucursal.producto_id', '=', 'productos.codigo')
            ->orderBy('productos.grupo_id')
            ->orderBy('productos.descripcion')
            ->get()
            ->toArray();

        return $productos;
    }
}   
