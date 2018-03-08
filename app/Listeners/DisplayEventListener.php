<?php

namespace App\Listeners;

use App\Events\DisplayEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DisplayEventListener
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
    public function handle(DisplayEvent $event)
    {
        return $event;
    }
}
