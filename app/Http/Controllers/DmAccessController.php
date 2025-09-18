<?php

namespace App\Http\Controllers;

use App\Models\DmAllowed;
use App\Models\User;
use Illuminate\Http\Request;

class DmAccessController extends Controller
{


      public function index()
    {
       abort_unless(auth()->user()->can('chat-anyone'), 403);

    $me = auth()->id();
    $q  = request('q');

    $users = User::query()
        ->when($q, fn($qq)=>$qq->where(function($w) use($q){
            $w->where('name','like',"%{$q}%")
              ->orWhere('email','like',"%{$q}%")
              ->orWhere('phone_number','like',"%{$q}%");
        }))
        ->whereKeyNot($me)
        ->orderBy('name')
        ->paginate(20)
        ->withQueryString();

    return view('admin.people', compact('users','q'));

        // return view('admin.people', compact('users', 'q', 'canChatAnyone'));
    }
    
   public function edit(User $user)
    {
        $allUsers = User::whereKeyNot($user->id)->orderBy('name')->get();
        $allowed  = $user->dmAllowedPeers()->pluck('users.id')->toArray();
        $hasGlobal = $user->can('chat-anyone');

        return view('admin.dm_access', compact('user','allUsers','allowed','hasGlobal'));
    }

    public function update(Request $request, User $user)
    {
        // toggle global permission
        if ($request->boolean('chat_anyone')) {
            $user->givePermissionTo('chat-anyone');
        } else {
            $user->revokePermissionTo('chat-anyone');
        }

        // pair-wise list
        $peerIds = collect($request->input('peers', []))
            ->map(fn($id)=>(int)$id)->unique()->reject(fn($id)=>$id===$user->id);

        DmAllowed::where('user_id',$user->id)->delete();
        foreach ($peerIds as $pid) {
            DmAllowed::create([
                'user_id'    => $user->id,
                'peer_id'    => $pid,
                'granted_by' => $request->user()->id,
            ]);
        }

        return back()->with('success','DM access updated.');
    }
}
