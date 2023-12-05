<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
//use Illuminate\Http\Request;


class ImprimeCierreController extends Controller
{
    /**
     * Muestra pagina que imprime cierre de caja y/o turno. Y redirecciona...
     * Name: turno.imprime.cierre
     *
     * @return \Illuminate\Http\Response
     */
    //public function index(Request $request)     // Provisorio para pruebas...
    public function index($sucursal_id, $caja_nro, $cierre_id)
    {
        //$sucursal_id = $request->input('sucursal_id'); $caja_nro = $request->input('caja_nro'); $cierre_id = $request->input('cierre_id');
        $impresora = (new Utils)->getImpresoras($sucursal_id, $caja_nro);
        //$impresora = [['nombre' => '']];
        $env = env('APP_ENV');

        $data = compact(
            'sucursal_id',
            'caja_nro',
            'cierre_id',
            'impresora',
            'env',
        );

        //dd($data);
        // http://localhost:8000/turno/imprime/cierre?sucursal_id=1&caja_nro=1&cierre_id=12
        return view('turnos.imprime.cierre', $data);
    }

    /**
     * Redirecciona a turno/index
     * Name: turno.imprime.exit
     */
    public function exit()
    {
        return redirect(route('turno.index'))->with('status', 'Caja cerrada !');
    }

}
