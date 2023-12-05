<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CajaMovimiento;
use App\Models\CajaTiposMovim;
use App\Models\User;
use App\Models\Sucursal;
use App\Http\Controllers\Utils\Utils;
use App\Models\DetalleComprobVenta;
use App\Models\Turno;
use Exception;


class CajaMovimientoController extends Controller
{
    protected $cant_paginas = 15;

    /**
     * Informe de movimiemtos de caja (del turno actual)
     * Name: caja_movimiento.index
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->perfil_id <= 2) {     // Admin
            $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        } else {
            $sucursal_id = Auth::user()->sucursal_id;
        }
        $sucursal = Sucursal::select('nombre')
            ->find($sucursal_id);
        $usuario_id = Auth::user()->id;
        $user_perfil_id = Auth::user()->perfil_id;
        $turno = $this->_getCajaNroUsuario($sucursal_id, $usuario_id);

        if (!$turno) {
            return back()->with('status', 'Sin movimientos !!');
        } else {
            $caja_nro = $turno->caja_nro;
        }

        $movims = $this->_getMovimientos($sucursal_id, $usuario_id, $user_perfil_id, $caja_nro);

        if (count($movims) === 0) {
            $request->session()->flash('status', 'Sin movimientos !!');
        }

        $data =  compact(
            'sucursal_id', 
            'sucursal',
            'usuario_id',
            'movims',
        );

        return view('caja.index', $data);
    }

    /**
     * Formulario para crear movimiento de caja
     * Name: caja_movimiento.create
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $utils = new Utils();
        $usuario = Auth::user();
        $sucursal_id = $this->_getSucursalId();
        $sucursal = Sucursal::select('nombre')
            ->find($sucursal_id);
        $fecha_actual = date('d/m/Y');      // date('Y-m-d');

        if (!$utils->getCajaAbiertaEnSucursal($sucursal_id)) {    //SI NO HAY CAJA ABIERTA NO PUEDE HACER MOVIMIENTO !!
            return redirect('abreturno');
        }

        $tipos_movim = CajaTiposMovim::where('estado', 1)        // Tipos de movimientos...
            ->orderBy('descripcion')
            ->get();
        // Si el usuario es admin or admin empresa, muestra las todas las cajas abiertas
        if ($usuario->perfil_id === 1 || $usuario->perfil_id === 2) {
            $ptos_vta = $utils->getCajasAbiertasSuc($sucursal_id);
        } else {    // Ver si tiene caja abierta el usuario...
            $ptos_vta = $utils->getCajaUsuario($sucursal_id, $usuario->id);
            if (count($ptos_vta) === 0) {
                return redirect('abreturno');
                //return back()->with('status', 'Usuario NO tiene cajas abiertas !');
            }
        }

        $new_id = 0;
        $nombreImpresora = $utils->getNombreImpresora($sucursal_id);
        $env = env('APP_ENV');
        $turno_nro = $this->_getTurnoSucursal($sucursal_id);

        $data = compact(
            'usuario',
            'sucursal_id',
            'sucursal',
            'ptos_vta',
            'fecha_actual',
            'tipos_movim',
            'new_id',
            'nombreImpresora',
            'env',
            'turno_nro',
        );

        return view('caja.create', $data);
    }

    /**
     * Crea un nuevo movimiento de caja
     * Name: caja_movimiento.store (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $respuesta = [
            'estado' => 'ok',
            'message' => 'Nuevo movimiento creado !!',
        ];
        $data = $request->all();

        if (isset($request->crea_mov)) {
            unset($data['crea_mov']);
            $esCreaMov = true;
        } else $esCreaMov = false;

        try {
            CajaMovimiento::create($data);
        } catch (Exception $e) {
            $respuesta = ['estado' => 'error', 'message' => $e];  //'ERROR !! No se guardaron datos.'];
        }

        if ($esCreaMov) {
            return back()->with('status', $respuesta['message']);
        } else {
            return json_encode($respuesta);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Edita movimiento de caja (PROHIBIDO - NO SE USA !!!)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $movim = CajaMovimiento::find($id);

        $sucursal_id = $this->_getSucursalId();
        $detalle_mov = DetalleComprobVenta::where('nro_comprobante', $movim->nro_comprobante)
            ->where('sucursal_id', $sucursal_id)
            ->get();
        $usuario = User::select('name')
            ->find($movim->usuario_id)
            ->name;
        $prevUrl = session('filtroUrl');
        $tipo_movimiento = $this->_getTipoMovim($movim);
        $data = compact(
            'movim',
            'prevUrl',
            'usuario',
            'tipo_movimiento',
            'detalle_mov',
        );

        return view('caja.edit', $data);
    }

    /**
     * Actualiza datos del movimiento
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Utils $utils)
    {
        $fromUrl = session('filtroUrl');
        $data = $this->_setData($request->all());

        $movCaja = CajaMovimiento::where('id', $id)
            ->lockForUpdate()
            ->update($data);

        if ($movCaja > 0) {
            return redirect($fromUrl)->with('status', 'Movimiento actualizado !');
        } else {
            return redirect($fromUrl)->with('status', 'ERROR ! No actualizó movimiento !');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Anula movimiento de caja por anulacion ticket venta
     * Name: caja_movimiento.anulamovim (GET)
     * 
     * @param  int  $nro (Numero de comprobante de venta)
     * @return \Illuminate\Http\Response
     */
    public function anulamovim($nro) 
    {
        $respuesta = [
            'resultado' => 'error',
        ];

        $rows = CajaMovimiento::where('nro_comprobante', $nro)
            ->where('tipo_movim_id', 0)     // Que sea una venta...
            ->lockForUpdate()
            ->update(['estado' => 0]);

        if ($rows > 0) {
            $respuesta['resultado'] = 'ok';
        }

        return response()->json($respuesta);
    }

