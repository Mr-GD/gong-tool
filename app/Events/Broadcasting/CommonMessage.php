<?php

namespace App\Events\Broadcasting;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommonMessage implements ShouldBroadcastNow
{

    use Dispatchable;

    public array $message = [
        'type'    => 'CommonMessage',
        'message' => []
    ];

    public function __construct(mixed $message)
    {
        $this->message['message'] = $message;
    }

    public function broadcastOn()
    {
        // TODO: Implement broadcastOn() method.
    }

    public function broadcastAs()
    {
        return 'commonMessage';
    }
}
