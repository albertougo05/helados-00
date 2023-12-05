<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetalleComprobVenta;
use Exception;

class DetalleComprobVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo "Detalle comprobante Venta...";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo "Detalle comprobante venta -> create...";
    }

    /**
     * Store a newly created resource in storage.
     * Name: detalle_comprob_venta (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Guarda detalle del comprobante
        $respuesta = ['estado' => 'ok',];
        $status = 200;

        try {
            foreach ($request->all() as $value) {
                $datosOk = $this->_ordenarDatos($value);
                DetalleComprobVenta::create($datosOk);
            }
   
        } catch (Exception $e) {
            $respuesta = [
                'estado' => 'error',
                'error' => $e->getMessage()
            ];
            $status = 204;
        }

        return response()->json($respuesta, $status);
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

    /**
     * Anula detalle del comprobante de venta
     * Devuelve json 
     * Name: ventas.detallecomprobventa.anular
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function anular($id)
    {
        $respuesta = [
            'resultado' => 'error',
        ];

        $rows = DetalleComprobVenta::where('comprobante_id', $id)
            ->lockForUpdate()
            ->update(['estado' => 0]);

        if ($rows > 0) {
            $respuesta['resultado'] = 'ok';
        }

        return response()->json($respuesta);
    }

    /**
     * Devuelve json con detalle de comprobante
     * Name: ventas.detallecomprobante
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function detalle(Request $request, $id)
    {
        // $detalle = DetalleComprobVenta::where('nro_comprobante', $id)
        //     ->get()
        //     ->toArray();

        return response()->json([
            'detalle' => DetalleComprobVenta::select(
                    'cantidad', 
                    'descripcion', 
                    'precio_unitario', 
                    'subtotal')
                ->where('sucursal_id', $request->input('suc_id'))
                ->where('nro_comprobante', $id)
                ->get()
                ->toArray()
        ]);
    }

    private function _ordenarDatos($valores) 
    {
        return [
            'sucursal_id'     => $valores['sucursal_id'],
            'comprobante_id'  => $valores['comprobante_id'],
            'nro_comprobante' => $valores['nro_comprobante'],
            'fecha_hora'      => $valores['fecha']." ".$valores['hora'],
            'fecha'           => $valores['fecha'],
            'hora'            => $valores['hora'],
            'producto_codigo' => $valores['codigo'],
            'descripcion'     => $valores['descripcion'],
            'cantidad'        => $valores['cantidad'],
            'unidades'        => $valores['unidades'],
            'peso_helado'     => $valores['peso_helado'],
            'precio_unitario' => $valores['precio_unitario'],
            'subtotal'        => $valores['subtotal'],
        ];
    }
}