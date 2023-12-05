<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;


class AbreTurnoController extends Controller
{
    /**
     * Pregunta si quiere abrir un turno de caja. Y redirecciona...
     * Name: turno.abre
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('turnos.abre');
    }


}
