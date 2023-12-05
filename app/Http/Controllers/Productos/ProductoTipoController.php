<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ProductoTipo;
use App\Http\Requests\ValidateProdTipoRequest;


class ProductoTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos = ProductoTipo::orderBy('descripcion')
            ->paginate(10);

        return view('productos.tipos.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipo = ProductoTipo::latest()->first();
        $newid = (integer) $tipo->id + 1;

        $data = compact(
            'newid',
        );

        return view('productos.tipos.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ValidateProdTipoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateProdTipoRequest $request)
    {
        $request->validated();
        $tipo = ProductoTipo::create(['descripcion' => $request->descripcion]);

        if ($tipo) {
            return redirect(route('producto_tipo.index'))->with('status', 'Nuevo Tipo creado !');
        } else {
            return redirect(route('producto_tipo.index'))->with('status', 'No se pudo crear nuevo Tipo !!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductoTipo  $productoTipo
     * @return \Illuminate\Http\Response
     */
    public function show(ProductoTipo $productoTipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductoTipo  $productoTipo
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductoTipo $productoTipo)
    {
        $tipo = $productoTipo;

        return view('productos.tipos.edit', compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ValidateProdTipoRequest $request
     * @param  \App\Models\ProductoTipo  $productoTipo
     * @return \Illuminate\Http\Response
     */
    public function update(ValidateProdTipoRequest $request, ProductoTipo $productoTipo)
    {
        $request->validated();
        $data = [
            'descripcion' => $request->descripcion,
            'estado' => $request->estado
        ];

        // Start transaction
        DB::beginTransaction();
            $rows = ProductoTipo::where('id', $request->input('id'))
                        ->lockForUpdate()
                        ->update($data);

        if ($rows == 0) {
            DB::rollBack();
            return redirect(route('producto_tipo.index'))->with('status', 'NO se pudo actualizar Tipo !!');
        }

        DB::commit();

        return redirect(route('producto_tipo.index'))->with('status', 'Tipo actualizado !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductoTipo  $productoTipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductoTipo $productoTipo)
    {
        //
    }
}
