<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use App\Models\CajaTiposMovim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ValidateCajaTipoRequest;


class TiposMovimientoController extends Controller
{
    /**
     * Lstado de Tipos de movimientos de caja
     * Name: caja_tipos_movim.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiposMov = CajaTiposMovim::orderBy('descripcion')
            ->paginate(15);

        $data = compact(
            'tiposMov', 
        );

        return view('caja.tipos_movim.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * Name: caja_tipos_movim.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipo = CajaTiposMovim::latest()->first();
        $newid = (integer) $tipo->id + 1;

        $data = compact(
            'newid',
        );

        return view('caja.tipos_movim.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * Name: caja_tipos_movim.store (POST)
     *
     * @param ValidateCajaTipoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateCajaTipoRequest $request)
    {
        $request->validated();
        $tipo = CajaTiposMovim::create([
            'descripcion' => $request->descripcion,
            'tipo_movim_id' => (integer) $request->tipo_movim_id,
        ]);

        if ($tipo) {
            return redirect(route('caja_tipos_movim.index'))->with('status', 'Nuevo Tipo creado !');
        } else {
            return redirect(route('caja_tipos_movim.index'))->with('status', 'No se pudo crear nuevo Tipo !!');
        }
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
     * Name: caja_tipos_movim.edit (PUT)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo = CajaTiposMovim::find($id);

        return view('caja.tipos_movim.edit', compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     * Name: caja_tipos_movim.update
     *
     * @param  ValidateCajaTipoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ValidateCajaTipoRequest $request)
    {
        $request->validated();
        $data = [
            'descripcion' => $request->descripcion,
            'tipo_movim_id' => (integer) $request->tipo_movim_id,
            'estado' => $request->estado
        ];

        // Start transaction
        DB::beginTransaction();
            $rows =  CajaTiposMovim::where('id', $request->input('id'))
                        ->lockForUpdate()
                        ->update($data);

        if ($rows == 0) {
            DB::rollBack();
            return redirect(route('caja_tipos_movim.index'))->with('status', 'NO se pudo actualizar Tipo !!');
        }

        DB::commit();

        return redirect(route('caja_tipos_movim.index'))->with('status', 'Tipo actualizado !');
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
