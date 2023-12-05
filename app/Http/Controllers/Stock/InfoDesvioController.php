<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Utils\Utils;
use App\Models\Producto;
use App\Models\ProductoGrupo;
use App\Models\DetalleComprobVenta;
use App\Models\DetalleComprobReceta;
use App\Models\StockComprobante;
use App\Models\StockDetalleComprobante;
use App\Models\StockRealPlanilla;
use App\Models\StockRealDetalleArticulo;
use App\Models\StockRealDetalleHelado;
use App\Models\Sucursal;


class InfoDesvioController extends Controller
{
	protected $ids_grupos_helados = "(1, 2, 4, 5, 7, 8)"; 	// [1, 2, 4, 5, 7, 8]
	protected $grupos_articulos; // = [3, 6, 9, 10, 11, 12, 13, 15, 16, 17];

	function __construct()
	{
		$this->grupos_articulos = $this->__getGruposArticulos();
	}

    /**
     * Inicio Informe desvio de Stock.
	 * Name: stock.infodesvio
	 * 
     * @param Request $request
	 * @param Utils $utils
     * @return \Illuminate\Contracts\View
     */
    public function index(Request $request, Utils $utils)
    {
		$usuario = Auth::user();

		if ($usuario->perfil_id < 3) {	// Usuarios admin
			$sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
		} else {
			$sucursal_id = Auth::user()->sucursal_id;
		}

		$sucursales = Sucursal::select('id', 'nombre')
			->where('id', $sucursal_id)
			->get()
			->toArray();

		$periodos = $this->_getPeriodos($sucursal_id);
		$nombreImpresora = ""; //$utils->getNombreImpresora($sucursal_id);

    	$data = compact(
			'usuario',
			'sucursal_id',
			'sucursales',
			'nombreImpresora',
			'periodos',
		);

		return view('stock.info_desvio.index', $data);
    }

    /**
     * Devuelve datos de Informe desvio de Stock.
	 * Name: stock.infodesvio.data
	 * http://localhost:8000/stock/infodesvio/data?desde=1&hasta=2
	 * 
     * @param Request $request
     * @return Response
     */
    public function data(Request $request, Utils $utils)
    {
		$dataResponse = [
			'status' => 'error',
			'data' => [],
		];
		$planillaDesde = StockRealPlanilla::find($request->input('desde'))->toArray();
		$planillaHasta = StockRealPlanilla::find($request->input('hasta'))->toArray();
		// LISTA DE PRODUCTOS QUE MIDEN DESVIO
		$productos_miden_desvio = $this->_getProductosMidenDesvio();
		// Carga datos de helados...
		$productos_miden_desvio = $this->_cargaHelados(
				$productos_miden_desvio, 
				$planillaDesde, 
				$planillaHasta,
				$utils
		);
		// Carga datos de articulos...
		$productos_miden_desvio = $this->_cargaArticulos(
			$productos_miden_desvio, 
			$request->input('desde'),
			$request->input('hasta'),
			$planillaDesde, 
			$planillaHasta, 
			$utils
		);

		if (count($productos_miden_desvio) > 0) {
			$dataResponse['status'] = 'ok';
			$dataResponse['data'] = $productos_miden_desvio;
		}

		return response()->json($dataResponse);
	}


	private function _getPeriodos(int $sucursal_id = 1)
	{
		$arrPlanilla = [];
		$planilla = StockRealPlanilla::select(
				'id', 
				'sucursal_id', 
				'tipo_toma_stock',
				'fecha_toma_stock',
				'hora_toma_stock')
			->where('sucursal_id', $sucursal_id)
			->orderBy('id', 'desc')
			->get();

		foreach ($planilla as $planilla) {
			$fechaHora = $this->_getFechaHora($planilla);

			$arrPlanilla[] = [
				'id' => $planilla->id,
				'sucursal_id' => $planilla->sucursal_id,
				'tipo_toma_stock' => $planilla->tipo_toma_stock,
				'fecha_toma_stock' => $planilla->fecha_toma_stock,
				'hora_toma_stock' => $planilla->hora_toma_stock,
				'fecha_hora' => $fechaHora,
			];
		}

		return $arrPlanilla;
	}

	private function _getFechaHora($planilla = null)
	{
		$fecha = date("d/m/Y", strtotime($planilla->fecha_toma_stock));
		$hora = date("H:i", strtotime($planilla->hora_toma_stock));

		return $fecha . " - " . $hora;
	}

