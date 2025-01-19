<?php

namespace App\Events\Broadcasting;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommonMessage implements ShouldBroadcast, ShouldQueue
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

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
        return new Channel('CommonMessage');
    }

    public function broadcastAs()
    {
        return 'CommonMessage';
    }
}
