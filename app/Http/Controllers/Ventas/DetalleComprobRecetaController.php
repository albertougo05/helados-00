<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\DetalleComprobReceta;
use App\Models\ProductosReceta;



class DetalleComprobRecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Name: detalle_comprob_receta (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * si el producto se compone de 3 items en la receta..
         * guarda 1 registro en “DetalleComprobantes”
         * guarda 3 registros en “Detalle_Cpte_segun_Receta”
         * y en cantidad tiene que poner: DetalleComprobantes.Cantidad * Receta.Cantidad
         *
         * con esta última tabla es que se arma el STOCK.
         */

        // Guarda detalle de la receta
        $respuesta = ['estado' => 'ok'];
        $status = 200;

        try {
            foreach ($request->all() as $value) {       // Para cada producto con receta
                $prodsReceta = $this->_productosReceta($value);      // Devuelve lista de prods de la receta

                foreach ($prodsReceta as $prod) {
                    DetalleComprobReceta::create($prod);
                }
            }

        } catch (Exception $e) {
            $respuesta = [
                'estado' => 'error',
                'datos' => $e->getMessage()
            ];
            $status = 404;
        }

        return response()->json($respuesta, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function show(DetalleComprobReceta $detalleReceta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetalleComprobReceta  $detalleReceta
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleComprobReceta $detalleReceta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleComprobReceta $detalleReceta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetalleComprobReceta $detalleReceta
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleComprobReceta $detalleReceta)
    {
        //
    }

    /**
     * Anula detalle del comprobante de venta
     * Devuelve json 
     * Name: ventas.detallecomprobreceta.anular
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function anular($id)
    {
        $respuesta = [
            'resultado' => 'error',
        ];

        $rows = DetalleComprobReceta::where('comprobante_id', $id)
            ->lockForUpdate()
            ->update(['estado' => 0]);

        if ($rows > 0) {
            $respuesta['resultado'] = 'ok';
        }

        return response()->json($respuesta);
    }

    /**
     * Arma registro, por cada prod de la receta
     *
     * @param  array $detalleProd
     * @return array $prodsReceta
     */
    private function _productosReceta($detalleProd)
    {
        // Datos de lo que viene en el parametro:
        // $detalleProd = [
        //     "sucursal_id" => 1
        //     "comprobante_id" => 37
        //     "nro_comprobante" => 137
        //     "fecha" => 2021-10-15
        //     "con_receta" => 1
        //     "producto_id" => "01-22"
        //     "codigo" => "01-22"
        //     "descripcion" => "Helado de 2 bochas - (con cucurucho, cucharita y servilleta)"
        //     "cantidad" => 2
        //     "precio_unitario" => 10
        //     "subtotal" => 20
        // ];

        $prodsReceta = [];

        // buscar productos de la receta del producto_id
        $prodsDeReceta = ProductosReceta::where('producto_id', $detalleProd['codigo'])
            ->get();

        foreach($prodsDeReceta as $value) {     // Para cada producto...

            $prodsReceta[] = [        // arma array para grabar datos en tabla
                'sucursal_id'     => $detalleProd['sucursal_id'],
                'comprobante_id'  => $detalleProd['comprobante_id'],
                'nro_comprobante' => $detalleProd['nro_comprobante'],
                'fecha_hora'      => $detalleProd['fecha']." ".$detalleProd['hora'],
                'fecha'           => $detalleProd['fecha'],
                'hora'            => $detalleProd['hora'],
                'producto_con_receta_codigo' => $detalleProd['codigo'],    // $detalleProd['producto_id'],
                'producto_codigo' => $value->producto_receta_id,
                'cantidad'        => $value->cantidad * $detalleProd['cantidad'],
                'peso_helado'     => $detalleProd['peso_helado'],
                'costo'           => $value->costo,
            ];
        };

        return $prodsReceta;
    }

}
