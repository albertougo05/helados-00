<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CierreTurno extends Mailable
{
    use Queueable, SerializesModels;

   /**
     * The Content object instance.
     *
     * @var Content
     */
    public $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->from('albertougo@gmail.com')
        return $this->from('admin@netsmart.com.ar')
                    ->subject("Cierre turno/caja: " . $this->content->sucursal)
                    ->text('mails.cierre_turno_plain');
                    //->view('mails.content')
                    //->with(
                    //    [
                    //        'sucursal_id' => '1',
                    //        'turno_id' => '2',
                    //]);

        //return $this->view('view.name');
    }
}