    private function _setData($data = null)     // Quita los campos que no están en la tabla
    {
        unset($data['_method']);
        unset($data['_token']);

        return $data;
    }

    private function _getTipoMovim($movim = null)
    {
        $tipo = 'Movimiento: Ticket/Factura ';
        if ($movim->total_efectivo > 0) {
            $tipo = $tipo . "en efectivo.";
        } else if ($movim->total_debito > 0) {
            $tipo = $tipo . "con tarjeta de débito.";
        } else if ($movim->total_tarjeta > 0) {
            $tipo = $tipo . "con tarjeta.";
        } else if ($movim->total_valores > 0) {
            $tipo = $tipo . "con valores.";
        } else if ($movim->total_transferencia > 0) {
            $tipo = $tipo . "con transferencia.";
        } else if ($movim->total_bonos > 0) {
            $tipo = $tipo . "con bonos.";
        } else if ($movim->cuenta_corriente > 0) {
            $tipo = $tipo . "en cuenta corriente.";
        } else if ($movim->tipo_comprobante_id == 2) {
            $tipo = "Nota de crédito.";
        } else if ($movim->tipo_comprobante_id == 3) {
            $tipo = "Nota de dédito.";
        } else if ($movim->tipo_comprobante_id == 4) {
                $tipo = "Egreso.";
        } else if ($movim->tipo_comprobante_id == 5) {
                $tipo = "Ingreso.";
        } else $tipo = "S/D";

        return $tipo;
    }

    private function _getTurnoSucursal($sucursal_id)
    {
        $whereClaus = [
            ['cierre_fecha_hora', null],
            ['sucursal_id', $sucursal_id]
        ];

        $turno = Turno::select('id', 'turno_sucursal')
            ->where($whereClaus)
            ->first();
        
        return $turno->turno_sucursal;
    }

    private function _getSucursalId()
    {
        // Si existe session key 'sucursal_id', la usa, si no, usa la del usuario
        if (session()->has('sucursal_id')) {
            $sucursal_id = session('sucursal_id');
        } else {
            $sucursal_id = Auth::user()->sucursal_id;
        }

        return $sucursal_id;
    }

    private function _getMovimientos($sucursal_id, $usuario_id, $user_perfil_id, $caja_nro)
    {
        if ($user_perfil_id <= 2) {     // Admin
            $caja_nro = 0;
        }

        $condiciones = $this->_getCondiciones($sucursal_id, $usuario_id, $caja_nro);
        $movims = $this->_getMovsCaja($condiciones);

        return $movims;
    }

    protected function _getCajaNroUsuario($sucursal_id, $usuario_id)
    {
        return Turno::where('sucursal_id', $sucursal_id)
            ->where('usuario_id', $usuario_id)
            ->where('cierre_fecha_hora', null)
            ->first();
    }

    private function _getCondiciones($sucursal_id, $usuario_id, $caja_nro)
    {
        $condiciones = [
            ['caja_movimientos.sucursal_id', '=', $sucursal_id],
            ['turno_cierre_id', '=', 0], 
            ['tipo_comprobante_id', '>', 3],        // tipo_comprobante_id == 4 or == 5
        ];
        if ($usuario_id != 0) {
            $condiciones[] = ['caja_movimientos.usuario_id', '=', $usuario_id];
        }
        if ($caja_nro != 0) {
            $condiciones[] = ['caja_movimientos.caja_nro', '=', $caja_nro];
        }

        return $condiciones;
    }

    private function _getMovsCaja($condiciones)
    {
        $movims = CajaMovimiento::select('caja_movimientos.id', 'caja_movimientos.sucursal_id',
                    'caja_movimientos.caja_nro', 'caja_movimientos.tipo_comprobante_id',
                    'caja_movimientos.fecha_hora', 'caja_movimientos.total_efectivo',  
                    'caja_movimientos.total_debito', 'caja_movimientos.total_tarjeta',
                    'caja_movimientos.total_valores', 'caja_movimientos.total_transfer',
                    'caja_movimientos.total_bonos', 'caja_movimientos.total_retenciones',
                    'caja_movimientos.total_otros', 'caja_movimientos.cuenta_corriente', 
                    'caja_movimientos.estado', 'caja_movimientos.usuario_id',
                    'caja_movimientos.concepto', 'caja_movimientos.importe', 
                    'caja_movimientos.turno_cierre_id')
            ->where($condiciones)
            ->orderBy('caja_movimientos.fecha_hora', 'desc')
            ->paginate($this->cant_paginas);

        return $movims;        
    }
}
