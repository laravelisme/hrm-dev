<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TenantCreated
{
    use Dispatchable, SerializesModels;

    public $tenant;
    public $data;
    public $rawPassword;

    public function __construct($tenant, $data, $rawPassword)
    {
        $this->tenant = $tenant;
        $this->data = $data;
        $this->rawPassword = $rawPassword;
    }
}