	private function _getProductosMidenDesvio()
	{
		$productos = $this->_getProductosMD();
		$modelo_producto = $this->_getModeloProd();
		$arr_helado = $this->_setArrayHelado($modelo_producto);		// Primera linea del listado
		$arrProductos = $this->_getProductosYaConHelado($productos, $modelo_producto, $arr_helado);

		return $arrProductos;
	}

	private function _getProductosMD()
	{
		$sql = "SELECT p.grupo_id, pg.descripcion as grupo, p.id, p.codigo, p.descripcion, p.costo_x_unidad, p.articulo_indiv_id ";
		$sql = $sql . "FROM productos p LEFT JOIN producto_grupos pg ";
		$sql = $sql . "ON pg.id = p.grupo_id WHERE p.estado = 1 ";
		$sql = $sql . "AND p.grupo_id NOT IN " . $this->ids_grupos_helados;		//  PRODUCTOS QUE NO SEAN HELADOS !!
		$sql = $sql . "ORDER BY pg.descripcion, p.descripcion";

		return DB::select($sql);
	}

	private function _getModeloProd()
	{
		return [
			'grupo' => '',
			'codigo' => '',
			'descripcion' => '',
			'codigo_art_ind' => 0,
			'costo' => 0,
			'stock_inicial' => 0,
			'ingresos' => 0,
			'salidas' => 0,
			'stock_final' => 0,
			'si_i_s_sf'	=> 0,
			'ventas_pos'  => 0,
			'desvio_unid' => 0,
			'desvio_kilos' => 0,
			'desvio_pesos' => 0,
			'porc_desvio_stissf' => 0,
			'porc_desvio_vta_pos' => 0,
		];
	}

	private function _setArrayHelado($modelo)
	{
		$modelo['grupo'] = 'Helados';
		$modelo['codigo'] = '01-1';
		$modelo['descripcion'] = 'Helado';

		return $modelo;
	}

	private function _getProductosYaConHelado($productos, $modelo, $arrHelado)
	{
		$arrProductos = [
			$arrHelado,
		];

		foreach ($productos as $value) {
			$clon_modelo = $modelo;
			$clon_modelo['grupo'] = $value->grupo;
			$clon_modelo['codigo'] = $value->codigo;
			$clon_modelo['descripcion'] = $value->descripcion;
			$clon_modelo['codigo_art_ind'] = $value->articulo_indiv_id;
			$clon_modelo['costo'] = (float) $value->costo_x_unidad;

			$arrProductos[] = $clon_modelo;
		}

		return $arrProductos;
	}

	private function _setFechaHora($desde, $hasta)
	{
		$fecha_hora_desde = $desde['fecha_toma_stock']." ".$desde['hora_toma_stock'];
		$fecha_hora_hasta = $hasta['fecha_toma_stock']." ".$hasta['hora_toma_stock'];

		return [
			'desde' => $fecha_hora_desde,
			'hasta' => $fecha_hora_hasta
		];
	}

	private function _cargaArticulos($productos, $desde, $hasta, $planDesde, $planHasta, Utils $utils)
	{
		$productos = $this->_cargaStockInicialFinal(
			$productos, 
			$desde,
			'stock_inicial');

		$productos = $this->_cargaIngresosEgresos(
		 	$planDesde, 
		 	$planHasta, 
		 	$productos,
		 	'ingreso'
		);

		$productos = $this->_cargaIngresosEgresos(
			$planDesde, 
			$planHasta, 
			$productos,
			'egreso'
		);

		$productos = $this->_cargaVentas(
		 	$planDesde, 
		 	$planHasta, 
		 	$productos
		);

		$productos = $this->_cargaStockInicialFinal(
			$productos, 
			$hasta,
			'stock_final'
		);

		$productos = $this->_calculosDesvio($productos, $utils);

		//dd("Veamos productos:", $productos);

		return $productos;
	}

