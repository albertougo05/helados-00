<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Turno;
use App\Models\Sucursal;
use App\Models\CajaMovimiento;
use App\Models\BilleteArqueo;
use App\Models\User;
use App\Http\Controllers\Utils\Utils;
use App\Http\Controllers\Turnos\CierraTurnoCajaController;
//use App\Http\Controllers\Mails\CierreTurnoController;


class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     * Name: turno.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Si existe session key 'sucursal_id', la usa, si no, usa la del usuario
        if (session()->has('sucursal_id')) {
            $sucursal_id = session('sucursal_id');
        } else {
            $sucursal_id = Auth::user()->sucursal_id;
        }
        $usuario = Auth::user();
        $turnos = $this->_turnosConArqueoParcial($usuario, $sucursal_id);
        $sucursal = Sucursal::select('cant_puntos_venta')
            ->find($sucursal_id);

        if ($sucursal->cant_puntos_venta === count($turnos)) {
            $no_puede_abrir_turno = true;
        } else {
            $no_puede_abrir_turno = false;
        }

        return view('turnos.index', compact('turnos', 'no_puede_abrir_turno'));
    }

    /**
     * Show the form for creating a new resource.
     * 
     * Crea un nuevo turno de caja
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $utils = new Utils;
        $usuario = Auth::user();
        if (Auth::user()->perfil_id <= 2) {
            $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        } else {
            $sucursal_id = Auth::user()->sucursal_id;
        }
        $ptos_vta = $utils->getPtosVta($sucursal_id);

        if (count($ptos_vta) == 0) {  // NO hay puntos de venta disponibles
            //if (Auth::user()->perfil_id < 2) {
            //    return redirect()->route('selecptovta');
            //} else {
                return redirect()->route('noabreturno');
            //}
        }

        $sucursal = Sucursal::select('nombre')
            ->find($sucursal_id);
        $turno = Turno::latest('turno_sucursal')
            ->where('sucursal_id', $sucursal_id)
            //->where('caja_nro', $ptos_vta[0]['id'])   // LOS TURNOS SON POR SUCURSAL (NO POR CAJA)
            ->first();

        if ($turno) {
            $newid = (integer) $turno->turno_sucursal + 1;  // PONER turno_suc
            $saldo_inicio = $turno->arqueo ?? 0;
        } else {
            $newid = 1;
            $saldo_inicio = 0;
        }

        $fecha_actual = date('Y-m-d');
        $data = compact(
            'newid',
            'usuario',
            'sucursal_id',
            'sucursal',
            'fecha_actual',
            'ptos_vta',
            'saldo_inicio',
        );

        return view('turnos.create', $data);
    }

    /**
     * Crea un nuevo turno de caja.
     * Name: turno.store (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $utils = new Utils;
        $data = $request->all();
        // Convertir string en float
        $data['saldo_inicio'] = $utils->convStrToFloat($data['saldo_inicio']);
        $data['caja'] = $utils->convStrToFloat($data['caja']);

        $movCaja = CajaMovimiento::create($this->_dataMovCaja($data));

        if (!$movCaja) {
            return redirect(route('turno.index'))->with('status', 'No se pudo crear nuevo Turno !!');
        }

        $turno = Turno::create($data);

        if ($turno) {
            return redirect(route('turno.index'))->with('status', 'Nuevo Turno creado !');
        } else {
            return redirect(route('turno.index'))->with('status', 'No se pudo crear nuevo Turno !!');
        }
    }

    /**
     * Display the specified resource.
     * Name: turno.show
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $utils = new Utils;
        $usuario = Auth::user();
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $turno = Turno::find($id);
        $sucursal = Sucursal::find($sucursal_id);

        if (!$turno->cierre_fecha_hora) {   // Turno abierto, sin fecha ni hora de cierre
            $totales = $this->_getTotales(
                $sucursal_id, 
                $turno->caja_nro,
                (float) $turno->saldo_inicio
            );
            $cierre_fecha = "";
            $id = 0;
        } else {
            $totales = [];
            $cierre_fecha = date("d/m/Y", strtotime($turno->cierre_fecha_hora));
        }

        $cierre_hora =  date("H:i", strtotime($turno->cierre_fecha_hora));
        $comprobantes = $this->_getComprobantes($sucursal_id, $turno->caja_nro, $id);
        $usuario_turno = User::find($turno->usuario_id)->name;
        $apertura_fecha_hora = $this->_convertFechaHora($turno->apertura_fecha_hora);
        $pathToEmail = route('email.enviar', $id);
        $impresora = $utils->getNombreImpresora($sucursal_id);
        $env = env('APP_ENV');

        $data = compact(
            'turno',
            'usuario',
            'usuario_turno',
            'sucursal_id',
            'sucursal',
            'comprobantes',
            'apertura_fecha_hora',
            'cierre_fecha',
            'cierre_hora',
            'totales',
            'pathToEmail',
            'impresora',
            'env',
        );

        return view('turnos.show', $data);
    }

    /**
     * Cierra de turno de caja
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $usuario = Auth::user();
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $turno = Turno::find($id);
        $turno->saldo_inicio = (float) $turno->saldo_inicio;

        if ($usuario->perfil_id > 2) {      // Si es admin puede cerrar el turno ?
            // Solo usuario que abre turno lo puede cerrar !!
            if (!$this->_checkTurnoUsuario($usuario, $sucursal_id)) {
                return back()->with('status', 'No puede cerrar turno');
            }
        }

        $fecha_hora_apertura = $this->_convertFechaHora($turno->apertura_fecha_hora);
        $fecha_actual = date('Y-m-d');
        $totales = $this->_getTotales(
            $sucursal_id, 
            $turno->caja_nro, 
            $turno->saldo_inicio
        );
        $usuario_turno = User::find($turno->usuario_id)->name;
        $billetes = BilleteArqueo::select('id', 'importe')
            ->orderBy('importe')
            ->where('estado', 1)
            ->get();

        $data = compact(
            'turno',
            'fecha_hora_apertura',
            'usuario',
            'usuario_turno',
            'sucursal_id',
            'fecha_actual',
            'totales',
            'billetes',
        );

        return view('turnos.edit', $data);
    }

    /**
     * Realiza cierre de turno de caja.
     * Name: turno.update
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Turno $turno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $turno)
    {
        $sucursal_id = $request->input('sucursal_id');
        $caja_nro = $request->input('caja_nro');
        $cierre_id = $turno;
        $utils = new Utils;

        $cantUpd = Turno::where('id', $turno)
            ->lockForUpdate()
            ->update([
                'cierre_fecha_hora' => $request->cierre_fecha_hora,
                'venta_total'       => $request->venta_total, // $utils->convStrToFloat($request->venta_total),
                'efectivo'          => $utils->convStrToFloat($request->efectivo),
                'tarjeta_credito'   => $utils->convStrToFloat($request->tarjeta_credito),
                'tarjeta_debito'    => $utils->convStrToFloat($request->tarjeta_debito),
                'cuenta_corriente'  => $utils->convStrToFloat($request->cuenta_corriente),
                'otros'             => $utils->convStrToFloat($request->otros),
                'egresos'           => $request->egresos,  // $utils->convStrToFloat($request->egresos),
                'ingresos'          => $request->ingresos, // $utils->convStrToFloat($request->ingresos),
                'caja'              => $request->caja,  // $utils->convStrToFloat($request->caja),
                'arqueo'            => $utils->convStrToFloat($request->arqueo),
                'diferencia'        => $request->diferencia, // $utils->convStrToFloat($request->diferencia),
        ]);

        // Actualizar id cierre de turno en movimientos de caja
        $jsonResult = (new CierraTurnoCajaController)
            ->cierra($sucursal_id, $caja_nro, $cierre_id);

        if ($cantUpd > 0) {

            //(new CierreTurnoController)->enviar($cierre_id);    // Envia e-mail...

            $pathToEmail = route('email.enviar', $cierre_id);
            $impresora = $utils->getImpresoras($sucursal_id, $caja_nro);
            $env = env('APP_ENV');
            $data = compact(
                'sucursal_id',
                'caja_nro',
                'cierre_id',
                'impresora',
                'env',
                'pathToEmail'
            );

            // Muestra pantalla con spin y manda a imprimir ticket de cierre...
            return view('turnos.imprime.cierre', $data);    // Abre página que muestra spin e imprime ticket cierre
        } else {
            return redirect(route('turno.index'))->with('status', 'ERROR ! No se pudo cerrar caja !');
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


    private function _convertFechaHora(String $fechaHora = null)
    {
        $fecha = substr($fechaHora, 0, 10);
        $temp = explode("-", $fecha);
        $fecha = $temp[2] . "/" . $temp[1] . "/" . $temp[0];
        $hora = substr($fechaHora, -8, 5);

        return $fecha . " - " . $hora;
    }

    private function _getTotales($suc_id, $caja_nro, $saldo_inic)
    {
        $totales = [];
        $totales['venta'] = $this->_getTotalCampo('importe', $suc_id, $caja_nro);
        $totales['efectivo'] = $this->_getTotalCampo('total_efectivo', $suc_id, $caja_nro);
        $totales['tarj_cred'] = $this->_getTotalCampo('total_tarjeta', $suc_id, $caja_nro);
        $totales['tarj_debi'] = $this->_getTotalCampo('total_debito', $suc_id, $caja_nro);
        $totales['cta_cte'] = $this->_getTotalCampo('cuenta_corriente', $suc_id, $caja_nro);
        $totales['transfer'] = $this->_getTotalCampo('total_transfer', $suc_id, $caja_nro);;
        $totales['egresos'] = $this->_getEgresoIngreso('egr', $suc_id, $caja_nro);
        $totales['ingresos'] = $this->_getEgresoIngreso('ing', $suc_id, $caja_nro);

        $totales['caja_teorica'] = $this->_getCajaTeorica(
            $totales['efectivo'], 
            $totales['ingresos'], 
            $totales['egresos']
        );

        $totales['ingresos'] = $totales['ingresos'] - $saldo_inic;

        return $totales;
    }

    private function _getTotalCampo($campo, $suc_id, $caja_nro)
    {
        $total = CajaMovimiento::where('sucursal_id', $suc_id)
            ->where('caja_nro', $caja_nro)
            ->where('turno_cierre_id', 0)
            ->where('tipo_comprobante_id', 1)  // Facturas/Ticket
            ->where('estado', 1)
            ->sum($campo);

        return floatval($total);
    }

    private function _getEgresoIngreso($ingEgr, $suc_id, $caja_nro)
    {
        switch ($ingEgr) {
            case 'ing':
                $tipo = 5;
                break;

            case 'egr':
                $tipo = 4;
                break;
        }
        $importe = CajaMovimiento::where('sucursal_id', $suc_id)
            ->where('caja_nro', $caja_nro)
            ->where('turno_cierre_id', 0)
            ->where('tipo_comprobante_id', $tipo)
            ->where('estado', 1)
            ->sum('importe');

        return floatval($importe);
    }

    private function _getCajaTeorica($efectivo, $ingresos, $egresos)
    {
        $teorica = $efectivo + $ingresos;

        return $teorica - $egresos;
    }

    private function _getComprobantes($suc_id, $caja_nro, $cierre_turno = 0)
    {
        $arrComp = [
            'cantidad' => 0,
            'f_desde' => '',    // Fecha desde
            'h_desde' => '',    // Hora desde
            'f_hasta' => '',
            'h_hasta' => '',
        ];
        $comprobs = CajaMovimiento::where('sucursal_id', $suc_id)
            ->where('caja_nro', $caja_nro)
            ->where('turno_cierre_id', $cierre_turno)
            ->get();

        if ($comprobs->count() > 0) {
            $arrComp['cantidad'] = $comprobs->count();
            $desde = $comprobs->first()->fecha_hora;
            $arrComp['f_desde'] = date("d/m/Y", strtotime($desde));
            $arrComp['h_desde'] = date("H:i", strtotime($desde));
            $hasta = $comprobs->last()->fecha_hora;
            $arrComp['f_hasta'] = date("d/m/Y", strtotime($hasta));
            $arrComp['h_hasta'] = date("H:i", strtotime($hasta));
        }

        return $arrComp;
    }

    /** 
     * Verifica si el usuario tiene abierto el turno y puede cerrarlo
     * 
     * @param $usuario
     * @param integer $suc_id
     * @return boolean
     */
    private function _checkTurnoUsuario($usuario, $suc_id)
    {
        $result = false;
        $turno = Turno::where('cierre_fecha_hora', null)
            ->where('usuario_id', $usuario->id)
            ->where('sucursal_id', $suc_id)
            ->first();

        if ($turno) {
            $result = true;
        }

        return $result;
    }

    /**
     * Devuelve array con turnos y cálculo de arqueo hasta ese momento
     * 
     * @param collection $usuario
     * @param integer $sucursal_id
     * @return array
     */
    private function _turnosConArqueoParcial($usuario, $sucursal_id)
    {
        $turnosConArqueo = [];
        $wheres = [
            ['turnos.sucursal_id', '=', $sucursal_id],
            ['turnos.cierre_fecha_hora', '=', null],
            ['turnos.estado', '=', 1]
        ];

        if ($usuario->perfil_id >= 3) {     // Si empleados
            $wheres[] = ['turnos.usuario_id', '=', $usuario->id];
        }

        $turnos = Turno::select('turnos.id', 'turnos.turno_sucursal', 
                                'turnos.sucursal_id', 'turnos.usuario_id',
                                'turnos.apertura_fecha_hora', 'turnos.cierre_fecha_hora', 
                                'turnos.saldo_inicio', 'turnos.estado', 'turnos.caja_nro',
                                'turnos.observaciones', 'sucursales.nombre', 'users.name')
            ->leftjoin('sucursales', 'turnos.sucursal_id', '=', 'sucursales.id')
            ->leftjoin('users', 'turnos.usuario_id', '=', 'users.id')
            ->where($wheres)
            ->get();

        if($turnos) {
            $turnosConArqueo = $this->_getArrayTurnos($turnos);
        }

        return $turnosConArqueo;
    }

    /**
     * Devuelve array con el cálculo de arqueo
     */
    private function _getArrayTurnos($turnos)
    {
        $arrTurnos = $temp = [];

        foreach($turnos as $turno) {
            $temp['id'] = $turno->id;
            $temp['turno_sucursal'] = $turno->turno_sucursal;
            $temp['sucursal_id'] = $turno->sucursal_id;
            $temp['usuario_id'] = $turno->usuario_id;
            $temp['apertura_fecha_hora'] = $turno->apertura_fecha_hora;
            $temp['cierre_fecha_hora'] = $turno->cierre_fecha_hora;
            $temp['saldo_inicio'] = $turno->saldo_inicio;
            $temp['estado'] = $turno->estado;
            $temp['caja_nro'] = $turno->caja_nro;
            $temp['observaciones'] = $turno->observaciones;
            $temp['nombre'] = $turno->nombre;
            $temp['name'] = $turno->name;
            $temp['arqueo_parcial'] = $this->_getArqueoParcial(
                $turno->sucursal_id, 
                $turno->caja_nro, 
                $turno->saldo_inicio
            );

            $arrTurnos[] = $temp;
        }

        return $arrTurnos;
    }

    /**
     * Hace un cáculo de arqueo parcial
     * 
     * @param int $suc_id
     * @param int $caja_nro
     * @param float $saldo_inic
     * @return array
     */
    private function _getArqueoParcial($suc_id, $caja_nro, $saldo_inic)
    {
        $efectivo = $this->_getTotalCampo('total_efectivo', $suc_id, $caja_nro);
        $egresos = $this->_getEgresoIngreso('egr', $suc_id, $caja_nro);
        $ingresos = $this->_getEgresoIngreso('ing', $suc_id, $caja_nro);

        $arqueoParcial = $this->_getCajaTeorica(
            $efectivo, 
            $saldo_inic, 
            $ingresos, 
            $egresos,
        );

        return $arqueoParcial;
    }

    protected function _dataMovCaja($data)
    {
        return [
            'sucursal_id' => $data['sucursal_id'],
            'usuario_id' => $data['usuario_id'],
            'caja_nro' => $data['caja_nro'],
            'tipo_comprobante_id' => 5,
            'fecha_hora' => $data['apertura_fecha_hora'],
            'fecha_registro' => date('Y-m-d'),
            'tipo_movim_id' => 1,
            'forma_pago_id' => 0,
            'nro_comprobante' => 0,     //$id_movs_caja,    // ES número comprobante de VENTA !!
            'concepto' => 'Apertura de caja',
            'importe' => $data['saldo_inicio'],
            'observaciones' => 'Inicio de turno',
        ];
    }
 
}
