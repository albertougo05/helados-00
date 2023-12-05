<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;
use App\Models\CajaMovimiento;


class CierraTurnoCajaController extends Controller
{
    /**
     * Registra el ID de cierre de caja en tabla caja_movimientos
     * Name: turno.cierraturnocaja
     *
     * @param  \Illuminate\Http\Request  $request (sucursal_id, caja_nro, cierre_id)
     * @return \Illuminate\Http\Response Json
     */
    //public function __invoke(Request $request)
    public function cierra($sucursal_id, $caja_nro, $cierre_id)
    {
        $resultado = ["error"];

        if ($this->_setTurnoId($sucursal_id, $caja_nro, $cierre_id)) {
            $resultado = ["ok"];
        }

        return response()->json($resultado);
    }

    private function _setTurnoId($sucursal_id, $caja_nro, $cierre_id)
    {
        $cantReg = CajaMovimiento::where('turno_cierre_id', 0)
            ->where('sucursal_id', $sucursal_id)
            ->where('caja_nro', $caja_nro)
            ->update(['turno_cierre_id' => $cierre_id]);

        if ($cantReg > 0) {
            return true;
        } else {
            return false;
        }
    }
}
