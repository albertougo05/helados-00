<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductoSucursal; 

class ProductoSucursalController extends Controller
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
        // producto_sucursal.store
    }

    /**
     * Borra todos lo registros del producto.
     * Name: producto_sucursal (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $estado = 'error';

        DB::beginTransaction();
            $prodSuc = ProductoSucursal::create([ 
                            'producto_id' => $request->input('producto_id'), 
                            'sucursal_id' => $request->input('sucursal_id'), 
                            'precio_vta'  => 0,
                        ]);

            if ($prodSuc) {
                DB::commit();
                $estado = 'ok';
            } else {
                DB::rollBack();
            }

        return response()->json([ 'estado' => $estado ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * Name: producto_sucursal.update
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $producto_sucursal
     * @return \Illuminate\Http\Response
     */
    public function destroy($producto_sucursal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * Name: producto_sucursal.elimina (GET)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function elimina($id)
    {
        ProductoSucursal::where('id', $id)->delete();

        return response()->json([ 'estado' => 'ok' ]);
    }
}
