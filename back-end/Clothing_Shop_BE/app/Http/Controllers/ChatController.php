<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessages;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
  public function store(User $user, Request $request)
  {
    $message = ChatMessages::create([
      'sender_id' => Auth::id(),
      'recei_id' => $user->id,
      'text' => $request->text
    ]);
    broadcast(new MessageSent($user, $message))->toOthers();

    return response()->json($message);
  }
}
