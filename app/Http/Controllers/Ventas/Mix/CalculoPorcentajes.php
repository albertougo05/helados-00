<?php

namespace App\Http\Controllers\Ventas\Mix;

class CalculoPorcentajes {

    /**
     * Devuelve array con totales generales
     * 
     * @param array $listado
     * @param array $totales
     */
    public function inicio($listado, $totales) : array {
        $listaConPorcentajes = [];
        $totalPesos = $totales['total'];
        $totalKilos = $totales['kilos'];

        foreach ($listado as $key => $value) {
            $listaConPorcentajes[] = $value;
            $listaConPorcentajes[$key]['porc_total'] = $this->_getPorcentaje($value['total'], $totalPesos);
            $listaConPorcentajes[$key]['porc_kilos'] = $this->_getPorcentaje($value['kilos'], $totalKilos);
        }

        return $listaConPorcentajes;
    }

    private function _getPorcentaje($valor, $total) : float {
        $porcent = ($valor * 100) / $total;

        return $this->_redondear_2_dec($porcent);
    }

    private function _redondear_2_dec($valor) {
        $float_redondeado = round($valor * 100) / 100;
        return (float) $float_redondeado;
     }

}
