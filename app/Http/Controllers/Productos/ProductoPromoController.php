<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use App\Models\ProductoPromo;
use App\Models\ProductoPromoOpciones;
use Illuminate\Http\Request;
use Exception;


class ProductoPromoController extends Controller
{
    /**
     * No tiene index...
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Salvar datos producto promocion
     * Name: producto.promo.salvar_producto (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salvarProducto(Request $request)
    {
        $result = 'ok';

        try {
            ProductoPromo::updateOrInsert(
                                ['producto_codigo' => $request->input('producto_codigo')], 
                                $request->all())
                         ->lockForUpdate();

        } catch (Exception $e) {
            $result = 'error';
        }

        return response()->json(['status' => $result]);
    }

    /**
     * Salvar articulos opcionales de la promocion
     * Name: producto.promo.salvar_opciones (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salvarOpciones(Request $request)
    {
        $result = 'ok';

        try {
            // Vienen todos los articulos en un array
            foreach ($request->all() as $value) {
                ProductoPromoOpciones::updateOrInsert(
                                        ['id' => $value['id']], 
                                        $value)
                                    ->lockForUpdate();
            }

        } catch(Exception $e) {

            $result = 'error';
        }

        return response()->json(['status' => $result]);
    }

}
