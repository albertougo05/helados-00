<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Utils\Utils;
use App\Models\Sucursal;
use App\Models\ProductoGrupo;
use App\Models\Producto;
use App\Models\StockRealPlanilla;
use App\Models\StockRealDetalleArticulo;
use App\Models\StockComprobante;
use App\Models\StockDetalleComprobante;
use App\Models\DetalleComprobVenta;
use App\Models\DetalleComprobReceta;
use App\Models\ProductoSucursal;
use Illuminate\Database\Eloquent\Collection;


/**
 * Informe de stock diario. (Toda la mercaderia que hay)
 */
class InformeDiarioController extends Controller
{
    private $grupos_articulos;

    public function __construct() 
    {
        $this->grupos_articulos = $this->_getGruposArticulos();
    } 

    /**
     * Inicio - Informe diario de Stock.
	 * Name: stock.informediario
	 * 
     * @param Request $request
     * @return \Illuminate\Contracts\View
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
		$utils = new Utils;
        $turnos_abiertos = $utils->getTurnosAbiertosSucursal($sucursal_id);
        $turno_id = $turnos_abiertos[0]['turno_id'];
        $turno_sucursal = $turnos_abiertos[0]['turno_sucursal'];
        $sucursal = Sucursal::select('nombre')->find($sucursal_id);
        $articulos = $this->_getArticulos($sucursal_id);
        $nombreImpresora = $utils->getNombreImpresora($sucursal_id);
		$env = env('APP_ENV');
		//dd($articulos);
        $data = compact(
			'usuario',
			'turno_id',
			'turno_sucursal',
            'sucursal_id',
			'sucursal',
			'articulos',
			'nombreImpresora',
			'env',
		);

		return view('stock.informediario.index', $data);
    }

	/**
	 * Es llamada desde ../Printer/InformeDiarioController
	 */
	public function getListaArticulos($sucursal_id)
	{
		return $this->_getArticulos($sucursal_id);
	}


	private function _getGruposArticulos()
    {
        $func = function($val) { 
            return $val['id']; 
        };
        $arrGruposArtic = ProductoGrupo::select('id')
            ->where('carga_stock', 1)
            ->where('id', '!=', 2)      // Que no sea helado
            ->get()
            ->toArray();

        return array_map($func, $arrGruposArtic);
    }

    private function _getArticulos($sucursal_id)
    {
        $listaArticulos = $this->__getListaArticulos($sucursal_id);
		$planillaStockDesde = $this->__getPlanillaStockDesde($sucursal_id);
        $modelArticulo = $this->__getModelArticulo();		// MODELO DE ARTICULO

		$articulosStock = $this->__getArticulosStock(		// ARMA LISTA DE ARTICULOS, SEGUN MODELO
            $listaArticulos, 
            $modelArticulo);

		$articulosStock = $this->__cargaStockInicial(    	// CARGA STOCK INICIAL
			$articulosStock, 
			$planillaStockDesde['id']);

        $articulosStock = $this->__cargaIngresosSalidas(    // SUMAR INGRESOS
            $planillaStockDesde, 
            $articulosStock, 
            'ingresos');

		$articulosStock = $this->__cargaIngresosSalidas(    // RESTAR SALIDAS
            $planillaStockDesde, 
            $articulosStock, 
            'salidas');

		$articulosStock = $this->__cargaVentasPos(			// SUMAR VENTAS
			$planillaStockDesde,
			$articulosStock);

		$articulosStock = $this->__calculoStock($articulosStock);

        return $articulosStock;
    }

    private function __getListaArticulos($sucursal_id)
    {
		$insumos = Producto::select('id', 'codigo', 'grupo_id', 'tipo_producto_id', 'descripcion',
			'costo_x_unidad', 'costo_x_bulto', 'unidades_x_bulto', 'articulo_indiv_id')
            ->whereIn('grupo_id', $this->grupos_articulos)
			->where('tipo_producto_id', 3)	// <<-- INSUMOS
            ->where('estado', 1)
            ->orderBy('descripcion')
            ->get();

        $restoProds = Producto::select('id', 'codigo', 'grupo_id', 'tipo_producto_id', 'descripcion',
			'costo_x_unidad', 'costo_x_bulto', 'unidades_x_bulto', 'articulo_indiv_id')
            ->whereIn('grupo_id', $this->grupos_articulos)
			->where('tipo_producto_id', '!=', 3)	// <<-- NOT INSUMOS
            ->where('estado', 1)
            ->orderBy('descripcion')
            ->get();

		$temp = $insumos->merge($restoProds);
		$articulos = new Collection;

		foreach ($temp as $value) {		// Filtro por solo los articulos de la sucursal...
			$prod = ProductoSucursal::where('sucursal_id', $sucursal_id)
				->where('producto_id', $value->codigo)
				->first();

			if ($prod) {
				$articulos->push($value);
			}
		}
		//dd($articulos->toArray());
		return $articulos;
    }

