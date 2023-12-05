<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CajaMovimiento;


class ComprobantesVtasController extends Controller
{
    /**
     * Búsqueda de comprobantes de ventas
     * (Para cerrar turno caja)
     * Name: turno.comprobantesvtas
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response Json
     */
    public function __invoke(Request $request)
    {
        $result = ['Sin datos'];
        $dataDB = $this->_getDataDB(
            $request->suc_id, 
            $request->caja_nro,
            $request->turno_cierre_id
        );

        if ($dataDB) {
            return response()->json($dataDB);
        }

        return response()->json($result);
        //return response()->json([$request->suc_id, $request->caja_nro]);
    }

    private function _getDataDB($suc_id, $caja_nro, $cierre_id)
    {
        $data = CajaMovimiento::select('caja_movimientos.id', 'caja_movimientos.sucursal_id',
                'caja_movimientos.caja_nro', 'caja_movimientos.tipo_comprobante_id',
                'caja_movimientos.fecha_hora', 'caja_movimientos.total_efectivo',  
                'caja_movimientos.total_debito', 'caja_movimientos.total_tarjeta',
                'caja_movimientos.total_valores', 'caja_movimientos.total_transfer',
                'caja_movimientos.total_bonos', 'caja_movimientos.total_retenciones',
                'caja_movimientos.total_otros', 'caja_movimientos.cuenta_corriente', 
                'caja_movimientos.estado',
                'caja_movimientos.codigo_comprob', 'caja_movimientos.nro_comprobante', 
                'caja_movimientos.concepto', 'caja_movimientos.importe', 'sucursales.nombre')
            ->leftJoin('sucursales', 'caja_movimientos.sucursal_id', '=', 'sucursales.id')
            ->where('caja_movimientos.turno_cierre_id', $cierre_id)
            ->where('caja_movimientos.sucursal_id', $suc_id)
            ->where('caja_movimientos.caja_nro', $caja_nro)
            ->orderBy('caja_movimientos.fecha_hora', 'desc')
            ->get()
            ->toArray();

        return $data;
    }
}
