<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\StockDetalleComprobante;
use Illuminate\Http\Request;
use Exception;


class DetalleComprobanteStockController extends Controller
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
     * Store a newly created resource in storage.  (Buscar: stock_comprobante_id)
     * Name: detalle_comprobante_stock (POST)
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $respuesta = ['result' => 'ok'];
        $envio = $request->all();

        foreach ($envio['detalle'] as $value) {
            try {
                StockDetalleComprobante::create($value);
            } catch (Exception $e) {
                $respuesta = ['result' => 'error', 'message' => $e];
            }
        };

        return json_encode($respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockDetalleComprobante  $detalleComprobanteStock
     * @return \Illuminate\Http\Response
     */
    public function show(StockDetalleComprobante $detalleComprobanteStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Name: detalle_comprobante_stock.edit (/detalle_comprobante_stock/id/edit )
     *
     * @param  \App\Models\StockDetalleComprobante  $detalleComprobanteStock
     * @return \Illuminate\Http\Response
     */
    public function edit(StockDetalleComprobante $detalleComprobanteStock)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $result = 'ok';
        $comprob_id = $request->comprob_id;
        try {
            $detalle = $request->detalle;

            //dd($detalle, $comprob_id);

            foreach ($detalle as $value) {
                StockDetalleComprobante::updateOrCreate(
                    ['id' => $value['id']],
                    $value
                );
            }
        } catch (\Exception $e) {
            $result = 'error: '.$e->getMessage();
        }

        return response()->json(['result' => $result]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockDetalleComprobante  $detalleComprobanteStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockDetalleComprobante $detalleComprobanteStock)
    {
        //
    }
}
