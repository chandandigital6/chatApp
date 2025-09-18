<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index()
    {
        $me = auth()->id();

        // last message per peer
        $threads = Message::selectRaw('IF(sender_id=?, receiver_id, sender_id) as peer_id, MAX(id) as last_id', [$me])
            ->where(fn($q)=>$q->where('sender_id',$me)->orWhere('receiver_id',$me))
            ->groupBy('peer_id')
            ->orderByDesc('last_id')
            ->get();

        $peers = User::whereIn('id', $threads->pluck('peer_id'))->get()->keyBy('id');

        return view('dm.inbox', compact('threads','peers'));
    }
}
