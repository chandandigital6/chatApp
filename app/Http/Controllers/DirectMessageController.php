<?php

namespace App\Http\Controllers;
use App\Events\DirectMessageSent;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class DirectMessageController extends Controller
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


     public function show(User $user)
    {
        // Permission: can I DM this peer?
        abort_unless(Gate::allows('dm-start', $user->id), 403);

        $me = auth()->id();

        // Fetch last N messages between me and peer (oldest->newest)
        $messages = Message::with('sender:id,name')
            ->where(function ($q) use ($me, $user) {
                $q->where('sender_id', $me)->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me);
            })
            ->orderBy('id', 'asc')
            ->take(200)
            ->get();

        // Mark unread as read (peer → me)
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('dm.index', [
            'peer'     => $user,
            'messages' => $messages,
        ]);
    }


// public function store(Request $request, User $user)
// {
//     abort_unless(Gate::allows('dm-start', $user->id), 403);

//     $data = $request->validate(['body'=>'required|string|max:3000']);
//     $msg = Message::create([
//         'sender_id'=>$request->user()->id,
//         'receiver_id'=>$user->id,
//         'body'=>$data['body'],
//     ]);
//     broadcast(new DirectMessageSent($msg->load('sender')))->toOthers();
//     return response()->json(['ok'=>true,'message'=>$msg]);
// }



  

public function store(Request $request, User $user)
{
    abort_unless(Gate::allows('dm-start', $user->id), 403);

    $data = $request->validate([
        'body' => 'nullable|string|max:3000',
        'attachments.*' => 'file|max:25600|mimetypes:image/jpeg,image/png,image/webp,image/gif,application/pdf,video/mp4,video/webm,video/quicktime',
    ], [
        'attachments.*.max' => 'Each file must be <= 25MB',
    ]);

    if (!$request->hasFile('attachments') && blank($data['body'] ?? null)) {
        return response()->json(['ok'=>false,'message'=>'Nothing to send'], 422);
    }

    $msg = Message::create([
        'sender_id'   => $request->user()->id,
        'receiver_id' => $user->id,
        'body'        => $data['body'] ?? '',
    ])->load('sender:id,name');

    // save each attachment
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            if (!$file->isValid()) continue;
            // SECURITY: disallow svg to avoid script injection
            if ($file->getClientOriginalExtension() === 'svg') continue;

            $path = $file->store('chat', 'public');
            $mime = $file->getMimeType();
            $size = $file->getSize();
            $orig = $file->getClientOriginalName();

            $width = $height = $duration = null;

            if (str_starts_with($mime, 'image/')) {
                try {
                    [$width,$height] = getimagesize($file->getRealPath());
                } catch (\Throwable $e) {}
            }
            // (optional) if you want duration, install getid3 via composer and uncomment:
            // if (str_starts_with($mime, 'video/')) {
            //     $an = (new \getID3)->analyze($file->getRealPath());
            //     $duration = (int) round($an['playtime_seconds'] ?? 0);
            // }

            MessageAttachment::create([
                'message_id'    => $msg->id,
                'path'          => $path,
                'mime'          => $mime,
                'size'          => $size,
                'original_name' => $orig,
                'width'         => $width,
                'height'        => $height,
                'duration'      => $duration,
            ]);
        }
    }

    // include attachments for realtime payload
    $msg->load('attachments');

    // broadcast(new DirectMessageSent($msg))->toOthers();
    broadcast(new DirectMessageSent($msg->load('sender','attachments')))->toOthers();

    return response()->json(['ok'=>true,'message'=>$msg]);
}
}
