<?php

namespace App\Http\Controllers\Printer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComprobanteVenta;
use App\Models\DetalleComprobVenta;
use App\Models\Sucursal;
use App\Models\Turno;
use App\Models\CajaMovimiento;
use App\Models\Producto;


/**
 * Controlador devuelve datos para imprimir cierre de caja/turno (sin Auth!)
 * (Llamado desde localhost/printer/...)
 * Name: toprint.cierrecaja (GET)
 * 
 * Url server: http://localhost:8000/toprint/cierrecaja?sucursal_id=1&cierre_id=60
 * 
 * @param Request $request
 * @return Response json
 */
class CierreCajaController extends Controller
{
    public function enviar(Request $request)
    {
        $data = $this->_getData($request);

        return response()->json($data);
    }

    /**
     * FUNCIONES PRIVADAS
     */
    private function _getData(Request $request)
    {
        $turno = $this->_getTurno($request->input('cierre_id'));
        $sucursal = Sucursal::find($request->input('sucursal_id'))->nombre;
        $detalle = $this->_getDetalleVtaAndMovs($request->input('cierre_id'), $turno);
        $suma_detalle = $this->_getSumaDetalle($detalle);
        $arqueo = $this->_getArqueoCaja($turno, $suma_detalle);
        $resumen = $this->_getResumen($request->input('cierre_id'), $turno);

        return compact(
            'turno', 
            'sucursal',
            'detalle',
            'suma_detalle',
            'arqueo',
            'resumen',
        );
    }

    private function _getTurno($id) : array 
    {
        $turno = Turno::select('turnos.id', 
                'turnos.turno_sucursal',
                'turnos.caja_nro',
                'turnos.apertura_fecha_hora',
                'turnos.cierre_fecha_hora',
                'turnos.saldo_inicio',
                'turnos.venta_total',
                'turnos.efectivo',
                'turnos.tarjeta_credito',
                'turnos.tarjeta_debito',
                'turnos.cuenta_corriente',
                'turnos.otros',
                'turnos.egresos',
                'turnos.ingresos',
                'turnos.caja',
                'turnos.arqueo',
                'turnos.diferencia',
                'turnos.observaciones',
                'users.name as nombre_usuario')
            ->join('users', 'turnos.usuario_id', 'users.id')
            ->where('turnos.id', $id)
            ->get()
            ->toArray();

        return $turno[0];
    }

    private function _getDetalleVtaAndMovs($idTurno, $turno) : array 
    {
        $movimientos = $this->_getMovimientosCaja($idTurno);
        $ventas = $this->_getVentas($turno);

        return array_merge($movimientos, $ventas);
    }

    private function _getMovimientosCaja($idCierre) : array 
    {
        $movimientos = CajaMovimiento::select('id AS nro_comprobante',
                'usuario_id AS codigo',
                'tipo_movim_id',
                'concepto AS descripcion',
                'importe')
            ->where([['estado', 1],
                ['turno_cierre_id', $idCierre],
                ['tipo_movim_id', '>', 0]])
            ->orderBy('id')
            ->get()
            ->toArray();

        $movimientos = $this->_convertirIngrEgre($movimientos);

        return $movimientos;
    }

    private function _getVentas($turno) : array 
    {
        // Buscar numeros de comprobantes desde hasta
        $nrosComprobantes = $this->_getNrosComprobantes($turno['id']);

        if (count($nrosComprobantes) > 0) {
            $detalle = DetalleComprobVenta::selectRaw(
                    'producto_codigo, SUM(cantidad) AS cantidad, SUM(subtotal) AS importe')
                ->where('estado', 1)
                //->whereBetween('fecha_hora', [$desde, $hasta])
                ->whereBetween('nro_comprobante', [$nrosComprobantes['desde'], $nrosComprobantes['hasta']])
                ->groupBy('producto_codigo')
                ->orderBy('producto_codigo')
                ->get()
                ->toArray();
    
            $detalle = $this->_agregarDescripcion($detalle);
        } else $detalle = [];

        return $detalle;
    }

    private function _getNrosComprobantes($turno_id) : array 
    {
        $comprobs = ComprobanteVenta::select('nro_comprobante')
            ->where('turno_id', $turno_id)
            ->get()
            ->toArray();
        $cant = count($comprobs);

        if ($cant > 0) {
            return [ 
                'desde' => $comprobs[0]['nro_comprobante'], 
                'hasta' => $comprobs[$cant - 1]['nro_comprobante'] 
            ];
        } else return [];
    }

