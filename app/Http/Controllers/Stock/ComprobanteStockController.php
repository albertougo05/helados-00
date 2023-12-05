<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Models\StockComprobante;
use App\Models\StockComprobanteTipoMovimiento;  // StockComprobanteTipoMovimiento
use App\Models\StockDetalleComprobante;
use App\Models\ProductoGrupo;
use App\Models\ProductoSucursal;
use App\Models\Sucursal;
use App\Http\Controllers\Utils\Utils;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Collection;


class ComprobanteStockController extends Controller
{
    protected $grupos_helado = [2];

    /**
     * Ingreso Movimientos de Stock
     * Name: comprobante_stock (GET)
     * 
     * @param Utils $utils
     * @return \Illuminate\Http\Response
     */
    public function index(Utils $utils)
    {
        $grupos_helado = $this->grupos_helado;
        $grupos = $this->_gruposParaStock();  // GRUPOS PARA CARGA STOCK (en sucursal)
        $usuario = Auth::user();
        $perfil_id = $usuario->perfil_id;
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $turnos_abiertos = $utils->getTurnosAbiertosSucursal($sucursal_id);
        $turno_id = $turnos_abiertos[0]['turno_id'];
        $turno_sucursal = $turnos_abiertos[0]['turno_sucursal'];
        $sucursal = Sucursal::select('nombre')
            ->find($sucursal_id);
        $tipos_movim = StockComprobanteTipoMovimiento::select('id', 'descripcion')
            ->orderBy('descripcion')
            ->get();
        $productos = $this->_productosDeLaSucursal($sucursal_id, $grupos);
        $productos_indiv = $this->_getProductosIndiv($productos);
        $new_id_comprob = $this->_getNewIdComprobStock($sucursal_id);
        $nombreImpresora = $utils->getNombreImpresora($sucursal_id);
        $fecha = date('Y-m-d');
        $titulo = 'Ingreso movimientos stock';
        $env = env('APP_ENV');

        $data = compact(
            'titulo',
            'new_id_comprob',
            'grupos', 
            'grupos_helado',
            'productos',
            'productos_indiv',
            'tipos_movim',
            'fecha', 
            'sucursal_id',
            'sucursal',
            'perfil_id',
            'turno_id',
            'turno_sucursal',
            'nombreImpresora',
            'env',
        );

        return view('stock.index', $data);
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
     * Name: comprobante_stock.store (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $respuesta = ['result' => 'ok'];
        $comp = StockComprobante::create($request->all());

        if (!$comp) {
            $respuesta = ['result' => 'error'];
        } else {
            $respuesta['id'] = $comp->id;
        }

        return json_encode($respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockComprobante  $StockComprobante
     * @return \Illuminate\Http\Response
     */
    public function show(StockComprobante $comprobanteStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Name: comprobante_stock.edit
     *
     * @param  int  $comprob_id
     * @param  Utils $utils
     * @return \Illuminate\Http\Response
     */
    public function edit($comprob_id, Utils $utils)
    {
        $comprobante = StockComprobante::find($comprob_id);
        $titulo = 'Edición movimiento stock';
        $grupos = $this->_gruposParaStock();
        $grupos_helado = $this->grupos_helado;
        $productos = $this->_productosDeLaSucursal($comprobante->sucursal_id, $grupos);
        $productos_indiv = $this->_getProductosIndiv($productos);
        $tipos_movim = StockComprobanteTipoMovimiento::select('id', 'descripcion')
            ->get();
        $detalle = $this->_getDetalleIngresoStock($comprob_id);
        $sucursal = Sucursal::select('nombre')->find($comprobante->sucursal_id);
        $turno_sucursal = $comprobante->turno_sucursal;
        $fecha_hora = $this->_setFechaHora($comprobante->fecha_hora);
        $nombreImpresora = "";  //$utils->getNombreImpresora($comprobante->sucursal_id);
        $rutaInfoMovs = route('comprobante_stock.edit', $comprob_id);

        $data = compact(
            'titulo',
            'grupos', 
            'grupos_helado',
            'productos',
            'productos_indiv',
            'tipos_movim',
            'sucursal',
            'turno_sucursal',
            'fecha_hora',
            'detalle',
            'comprobante',
            'nombreImpresora',
            'rutaInfoMovs',
        );

        return view('stock.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * Name: comprobante_stock.update
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        StockComprobante::where('id', $id)
            ->update(['estado' => 2, 'observaciones' => $request['observaciones']]);

        return response()->json(['result' => 'ok']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockComprobante  $comprobanteStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockComprobante $comprobanteStock)
    {
        //
    }

    /**
     * Anula comprobante.     (NO USADO)
     * Name: comprobante_stock/anula
     *
     * @param int   $id
     * @return \Illuminate\Http\Response
     */
    public function anula(int $id)
    {
        $affected = StockComprobante::where('id', $id)
            ->lockForUpdate()
            ->update(['estado' => 0]);

        if ($affected > 0) {
            $respuesta = ['estado' => 'ok', 'id_comprob' => $id,];
        } else {
            $respuesta = ['estado' => 'error', 'id_comprob' => $id,];
        }

        return response()->json($respuesta);
    }


    /**
     * LOS PRODUCTOS SERAN LOS QUE TENGA CADA SUCURSAL
     * 
     */
    private function _productosDeLaSucursal($sucursal_id, $grupos)
    {
        $idsGrupos = $this->_getIdsGrupos($grupos);
        $productos = ProductoSucursal::select(
                'productos_sucursal.sucursal_id',
                'productos_sucursal.precio_vta',
                'productos.id',
                'productos.codigo',
                'productos.grupo_id',
                'productos.descripcion',
                'productos.descripcion_ticket',
                'productos.costo_x_unidad',
                'productos.costo_x_bulto',
                'productos.unidades_x_bulto',
                'productos.unidades_x_caja',
                'productos.articulo_indiv_id',
                'productos.peso_materia_prima',
            )
            ->where('productos_sucursal.sucursal_id', $sucursal_id)
            ->where('productos.estado', 1)
            ->whereIn('productos.grupo_id', $idsGrupos)
            ->leftJoin('productos', 'productos_sucursal.producto_id', '=', 'productos.codigo')
            ->orderBy('grupo_id')
            ->orderBy('descripcion')
            ->get();

        foreach ($productos as $value) {
            $value->descripcion = strtoupper($value->descripcion);
            $pos = strripos($value->descripcion, ' X CAJA', 0);
            // Si tiene el texto...
            if ($pos) {
                $value->descripcion = substr_replace($value->descripcion, "", $pos);
            }
        }

        return $productos;
    }

    private function _getDetalleIngresoStock($id) {
        return StockDetalleComprobante::select('id', 'codigo', 'producto_id',
                        'cantidad', 'descripcion', 'unidad_medida', 
                        'cant_total_unid', 'observaciones')
                    ->where('stock_comprob_id', $id)
                    ->where('cantidad', '>', 0)
                    ->get();
    }

    private function _getIdsGrupos(Collection $grupos)
    {
        $ids = [];
        foreach ($grupos as $value) {
            array_push($ids, $value->id);
        }

        return $ids;
    }

    /**
     * Selecciona grupos para cargar stock
     * (Quita el texto 'x CAJA' de la descripción)
     * 
     *  // Ids de grupos para carga de stock
     * $arrGruposIds = [2, 3, 6, 9, 10, 11, 12, 14];
     * 
     * @param array $arrGruposIds
     * @return array
     */
    private function _gruposParaStock()
    {
        //$grupos = ProductoGrupo::whereIn('id', $arrGruposIds)
        $grupos = ProductoGrupo::where('carga_stock', 1)
        ->orderBy('descripcion')
        ->get();

        foreach ($grupos as $value) {
            $value->descripcion = strtoupper($value->descripcion);
            $pos = strripos($value->descripcion, ' X CAJA', 0);
            // Si tiene el texto...
            if ($pos) {
                $value->descripcion = substr_replace($value->descripcion, "", $pos);
            }
        }

        return $grupos;
    }

    private function _getNewIdComprobStock(int $sucursal_id)
    {
        $id = 1;
        $reg = StockComprobante::where('sucursal_id', $sucursal_id)
            ->latest()
            ->first();
        
        if ($reg) {
            $id = (integer) $reg->id + 1;
        }

        return $id;
    }

    private function _getProductosIndiv($prods)
    {
        $prodsInd = [];
        foreach ($prods as $value) {
            if ($value->articulo_indiv_id == 0) {
                continue;
            }
            $prod = Producto::select('id', 'descripcion', 'costo_x_unidad')
                ->find($value->articulo_indiv_id);
            array_push($prodsInd, $prod);
        }

        return $prodsInd;
    }

    private function _setFechaHora(string $fechaHora) : string 
    {
        $date = new DateTime($fechaHora);

        return $date->format('d/m/Y H:i'); //date('d/m/Y H:i', $fechaHora);
    }

}
