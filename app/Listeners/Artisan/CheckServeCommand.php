<?php

namespace App\Listeners\Artisan;

use common\Observe\Artisan\ServeStarted;
use Illuminate\Console\Events\CommandStarting;

class CheckServeCommand
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommandStarting $event): void
    {
        switch ($event->command) {
            case 'serve':
                listen()->register(new ServeStarted());
                break;
        }

        listen()->notify();
    }
}
