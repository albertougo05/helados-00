<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\ProductoGrupo;
use App\Models\Sucursal;
use App\Http\Controllers\Ventas\Mix\TotalGeneral;
use App\Http\Controllers\Ventas\Mix\CalculoPorcentajes;
use App\Http\Controllers\Ventas\Mix\ArmarMix;


class MixController extends Controller
{
    /**
     * 
     * Clase que totaliza el listado del mix
     * 
     */
    protected $_totalGeneral;
    protected $_calcPorcentajes;
    protected $_armarMix;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->_totalGeneral = new TotalGeneral();
        $this->_calcPorcentajes = new CalculoPorcentajes();
        $this->_armarMix = new ArmarMix();
    }

    /**
     * Mix de ventas
     * name: ventas.mix
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sucursal_id = session('sucursal_id');
        $sucursal = Sucursal::select('nombre')->find($sucursal_id)->nombre;

    	$data = compact(
			'sucursal_id',
			'sucursal',
		);

		return view('ventas.mix.index', $data);
    }

    /**
     * Devuelve los datos para el mix de venta.
	 * Name: ventas.mix.data
	 * http://localhost:8000/ventas/mix/data?desde=2023-05-01&hasta=2023-08-15
	 * 
     * @param Request $request
     * @return Response
     */
    public function data(Request $request)
    {
        $productos = $this->_getListaProductos($request);        // Lista de productos
        $totgral = $this->_totalGeneral->getTotalesGenerales($productos);
        $productos = $this->_calcPorcentajes->inicio($productos, $totgral);
        $sucursal_id = session('sucursal_id');
        $sucursal = Sucursal::select('nombre')->find($sucursal_id)->nombre;
        $titulo = $this->_getTituloMix($request);

    	$data = compact(
			'sucursal_id',
			'sucursal',
            'productos',
            'titulo',
            'totgral',
		);

		return view('ventas.mix.show', $data);
    }

    private function _getTituloMix($req) : string 
    {
        $titulo = "Desde: ".date("d/m/Y H:i", strtotime($req['desde']));
        $titulo = $titulo . " - Hasta: ".date("d/m/Y H:i", strtotime($req['hasta']));

        return $titulo;
    }

    private function _getGrupos() : array
    {
        return ProductoGrupo::select('id', 'descripcion')
                ->where([
                    ['id', '>', 2],      // No van los primeros 2
                    ['estado', 1],
                    //['unidad', 0]
                ])
                ->orderBy('descripcion')
                ->get()
                ->toArray();
    }

    private function _getListaProductos(Request $req) : array
    {
        $listado = [];
        $productos = $this->_getTodosLosProductos();
        //dd($productos);
        $listado = $this->_armarMix->getMixVtas($productos, $req['desde'], $req['hasta']);
        //dd($listado);

        return $listado;
    }

    private function _getTodosLosProductos() : array 
    {
        $grupos = $this->_getGrupos();
        $grupos_ids = $this->_getGruposIds($grupos);

        return Producto::select('productos.codigo', 'productos.grupo_id', 
                    'producto_grupos.descripcion as grupo', 'productos.con_receta', 
                    'productos.descripcion', 'productos.articulo_indiv_id')
                ->where('productos.estado', 1)
                ->whereIn('productos.grupo_id', $grupos_ids)
                ->join('producto_grupos', 
                    'productos.grupo_id', 
                    '=', 
                    'producto_grupos.id')
                ->orderBy('productos.grupo_id')
                ->orderBy('productos.descripcion')
                ->get()
                ->toArray();
    }

    private function _getGruposIds($grupos) : array 
    {
        $arr = [];
        foreach ($grupos as $value) {
            $arr[] = $value['id'];
        }

        return $arr;
    }
}
