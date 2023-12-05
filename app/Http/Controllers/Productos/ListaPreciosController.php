<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ListaPreciosController extends Controller
{
    protected $_cant_por_pagina = 15;

    /**
     * Muestra lista de productos para modifica precios.
     * Name: producto.lista_precios
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productos = $this->_getProductos();

        return view('productos.lista_precios.index', compact('productos'));
    }

    /** 
     * Actualiza datos de precios de productos
     * Name: producto.lista_precios.actualiza
     * 
     * @param Request $request
     * @return json
     */
    public function actualiza(Request $request)
    {
        $result = 'error';
        $data = $request->input('data');

        DB::beginTransaction();
        try {
            foreach ($data as $value) {
                Producto::where('id', $value['id'])
                    ->update([
                        'costo_x_unidad' => $value['costo_x_unidad'],
                        'costo_x_bulto' => $value['costo_x_bulto'],
                        'precio_lista_1' => $value['precio_lista_1'],
                        'precio_lista_2' => $value['precio_lista_2'],
                        'precio_lista_3' => $value['precio_lista_3'],
                    ]);
            }
            $result = 'ok';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return response()->json(['estado' => $result]);
    }


    private function _getProductos() : array {
        $productos = Producto::select('id', 'codigo', 'descripcion', 'costo_x_unidad', 
                'costo_x_bulto', 'unidades_x_bulto', 'articulo_indiv_id',
                'precio_lista_1', 'precio_lista_2', 'precio_lista_3')
            ->orderBy('descripcion')
            ->get();

        $arrProductos = [];

        foreach ($productos as $value) {
            $arrProductos[] = [
                'id' => $value->id,
                'codigo' => $value->codigo,
                'descripcion'=> $value->descripcion,
                'costo_x_unidad' => (float) $value->costo_x_unidad,
                'costo_x_bulto' => (float) $value->costo_x_bulto,
                'unidades_x_bulto' => $value->unidades_x_bulto, 
                'articulo_indiv_id' => $value->articulo_indiv_id,
                'precio_lista_1' => (float) $value->precio_lista_1,
                'precio_lista_2' => (float) $value->precio_lista_2,
                'precio_lista_3' => (float) $value->precio_lista_3,
            ];
        }

        return $arrProductos;
    }
}
