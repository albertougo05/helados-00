<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;


class NoAbreTurnoController extends Controller
{
    /**
     * Respode que no puede abrir un turno de caja. Y redirecciona...
     * Name: turno.noabreturno
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('turnos.noabreturno');
    }


}
