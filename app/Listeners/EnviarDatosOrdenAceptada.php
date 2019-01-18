<?php

namespace App\Listeners;

use App\Events\OrdenAceptada;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarDatosOrdenAceptada
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrdenAceptada  $event
     * @return void
     */
    public function handle(OrdenAceptada $event)
    {
        //
    }
}
