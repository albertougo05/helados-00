<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComprobanteVenta;
use App\Models\Firma;
use App\Models\ProductoGrupo;
use App\Models\ProductoPromo;
use App\Models\ProductoPromoOpciones;
use App\Models\Sucursal;
use App\Models\Turno;
use App\Http\Controllers\Utils\Utils;
use App\Http\Controllers\Productos\ProductosDeSucursal;
use Illuminate\Support\Facades\Auth;


class ComprobanteController extends Controller
{
    /**
     * Muestra comprobante de ventas
     * Name: ventas.comprobante
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $utils = new Utils;

        // Verificar si hay caja/turno abierto
        if (!$this->_checkCajaAbierta($user_sucursal_id)) {
            return redirect('abreturno');
        }

        $cajas_suc = $utils->getCajas($user_sucursal_id);
        $sucursal = Sucursal::find($user_sucursal_id);
        $caja_nro = $this->_getCajaNro($user_sucursal_id);
        $formas_pago = ['Efectivo', 'Tarj. Débito', 'Tarj. Crédito', 'Transferencia', 'Cta. Cte.'];
        //$fecha = date('d/m/Y');
        $productos = (new ProductosDeSucursal)->getProductosSucursal($user_sucursal_id);
        $promos = $this->_getPromos();
        $promo_opcs = $this->_getPromosOpc($promos);
        $clientes = Firma::select('id', 'firma')
            ->where([['cliente', '=', 1], ['estado', '=', 1]])
            ->orderBy('firma')
            ->get();
        $numero = $this->_getNumeroComprobante($user_sucursal_id);
        $grupos = ProductoGrupo::where('orden', '<', 99)
            ->orderBy('orden')
            ->get();

        $turno = $this->_getTurnoIdSucursal($user_sucursal_id, Auth::user()->perfil);
        $turno_id = $turno->id;
        $turno_sucursal = $turno->turno_sucursal;

        $impresora = $utils->getImpresoras($user_sucursal_id, $caja_nro);
        //$impresora = [['nombre' => '']];
        $env = env('APP_ENV');

        $data = compact(
            'productos',
            'promos',
            'promo_opcs',
            'cajas_suc',
            'sucursal', 
            'caja_nro',
            //'fecha', 
            'numero', 
            'formas_pago',
            'clientes',
            'grupos',
            'user_sucursal_id',
            'turno_id',
            'turno_sucursal',
            'impresora',
            'env'
        );

        return view('ventas.index', $data);
    }

    /**
     * Guarda nuevo comprobante de venta
     * Name: ventas.comprobante (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $respuesta = ['estado' => 'ok'];
        $status = 200;
        $comprob = ComprobanteVenta::create($request->all());

        if (!$comprob) {
            $respuesta = ['estado' => 'error'];
            $status = 204;
        } else {
            $respuesta['nro_comprobante'] = $comprob->nro_comprobante;
            $respuesta['comprobante_id'] = $comprob->id;
        }

        return response()->json($respuesta, $status);
    }

    /**
     * Devuelve json con número de comprobante disponible, según nro_sucursal y código
     * Name: ventas.comprobante.getnumero
     * 
     * @param  \Illuminate\Http\Request $request (sucursal_id)
     * @return \Illuminate\Http\Response
     */
    public function getNumero(Request $request)
    {
        $respuesta = [
            'estado' => 'error',
            'numero' => 101,
        ];

        $nroComp = ComprobanteVenta::select('nro_comprobante')
            ->where('sucursal_id', $request->input('sucursal_id'))
            ->latest()
            ->first();

        if ($nroComp) {
            $respuesta['estado'] = 'ok';
            $respuesta['numero'] = (integer) $nroComp->nro_comprobante + 1;
        }

        return response()->json($respuesta);
    }

    /**
     * Anula comprobante de venta
     * Devuelve json 
     * Name: ventas.comprobante.anular
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function anular($id)
    {
        $respuesta = [
            'resultado' => 'error',
        ];

        $rows = ComprobanteVenta::where('id', $id)
            ->lockForUpdate()
            ->update(['estado' => 0]);

        if ($rows > 0) {
            $respuesta['resultado'] = 'ok';
        }

        return response()->json($respuesta);
    }


    /** 
     * Devuelve codigos de la sucursal 
     * y numero de comprobante, para el primer código
     * 
     * @return string
     */
    private function _getNumeroComprobante(int $user_suc_id)
    {
        // Buscar el número de comprobante
        $compVta = ComprobanteVenta::select('nro_comprobante')
            ->where('sucursal_id', '=', $user_suc_id)
            ->latest()
            ->first();

        if ($compVta) {
            $nroComp = $compVta->nro_comprobante;
        } else {
            $nroComp = 100;
        }

        return $nroComp + 1;
    }

    private function _checkCajaAbierta($sucursal_id)
    {
        $turno = Turno::where('cierre_fecha_hora', null)
            ->where('sucursal_id', $sucursal_id)
            ->where('usuario_id', Auth::user()->id)
            ->get();

        if ($turno->count() > 0) {
            $retorno = true;
        } else {
            $retorno = false;
        }

        return $retorno;
    }

    private function _getTurnoIdSucursal($sucursal_id, $perfilUser)
    {
        $whereClaus = [
            ['cierre_fecha_hora', null],
            ['sucursal_id', $sucursal_id]
        ];

        if ($perfilUser > 2) {      // Si el usuario NO es admin...
            $whereClaus[] = ['usuario_id', Auth::user()->id];
        }

        return Turno::select('id', 'turno_sucursal')
            ->where($whereClaus)
            ->first();
    }

    private function _getCajaNro($sucursal_id)
    {
        $turno = Turno::where('sucursal_id', $sucursal_id)
            ->where('usuario_id', Auth::user()->id)
            ->where('cierre_fecha_hora', null )
            ->first();

        if ($turno) {
            $caja_nro = $turno->caja_nro;
        } else $caja_nro = 0;

        return $caja_nro;
    }

    private function _getPromos() : array 
    {
        $fechaActual = date('Y-m-d');
        $diaSemana = date('w');     // 0-dom / 1-lun / 2-mar ...

        $listado = ProductoPromo::select([
                'producto_codigo',
                //'desde_fecha_hora',
                //'hasta_fecha_hora',
                //'dias_semana',
                'precio_vta',
            ])
            ->where('dias_semana', 'LIKE', "%$diaSemana%")
            ->whereDate('desde_fecha_hora', '<=', $fechaActual)
            ->whereDate('hasta_fecha_hora', '>=', $fechaActual)
            ->where('estado', 1)
            ->get()
            ->toArray();

        if ($listado) {
            return $listado;
        } else {
            return [];
        }
    }

    function _getPromosOpc($promos) : array 
    {
        $opciones = [];

        if (count($promos) > 0) {
            foreach ($promos as $value) {
                $opcs = ProductoPromoOpciones::select([
                        'producto_codigo',
                        'nro_combo',
                        'producto_codigo_opcion',
                        'descripcion',
                        'cantidad',
                        'precio',
                    ])
                    ->where('producto_codigo', $value['producto_codigo'])
                    ->get()
                    ->toArray();
                $opciones[] = $opcs;
            }
            return $opciones[0];
        }

        return $opciones;
    }

}
