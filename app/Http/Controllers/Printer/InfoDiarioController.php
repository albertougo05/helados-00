<?php

namespace App\Http\Controllers\Printer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Stock\InformeDiarioController;


/**
 * Controlador devuelve datos para imprimir cierre de caja/turno (sin Auth!)
 * (Llamado desde localhost/printer/...)
 * Name: toprint.infodiario (GET)
 * 
 * Url server: http://localhost:8000/toprint/infodiario?sucursal_id=1
 * 
 * @param Request $request
 * @return json
 */
class InfoDiarioController extends Controller
{
    public function enviar(Request $request)
    {
        $data = (new InformeDiarioController)->getListaArticulos($request->input('sucursal_id'));

        return response()->json($data);
    }

}
