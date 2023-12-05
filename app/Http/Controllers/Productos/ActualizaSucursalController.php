<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use App\Models\ProductoSucursal;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sucursal;


class ActualizaSucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     * Name: producto.actualiza.sucursal
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fechaActual = date('d/m/Y');
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $sucursal = Sucursal::select('id', 'nombre')
            ->find($sucursal_id);

        $data = compact(
            'fechaActual', 
            'sucursal',
        );

        return view('productos.sucursal.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * Name: producto.actualiza.store
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $precios = $this->_actualizar($request->id);

        if ($precios) {
            return back()->with('status', 'Precios sucursal actualizados !!');
        } else {
            return back()->with('status', 'ERROR ! No se actualizaron precios !!');
        }
    }

    /** 
     * Actualiza precios de sucursal
     * 
     * @param integer $idSuc
     */
    private function _actualizar($idSuc)
    {
        $estado = false;
        // Obtengo el número de lista de precios de la sucursal
        $sucursal = Sucursal::select('lista_precio')
            ->find($idSuc);
        $campoLista = "precio_lista_" . $sucursal->lista_precio;
        $prodsSuc = ProductoSucursal::where('sucursal_id', $idSuc)
            ->get();

        try {
            foreach ($prodsSuc as $value) {
                $prod = Producto::select($campoLista)
                    ->where('codigo', $value->producto_id)
                    ->first();

                $cant = ProductoSucursal::where('sucursal_id', $idSuc)
                    ->where('producto_id', $value->producto_id)
                    ->lockForUpdate()
                    ->update(['precio_vta' => $prod->$campoLista]);
            }

            if ($cant > 0) $estado = true;

        } catch (\Throwable $th) {
            throw $th;
        }

        return $estado;
    }
}
