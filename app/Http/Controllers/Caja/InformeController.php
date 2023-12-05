<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CajaMovimiento;
use App\Models\CajaTiposMovim;
use App\Models\CajaFormasPago;
use App\Models\User;
use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Caja\PhpXlsxGenerator;


class InformeController extends Controller
{
    protected $cant_x_pagina = 15;

    /**
     * Informe movimientos de caja (solo para Admin)
     * Name: caja_movimientos.informe.index
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
        $usuarios = $this->_getUsuarios();
        $tipos_movim = $this->_getTiposMovim();
        $formas_pago = $this->_getFormasPago();

        $data =  compact(
            'sucursal_id', 
            'sucursal',
            'usuarios',
            'tipos_movim',
            'formas_pago',
        );

        return view('caja.informe.index', $data);
    }

    /**
     * Muestra, para imprimir, informe movimientos de caja
     * Name: caja_movimientos.informe.show
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $sucursal_id = Auth::user()->sucursal_id;
        $sucursal = Sucursal::select('nombre')
            ->find($sucursal_id)->nombre;

        $betweenClaus = [$request['desde'], $request['hasta']];
        $whereAndTitle = $this->_getWhereClausAndTitle($request);
        $titulo = $whereAndTitle['title'];
        $informe = $this->_getInforme($betweenClaus, $whereAndTitle['whereClaus']);

        $data =  compact(
            'sucursal',
            'titulo',
            'informe',
        );

        return view('caja.informe.imprimir', $data);
    }

    /**
     * Exporta informe movimientos de caja a descargar planilla excel
     * Name: caja_movimientos.informe.excel
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function excel(Request $request)
    {
        $betweenClaus = [$request['desde'], $request['hasta']];
        $whereAndTitle = $this->_getWhereClausAndTitle($request);
        // Excel file name for download 
        $fileName = "informe_caja_" . date('d-m-Y') . ".xlsx"; 
        // Define column names 
        $excelData[] = array('', $whereAndTitle['title']);
        $excelData[] = [];      // Linea en blanco
        $excelData[] = array('FECHA HORA', 'SUCURSAL', 'USUARIO', 'TURNO', 'DESCRIPCION', 'IMPORTE'); 
        // Fetch records from database and store in an array 
        $informe = $this->_getInforme($betweenClaus, $whereAndTitle['whereClaus']);
        // Export data to excel and download as xlsx file
        foreach ($informe as $value) {
            $lineData = array(
                $value['fechahora'], 
                $value['nombre'], 
                $value['nombre_usuario'],
                $value['turno_cierre_id'],
                $value['concepto'],
                //number_format($value['importe'], 2, ',', '.')
                $value['importe']
            );  
            $excelData[] = $lineData; 
        }
        $xlsx = PhpXlsxGenerator::fromArray($excelData);
        $xlsx->downloadAs($fileName); 

        exit; 
    }

    private function _getWhereClausAndTitle($req) : array {
        $whereClaus = [];

        if ($req['user'] !== '0') {     // Filtro usuario
            $whereClaus[] = ['caja_movimientos.usuario_id', $req['user']];
        } 
        if ($req['timo'] !== '0') {    // Filtro tipo movimiento
            $whereClaus[] = ['caja_movimientos.tipo_movim_id', $req['timo']];
            $titulo = $this->_getTitulo('timo', $req['timo']);
        } 
        if ($req['fopa'] !== '0') {   // Filtro forma de pago
            $whereClaus[] = ['caja_movimientos.forma_pago_id', $req['fopa']];
            $titulo = $this->_getTitulo('fopa', $req['fopa']);
        }

        $whereClaus[] = ['caja_movimientos.turno_cierre_id', '<>', 0];

        return [
            'whereClaus' => $whereClaus,
            'title' => $titulo
        ];
    }

    private function _getUsuarios() : array{
        return User::select('id', 'name')
            ->where('perfil_id', '>', 2)
            ->where('estado', 1)
            ->get()
            ->toArray();
    }

    private function _getTiposMovim() : array {
        return CajaTiposMovim::select('id', 'tipo_movim_id', 'descripcion')
            ->orderBy('descripcion')
            ->get()
            ->toArray();
    }

    private function _getFormasPago() : array {
        return CajaFormasPago::select('id', 'descripcion')
        ->orderBy('descripcion')
        ->get()
        ->toArray();;
    }

    private function _getInforme($betwClaus, $whereClaus) : array {

        return CajaMovimiento::select('caja_movimientos.sucursal_id', 
                'sucursales.nombre', 'users.nombre_usuario', 
                'caja_movimientos.turno_cierre_id', 
                DB::raw('DATE_FORMAT(caja_movimientos.fecha_hora, "%d/%m/%Y %H:%i") as fechahora'),
                'caja_movimientos.concepto', 'caja_movimientos.importe')
            ->whereBetween('fecha_hora', $betwClaus)
            ->where($whereClaus)
            ->join('sucursales', 
                'caja_movimientos.sucursal_id', 
                '=', 
                'sucursales.id')
            ->join('users', 
                'caja_movimientos.usuario_id', 
                '=', 
                'users.id')
            ->orderBy('caja_movimientos.fecha_hora')
            ->get()
            ->toArray();
    }

    private function _getTitulo($tipo, $id) : string {
        $titulo = "Filtro por";

        switch ($tipo) {
            case 'timo':
                $titulo = $titulo . " movimiento: " . $this->_getTimo($id);
                break;
            case 'fopa':
                $titulo = $titulo . " forma de pago: " . $this->_getFopa($id);
                break;
        }
        return $titulo;
    }

    private function _getTimo($id) : string {

        return CajaTiposMovim::find($id)->descripcion;
    }

    private function _getFopa($id) : string {

        return CajaFormasPago::find($id)->descripcion;
    }
}
