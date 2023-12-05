<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Utils\Utils;
use App\Models\StockComprobante;
use App\Models\StockDetalleComprobante;


class InfoMovimientosController extends Controller
{
    /**
     * Inicio Informe Movimientos de Stock.
	 * Name: stock.infomovimientos
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

		$turnos_abiertos = $utils->getTurnosAbiertosSucursal($sucursal_id);
        $turno_id = 0; 
		$turno_sucursal = 0;
		$comprobantes = [];
		$nombreImpresora = $utils->getNombreImpresora($sucursal_id);
		$mensaje_error = '';

		if ($turnos_abiertos[0]['turno_id'] === 0) {
			$mensaje_error = 'No hay turno abierto !!';
		} else {
			$turno_id = $turnos_abiertos[0]['turno_id'];
			$turno_sucursal = $turnos_abiertos[0]['turno_sucursal'];
			$comprobantes = $this->_getComprobantes($usuario, $sucursal_id, $turno_id);
		}

		$env = env('APP_ENV');
		$data = compact(
			'sucursal_id',
			'usuario',
			'turno_id',
			'turno_sucursal',
			'mensaje_error',
			'comprobantes',
			'nombreImpresora',
			'env',
		);

		return view('stock.informe_movimientos.index', $data);
    }

	/**
	 * Devuelve detalle del id de comprobante de stock
	 * Name: stock.infomovimientos.getdetalle {id}
	 * 
	 * @param Request $request
	 * @param integer $id
	 * @return json
	 */
	public function getDetalle(Request $request, $id)
	{
		return response()->json([
            'detalle' => StockDetalleComprobante::select(
                'cantidad', 'descripcion', 'unidad_medida', 'cant_total_unid')
                ->where('stock_comprob_id', $id)
				->where('cantidad', '>', 0)
                ->get()
                ->toArray()
        ]);
	}

	private function _getComprobantes($usuario, $id_sucursal, $id_turno)
	{
		// Si es admin, puede ver turno actual (sin ser usuario del turno)
		if ($usuario->perfil_id == 1 || $usuario->perfil_id == 2) {	
			return $this->_comprobsAdmin($id_sucursal);
		} else {
			return $this->_comprobsUser($usuario, $id_sucursal, $id_turno);
		}
	}

	private function _comprobsUser($usuario, $id_sucursal, $id_turno)
	{
		$whereClaus = [
			['stock_comprobantes.sucursal_id', $id_sucursal],
			['stock_comprobantes.usuario_id', $usuario->id],
			['stock_comprobantes.turno_id', $id_turno],
			['stock_comprobantes.estado', '>', 0]
		];

		return StockComprobante::where($whereClaus)
			->join('stock_comprobante_tipos_movim', 
				'stock_comprobantes.tipo_movimiento_id',
				'=',
				'stock_comprobante_tipos_movim.id')
			->select('stock_comprobantes.id',
				'stock_comprobantes.nro_comprobante',
				'stock_comprobantes.tipo_movimiento_id',
				'stock_comprobantes.fecha',
				'stock_comprobantes.hora',
				'stock_comprobantes.estado',
				'stock_comprobante_tipos_movim.descripcion')
			->orderBy('id')
			->get();
	}

	private function _comprobsAdmin($id_sucursal)
	{
		$date_now = date('Y-m-d');
		$date_past = strtotime('-60 day', strtotime($date_now));
		$date_past = date('Y-m-d', $date_past);

		return StockComprobante::where([
					['stock_comprobantes.sucursal_id', $id_sucursal],
					['stock_comprobantes.fecha', '>', $date_past],
					['stock_comprobantes.estado', '>', 0]
				])
				->join('stock_comprobante_tipos_movim', 
					'stock_comprobantes.tipo_movimiento_id',
					'=',
					'stock_comprobante_tipos_movim.id')
				->select('stock_comprobantes.id',
					'stock_comprobantes.nro_comprobante',
					'stock_comprobantes.tipo_movimiento_id',
					'stock_comprobantes.fecha',
					'stock_comprobantes.hora',
					'stock_comprobantes.estado',
					'stock_comprobante_tipos_movim.descripcion')
				->orderBy('id', 'desc')
				->get();
	}
}