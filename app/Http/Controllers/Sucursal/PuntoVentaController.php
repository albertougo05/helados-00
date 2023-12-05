<?php

namespace App\Http\Controllers\Sucursal;

use App\Http\Controllers\Controller;
use App\Models\PuntoVenta;
use App\Models\Sucursal;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PuntoVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Name: puntos_venta
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $puntosVta = PuntoVenta::orderBy('nombre')
            ->where('empresa_id', Auth::user()->empresa_id)
            ->paginate(10);

        $data = compact(
            'puntosVta', 
        );

        return view('sucursales.puntos_venta.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * Name: puntos_venta.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuario = Auth::user();
        $empresa_id = $usuario->empresa_id || 1;
        $empresa = Empresa::select('id', 'razon_social')
            ->find($empresa_id);

        $sucursales = Sucursal::where('empresa_id', $empresa_id);

        $data = compact(
            'empresa',
            'sucursales',
        );

        return view('sucursales.puntos_venta.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * Name: puntos_venta.store (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Name: puntos_venta.edit (PUT)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

}

