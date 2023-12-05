<?php

namespace App\Http\Controllers\Parametros;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\EmailSucursal;
use App\Models\Sucursal;
use App\Models\Impresora;
use App\Models\PesoEnvaseHelado;


class ParametroController extends Controller
{
    /**
     * Muestra parametros del sistema
     * Name: parametros
     * 
     */
    public function index(Request $request, Utils $utils)
    {
        $SO = $utils->getSistemaOperativo();
        $sucursal_id = session('sucursal_id', Auth::user()->sucursal_id);
        $sucursal = Sucursal::select('nombre')
            ->find($sucursal_id);
        $cajas_sucursal = $utils->getCajas($sucursal_id);
        $cant_cajas = count($cajas_sucursal);
        $nombre_impresora = '';

        $impresoras = $utils->getImpresoras($sucursal_id);         // Buscar impresora
        $peso_envase = $this->_getPesoEnvase($utils, $sucursal_id);
        $lista_emails = $this->_getListaEmails($sucursal_id);
        $email_1 = '';

//dd($lista_emails);

        $data = compact(
            'impresoras',
            'SO',
            'sucursal',
            'cajas_sucursal',
            'cant_cajas',
            'sucursal_id',
            'nombre_impresora',
            'peso_envase',
            'lista_emails',
            'email_1',
        );

        //dd($data, $lista_emails);

        return view('parametros.index', $data);
    }

    /**
     * Guarda los datos de impresora.
     * Name: parametros.store_impresora (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **/
    public function store_impresora(Request $request)
    {
        $respuesta = ['estado' => 'ok'];

        foreach ($request->all() as $value) {
            $data = ['sucursal_id' => $value['sucursal_id'],
                     'caja_nro' => $value['caja_nro'],
                     'nombre' => $value['nombre'],
                     'sist_operativo' => $value['sist_operativo']
            ];

            $registro = Impresora::where([
                'sucursal_id' => $value['sucursal_id'],
                'caja_nro' => $value['caja_nro'],
                'sist_operativo' => $value['sist_operativo']
            ])->first();

            if ($registro) {
                $registro->nombre = $value['nombre'];
                $registro->save();
            } else {
                Impresora::create($data);
            }
        };

        return response()->json($respuesta);
    }

    /**
     * elimina los datos de impresora.
     * Name: parametros.eliminar_impres (GET)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **/
    public function eliminar_impres(Request $request)
    {
        $respuesta = ['estado' => 'ok'];

        $deleted = Impresora::where('id', $request->input('id_impres'))
            ->delete();
        if($deleted === 0) {
            $respuesta = ['estado' => 'error'];
        }

        return response()->json($respuesta);
    }

    /**
     * Guarda los datos de impresora.
     * Name: parametros.store_peso_env (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **/
    public function store_peso_env(Request $request)
    {
        $respuesta = ['estado' => 'error'];
        //$respuesta = $request->all();

        $envase = PesoEnvaseHelado::where('sucursal_id', $request->input('sucursal_id'))
            ->first();

        $data = [
            "sucursal_id" => $request->input('sucursal_id'),
            "peso" => $request->input('peso')
        ];

        if (!$envase) {
            PesoEnvaseHelado::create($data);
            $respuesta = ['estado' => 'ok'];
        } else {
            $envase->peso = $request->input('peso');
            $envase->save();
            $respuesta = ['estado' => 'ok'];
        }

        return response()->json($respuesta);
    }

    /**
     * Guarda los datos de Emails.
     * Name: parametros.store_email (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **/
    public function store_email(Request $request)
    {
        $respuesta = ['estado' => 'error'];
        // Delete all emails
        EmailSucursal::where('sucursal_id', $request->input('sucursal_id'))
            ->delete();
        $emailsEnviados = $request->input('emails');

        foreach ($emailsEnviados as $value) {
            $data = [
                "sucursal_id" => $request->input('sucursal_id'),
                "direccion_email" => $value
            ];
            EmailSucursal::create($data);
            $respuesta = ['estado' => 'ok'];
        }

        return response()->json($respuesta);
    }


    private function _getPesoEnvase(Utils $utils, int $sucursal_id = 1)
    {
        $pesoEnvase = PesoEnvaseHelado::select('peso')
            ->where('sucursal_id', $sucursal_id)
            ->first();

        if (!$pesoEnvase) {
            $peso_envase = 0.300;
        } else {
            $peso_envase = $utils->convStrToFloat($pesoEnvase->peso);
        }

        return $peso_envase;
    }

    private function _getListaEmails($sucursal_id)
    {
        $emails = EmailSucursal::select('id', 'sucursal_id', 'direccion_email')
            ->where('sucursal_id', $sucursal_id)
            ->get()
            ->toArray();
        //$emails = false;

        if ($emails) {
            return $emails;
        } else {
            return [[
                'id' => '0', 
                'sucursal_id' => $sucursal_id, 
                'direccion_email' => ''
            ]];
        }
    }
}
