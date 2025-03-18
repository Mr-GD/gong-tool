<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Queue\InteractsWithQueue;

/**
 * 控制器执行完毕
 */
class ControllerHasFinishedExecuting
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
    public function handle(RequestHandled $event): void
    {
        /** 执行观察者 */
        $this->fireListen();
    }


    public function fireListen()
    {
        listen()->notify();
        listen()->clearResult();
    }
}
