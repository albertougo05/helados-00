<?php

namespace App\Http\Controllers\Firmas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TipoDocAfip;
use App\Models\Firma;


class FirmaController extends Controller
{
    /**
     * Muestra la lista de Firmas/Clientes.
     * Name: firmas.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filtroBuscar = false;
        $filtros = [];
        $tiposDocumento = TipoDocAfip::all();
        $firmas = Firma::where('estado', 1)
            ->orderBy('firma')
                ->paginate(10);

        return view('firmas.index', compact('firmas', 'tiposDocumento', 'filtroBuscar','filtros'));
    }

    /**
     * Show the form for creating a new resource.
     * Name: firmas.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dataCbos = $this->_dataBasicToForm();
        $new_id = $this->_newId();

        $data = array_merge($dataCbos, $new_id);

        return view('firmas.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $req_data = $request->all();
        $_key = array_shift($req_data);
        $firma = Firma::create($req_data);

        if ($firma) {
            return redirect(route('firma.index'))->with('status', 'Cliente/Firma creado !');

        } else return redirect(route('firma.index'))->with('status', 'No se pudo crear Cliente/Firma !!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Firma  $firma
     * @return \Illuminate\Http\Response
     */
    public function show(Firma $firma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Firma  $firma
     * @return \Illuminate\Http\Response
     */
    public function edit(Firma $firma)
    {
        $dataCbos = $this->_dataBasicToForm();
        $data = array_merge($dataCbos, compact('firma'));

        return view('firmas.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Firma  $firma
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Firma $firma)
    {
        $req_data = $request->all();
        $_key1 = array_shift($req_data);
        $_key2 = array_shift($req_data);
        unset($_key1, $_key2);

        $firma = Firma::where('id', $firma->id)
            ->update($req_data);

        if ($firma > 0) {
            return redirect(route('firma.index'))->with('status', 'Cliente/Firma actualizado !');

        } else return redirect(route('firma.index'))->with('status', 'No se pudo actualizar Cliente/Firma !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Firma  $firma
     * @return \Illuminate\Http\Response
     */
    public function destroy(Firma $firma)
    {
        //
    }

    /**
     * Lista de firmas filtradas
     * Name: firma.filtrado
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function filtrado(Request $request)
    {
        // $filtroBuscar = $request->input('buscar');

        // $filtros = $this->_setFiltros($request);

        // $productos = Producto::where($filtros)
        //                      ->orderBy('descripcion')
        //                      ->paginate(10);

        // return view('firmas.index', compact('firmas', 'tiposDocumento', 'filtroBuscar', 'filtros'));
    }

    /**
     * Devuelve datos de un cliente (campo cliente = 1)
     * 
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getCliente(Request $request)
    {
        $cliente = Firma::select('id', 'firma', 'direccion', 'localidad', 'plan_cuenta_id')
            ->find($request->input('cliente_id'))
            ->toArray();

        return response()->json($cliente);
    }


    private function _dataBasicToForm() : array 
    {
        $tipos_doc = TipoDocAfip::select('codigo_afip', 'documento')
            ->orderBy('documento')
            ->get()
            ->toArray();
        $condic_iva = ['CF' => 'Consumidor final', 
            'RI' => 'Resp. Inscripto', 
            'MO' => 'Monotributo', 
            'EX' => 'Exento', 
            'NR' => 'No responsable'];

        return compact(
            'tipos_doc',
            'condic_iva',
        );        
    }

    private function _newId() : array 
    {
        $new_id = Firma::select('id')
            ->latest()
            ->first()->id;

        return compact('new_id');
    }
}
