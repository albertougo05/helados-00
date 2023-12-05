<?php

namespace App\Http\Controllers\Productos;

use App\Models\ProductoGrupo;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateProdGrupoRequest;


class ProductoGrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grupos = ProductoGrupo::orderBy('descripcion')
            //->where('estado', 1)
            ->paginate(12);

        return view('productos.grupos.index', compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grupo = ProductoGrupo::latest()->first();
        $newid = (integer) $grupo->id + 1;

        $data = compact(
            'newid',
        );

        return view('productos.grupos.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ValidateProdGrupoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateProdGrupoRequest $request)
    {
        $request->validated();
        
        $data = [
            'descripcion' => $request->descripcion,
            'orden' => is_null($request->orden) ? 99 : $request->orden,
            'mide_desvio' => isset($request->mide_desvio) ? 1 : 0,
            'carga_stock' => isset($request->carga_stock) ? 1 : 0,
            'unidad' => isset($request->unidad) ? 1 : 0,
            'caja' => isset($request->caja) ? 1 : 0,
        ];

         $grupo = ProductoGrupo::create($data);

        if ($grupo) {
            return redirect(route('producto_grupo.index'))->with('status', 'Nuevo grupo creado !');
        } else {
            return redirect(route('producto_grupo.index'))->with('status', 'No se pudo crear nuevo grupo !!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductoGrupo  $productoTipo
     * @return \Illuminate\Http\Response
     */
    public function show(ProductoGrupo $productoGrupo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductoTipo  $ProductoTipo
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductoGrupo $productoGrupo)
    {
        $grupo = $productoGrupo;

        return view('productos.grupos.edit', compact('grupo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ValidateProdGrupoRequest $request
     * @param  \App\Models\ProductoGrupo  $productoGrupo
     * @return \Illuminate\Http\Response
     */
    public function update(ValidateProdGrupoRequest $request, ProductoGrupo $productoGrupo)
    {
        $request->validated();

        $data = [
            'descripcion' => $request->descripcion,
            'orden' => intval($request->orden),
            'estado' => intval($request->estado),
            'mide_desvio' => isset($request->mide_desvio) ? 1 : 0,
            'carga_stock' => isset($request->carga_stock) ? 1 : 0,
            'unidad' => isset($request->unidad) ? 1 : 0,
            'caja' => isset($request->caja) ? 1 : 0,
        ];

        // Start transaction
        DB::beginTransaction();
            $rows = ProductoGrupo::where('id', $request->input('id'))
                        ->lockForUpdate()
                        ->update($data);
        if ($rows == 0) {
            DB::rollBack();
            return redirect(route('producto_grupo.index'))->with('status', 'NO se pudo actualizar Grupo !!');
        } else {
            DB::commit();
        }

        return redirect(route('producto_grupo.index'))->with('status', 'Grupo actualizado !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductoGrupo  $productoGrupo
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductoGrupo $productoGrupo)
    {
        //
    }
}
