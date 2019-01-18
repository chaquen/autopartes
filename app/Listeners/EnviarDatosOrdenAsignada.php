<?php

namespace App\Listeners;

use App\Events\OrdenAsignada;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarDatosOrdenAsignada
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
     * @param  OrdenAsignada  $event
     * @return void
     */
    public function handle(OrdenAsignada $event)
    {
        //
    }
}