    private function __getPlanillaStockDesde($sucursal_id)
    {
        $planillaDesde = StockRealPlanilla::select('id', 'sucursal_id', 'fecha_toma_stock', 'hora_toma_stock')
            ->where('sucursal_id', $sucursal_id)
            ->latest()
            ->get()
            ->toArray();
        
        return $planillaDesde[0];
    }

    private function __getModelArticulo()
	{
		return [
            'id' => 0,
			'grupo_id' => 0,
			'tipo_producto_id' => 0,
			'codigo' => '',
			'descripcion' => '',
			'codigo_art_ind' => 0,
			'costo' => 0,
			'unid_x_bulto' => 0,  // unidades_x_bulto
            'costo_x_bulto' => 0,
            'inicio_unid' => 0,
            'ingresos_unid' => 0,
			'salidas_unid' => 0,
			'ventas_pos'  => 0,
			'final_bultos' => 0,
            'final_unid' => 0,
		];
    }

    private function __getArticulosStock($articulos, $modelo)
    {
		foreach ($articulos as $value) {
			$clon_modelo = $modelo;
            $clon_modelo['id'] = $value->id;
			$clon_modelo['grupo_id'] = $value->grupo_id;
			$clon_modelo['tipo_producto_id'] = $value->tipo_producto_id;
			$clon_modelo['codigo'] = $value->codigo;
			$clon_modelo['descripcion'] = $value->descripcion;
			$clon_modelo['codigo_art_ind'] = $value->articulo_indiv_id;
			$clon_modelo['costo'] = (float) $value->costo_x_unidad;

			$arrProductos[] = $clon_modelo;
		}

		return $arrProductos; 
    }

    private function __cargaStockInicial($articulos, $desde)
	{
		$stock_real_desde = $this->__getStockRealAnterior($desde);

        //dd($stock_real_desde);

		foreach ($articulos as $key => $value) {
            //if ($key === 0) continue;		// Porque es helado

			$valores = $this->__getValuesStockInic($value['codigo'], $stock_real_desde);
			$articulos[$key]['unid_x_bulto'] = $valores['unid_x_bulto'];
            $articulos[$key]['inicio_unid'] = $valores['total_unid'];
			$articulos[$key]['costo'] = (float) $valores['costo_unid'];
		}

		return $articulos;
	}

    private function __getStockRealAnterior($idPlanilla = null)
	{
		return StockRealDetalleArticulo::where('planilla_id', $idPlanilla)
			->orderBy('descripcion')
			->get()
			->toArray();
	}

    private function __getValuesStockInic($codigo, $stRealDesde)
	{
		$valores = ['unid_x_bulto' => 0, 
                    'total_unid' => 0, 
                    'costo_unid' => 0];

		foreach ($stRealDesde as $value) {
			if ($value['codigo'] === $codigo) {
				$valores['unid_x_bulto'] = $value['unid_x_bulto'];
                $valores['total_unid']   = $value['total_unid'];
				$valores['costo_unid']   = $value['costo_unid'];
				break;
			}
		}

		return $valores;
	}

