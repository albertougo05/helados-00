<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use App\Models\ProductosReceta;
use Illuminate\Http\Request;

class ProductosRecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Guarda los productos de una receta de producto
     * Name: producto_receta.store (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = 'Error';
        // Vienen todos los productos en array (Ver: btnConfirmaReceta.addEventListener en edit.js)
        foreach ($request->all() as $value) {
            ProductosReceta::updateOrInsert(['id' => $value['id']], $value)
                            ->lockForUpdate();
            $result = 'Ok';
        }

        return response()->json(['status' => $result]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductosReceta $productoReceta
     * @return \Illuminate\Http\Response
     */
    public function show(ProductosReceta $productosReceta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductosReceta  $productosReceta
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductosReceta $productosReceta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * Name: producto_receta.update (PUT)
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductosReceta  $productosReceta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductosReceta $productosReceta)
    {
        $result = 'error';
        $receta = $productosReceta->lockForUpdate()
            ->update($this->_datosProductoReceta($productosReceta));

        if($receta) {
            $result = 'ok';
        }

        return response()->json(['resultado' => $result]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductosReceta  $productosReceta
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductosReceta $productosReceta)
    {
        $id = $productosReceta->id;

        return response()->json(['destroy' => $id]);
    }

    /**
     * Elimina el producto de la receta
     * Name: producto_receta.elimina (GET)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function elimina(Request $request, $id)
    {
        $cant = ProductosReceta::where('id', '=', $id)->delete();
        
        if ($cant > 0) {
            $status = 'ok';
        } else {
            $status = 'error';
        }

        return response()->json(['status' => $status]);
    }

    /** 
     * Devuelve array para almacenar data en tabla
     */
    private function _datosProductoReceta(ProductosReceta $pr)
    {
       return [
           'producto_id' => $pr->producto_id,
           'producto_receta_id' => $pr->producto_receta_id,
           'descripcion' => $pr->descripcion,
           'cantidad' => $pr->cantidad,
           'costo' => $pr->costo
       ];
    }

}
