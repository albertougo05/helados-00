<?php

namespace App\Http\Controllers\Ventas\Imprimir;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Utils\Utils;
use App\Models\ComprobanteVenta;
use App\Models\DetalleComprobVenta;
use App\Models\Sucursal;
use App\Models\Turno;


class ImprimirController extends Controller
{
    /**
     * Imprime comprobantes de ventas (con ventana en pestaña para imprimir por impresora local)
     * Name: ventas.comprobante.imprimir
     * http://localhost:8000/ventas/comprobante/imprimir?id=
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function imprimir(Request $request, Utils $utils)
    {
        $data = $this->_getData($request, $utils);

        //dd($data);

        return view('ventas.imprimir', $data);
    }


    private function _getData(Request $request, Utils $utils)
    {
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $sist_operativo = $utils->getSistemaOperativo();
        $impresoras = $utils->getImpresoras();
        $impresora = $impresoras[0];

        $comprobante = ComprobanteVenta::select(
                'sucursal_id', 
                'nro_comprobante', 
                'turno_id', 
                'total')
            ->find($request->input('id'))
            ->toArray();

        $detalle = DetalleComprobVenta::select(
                'descripcion', 
                'cantidad', 
                'precio_unitario', 
                'subtotal')
            ->where('comprobante_id', $request->input('id'))
            ->get()
            ->toArray();

        $sucursal = Sucursal::find($sucursal_id)->nombre;
        $nro_comprobante = (integer) $comprobante['nro_comprobante'];
        $pedido_nro = substr(strval($nro_comprobante), -2);
        $serial = sprintf("%06d", substr(strval($nro_comprobante), 0, -2)); //  Rellena con 0 a la izquierda
        $turno_sucursal = $this->_getTurnoId($comprobante['sucursal_id']);

        return compact(
            'comprobante', 
            'detalle',
            'sucursal',
            'nro_comprobante',
            'pedido_nro', 
            'serial',
            'turno_sucursal',
            'sist_operativo',
            'impresora',
        );
    }

    private function _getTurnoId($sucursal_id)
    {
        $turno = Turno::select('turno_sucursal')
            ->where('cierre_fecha_hora', null)
            ->where('sucursal_id', $sucursal_id)
            ->where('usuario_id', Auth::user()->id)
            ->first();

        return sprintf("%05d", $turno->turno_sucursal);
    }

}
