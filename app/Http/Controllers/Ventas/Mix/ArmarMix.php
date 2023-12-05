<?php

namespace App\Http\Controllers\Ventas\Mix;

use Illuminate\Support\Facades\DB;
use App\Models\DetalleComprobReceta;
use App\Models\DetalleComprobVenta;


class ArmarMix {

    /**
     * Devuelve array con totales generales
     * 
     * @param array $prods
     * @param string $desde
     * @param string $hasta
     */
    public function getMixVtas($prods, $desde, $hasta) : array {
        $listado = [];      // Aqui va el array de todos los productos
        $fechaHora = $this->_setFechaHora($desde, $hasta);
        $grupoId = $contLineasGrupo = 0;

        foreach ($prods as $value) {

            if ($grupoId === 0) {
                $grupoId = $value['grupo_id'];
            } 
            if ($grupoId !== $value['grupo_id'] && $contLineasGrupo > 0) {  // Agregar linea total de grupo
                $listado[] = $this->_getLineaTotalGrupo($listado, $grupoId);
                $grupoId = $value['grupo_id'];
                $contLineasGrupo = 0;
            } else {
                $grupoId = $value['grupo_id'];
            }

			$totalVtaPos = $precioProm = $cantKilos = 0;
			$dataVtaPos = $this->_getVentaPosArtic(			// Suma ventas...
                $value['codigo'],
				$fechaHora
			);

            if ($dataVtaPos['totalUnid'] > 0) {
                $totalVtaPos += $dataVtaPos['totalUnid'];
                $precioProm = $dataVtaPos['precioProm'];
                $cantKilos = $dataVtaPos['pesoHelado'];
            }

            if ($value['con_receta'] === 1) {    // Suma Ventas articulos de receta
                $dataVtaRecetas = $this->_getVtaPosArticReceta(
                    $value['codigo'],
                    $fechaHora
                );

                if ($dataVtaRecetas['totalUnid'] > 0) {
                    $totalVtaPos += $dataVtaRecetas['totalUnid'];
                    $precioProm += $dataVtaRecetas['promCosto'];
                }
            }

            if ($value['grupo_id'] === 4 || $value['grupo_id'] === 5) {     // Calculo kilos de helado
                if ($totalVtaPos > 0) {
                    $cantKilos = $this->_getKilosHelado($value['codigo'], $fechaHora);
                }
            }

            if ($totalVtaPos > 0) {		// Cargar producto al listado
                $listado[] = $this->_getDatosProducto($value, 
                                $totalVtaPos, 
                                $precioProm, 
                                $cantKilos);
                $contLineasGrupo++;
            }
        }

        return $listado;
    }


    private function _getModeloProducto() : array 
    {
        return [
            'total_grupo'    => false,
			'codigo'         => '',
			'descripcion'    => '',
            'grupo_id'       => 0,
            'grupo'          => '',
			'articulo_indiv_id' => 0,
			'precio_prom'    => 0,
			'cant_vtas'      => 0,
            'total'          => 0,
			'porc_total'     => 0,
			'kilos'          => 0,
			'porc_kilos'     => 0,
        ];
    }

	private function _setFechaHora($desde, $hasta)
	{
		return [
			'desde' => $desde,
			'hasta' => $hasta,
		];
	}

    private function _redondear_dos_decimal($valor) {
        $float_redondeado = round($valor * 100) / 100;
        return $float_redondeado;
     }

    private function _getVentaPosArtic($codigo, $fechaHora) : array 
    {
        $total_cant = $precio_prom = $totalPrecProm = $pesoHelado = 0;
		// Total ventas del producto en el periodo...
		$totalVenta = $this->_getTotalVta($fechaHora, $codigo);

		if ($totalVenta) {
			$total_cant += (integer) $totalVenta[0]['total_cant'];
            $totalPrecProm += (float) $totalVenta[0]['precio_unit'];
            $pesoHelado += (float) $totalVenta[0]['peso_helado'];
		}

        if ($total_cant > 0) {
            $precio_prom = $totalPrecProm / $total_cant;
            $precio_prom = $this->_redondear_dos_decimal($precio_prom);
        }

		return [ 'totalUnid' => $total_cant, 
                 'precioProm' => $precio_prom, 
                 'pesoHelado' => $pesoHelado ];
    }

