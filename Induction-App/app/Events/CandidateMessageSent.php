<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CandidateMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $candidateId;
    public $adminId;

    public function __construct($message, $candidateId, $adminId)
    {
        $this->message = $message;
        $this->candidateId = $candidateId;
        $this->adminId = $adminId;
    }

    public function broadcastOn()
    {
        return new Channel('chat.admin.'.$this->adminId);
    }

    public function broadcastAs()
    {
        return 'candidate.message';
    }
}
