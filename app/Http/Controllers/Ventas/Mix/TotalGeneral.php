<?php

namespace App\Http\Controllers\Ventas\Mix;

class TotalGeneral {

    /**
     * Devuelve array con totales generales
     * 
     * @param array $_listado
     */
    public function getTotalesGenerales($_listado) : array {

        $arrTotales = $this->_totalizar($_listado);

        return [
            'cant_vtas' => $arrTotales['cant_vtas'],
            'precio_prom' => $arrTotales['precio_prom'],
            'total' => $arrTotales['total'],
            'porc_total' => $arrTotales['porc_total'],
            'kilos' => $arrTotales['kilos'],
            'porc_kilos' => $arrTotales['porc_kilos']
        ];
    }

    private function _totalizar($listado) : array {
        $cant_vtas =  $total = 0;
        $kilos = $sum_precio_prom = $cantLineas = 0;

        foreach ($listado as $value) {
            if (!$value['total_grupo']) {
                $cant_vtas += $value['cant_vtas'];
                $sum_precio_prom += $value['precio_prom'];
                $total += $value['total'];
                $kilos += $value['kilos'];

                $cantLineas++;
            }
        }

        return [
            'cant_vtas' => $cant_vtas,
            'precio_prom' => $sum_precio_prom / $cantLineas,
            'total' => $total,
            'porc_total' => 100,
            'kilos' => $kilos,
            'porc_kilos' => 100
        ];
    }

}
