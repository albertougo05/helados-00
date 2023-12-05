<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Utils\Utils;
use App\Models\Turno;
use App\Models\User;
use App\Models\Sucursal;


class InfoFiltradoController extends Controller
{
    private $_cant_paginas = 10;

    /**
     * Informe Filtrado de turnos
     * Name: turnos.informe.filtrado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Utils $utils)
    {
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $sucursal = Sucursal::select('nombre')
            ->find($sucursal_id);

        if (isset($request->page)) {    // Si viene paginado tomo las variables de session
            $usuario_id = session('usuario_id');
            $caja_nro = session('caja_nro');
            $hasta = session('hasta');
            $desde = session('desde');
        } else {
            $usuario_id = $request->usuario_id;
            $caja_nro = $request->caja_nro;
            $hasta = $request->hasta;
            $desde = $request->desde;
            $this->_setSession($usuario_id, $caja_nro, $hasta, $desde);
        }

        session(['filtroUrl' => $request->fullUrl()]);

        $usuarios = User::select('id', 'name')->get();
        $cajas = $utils->getCajas($sucursal_id);
        $turnos = $this->_getTurnos($sucursal_id, $usuario_id, $caja_nro, $desde, $hasta);

        $data = compact(
            'usuario_id',
            'sucursal',
            'caja_nro',
            'turnos',
            'usuarios',
            'desde',
            'hasta',
            'cajas',
        );

        return view('turnos.informes.filtrado', $data);
    }


    private function _setSession($usuario_id, $caja_nro, $hasta, $desde)
    {
        session(['usuario_id' => $usuario_id]);
        session(['caja_nro' => $caja_nro]);
        session(['hasta' => $hasta]);
        session(['desde' => $desde]);

        return null;
    }

    private function _getTurnos($sucursal_id, $usuario_id, $caja_nro, $desde, $hasta)
    {
        $condiciones = $this->_getCondiciones($sucursal_id, $usuario_id, $caja_nro, $desde, $hasta);

        $turnos = $this->_getTodosLosTurnos($condiciones);

        return $turnos;
    }

    private function _getCondiciones($sucursal_id, $usuario_id, $caja_nro, $desde, $hasta)
    {
        $condiciones = [
            ['turnos.sucursal_id', '=', $sucursal_id],
        ];

        if ($usuario_id != 0) {
            $condiciones[] = ['turnos.usuario_id', '=', $usuario_id];
        }

        if ($caja_nro != 0) {
            $condiciones[] = ['turnos.caja_nro', '=', $caja_nro];
        }

        $fechas = $this->_setDesdeHasta($desde, $hasta);

        if ($fechas['desde'] !== '') {
            $condiciones[] = ['turnos.apertura_fecha_hora', '>=', $fechas['desde']];
        }

        $condiciones[] = ['turnos.apertura_fecha_hora', '<=', $fechas['hasta']];

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

    private function _getTodosLosTurnos($condiciones)
    {

        $turnos = Turno::select('turnos.id', 'turnos.sucursal_id', 'turnos.usuario_id',
                                'turnos.apertura_fecha_hora', 'turnos.cierre_fecha_hora',
                                'turnos.saldo_inicio', 'turnos.venta_total', 'turnos.efectivo',
                                'turnos.tarjeta_credito', 'turnos.tarjeta_debito', 'turnos.caja',
                                'turnos.cuenta_corriente', 'turnos.egresos', 'turnos.ingresos',
                                'turnos.arqueo', 'turnos.diferencia', 'turnos.estado', 'turnos.caja_nro',
                                'turnos.observaciones', 'sucursales.nombre', 'users.name')
            ->leftjoin('sucursales', 'turnos.sucursal_id', '=', 'sucursales.id')
            ->leftjoin('users', 'turnos.usuario_id', '=', 'users.id')
            ->where($condiciones)
            ->orderBy('turnos.id', 'desc')
            ->paginate($this->_cant_paginas);

        return $turnos;
    }

}
