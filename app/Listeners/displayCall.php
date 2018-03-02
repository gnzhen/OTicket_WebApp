<?php

namespace App\Listeners;

use App\Events\displayEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class displayCall
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
     * @param  displayEvent  $event
     * @return void
     */
    public function handle(displayEvent $event)
    {
        //
    }
}
