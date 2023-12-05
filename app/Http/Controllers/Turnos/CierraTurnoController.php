<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CajaMovimiento;


class CierraTurnoController extends Controller
{
    /**
     * Cierra turno de caja
     * Name: turno.cierraturno
     *
     * @param  \Illuminate\Http\Request  $request (sucursal_id, caja_nro, cierre_id)
     * @return \Illuminate\Http\Response Json
     */
    public function __invoke(Request $request)
    {
        $resultado = ["error"];

        if ($this->_setTurnoId($request)) {
            $resultado = ["ok"];
        }

        return response()->json($resultado);
    }

    private function _setTurnoId(Request $req)
    {
        $cantReg = CajaMovimiento::where('turno_cierre_id', 0)
            ->where('sucursal_id', $req->sucursal_id)
            ->where('caja_nro', $req->caja_nro)
            ->update(['turno_cierre_id' => $req->cierre_id]);
        
        if ($cantReg > 0) {
            return true;
        } else {
            return false;
        }
    }



}
