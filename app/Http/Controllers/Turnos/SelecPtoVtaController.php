<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Sucursal;


class SelecPtoVtaController extends Controller
{
    /**
     * 
     * Name: turno.selecptovta
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $sucursal = Sucursal::find($sucursal_id);
        $nros_cajas = $sucursal->cant_puntos_venta;

        for ($i=1; $i <= $nros_cajas; $i++) { 
            $ptosVta[] = [
                "id" => $i,
                "texto" => "Caja " . $i,
            ];
        }

        return view('turnos.selecptovta', compact('ptos_vta'));
    }


}
