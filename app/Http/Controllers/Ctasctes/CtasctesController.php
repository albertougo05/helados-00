<?php

namespace App\Http\Controllers\Ctasctes;

use App\Http\Controllers\Controller;
use App\Models\Firma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sucursal;
use App\Models\CajaMovimiento;


class CtasctesController extends Controller
{
    protected $cant_paginas = 15;

    /**
     * Informe de Cuentas Corrientes
     * Name: ctasctes.index
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $sucursal = Sucursal::select('nombre')
            ->find($sucursal_id);
        $usuario_id = Auth::user()->id;
        $clientes = Firma::select('id', 'firma', 'localidad')
                        ->where('cliente', 1)
                        ->get();

        $data =  compact(
            'sucursal_id', 
            'sucursal',
            'usuario_id',
            'clientes',
        );

        return view('ctasctes.index', $data);
    }

    /** 
     * Devuelve json con datos de cuenta corriente
     * Name: ctasctes.getdata (GET)
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request) {
        $data = CajaMovimiento::select('id', 'fecha_hora', 'fecha_registro', 
                'nro_comprobante', 'concepto', 'importe')
            ->where('firma_id', $request->input('firma_id'))
            ->where('estado', 1)
            ->whereBetween('fecha_registro', [$request->input('fecha_desde'), $request->input('fecha_hasta')])
            ->get()
            ->toArray();

        return response()->json($data);
    }

}