	private function _cargaHelados($productos, $desde, $hasta, Utils $utils)
	{
		$productos[0]['costo'] = $this->_getCostoHeladoGenerico();
		$productos[0]['stock_inicial'] = $this->_getStockInicFinalHelados($desde['id']);
		$productos[0]['ingresos'] = $this->_getIngresoHelados($desde, $hasta);
		$productos[0]['salidas'] = $this->_getSalidasHelados($desde, $hasta);
		$productos[0]['stock_final'] = $this->_getStockInicFinalHelados($hasta['id']);
		$ingresos = $productos[0]['stock_inicial'] + $productos[0]['ingresos'];
		$salidas = $productos[0]['salidas'] + $productos[0]['stock_final'];
		$productos[0]['si_i_s_sf'] = $utils->redondear_dos_decimal($ingresos - $salidas);
		$productos[0]['ventas_pos'] = $this->_getVentaPosHelados($desde, $hasta);
		$productos[0]['desvio_kilos'] = $utils->redondear_dos_decimal($productos[0]['si_i_s_sf'] - $productos[0]['ventas_pos']);
		$productos[0]['desvio_pesos'] = $utils->redondear_dos_decimal($productos[0]['costo'] * $productos[0]['desvio_kilos']);
		$productos[0]['porc_desvio_stissf'] = $utils->redondear_dos_decimal(($productos[0]['desvio_kilos'] * 100) / $productos[0]['si_i_s_sf']);
		$productos[0]['porc_desvio_stissf'] = $utils->redondear_dos_decimal($utils->redondear_dos_decimal($productos[0]['porc_desvio_stissf']));
		$porcDesvVta = ($productos[0]['desvio_kilos'] * 100) / $productos[0]['ventas_pos'];
		$productos[0]['porc_desvio_vta_pos'] = $utils->redondeado($porcDesvVta, 3);

		return $productos;
	}

	private function _getStockInicFinalHelados($idPlanilla)
	{
		$total_kilos = StockRealDetalleHelado::select(
				DB::raw('sum(kilos_totales) as total_kilos, planilla_id'))
			->where('planilla_id', $idPlanilla)
			->groupBy('planilla_id')
			->get()
			->toArray();

		if (count($total_kilos) > 0) {
			return (float) $total_kilos[0]['total_kilos'];
		} else {
			return 0;
		}
	}

	private function _getCostoHeladoGenerico()
	{
		return (float) Producto::where('codigo', '01-1')
			->value('costo_x_unidad');
	}

	private function _getIngresoHelados($desde, $hasta)
	{
		$fechaHora = $this->_setFechaHora($desde, $hasta);

		$comprobIngresos = StockComprobante::where('sucursal_id', $desde['sucursal_id'])
			->whereIn('tipo_movimiento_id', [1, 2])
			->whereBetween('fecha_hora', [$fechaHora['desde'], $fechaHora['hasta']])
			->get()
			->toArray();

		if (count($comprobIngresos) > 0) {
			$pesoTotalIngreso = $this->_getPesoTotalHelado($comprobIngresos);
		} else {
			$pesoTotalIngreso = 0;
		}

		return $pesoTotalIngreso;
	}

	private function _getPesoTotalHelado($comprobantes)
	{
		$total_kilos = 0;

		foreach ($comprobantes as $comprob) {
			$registro = StockDetalleComprobante::select(
					DB::raw('sum(peso_neto_total) as total_kilos, stock_comprob_id'))
				->where('stock_comprob_id', $comprob['nro_comprobante'])
				->groupBy('stock_comprob_id')
				->get()
				->toArray();

			if (count($registro) > 0) {
				$total_kilos += (float) $registro[0]['total_kilos'];
			}
			unset($registro);
		}

		return $total_kilos;
	}

	private function _getSalidasHelados($desde, $hasta)
	{
		// Salidas por movimientos de stock...
		$fechaHora = $this->_setFechaHora($desde, $hasta);

		$comprobEgresos = StockComprobante::where('sucursal_id', $desde['sucursal_id'])
			->whereIn('tipo_movimiento_id', [3, 4, 5])
			->whereBetween('fecha_hora', [$fechaHora['desde'], $fechaHora['hasta']])
			->get()
			->toArray();

		if (count($comprobEgresos) > 0) {
			$pesoTotalEgreso = $this->_getPesoTotalHelado($comprobEgresos);
		} else {
			$pesoTotalEgreso = 0;
		}

		return $pesoTotalEgreso;
	}

