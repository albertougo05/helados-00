<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Utils\Utils;
use App\Models\Turno;
use App\Models\User;
use App\Models\Sucursal;



class InformeController extends Controller
{
    private $_cant_paginas = 10;

    /**
     * Informe de turnos
     * Name: turnos.informe
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Utils $utils)
    {
        //$sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        // Si existe session key 'sucursal_id', la usa, si no, usa la del usuario
        if (session()->has('sucursal_id')) {
            $sucursal_id = session('sucursal_id');
        } else {
            $sucursal_id = Auth::user()->sucursal_id;
        }
        $sucursal = Sucursal::select('nombre')
            ->find($sucursal_id);

        $user_id = Auth::user()->id;

// SI EL USUARIO NO ES ADMIN, SOLO VE LOS TURNOS DE ESE USUARIO ?

        $usuarios = User::select('id', 'name')->get();
        $cajas = $utils->getCajas($sucursal_id);
        $fecha_hasta = date('Y-m-d');
        $fecha_desde = date("Y-m-d", strtotime($fecha_hasta.'- 30 days'));
        $turnos = $this->_getTodosLosTurnos($fecha_desde, $fecha_hasta, $sucursal_id);

        $data = compact(
            'turnos',
            'usuarios',
            'sucursal',
            'fecha_desde',
            'fecha_hasta',
            'cajas',
        );

        return view('turnos.informes.index', $data);
    }


    private function _getTodosLosTurnos(string $desde, string $hasta, int $suc_id = 1)
    {
        $desde = $desde ." 00:00";
        $hasta = $hasta ." 23:59";
        $turnos = Turno::select('turnos.id', 'turnos.turno_sucursal', 
                                'turnos.sucursal_id', 'turnos.usuario_id',
                                'turnos.apertura_fecha_hora', 'turnos.cierre_fecha_hora',
                                'turnos.saldo_inicio', 'turnos.venta_total', 'turnos.efectivo',
                                'turnos.tarjeta_credito', 'turnos.tarjeta_debito', 'turnos.caja',
                                'turnos.cuenta_corriente', 'turnos.egresos', 'turnos.ingresos',
                                'turnos.arqueo', 'turnos.diferencia', 'turnos.estado', 'turnos.caja_nro',
                                'turnos.observaciones', 'sucursales.nombre', 'users.name')
            ->leftjoin('sucursales', 'turnos.sucursal_id', '=', 'sucursales.id')
            ->leftjoin('users', 'turnos.usuario_id', '=', 'users.id')
            ->where('turnos.sucursal_id', $suc_id)
            ->where('apertura_fecha_hora', '>=', $desde)
            ->where('apertura_fecha_hora', '<=', $hasta)
            ->orderBy('turnos.id', 'desc')
            ->paginate($this->_cant_paginas);

        return $turnos;
    }

}
