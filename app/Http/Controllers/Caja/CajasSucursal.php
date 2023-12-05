<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Utils\Utils;


class CajasSucursal extends Controller
{
    /**
     * Devuelve las cajas de la sucursal. 
     * 
     * Name: movimientos_caja.cajas_sucursal
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response Json
     */
    public function __invoke(Request $request, Utils $utils)
    {
        $cajas = $utils->getCajas($request->sucursal_id);

        return response()->json($cajas);
    }

}
