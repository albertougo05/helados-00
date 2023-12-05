<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CajaMovimiento;
use App\Models\Sucursal;
use App\Models\User;
use App\Http\Controllers\Utils\Utils;


class MovimientosCajaFiltrado extends Controller
{
    private $cant_paginas = 15;

    /**
     * Informe movimiemtos de caja filtrado
     * Name: movimientos_caja.index
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Utils $utils)
    {
        if (isset($request->page)) {    // Si viene paginado tomo las variables de session
            $sucursal_id = session('sucursal_id');
            $usuario_id = session('usuario_id');
            $caja_nro = session('caja_nro');
            $turno_cierre = session('turno_cierre');
            $hasta = session('hasta');
            $desde = session('desde');
        } else {
            $sucursal_id = $request->suc_id;
            $usuario_id = $request->usuario_id;
            $caja_nro = $request->caja_nro;
            $turno_cierre = $request->turno_id;
            $hasta = $request->hasta;
            $desde = $request->desde;
            $this->_setSession($sucursal_id, $usuario_id, $caja_nro, $turno_cierre, $hasta, $desde);
        }

        session(['filtroUrl' => $request->fullUrl()]);
        $movims = $this->_getMovimientos($sucursal_id, $usuario_id, $caja_nro, $turno_cierre, $desde, $hasta);
        $user_perfil = $utils->getUserPerfil();
        $usuarios = $this->_getUsuariosSegunPerfil($user_perfil, $sucursal_id);
        $sucursales = Sucursal::orderBy('nombre')
            ->get();
        $ptos_vta = $utils->getCajas((integer) $sucursal_id);

        $data = compact(
            'movims', 
            'sucursal_id', 
            'usuario_id',
            'caja_nro',
            'turno_cierre',
            'usuarios',
            'sucursales',
            'desde',
            'hasta',
            'ptos_vta',
            'user_perfil',
        );

        if (count($movims) === 0) {
            return back()->with('status', 'Sin movimientos !!');
        }

        return view('caja.filtrado.index', $data);
    }

    private function _getUsuariosSegunPerfil($perfil, $sucursal_id)
    {
        switch ($perfil) {
            case 'admin':
                $usuarios = User::where('estado', 1)
                    ->where('sucursal_id', $sucursal_id)
                    ->get();
                break;
            case 'empleado':
                $usuarios = User::where('estado', 1)
                    ->where('sucursal_id', $sucursal_id)
                    ->where('perfil_id', 3)
                    ->get();
                break;
                
        }

        return $usuarios;
    }

    private function _getMovimientos($sucursal_id, $usuario_id, $caja_nro, $turno_cierre, $desde, $hasta)
    {
        $condiciones = $this->_getCondiciones($sucursal_id, $usuario_id, $caja_nro, $turno_cierre, $desde, $hasta);
        $movims = $this->_getMovsCaja($condiciones);

        return $movims;
    }

    private function _setSession($sucursal_id, $usuario_id, $caja_nro, $turno_cierre, $hasta, $desde)
    {
        session(['sucursal_id' => $sucursal_id]);
        session(['usuario_id' => $usuario_id]);
        session(['caja_nro' => $caja_nro]);
        session(['turno_cierre' => $turno_cierre]);
        session(['hasta' => $hasta]);
        session(['desde' => $desde]);

        return null;
    }

    private function _getCondiciones($sucursal_id, $usuario_id, $caja_nro, $turno_cierre, $desde, $hasta)
    {
        $condiciones = [
            ['caja_movimientos.sucursal_id', '=', $sucursal_id],
            ['tipo_comprobante_id', '>', 3],        // tipo_comprobante_id == 4 or == 5
        ];
        if ($usuario_id != 0) {
            $condiciones[] = ['caja_movimientos.usuario_id', '=', $usuario_id];
        }
        if ($caja_nro != 0) {
            $condiciones[] = ['caja_movimientos.caja_nro', '=', $caja_nro];
        }
        if ($turno_cierre != null) {
            $condiciones[] = ['caja_movimientos.turno_cierre_id', '=', $turno_cierre];
        }
        $fechas = $this->_setDesdeHasta($desde, $hasta);
        if ($fechas['desde'] !== '') {
            $condiciones[] = ['caja_movimientos.fecha_hora', '>=', $fechas['desde']];
        }
        $condiciones[] = ['caja_movimientos.fecha_hora', '<=', $fechas['hasta']];

        return $condiciones;
    }

    private function _setDesdeHasta($desde, $hasta)
    {
        $fechas = [
            'desde' => '',
            'hasta' => ''
        ];
        if ($desde) {
            $fechas['desde'] = $desde . " 00:00";
        };
        if (!$hasta) {
            $hasta = date('Y-m-d');
        }
        $fechas['hasta'] = $hasta . " 23:59";

        return $fechas;
    }

    private function _getMovsCaja($condiciones)
    {
        $movims = CajaMovimiento::select('caja_movimientos.id', 'caja_movimientos.sucursal_id',
                    'caja_movimientos.caja_nro', 'caja_movimientos.tipo_comprobante_id',
                    'caja_movimientos.fecha_hora', 'caja_movimientos.total_efectivo',  
                    'caja_movimientos.total_debito', 'caja_movimientos.total_tarjeta',
                    'caja_movimientos.total_valores', 'caja_movimientos.total_transfer',
                    'caja_movimientos.total_bonos', 'caja_movimientos.total_retenciones',
                    'caja_movimientos.total_otros', 'caja_movimientos.cuenta_corriente', 
                    'caja_movimientos.estado', 'caja_movimientos.usuario_id',
                    'caja_movimientos.codigo_comprob', 'caja_movimientos.nro_comprobante', 
                    'caja_movimientos.concepto', 'caja_movimientos.importe', 'sucursales.nombre',
                    'caja_movimientos.turno_cierre_id', 'users.name')
            ->leftJoin('sucursales', 'caja_movimientos.sucursal_id', '=', 'sucursales.id')
            ->leftjoin('users', 'caja_movimientos.usuario_id', '=', 'users.id')
            ->where($condiciones)
            ->orderBy('caja_movimientos.fecha_hora', 'desc')
            ->paginate($this->cant_paginas);

        return $movims;        
    }

}