	private function __cargaIngresosSalidas($desde, $articulos, $tipo = 'ingresos')
	{
		switch ($tipo) {
			case 'ingresos':
				$tipoMovId = [1, 2];
				$clave = 'ingresos_unid';
				break;
			
			case 'salidas':
				$tipoMovId = [3, 4, 5];
				$clave = 'salidas_unid';
				break;
		}
		$fechaHora = $this->__estableceRangoFechaHora($desde);

		$comprobantes = StockComprobante::where('sucursal_id', $desde['sucursal_id'])
            ->where('estado', '>', 0)
			->whereIn('tipo_movimiento_id', $tipoMovId)
			->whereBetween('fecha_hora', [$fechaHora['desde'], $fechaHora['hasta']])
			->get()
			->toArray();

		//dd("Comprobantes:", $comprobantes, "Desde:", $fechaHora['desde'], "Hasta:", $fechaHora['hasta']);

		if (count($comprobantes) > 0) { // ACA CONSULTA POR TODOS LOS DETALLES DEL PERIODO !!
			$detalle = $this->__getDetalleEntradasSalidas($desde['sucursal_id'], $comprobantes);
			$cantDetalle = count($detalle);

			//dd($comprobantes, $detalle, $detalle[0], count($detalle));

			if ($cantDetalle > 0) {
				foreach ($articulos as $key => $producto) {
					for ($i = 0; $i < count($detalle); $i++) {
						if ($producto['codigo'] === $detalle[$i]['codigo']) {
							$articulos[$key][$clave] = $articulos[$key][$clave] + (integer) $detalle[$i]['cant_total_unid'];
						}
					}
				}
			}
		}

		return $articulos;
	}

    private function __estableceRangoFechaHora($desde)
	{
		return [
			'desde' => $desde['fecha_toma_stock']." ".$desde['hora_toma_stock'],
			'hasta' => date('Y-m-d H:i')
		];
	}

    private function __getDetalleEntradasSalidas($sucursal_id, $comprobantes)
	{
		$detalle_ingresos = $detalles = [];

		foreach ($comprobantes as $compro) {	// POR CADA COMPROBANTE BUSCAR EL DETALLE
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
			//echo "<br><pre>"; print_r($arrDetalle); echo "</pre><br>";
			foreach ($arrDetalle as $value) {
				$detalles[] = $value;
			}
		}

		return $detalles;
	}

	private function __cargaVentasPos($desde, $productos)
	{
		$fechaHora = $this->__estableceRangoFechaHora($desde);
		$cantProd = count($productos);
		
		for ($key = 0; $key < $cantProd; $key++) {
			$totalVtaPos = 0;

			$totalVtaPos += $this->__getVentaPosArticulo(	// Carga ventas...
				$desde['sucursal_id'], 
				$fechaHora, 
				$productos[$key]['codigo'],
				$productos[$key]['codigo_art_ind']
			);

			// Ventas articulos de receta
			$totalVtaPos += $this->__getVentaPosArticuloReceta(
				$desde['sucursal_id'], 
				$fechaHora, 
				$productos[$key]['codigo']
			);

			// Cargar total venta a producto
			$productos[$key]['ventas_pos'] = $totalVtaPos;
		}

		return $productos;
	}

	private function __getVentaPosArticulo($sucursal_id, $fechaHora, $codigo, $artIndiv)
	{
		$total_unidades = 0;
		// Total ventas del producto en el periodo...
		$totalVenta = $this->__totalVenta($sucursal_id, $fechaHora, $codigo);

		if ($totalVenta) {
			$total_unidades += (integer) $totalVenta[0]['total_unid'];
		}

		if (!empty($artIndiv)) {
			# Busca y suma unidades del articulo individual
			$totalVtaReceta = $this->__totalVenta($sucursal_id, $fechaHora, $artIndiv);

			if ($totalVtaReceta) {
				$total_unidades += (integer) $totalVtaReceta[0]['total_unid'];
			}
		}

		return $total_unidades;
	}

	private function __totalVenta($sucursal_id, $fechaHora, $codigo)
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

	private function __getVentaPosArticuloReceta($sucursal_id, $fechaHora, $codigo)
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

	private function __calculoStock(array $articulos) : array {
		foreach ($articulos as $key => $artic) {
			$ingresos = $artic['inicio_unid'] + $artic['ingresos_unid'];
			$salidas = $artic['salidas_unid'] + $artic['ventas_pos'];
			$articulos[$key]['final_unid'] = $ingresos - $salidas;

			if ($artic['tipo_producto_id'] == 3) {	// Si es insumo calculo cant bultos
				if ($artic['final_unid'] > $artic['unid_x_bulto']) {
					$artic['final_bultos'] = floor($artic['final_unid'] / $artic['unid_x_bulto']);
					$artic['final_unid'] = $artic['final_unid'] - ($artic['final_bultos'] * $artic['unid_x_bulto']);
				}
			}
		};

		return $articulos;
	}
}
