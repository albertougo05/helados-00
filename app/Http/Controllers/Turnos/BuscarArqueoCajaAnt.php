<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Turno;


class BuscarArqueoCajaAnt extends Controller
{
    /**
     * Busca arqueo de caja para nuevo turno 
     * (Request params: sucursal_id, caja_nro)
     * Name: turno.buscararqueo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response Json
     */
    public function __invoke(Request $request)
    {
        //$turno = Turno::select('caja')
        $turno = Turno::select('arqueo')
            ->where('sucursal_id', $request->suc_id)
            ->where('caja_nro', $request->caja_nro)
            ->latest()
            ->first();

        if (!$turno) {
            $turno['arqueo'] = 0;
        } else {
            $turno->toArray();
            $turno['arqueo'] = floatval($turno['arqueo']);
            // $turno['arqueo'] = floatval($turno['arqueo']) + floatval($turno['diferencia']);
            //$turno['arqueo'] = floatval($turno['caja']);
        }

        return response()->json($turno);
    }

}