	private function _getVentaPosHelados($desde, $hasta)
	{
		$fechaHora = $this->_setFechaHora($desde, $hasta);

		// Salidas por ventas (Helados, se vende en productos con receta)
		$detalleVtas = DetalleComprobReceta::select(
				DB::raw('sum(cantidad) as total_kilos, producto_codigo'))
			->whereBetween('fecha_hora', [$fechaHora['desde'], $fechaHora['hasta']])
			->where('sucursal_id', $desde['sucursal_id'])
			->where('producto_codigo', '01-1')
			->groupBy('producto_codigo')
			->get()
			->toArray();

		if (count($detalleVtas) > 0) {
			return (float) $detalleVtas[0]['total_kilos'];
		} else {
			return 0;
		}

	}

	private function _cargaStockInicialFinal($productos, $desde, $tipoStock)
	{
		$stock_real_desde = $this->_getStockRealArticulos($desde);

		foreach ($productos as $key => $value) {
			if ($key === 0) continue;		// Porque es helado

			$valores = $this->_getValoresStockInicial($value['codigo'], $stock_real_desde);
			$productos[$key][$tipoStock] = $valores['total_unid'];
			$productos[$key]['costo'] = (float) $valores['costo_unid'];
		}

		return $productos;
	}

	private function _getValoresStockInicial($codigo, $stRealDesde)
	{
		$valores = ['total_unid' => 0, 'costo_unid' => 0];

		foreach ($stRealDesde as $value) {
			if ($value['codigo'] === $codigo) {
				$valores['total_unid'] = $value['total_unid'];
				$valores['costo_unid'] = $value['costo_unid'];
				break;
			}
		}

		return $valores;
	}

	private function _getStockRealArticulos($idPlanilla = null)
	{
		return StockRealDetalleArticulo::where('planilla_id', $idPlanilla)
			->orderBy('descripcion')
			->get()
			->toArray();
	}

	private function _cargaIngresosEgresos($desde, $hasta, $productos, $tipo = 'ingreso')
	{
		switch ($tipo) {
			case 'ingreso':
				$tipoMovId = [1, 2];
				$clave = 'ingresos';
				break;
			
			case 'egreso':
				$tipoMovId = [3, 4, 5];
				$clave = 'salidas';
				break;
		}
		$fechaHora = $this->_setFechaHora($desde, $hasta);

		$comprobantes = StockComprobante::where('sucursal_id', $desde['sucursal_id'])
			->whereIn('tipo_movimiento_id', $tipoMovId)
			->whereBetween('fecha_hora', [$fechaHora['desde'], $fechaHora['hasta']])
			->get()
			->toArray();
		//dd("Comprobantes", $comprobantes, "Desde: ", $fechaHora['desde'], "Hasta: ", $fechaHora['hasta']);
		if (count($comprobantes) > 0) {
			// ACA HAGO LA CONSULTA POR TODOS LOS DETALLES DEL PERIODO !!
			$detalle = $this->_getDetalleIngEgr($desde['sucursal_id'], $comprobantes);
			$cantDetalle = count($detalle);
			//dd($detalle, $detalle[0], count($detalle), gettype($detalle));
			if ($cantDetalle > 0) {
				foreach ($productos as $key => $producto) {
					if ($key === 0) continue;

					for ($i = 0; $i < count($detalle); $i++) {
						if ($producto['codigo'] === $detalle[$i]['codigo']) {
							$productos[$key][$clave] = $productos[$key][$clave] + (integer) $detalle[$i]['cant_total_unid'];
							//$productos[$key]['costo'] = (float) $detalle[$i]['costo_unitario'];
						}
					}
				}
			}
		}

		return $productos;
	}

	private function _getDetalleIngEgr($sucursal_id, $comprobantes)
	{
		$detalle_ingresos = $detalles = [];

		foreach ($comprobantes as $compro) {		// POR CADA COMPROBANTE BUSCAR EL DETALLE
			$detalle = StockDetalleComprobante::select(
					'id', 'codigo', 'grupo_id', 'descripcion', 'cant_total_unid', 'costo_unitario')
				->where('stock_comprob_id', $compro['id'])
				->where('sucursal_id', $sucursal_id)
				->whereIn('grupo_id', $this->grupos_articulos)
				->get()
				->toArray();

			if (count($detalle) > 0) {
				$detalle_ingresos[] = $detalle;
			}
		}

		foreach ($detalle_ingresos as $arrDetalle) {
			foreach ($arrDetalle as $value) {
				$detalles[] = $value;
			}
		}

		return $detalles;
	}

