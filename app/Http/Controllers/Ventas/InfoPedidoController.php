<?php

namespace App\Http\Controllers\Ventas;


use App\Http\Controllers\Controller;
use App\Models\ComprobanteVenta;
use App\Models\Turno;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Utils\Utils;
use App\Models\User;

class InfoPedidoController extends Controller
{
    /**
     * Informe de pedidos (ventas tickets) del turno actual.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Utils $utils)
    {
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $usuario_id = Auth::user()->id;
        $caja_nro = $this->_getCajaNro($sucursal_id);
        $turno = $this->_getTurnoId($sucursal_id, $usuario_id);

        if ($turno) {
            $turno_id = $turno['id'];
            $turno_sucursal = $turno['turno_sucursal'];
            $usuario_name = User::find($turno['usuario_id'])->name;
        } else {
            return redirect('abreturno');   // Si no hay turno abierto, pide abrir uno
        }

        $comprobantes = $this->_getComprobantes($sucursal_id, $turno_id);

//dd($comprobantes);

        $formas_pago = [
            'Movim. caja', 'Efectivo', 'Cta. cte.', 'Otra forma pago', 'T. Crédito', 'T. Débito'
        ];

        $impresora = $utils->getImpresoras($sucursal_id, $caja_nro);
        //$impresora = [['nombre' => '']];
        $env = env('APP_ENV');

        $data = compact(
            'turno_id', 
            'turno_sucursal', 
            'sucursal_id',
            'usuario_id',
            'usuario_name',
            'comprobantes',
            'impresora',
            'env',
            'formas_pago',
        );

        //dd($data);

        return view('ventas.informes.pedidos', $data);
    }


    /**
     * 
     * FUNCIONES PRIVADAS
     * 
     */

    /**
     * Devuelve id de turno abierto
     * 
     * @param $suc_id  int
     * @param $user_id int
     * @return integer
     */
     private function _getTurnoId($suc_id, $user_id)
    {
        if (Auth::user()->perfil_id === 1 || Auth::user()->perfil_id === 2) {
            // selecciona caja y cualquier usuario
            $turno = Turno::select('id', 'turno_sucursal', 'usuario_id')
            ->where('sucursal_id', $suc_id)
            ->where('cierre_fecha_hora', null)
            ->first();
        } else {
            $turno = Turno::select('id', 'turno_sucursal', 'usuario_id')
                ->where('sucursal_id', $suc_id)
                ->where('usuario_id', $user_id)
                ->where('cierre_fecha_hora', null)
                ->first();
        }

        if (!$turno) {
            # Si no encuentra el turno, devuelve false
            return false;
        }

        return ['id' => $turno->id,
            'turno_sucursal' => $turno->turno_sucursal,
            'usuario_id' => $turno->usuario_id];
    }

    private function _getComprobantes(int $suc_id, int $turno_id)
    {
        $comprobs = ComprobanteVenta::join(
            'caja_movimientos', 
            'comprobantes_venta.nro_comprobante', 
            '=', 
            'caja_movimientos.nro_comprobante'
            )
            ->select(
                'comprobantes_venta.id',
                'comprobantes_venta.sucursal_id',
                'comprobantes_venta.usuario_id',
                'comprobantes_venta.caja_nro',
                'comprobantes_venta.nro_comprobante',
                'comprobantes_venta.turno_id',
                'comprobantes_venta.cliente_id',
                'comprobantes_venta.fecha',
                'comprobantes_venta.hora',
                'comprobantes_venta.total',
                'comprobantes_venta.estado',
                'caja_movimientos.forma_pago_id'
                )
            ->where('comprobantes_venta.sucursal_id', $suc_id)
            ->where('comprobantes_venta.turno_id', $turno_id)
            ->get()
            ->toArray();

        return $comprobs;
    }

    private function _getCajaNro($sucursa_id)
    {
        $turno = Turno::where('sucursal_id', $sucursa_id)
            ->where('usuario_id', Auth::user()->id)
            ->where('cierre_fecha_hora', null )
            ->first();

        if ($turno) {
            $caja_nro = $turno->caja_nro;
        } else $caja_nro = 0;

        return $caja_nro;
    }
}
