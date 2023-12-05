<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Sucursal;
use App\Models\Turno;
use App\Models\Impresora;


class Utils extends Controller
{
	/**
	 * Convierte string de numero a float 
	 * (quita puntos de miles y cambia coma por punto decimal)
	 * 
	 * @param  string $num
	 * @return float 
	 */
	public function convStrToFloat($num)
	{
		// $sinPuntos  = str_replace('.', '', $num);
  		// $cambioComa = str_replace(',', '.', $sinPuntos);
		// return floatval($cambioComa);

        $dotPos = strrpos($num, '.');
        $commaPos = strrpos($num, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : 
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
       
        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        } 
    
        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
            preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
        );
	}

    /**
     * Redondear float a dos decimales
     * (3.56666 => 3.57 / 3.00890 => 3.01 / 3.199 => 3.2 / 3.9999 => 4)
     */
    public function redondear_dos_decimal($valor) {
        $float_redondeado = round($valor * 100) / 100;
        return $float_redondeado;
    }

     /**
     * Redondear float a la cantidad de decimales que se quiera
     * 
     */
    function redondeado ($numero, $decimales = 2) {
        $factor = pow(10, $decimales);
        return (round($numero * $factor) / $factor); 
    }

	/**
	 * Devuelve array con los puntos de venta de una sucursal
	 * 
	 * @param integer $suc_id
	 * @return array $ptosVta
	 */
    public function getPtosVta(int $suc_id)
    {
        $ptosVta = $cajasAbiertas = [];
        $sucursal = Sucursal::find($suc_id);
        $turnos = Turno::where('sucursal_id', $suc_id)
            ->where('cierre_fecha_hora', null)
            ->get();

        if ($turnos) {
            foreach ($turnos as $value) {
                $cajasAbiertas[] = $value->caja_nro;
            }
        } else $cajasAbiertas[] = 1;

        $nros_cajas = $sucursal->cant_puntos_venta;

        for ($i=1; $i <= $nros_cajas; $i++) { 
            // Si el valor está en cajas abiertas, lo saltea...
            if (in_array($i, $cajasAbiertas)) continue;

            $ptosVta[] = [
                "id" => $i,
                "texto" => "Caja " . $i,
            ];
        }

        return $ptosVta;
    }

	/**	
	 * Devuelve array con las cajas de la sucursal
	 * 
	 * @return array
	 */
    public function getCajas(int $suc_id)
    {
        $sucursal = Sucursal::find($suc_id);
        $nros_cajas = $sucursal->cant_puntos_venta;

        for ($i=1; $i <= $nros_cajas; $i++) { 
            $ptosVta[] = [
                "id" => $i,
                "texto" => "Caja " . $i,
            ];
        }

        return $ptosVta;
    }

	/**	
	 * Devuelve array con cajas abiertas en sucursal
	 * 
	 * @return array
	 */
    public function getCajasAbiertasSuc(int $suc_id): array
    {
        $ptosVta = [];
        $cajas = Turno::select('caja_nro')
            ->where('cierre_fecha_hora', null)
            ->where('sucursal_id', $suc_id)
            ->get();

        if ($cajas->count() > 0) {
            foreach ($cajas as $caja) {
                $ptosVta[] = [
                    "id" => $caja->caja_nro,
                    "texto" => "Caja " . $caja->caja_nro,
                ];
            }
        }

        return $ptosVta;
    }

	/**	
	 * Devuelve array con caja abierta por el usuario
	 * 
	 * @return array
	 */
    public function getCajaUsuario(int $suc_id, int $user_id)
    {
        $ptosVta = [];
        $caja_id = Turno::select('caja_nro')
            ->where('cierre_fecha_hora', null)
            ->where('sucursal_id', $suc_id)
            ->where('usuario_id', $user_id)
            ->get();

        if ($caja_id->count() === 1) {
            $ptosVta[] = [
                "id" => $caja_id[0]['caja_nro'],
                "texto" => "Caja " . $caja_id[0]['caja_nro'],
            ];
        }

        return $ptosVta;
    }

	/**	
	 * Devuelve array con turnos abiertos en sucursal
	 * 
	 * @return array
	 */
    public function getTurnosAbiertosSucursal(int $suc_id)
    {
        $turnos = Turno::select('id', 'turno_sucursal', 'caja_nro', 'usuario_id')
        ->where('cierre_fecha_hora', null)
        ->where('sucursal_id', $suc_id)
        ->get();
        
        if ($turnos->count() > 0) {
            foreach ($turnos as $value) {
                $turnosAbiertos[] = [
                    "turno_id" => $value->id,
                    "turno_sucursal" => $value->turno_sucursal,
                    "usuario_id" => $value->usuario_id,
                    "caja_nro" => $value->caja_nro,
                    "texto" => "Caja " . $value->caja_nro,
                ];
            }
        } else {
            $turnosAbiertos = [[
                'turno_id' => 0,
                'turno_sucursal' => 0
            ]];
        }

        return $turnosAbiertos;
    }

	/**	
	 * Devuelve perfil del usuario actual
	 * 
	 * @return string $perfil
	 */
	public function getUserPerfil()
    {
        if (Auth::user()->perfil_id === 1 || Auth::user()->perfil_id === 2)
            $perfil = 'admin';
		else if (Auth::user()->perfil_id === 3 || Auth::user()->perfil_id === 4) {
			$perfil = 'empleado';
		}

		return $perfil;
    }

	/**	
	 * Devuelve true/false si hay caja abierta en sucursal
	 * 
	 * @return array
	 */
    public function getCajaAbiertaEnSucursal(int $suc_id)
    {
        $turno = Turno::where('cierre_fecha_hora', null)
            ->where('sucursal_id', $suc_id)
            ->exists();

        return $turno;
    }

    public function getSistemaOperativo()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];    //http_user_agent del visitante
        //$user_agent = 'Windows NT 6.1+';    // _getPlatform() devuelve => 'Windows 7'

        $plataformas = array(
           'Windows 10' => 'Windows NT 10.0+',
           'Windows 8.1' => 'Windows NT 6.3+',
           'Windows 8' => 'Windows NT 6.2+',
           'Windows 7' => 'Windows NT 6.1+',
           'Windows Vista' => 'Windows NT 6.0+',
           'Windows XP' => 'Windows NT 5.1+',
           'Windows 2003' => 'Windows NT 5.2+',
           'Windows' => 'Windows otros',
           'Mac OS X' => '(Mac OS X+)|(CFNetwork+)',
           'Mac otros' => 'Macintosh',
           'Android' => 'Android',
           'Linux' => 'Linux',
        );

        foreach($plataformas as $plataforma => $pattern){
           if (preg_match('/(?i)'.$pattern.'/', $user_agent))
              return $plataforma;
        }

        return 'Desconocido';
    }

    public function getImpresoras(string $sucursal_id = '1', $caja_nro = 0)
    {
        $so = $this->getSistemaOperativo();

        if ($caja_nro === 0) {
            $condiciones = [
                ['sucursal_id', $sucursal_id],
                ['sist_operativo', $so],
            ];
        } else {
            $condiciones = [
                ['sucursal_id', $sucursal_id],
                ['sist_operativo', $so],
                ['caja_nro', $caja_nro],
            ];
        }

        $impresoras = Impresora::where($condiciones)
                ->get()
                ->toArray();

        if (count($impresoras) === 0) {     // Si no hay impresora configurada
            $impresoras = [
                ['id' => 0, 
                 'sucursal_id' => $sucursal_id,
                 'caja_nro' => $caja_nro,
                 'nombre' => false]
            ];
        }

        return $impresoras;
    }

    public function getNombreImpresora(string $sucursal_id = '1', $caja_nro = 1)
    {
        $impresoras = $this->getImpresoras($sucursal_id, $caja_nro); 
        $nombre = $impresoras[0]['nombre'];

        return $nombre ?? '';
    }
}