	private function _cargaVentas($desde, $hasta, $productos)
	{
		$fechaHora = $this->_setFechaHora($desde, $hasta);
		$cantProd = count($productos);
		
		for ($key = 0; $key < $cantProd; $key++) {
			if ($key === 0) continue;
			
			$totalVtaPos = 0;

			// Carga ventas...
			$totalVtaPos += $this->_getVentaPosArticulo(
				$desde['sucursal_id'], 
				$fechaHora, 
				$productos[$key]['codigo'],
				$productos[$key]['codigo_art_ind']
			);

			// Ventas articulos de receta
			$totalVtaPos += $this->_getVentaPosArticuloReceta(
				$desde['sucursal_id'], 
				$fechaHora, 
				$productos[$key]['codigo']
			);

			// Cargar total venta a producto
			$productos[$key]['ventas_pos'] = $totalVtaPos;
		}

		return $productos;
	}

	private function _getVentaPosArticulo($sucursal_id, $fechaHora, $codigo, $artIndiv)
	{
		$total_unidades = 0;

		// Total ventas del producto en el periodo...
		$totalVenta = $this->_getTotalVenta($sucursal_id, $fechaHora, $codigo);

		if ($totalVenta) {
			$total_unidades += (integer) $totalVenta[0]['total_unid'];
		}

		if (!empty($artIndiv)) {
			# Busca y suma unidades del articulo individual
			$totalVtaReceta = $this->_getTotalVenta($sucursal_id, $fechaHora, $artIndiv);

			if ($totalVtaReceta) {
				$total_unidades += (integer) $totalVtaReceta[0]['total_unid'];
			}
		}

		return $total_unidades;
	}

	private function _getTotalVenta($sucursal_id, $fechaHora, $codigo)
	{
		return DetalleComprobVenta::select(
			DB::raw('sum(unidades) as total_unid, producto_codigo'))
				->whereBetween('fecha_hora', [$fechaHora['desde'], $fechaHora['hasta']])
				->where('sucursal_id', $sucursal_id)
				->where('producto_codigo', $codigo)
				->where('estado', 1)
				->groupBy('producto_codigo')
				->get()
				->toArray();
	}

	private function _getVentaPosArticuloReceta($sucursal_id, $fechaHora, $codigo)
	{
		$total_cantidad = 0;

		// Total ventas del producto de receta en el periodo...
		$detalleVtas = DetalleComprobReceta::select(
			DB::raw('sum(cantidad) as total_cant, producto_codigo'))
				->whereBetween('fecha_hora', [$fechaHora['desde'], $fechaHora['hasta']])
				->where('sucursal_id', $sucursal_id)
				->where('producto_codigo', $codigo)
				->where('estado', 1)
				->groupBy('producto_codigo')
				->get()
				->toArray();

		if ($detalleVtas) {
			$total_cantidad += (integer) $detalleVtas[0]['total_cant'];
		}

		return $total_cantidad;
	}

	private function _calculosDesvio(array $productos, Utils $utils)
	{
		foreach ($productos as $key => $producto) {
			if ($key === 0) continue;

			$ingresos = $producto['stock_inicial'] + $producto['ingresos'];
			$salidas = $producto['salidas'] + $producto['stock_final'];

			$productos[$key]['si_i_s_sf'] = $ingresos - $salidas;
			$productos[$key]['desvio_unid'] = $productos[$key]['si_i_s_sf'] - $productos[$key]['ventas_pos'];
			$productos[$key]['desvio_pesos'] = $productos[$key]['costo'] * $productos[$key]['desvio_unid'];

			if ($productos[$key]['si_i_s_sf'] !==0) {
				$porcDesvio = ($productos[$key]['desvio_unid'] * 100) / $productos[$key]['si_i_s_sf'];
				$productos[$key]['porc_desvio_stissf'] = $utils->redondear_dos_decimal($porcDesvio);
			}

			if ($productos[$key]['ventas_pos'] !== 0) {
				$desvioVta = ($productos[$key]['desvio_unid'] * 100) / $productos[$key]['ventas_pos'];
				$productos[$key]['porc_desvio_vta_pos'] = $utils->redondear_dos_decimal($desvioVta);
			}
		}

		return $productos;
	}

    private function __getGruposArticulos()
    {
        $func = function($val) { 
            return $val['id']; 
        };
        $arrGruposArtic = ProductoGrupo::select('id')
            ->where('mide_desvio', 1)
            ->get()
            ->toArray();

        return array_map($func, $arrGruposArtic);
    }
}
