<?php

namespace App\Http\Controllers\Sucursal;

use App\Http\Controllers\Controller;
use App\Models\Sucursal;
use App\Models\Empresa;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateSucursalRequest;
use App\Models\ProductoSucursal;
use Illuminate\Support\Facades\DB;



class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sucursales = Sucursal::orderBy('nombre') 
            ->paginate(10);

        $data = compact(
            'sucursales', 
        );

        return view('sucursales.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sucursal = Sucursal::latest()->first();
        $newid = (integer) $sucursal->id + 1;
        $empresas = Empresa::select('id', 'razon_social')
            ->orderBy('razon_social')
            ->get();

        $data = compact(
            'newid',
            'empresas',
        );

        return view('sucursales.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ValidateSucursalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateSucursalRequest $request)
    {
        $request->validated();
        $sucursalSana = $this->_sanitizar($request);
        $sucursal = Sucursal::create($sucursalSana);

        // EN PROXIMA SINCRONIZACIÓN DE LISTA DE PRECIOS, SE CREA LA LISTA DE PRECIOS

        if ($sucursal) {
            return redirect(route('sucursal'))->with('status', 'Nueva sucursal creada !');
        } else {
            return redirect(route('sucursal'))->with('status', 'No se pudo crear nueva sucursal !!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function show(Sucursal $sucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function edit(Sucursal $sucursal)
    {
        $empresas = Empresa::select('id', 'razon_social')
            ->orderBy('razon_social')
            ->get();

        $data = compact(
            'sucursal',
            'empresas',
        );

        return view('sucursales.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ValidateSucursalRequest  $request
     * @param  \App\Models\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function update(ValidateSucursalRequest $request, Sucursal $sucursal)
    {
        $request->validated();
        $sucursalSana = $this->_sanitizar($request);

        // Start transaction
        DB::beginTransaction();
            $rows =  Sucursal::where('id', $request->input('id'))
                        ->lockForUpdate()
                        ->update($sucursalSana);

        if ($rows == 0) {
            DB::rollBack();
            return redirect(route('sucursal.index'))->with('status', 'NO se pudo actualizar Sucursal !!');
        } else {
            DB::commit();
        }

        return redirect(route('sucursal.index'))->with('status', 'Sucursal actualizada !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sucursal $sucursal)
    {
        //
    }

    /** 
     * Sanatiza los datos de Request y otras conversiones
     * 
     * @param Request $req
     * @return array $producto
     */
    private function _sanitizar(Request $req = null)
    {
        $sucursal = [
            'empresa_id' => $req->input('empresa_id'),
            'nombre' => $req->input('nombre'),
            'direccion' => $req->input('direccion'),
            'localidad' => $req->input('localidad'),
            'codigo_postal' => $req->input('codigo_postal'),
            'provincia' => $req->input('provincia'),
            'celular' => $req->input('celular'),
            'telefono' => $req->input('telefono'),
            'email' => $req->input('email'),
            'lista_precio' => (integer) $req->input('lista_precio'),
            'cant_puntos_venta' => (integer) $req->input('cant_puntos_venta'),
            'estado' => (integer) $req->input('estado'),
        ];

        return $sucursal;
    }


    /**
     * Crea lista de precio para la sucursal, segun lista seleccionada
     * 
     * @param Integer $id
     * @param String $nroLista
     * @return null
     */
    private function _crearListaDePrecio($id, $nroLista)
    {
        $result = 'ok';

        try {
            $productos = Producto::select('id', "precio_lista_" . $nroLista . " as precio")
                ->where('venta_publico', 1)
                ->get();

            foreach ($productos as $value) {
                ProductoSucursal::create(
                    [
                        'sucursal_id', $id,
                        'producto_id', $value->id,
                        'precio_vta', $value->precio,
                        'orden', 0
                    ]
                );
            }
            
        } catch (\Throwable $th) {
            throw $th;
            $result = 'error';
        }

        return $result;
    }


}
