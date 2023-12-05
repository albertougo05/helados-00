<?php
namespace App\Http\Controllers\Printer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComprobanteVenta;
use App\Models\DetalleComprobVenta;
use App\Models\Sucursal;


/**
 * Controlador devuelve datos para imprimir comprobante de venta (sin Auth!)
 * Name: toprint.ticketventa (GET)
 * 
 * Url server: http://localhost:8000/toprint/ticketventa?idcomp=44&idsuc=1&iduser=4&turnosuc=12
 * 
 * @param Request $request
 */
class TicketVentaController extends Controller
{
    public function enviar(Request $request)
    {
        $data = $this->_getData($request);

        return response()->json($data);
    }

    private function _getData(Request $request)
    {
        $sucursal_id = $request->input('idsuc');
        $comprobante = $this->_getDataComprobVta($request->input('idcomp'));
        $detalle = $this->_getDataDetalleComprobVenta($request->input('idcomp'));
        $sucursal = Sucursal::find($sucursal_id)->nombre;
        $nro_comprobante = (integer) $comprobante['nro_comprobante'];
        $pedido_nro = substr(strval($nro_comprobante), -2);
        $serial = sprintf("%06d", substr(strval($nro_comprobante), 0, -2)); //  Rellena con 0 a la izquierda
        $turno_sucursal = $request->input('turnosuc');

        return compact(
            'comprobante', 
            'detalle',
            'sucursal',
            'nro_comprobante',
            'pedido_nro', 
            'serial',
            'turno_sucursal',
        );
    }

    private function _getDataComprobVta($id)
    {
        return ComprobanteVenta::select(
                'sucursal_id', 
                'nro_comprobante', 
                'turno_id', 
                'total')
            ->find($id)
            ->toArray();
    }

    private function _getDataDetalleComprobVenta($id)
    {
        return DetalleComprobVenta::select(
                'descripcion', 
                'cantidad', 
                'precio_unitario', 
                'subtotal')
            ->where('comprobante_id', $id)
            ->get()
            ->toArray();
    }

}
