<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // public function dashboard()
    // {

    //     return view('dashboard');
    // }



    public function dashboard(Request $request)
    {
        $me = $request->user()->id;

        $unreadCount = Message::where('receiver_id', $me)
            ->whereNull('read_at')
            ->count();

        // Jitne users ko aap DM kar sakte ho (basic estimate; aap apna filter laga sakte ho)
        $peopleCount = User::whereKeyNot($me)->count();

        // Last broadcast time ko session flash se dikha sakte ho (ya alag table ho to wahan se)
        $lastBroadcastAt = session('last_broadcast_at'); // optional

        return view('dashboard', compact('unreadCount', 'peopleCount', 'lastBroadcastAt'));
    }
}
