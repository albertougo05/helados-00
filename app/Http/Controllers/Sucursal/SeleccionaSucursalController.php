<?php

namespace App\Http\Controllers\Sucursal;


use App\Http\Controllers\Controller;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SeleccionaSucursalController extends Controller
{
    /**
     * Pantalla donde el administrador, selecciona sucursal.
     * Name: selecciona.sucursal
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $sucursal = Sucursal::select('nombre')
            ->find($sucursal_id);

        $sucursales = Sucursal::select('id', 'nombre')
            ->orderBy('nombre') 
            ->get();

        return view('sucursales.selecciona.index', compact('sucursales', 'sucursal'));
    }

    /**
     * Selecciona sucursal para administradores
     * Name: selecciona.update
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $sucursal = Sucursal::find($request->sucursal_id);
        $textoSuccess = "AHORA está, en sucursal: " . $sucursal->nombre . " !";

        // Creo nueva session key
        session(['sucursal_id' => $request->sucursal_id]);

        return redirect()
            ->back()
            ->with('status', $textoSuccess);
    }


}