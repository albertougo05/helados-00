<?php
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Mail\CierreTurno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


/**
 * Controlador para envio de emails
 * 
 * Url: http://localhost:8000/email/send
 * 
 * Usa Configuracion de email de //App/Mail/CierreTurnoEmail
 */
class MailController extends Controller
{
    public function send(Request $request)
    {

        //dd($request->all());

        $objDemo = new \stdClass();
        $objDemo->sucursal = 'Jesús María';
        $objDemo->fecha = '09/05/2023';
        $objDemo->hora = '09:05';
        $objDemo->sender = 'Administrador';
        $objDemo->receiver = 'Alberto Ugolino';
 
        Mail::to("albertougo@gmail.com")->send(new CierreTurnoEmail($objDemo));

        return response()->json(['estado' => 'ok']);
    }
}