    private function _agregarDescripcion($detalle) : array 
    {
        $ventas = [];

        foreach ($detalle as $value) {
            $producto = Producto::where('codigo', $value['producto_codigo'])
                ->value('descripcion_ticket');
            $venta = [
                'codigo' => $value['producto_codigo'],
                'descripcion' => $producto,
                'cantidad' => (int) $value['cantidad'],
                'importe' => floatval($value['importe']),
            ];
            $ventas[] = $venta;
        }
        usort($ventas, array($this,'_ordenarPorDesc'));

        return $ventas;
    }

	private function _ordenarPorDesc($a, $b)
	{
		if ($a == $b)
            return 0;

        return ($a['descripcion'] < $b['descripcion']) ? -1 : 1;
	}

    private function _getSumaDetalle($detalle) : float {
        $suma = 0;
        foreach ($detalle as $value) {
            if (gettype($value['importe'] === 'string')) {
                $suma += floatval($value['importe']);
            } else {
                $suma += $value['importe'];
            }
        }

        return $suma;
    }

    private function _convertirIngrEgre($movs) : array 
    {
        $movims = [];
        foreach ($movs as $value) {
            $value['importe'] = floatval($value['importe']);
            if ((integer) $value['tipo_movim_id'] > 4 ) {       // 5,6,7 y 8
                $value['importe'] = 0 - $value['importe'];      // negativo
            }
            $movims[] = $value;
        }
        return $movims;
    }

    private function _getArqueoCaja($turno, $sumaDetalle) : array 
    {
        $efectivo = floatval($turno['arqueo']);
        $totalReal = $this->_getTotalCajaReal($efectivo, $turno);
        $diferencia = $totalReal - $sumaDetalle;

        return [
            'efectivo' => $efectivo,
            'tarj_credito' => floatval($turno['tarjeta_credito']),
            'tarj_debito' => floatval($turno['tarjeta_debito']),
            'cta_cte' => floatval($turno['cuenta_corriente']),
            'otros_conc' => floatval($turno['otros']),
            'total_real' => $totalReal,
            'caja_teorica' => $sumaDetalle,
            'diferencia' => $diferencia,
        ];
    }

    private function _getTotalCajaReal($efectivo, $turno) : float 
    {
        $total = $efectivo + floatval($turno['tarjeta_credito']);
        $total += floatval($turno['tarjeta_debito']) + floatval($turno['cuenta_corriente']);
        $total += floatval($turno['otros']);

        return $total;
    }

    private function _getResumen($cierre_id, $turno) : array 
    {
        $pedidos = $this->_getPedidos($cierre_id);

        return [
            'venta_total' => floatval($turno['venta_total']),
            'ventas_anuladas' => $this->_getImporteVtasAnuladas($cierre_id),
            'pedidos_emitidos' => $pedidos['emitidos'],
            'pedidos_desde' => $pedidos['desde'],
            'pedidos_hasta' => $pedidos['hasta'],
            'pedidos_anulados' => $pedidos['anulados'],
        ];
    }

    private function _getImporteVtasAnuladas($id) : int
    {
        $vtas_anul = ComprobanteVenta::selectRaw('SUM(total) AS importe')
            ->where('estado', 0)
            ->where('turno_sucursal', $id)
            ->get()
            ->toArray();

        if ($vtas_anul[0]['importe'] === null) {
            return 0;
        } else {
            return $vtas_anul[0]['importe'];
        }
    }

    private function _getPedidos($id) : array
    {
        $pedidos = ComprobanteVenta::select('nro_comprobante', 'estado')
            ->where('turno_id', $id)
            ->get()
            ->toArray();
        $anulados = 0;
        foreach ($pedidos as $value) {
            if ($value['estado'] === 0) $anulados++;
        }

        return [
            'emitidos' => count($pedidos),
            'desde' => $pedidos[0]['nro_comprobante'] ?? 0,
            'hasta' => $pedidos[count($pedidos) - 1]['nro_comprobante'] ?? 0,
            'anulados' => $anulados,
        ];
    }

}
