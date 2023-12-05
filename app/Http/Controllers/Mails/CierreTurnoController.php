<?php

namespace App\Http\Controllers\Mails;
 
use App\Http\Controllers\Controller;
use App\Mail\CierreTurno;
use App\Models\EmailSucursal;
use App\Models\Turno;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Support\Facades\Mail;


/**
 * Controlador para envio de emails
 * Name: email.enviar
 * 
 * Url: http://localhost:8000/email/enviar/4
 * 
 * Usa Configuracion de email de //App/Mail/CierreTurno
 */
class CierreTurnoController extends Controller
{
    public function enviar($id)
    {
        $objTurno = $this->_objEnviar($id);
        // Buscar direcciones de email
        $direcciones = EmailSucursal::where('sucursal_id', env('SUCURSAL_ID'))
            ->get();
        //dd($objTurno, $direcciones);

        foreach ($direcciones as $value) { // Loop para cada direccion
            Mail::to($value->direccion_email)->send(new CierreTurno($objTurno));
        }

        return response()->json(['estado' => 'Email enviado!']);
    }


    private function _objEnviar($id)
    {
        $turno = Turno::find($id);
        $obj = new \stdClass();
        $obj->sender = 'Administrador';
        $obj->receiver = 'Alberto Ugolino';         // NO SE USA !
        //$obj->sucursal_id = $turno->sucursal_id;
        $obj->sucursal = $this->_sucursal($turno->sucursal_id);
        //$obj->usuario_id = $turno->usuario_id;
        $obj->usuario = $this->_usuario($turno->usuario_id);
        $obj->turno_sucursal = $turno->turno_sucursal;
        $obj->apertura_fecha = date('d/m/Y H:i', strtotime($turno->apertura_fecha_hora));
        $obj->cierre_fecha = date('d/m/Y H:i', strtotime($turno->cierre_fecha_hora));
        $obj->saldo_inicio = number_format($turno->saldo_inicio, 2, ',', '.');
        $obj->vta_total = number_format($turno->venta_total, 2, ',', '.');
        $obj->vta_efectivo = number_format($turno->efectivo, 2, ',', '.');
        $obj->vta_credito = number_format($turno->tarjeta_credito, 2, ',', '.');
        $obj->vta_debito = number_format($turno->tarjeta_debito, 2, ',', '.');
        $obj->vta_ctacte = number_format($turno->cuenta_corriente, 2, ',', '.');
        $obj->vta_transf = number_format($turno->otros, 2, ',', '.');
        $obj->egresos = number_format($turno->egresos, 2, ',', '.');
        $obj->ingresos = number_format($turno->ingresos, 2, ',', '.');
        $obj->caja_teorica = number_format($turno->caja, 2, ',', '.');
        $obj->arqueo = number_format($turno->arqueo, 2, ',', '.');
        $obj->diferencia = number_format($turno->diferencia, 2, ',', '.');
        $obj->observaciones = $turno->observaciones;

        return $obj;
    }

    private function _sucursal($id)
    {
        return Sucursal::find($id)->nombre;
    }

    private function _usuario($id)
    {
        return User::find($id)->name;
    }
}
