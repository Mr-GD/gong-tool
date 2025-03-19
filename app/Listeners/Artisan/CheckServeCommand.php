<?php

namespace App\Listeners\Artisan;

use common\Observe\Artisan\ServeStarted;
use gong\tool\Observer\Action;
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
        $params = ['event' => $event];
        switch ($event->command) {
            case 'serve':
                listen()->register(new ServeStarted($params));
                break;
        }

        listen()->notify();
        Action::clear();
    }
}
