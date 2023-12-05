<?php

namespace App\Http\Controllers\Printer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Stock\ImprimeComprobanteController;


/**
 * Controlador devuelve datos para imprimir ingreso movimiento de stock (sin Auth!)
 * (Llamado desde localhost/printer/...)
 * Name: toprint.ingresostock (GET)
 * 
 * Url server: http://localhost:8000/toprint/ingresostock?comprob_id=13&sucursal_id=1
 * 
 * @param Request $request
 * @return json
 */
class IngresoStockController extends Controller
{
    public function enviar(Request $request)
    {
        $data = (new ImprimeComprobanteController)->detalleComprobante(
            $request->input('comprob_id'),
            $request->input('sucursal_id'),
        );

        return response()->json($data);
    }

}
