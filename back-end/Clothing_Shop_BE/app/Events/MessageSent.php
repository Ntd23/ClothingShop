<?php

namespace App\Events;

use App\Models\ChatMessages;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;
  public $user, $chatMessage;
  /**
   * Create a new event instance.
   */
  public function __construct(User $user, ChatMessages $chatMessages)
  {
    $this->user = $user;
    $this->chatMessage = $chatMessages;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return array<int, \Illuminate\Broadcasting\Channel>
   */
  public function broadcastOn(): array
  {
    return [
      new PrivateChannel('chat'),
    ];
  }
  public function broadcastWith()
  {
    return ['message' => $this->chatMessage];
  }
}