    private function _getLineaTotalGrupo($listado, $grupoId)
    {
        $lastIndex = count($listado) - 1;
        $sumas = $this->_sumasGrupo($listado, $grupoId);

        return [
            'total_grupo' => true,
            'grupo_id'    => $listado[$lastIndex]['grupo_id'], 
            'grupo'       => $listado[$lastIndex]['grupo'],
			'cant_vtas'   => $sumas['cant_vtas'],
			'precio_prom' => $sumas['precio_prom'],
            'total' => $sumas['total'],
			'porc_total'  => 0,
			'kilos'       => $sumas['kilos'],
			'porc_kilos'  => 0,
        ];
    }

    private function _getVtaPosArticReceta($codigo, $fechaHora)
	{
        $sucursal_id = session('sucursal_id');
		$total_cantidad = $total_costo = $prom_costo = 0;
		// Total ventas del producto de receta en el periodo...
		$detalleVtas = DetalleComprobReceta::select(
			DB::raw('sum(cantidad) as total_cant, 
                     sum(costo) as total_costo, 
                     producto_codigo'))
				->whereBetween('fecha_hora', [$fechaHora['desde'], $fechaHora['hasta']])
				->where('sucursal_id', $sucursal_id)
				->where('producto_codigo', $codigo)
				->where('estado', 1)
				->groupBy('producto_codigo')
				->get()
				->toArray();

		if ($detalleVtas) {
			$total_cantidad += (integer) $detalleVtas[0]['total_cant'];
            $total_costo = (float) $detalleVtas[0]['total_costo'];
            $prom_costo = $this->_redondear_dos_decimal($total_costo);
		}

        return [ 'totalUnid' => $total_cantidad, 'promCosto' => $prom_costo ];
	}

    private function _getDatosProducto($prod, $totalVtaPos, $precioProm, $kilosHela) : array 
    {
        $registroProd = $this->_getModeloProducto();
        $registroProd['codigo'] = $prod['codigo'];
        $registroProd['descripcion'] = $prod['descripcion'];
        $registroProd['grupo_id'] = $prod['grupo_id'];
        $registroProd['grupo'] = $prod['grupo'];
        $registroProd['articulo_indiv_id'] = $prod['articulo_indiv_id'];
        $registroProd['cant_vtas'] = $totalVtaPos;
        $registroProd['precio_prom'] = $precioProm;
        $registroProd['total'] = $precioProm * $totalVtaPos;
        $registroProd['kilos'] = $kilosHela;

        return $registroProd;
    }

    private function _getKilosHelado($codigo, $fechaHora) : float {
        $sucursal_id = session('sucursal_id');
		$cant_kilos = 0;
		// Total ventas del producto de receta en el periodo...
		$detalleVtas = DetalleComprobReceta::select(
			DB::raw('sum(cantidad) as cant_kilos, producto_codigo'))
				->whereBetween('fecha_hora', [$fechaHora['desde'], $fechaHora['hasta']])
				->where('estado', 1)
				->where('sucursal_id', $sucursal_id)
				->where('producto_codigo', '01-1')
                ->where('producto_con_receta_codigo', $codigo)
				->groupBy('producto_codigo')
				->get()
				->toArray();

		if ($detalleVtas) {
			//$cant_kilos = (float) $detalleVtas[0]['cant_kilos'] * 1000;
            $cant_kilos = (float) $detalleVtas[0]['cant_kilos'];
		}
        //echo "Codigo: ".$codigo." - Cant. kilos: $cant_kilos / Func getK <br>";
		return $cant_kilos;
    }

    private function _sumasGrupo($list, $grId)
    {
        $sumaVtas = $sumaPrecio = $sumaTotal = $sumaKilos = 0;

        foreach ($list as $value) {
            if ($value['grupo_id'] === $grId) {
                    $sumaVtas   += $value['cant_vtas'];
                    $sumaPrecio += $value['precio_prom'];
                    $sumaTotal  += $value['total'];
                    $sumaKilos  += $value['kilos'];
            }
        }

        return ['cant_vtas'   => $sumaVtas,
                'precio_prom' => $sumaPrecio,
                'total'       => $sumaTotal,
                'kilos'       => $sumaKilos ];
    }

    private function _getTotalVta($fechaHora, $codigo)
	{
        $sucursal_id = session('sucursal_id');

		return DetalleComprobVenta::select(
			DB::raw('sum(cantidad) as total_cant, 
                     sum(precio_unitario) as precio_unit, 
                     sum(peso_helado) as peso_helado, 
                     producto_codigo'))
				->whereBetween('fecha_hora', [$fechaHora['desde'], $fechaHora['hasta']])
				->where('sucursal_id', $sucursal_id)
				->where('producto_codigo', $codigo)
				->where('estado', 1)
				->groupBy('producto_codigo')
				->get()
				->toArray();
	}

}
