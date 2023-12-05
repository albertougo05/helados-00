<?php

namespace App\Http\Controllers\Ventas;


use App\Http\Controllers\Controller;
use App\Models\DetalleComprobReceta;
use App\Models\DetalleComprobVenta;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class InfoVentasProdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fecha_hasta = date('Y-m-d');
        $fecha_desde = date("Y-m-d", strtotime($fecha_hasta.'- 30 days'));
        $productos = Producto::select('id', 'codigo', 'descripcion')
            ->where('tipo_producto_id', '<', 5)   // Todos, menos las materias primas
            ->orderBy('descripcion')
            ->get();

        $data = compact('productos', 'fecha_desde', 'fecha_hasta');

        return view('ventas.informes.ventas-producto', $data);
    }

    /**
     * Devuelve lista de Movimientos de producto.
	   * Name: ventas.ventasprod (GET)
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View
     */
    public function ventasprod(Request $request)
    {
      $listado = [];
      $result = false;
      $producto = Producto::find($request->input('id'));

        if ($producto) {
            if ($producto->apto_receta == 0) {

              $listado = $this->_getListado($request, 'noAptoRec');

            } else if ($producto->apto_receta == 1) {  // Producto que forma parte de una receta

              $listado = $this->_getListado($request, 'aptoRec');
            }
        }

        if (count($listado) > 0) {
            $result = true;
        }

        return response()->json([
            'resultado' => $result,
            'listado' => $listado
        ]);
    }

    private function _getListado($req, $conSinRec) {
      $user = Auth::user();
      $hasta = $req->input('hasta') ? $req->input('hasta') : date('Y-m-d');
      $desde = $req->input('desde') ? $req->input('desde') : date("Y-m-d", strtotime($hasta.'- 10 days'));

      if ($conSinRec === 'noAptoRec') {
        $listado = DetalleComprobVenta::select('detalle_comprob_venta.*', 
            'sucursales.nombre', 
            'comprobantes_venta.hora',
            'comprobantes_venta.codigo_comprob',
            'comprobantes_venta.nro_comprobante',
          )
          ->where([
            ['detalle_comprob_venta.producto_codigo', '=', $req->input('id')],
            ['detalle_comprob_venta.sucursal_id', '=', $user->sucursal_id],
            ['detalle_comprob_venta.fecha', '>=', $desde],
            ['detalle_comprob_venta.fecha', '<=', $hasta]
          ])
          ->leftJoin('sucursales', 'detalle_comprob_venta.sucursal_id', '=', 'sucursales.id')
          ->leftJoin('comprobantes_venta', 'detalle_comprob_venta.nro_comprobante', '=', 'comprobantes_venta.nro_comprobante')
          ->get()
          ->toArray();
      } else {   // Con receta ('aptoRec')
        $listado = DetalleComprobReceta::select('detalle_comprob_receta.*', 
            'sucursales.nombre', 
            'comprobantes_venta.hora',
            'comprobantes_venta.codigo_comprob',
            'comprobantes_venta.nro_comprobante',
          )
          ->where([
            ['detalle_comprob_receta.producto_codigo', '=', $req->input('id')],
            ['detalle_comprob_receta.sucursal_id', '=', $user->sucursal_id],
            ['detalle_comprob_receta.fecha', '>=', $desde],
            ['detalle_comprob_receta.fecha', '<=', $hasta]
          ])
          ->leftJoin('sucursales', 'detalle_comprob_receta.sucursal_id', '=', 'sucursales.id')
          ->leftJoin('comprobantes_venta', 'detalle_comprob_receta.nro_comprobante', '=', 'comprobantes_venta.nro_comprobante')
          ->get()
          ->toArray();
      }

      return $listado;
    }



  }
