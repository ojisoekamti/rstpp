<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class OrderPlaced
{
    use InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return ['orders-channel'];
    }

    public function broadcastAs()
    {
        return 'order-placed';
    }
}
