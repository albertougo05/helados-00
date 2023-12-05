<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AlertSuccess extends Component
{
    /**
     * Titulo de la alerta.
     *
     * @var string
     */
    public $titulo;

    /**
     * Create the component instance.
     *
     * @param  string  $titulo
     * @return void
     */
    public function __construct($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.alert-success');
    }
}
