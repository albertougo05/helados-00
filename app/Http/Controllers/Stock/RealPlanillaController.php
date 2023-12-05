<?php

namespace App\Http\Controllers\Stock;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sucursal;
use App\Models\Producto;
use App\Models\StockRealPlanilla;
use App\Models\StockRealDetalleArticulo;
use App\Models\StockRealDetalleHelado;
use App\Models\PesoEnvaseHelado;
use App\Models\ProductoGrupo;

class RealPlanillaController extends Controller
{
    //protected $grupos_articulos = [3, 6, 9, 10, 11, 12, 13, 15];      // NO Promos
    protected $grupos_articulos;
    protected $grupos_helados = [2];
    protected $cant_por_pagina = 15;
    protected $cant_meses_ver_planillas = 6;

    public function __construct() 
    {
        $this->grupos_articulos = $this->_getGruposArticulos();
    } 

    /**
     * Muestra planilla de Stock Real
     * Name: stock.real.planilla
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $titulo = 'Planillas de Stock Real';
        $usuario_id = Auth::user()->id; 
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $sucursal = Sucursal::find($sucursal_id)->nombre;
        $planillas = $this->_getPlanillas($sucursal_id, $this->cant_meses_ver_planillas);

        $data = compact(
            'titulo',
            'sucursal_id',
            'usuario_id',
            'sucursal',
            'planillas',
        );

        return view('stock.real.index', $data);
    }

   /**
     * Crea planilla de Stock Real
     * Name: stock.real.planilla.create
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $titulo = 'Planilla Stock Real';
        $usuario_id = Auth::user()->id; 
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $sucursales = Sucursal::select('id', 'nombre')
            ->where('estado', 1)
            ->get();
        $sucursal = $sucursales->find($sucursal_id)->nombre;
        $articulos = $this->getArticulos($sucursal_id);
        $helados = $this->getHelados($sucursal_id);
        $peso_envase = $this->getPesoEnvase($sucursal_id);
        $new_id = $this->_getNewIdStockReal($sucursal_id);

        $data = compact(
            'titulo',
            'sucursal_id',
            'usuario_id',
            'sucursales',
            'sucursal',
            'articulos',
            'helados',
            'peso_envase',
            'new_id',
        );

        return view('stock.real.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * Name: stock.real.planilla.store (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $respuesta = ['estado' => 'error', 'id' => 0];
        $comp = StockRealPlanilla::create($request->all());

        if ($comp) {
            $respuesta = ['estado' => 'ok'];
            $respuesta['id'] = $comp->id;
        }

        return json_encode($respuesta);
    }

    /**
     * Salva/Guarda los articulos por primera vez.
     * Name: stock.real.planilla.store_articulos (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeArticulos(Request $request)
    {
        $respuesta = ['estado' => 'error'];

        if (count($request->all()) === 0) {
            $respuesta['estado'] = 'ok';
        } else {
            $articulos = $request->all();

            foreach ($articulos as $art) {
                $reg = StockRealDetalleArticulo::create($art);
            }
    
            if ($reg) {
                $respuesta['estado'] = 'ok';
            }
        }

        return json_encode($respuesta);
    }

    /**
     * Salva/Guarda los helados por primera vez.
     * Name: stock.real.planilla.store_helados (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeHelados(Request $request)
    {
        $respuesta = ['estado' => 'error'];

        if (count($request->all()) === 0) {
            $respuesta['estado'] = 'ok';
        } else {
            $helados = $request->all();

            foreach ($helados as $hela) {
                $reg = StockRealDetalleHelado::create($hela);
            }

            if ($reg) {
                $respuesta['estado'] = 'ok';
            }
        }

        return json_encode($respuesta);
    }

    public function getArticulos($sucursal_id)
    {
        return Producto::select('id', 'codigo', 'descripcion', 'costo_x_unidad', 
                'costo_x_bulto', 'unidades_x_bulto')
            ->whereIn('grupo_id', $this->grupos_articulos)
            ->where('estado', 1)
            ->orderBy('descripcion')
            ->get();
    }

    public function getHelados($sucursal_id) {

        return Producto::select('id', 'codigo', 'descripcion', 'peso_materia_prima', 'costo_x_bulto')
            ->selectRaw('costo_x_bulto / peso_materia_prima as costo_x_kilo')
            ->where('estado', 1)
            ->whereIn('grupo_id', $this->grupos_helados)
            ->orderBy('descripcion')
            ->get();
    }

    public function getPesoEnvase($suc_id)
    {
        $peso_envase = 0.3;

        $pesoEnv = PesoEnvaseHelado::where('sucursal_id', $suc_id)
            ->first();

        if ($pesoEnv) {
            $peso_envase = floatval($pesoEnv->peso);
        }

        return $peso_envase;
    }

    private function _getGruposArticulos()
    {
        $func = function($val) { 
            return $val['id']; 
        };
        $arrGruposArtic = ProductoGrupo::select('id')
            ->where('id', '!=', 17)         // NO PROMOS
            ->where('mide_desvio', 1)
            ->where('estado', 1)
            ->get()
            ->toArray();

        return array_map($func, $arrGruposArtic);
    }

    private function _getNewIdStockReal(int $sucursal_id)
    {
        $id = 1;
        $reg = StockRealPlanilla::where('sucursal_id', $sucursal_id)
            ->latest()
            ->first();
        
        if ($reg) {
            $id = (integer) $reg->id + 1;
        }

        return $id;
    }

    private function _getPlanillas($suc_id, $cant_meses)
    {
        $hoy = Carbon::now();
        $desde = $hoy->subMonths($cant_meses);

        return StockRealPlanilla::where('sucursal_id', $suc_id)
            ->where('fecha_toma_stock', '>', $desde)
            ->orderBy('fecha_toma_stock', 'desc')
            ->orderBy('hora_toma_stock', 'desc')
            ->paginate($this->cant_por_pagina);
    }

}
