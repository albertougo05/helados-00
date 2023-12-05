<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sucursal;
use App\Models\StockRealPlanilla;
use App\Models\StockRealDetalleArticulo;
use App\Models\StockRealDetalleHelado;


class RealPlanillaEditController extends Controller
{
    protected $grupos_helados = [2];
    protected $cant_por_pagina = 15;
    protected $cant_meses_ver_planillas = 6;

    /**
     * Edita planilla de Stock Real
     * Name: stock.real.planilla.edit
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $realPlanilla = new RealPlanillaController();
        $planilla = StockRealPlanilla::find($id);
        //$planilla_id = $id;
        $titulo = "Planilla Stock Real ($id)";
        $usuario_id = Auth::user()->id; 
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $sucursal = Sucursal::find($sucursal_id)->nombre;
        $sucursales = Sucursal::where('id', $sucursal_id)
            ->get();
        $peso_envase = $realPlanilla->getPesoEnvase($sucursal_id);
        $articulos = $realPlanilla->getArticulos($sucursal_id);
        $articulosPlanilla = $this->_getArtPlanilla($sucursal_id, $id);
        $helados = $realPlanilla->getHelados($sucursal_id);
        $heladosPlanilla = $this->_getHeladosPlanilla($sucursal_id, $id);

        $data = compact(
            'titulo',
            'planilla',
            'sucursal_id',
            'usuario_id',
            'sucursal',
            'sucursales',
            'articulos',
            'articulosPlanilla',
            'helados',
            'heladosPlanilla',
            'peso_envase',
        );

        return view('stock.real.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $respuesta = ['estado' => 'error'];

        $cant = StockRealPlanilla::lockForUpdate()
            ->where('id', $id)
            ->update($request->all());

        if ($cant > 0) {
            $respuesta = ['estado' => 'ok'];
        }

        return json_encode($respuesta);
    }

    public function updateArticulos(Request $request, $id)
    {
        $respuesta = ['estado' => 'error'];

        if (count($request->all()) === 0) {
            $respuesta['estado'] = 'ok';
        } else {
            foreach ($request->all() as $articulo) {
                $reg = StockRealDetalleArticulo::updateOrCreate(
                    ['id' => $articulo['id']],
                    $articulo
                );
            }

            if ($reg) {
                $respuesta['estado'] = 'ok';
            }
        }

        return json_encode($respuesta);
    }

    public function updateHelados(Request $request, $id)
    {
        $respuesta = ['estado' => 'error'];

        if (count($request->all()) === 0) {
            $respuesta['estado'] = 'ok';
        } else {
            foreach ($request->all() as $helado) {
                $reg = StockRealDetalleHelado::updateOrCreate(
                    ['id' => $helado['id']],
                    $helado
                );
            }

            if ($reg) {
                $respuesta['estado'] = 'ok';
            }
        }

        return json_encode($respuesta);
    }


    private function _getArtPlanilla(int $suc_id = null, int $id)
    {
        return StockRealDetalleArticulo::where('sucursal_id', $suc_id)
                ->where('planilla_id', $id)
                ->where('total', '!=', 0)
                ->orderBy('descripcion')
                ->get();
    }

    private function _getHeladosPlanilla(int $suc_id = null, int $id)
    {
        return StockRealDetalleHelado::where('sucursal_id', $suc_id)
                ->where('planilla_id', $id)
                ->where('total', '!=', 0)
                ->orderBy('descripcion')
                ->get();
    }

}
