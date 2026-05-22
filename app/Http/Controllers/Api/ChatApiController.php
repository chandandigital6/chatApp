<?php

namespace App\Http\Controllers\Api;

use App\Events\DirectMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FcmService;
use Illuminate\Support\Facades\Log;

class ChatApiController extends Controller
{
    private function superAdminId()
    {
        return (int) env('CHAT_SUPER_ADMIN_ID', 1);
    }

    private function canChatWith(User $authUser, User $peer): bool
    {
        $superAdminId = $this->superAdminId();

        // Super Admin kisi bhi user se chat kar sakta hai
        if ($authUser->id === $superAdminId || $authUser->hasRole('Super Admin')) {
            return $authUser->id !== $peer->id;
        }

        // Normal user sirf Super Admin se chat karega
        return $peer->id === $superAdminId;
    }

    public function users(Request $request)
    {
        $authUser = $request->user();
        $superAdminId = $this->superAdminId();

        if ($authUser->id === $superAdminId || $authUser->hasRole('Super Admin')) {
            $users = User::whereKeyNot($authUser->id)
                ->when($request->q, function ($q) use ($request) {
                    $q->where(function ($w) use ($request) {
                        $w->where('name', 'like', "%{$request->q}%")
                            ->orWhere('email', 'like', "%{$request->q}%")
                            ->orWhere('phone_number', 'like', "%{$request->q}%");
                    });
                })
                ->select('id', 'name', 'email', 'phone_number')
                ->orderBy('name')
                ->paginate(20);

            return response()->json([
                'status' => true,
                'message' => 'Users fetched successfully',
                'data' => $users,
            ]);
        }

        $admin = User::select('id', 'name', 'email', 'phone_number')
            ->findOrFail($superAdminId);

        return response()->json([
            'status' => true,
            'message' => 'User can chat only with Super Admin',
            'data' => [$admin],
        ]);
    }

    public function inbox(Request $request)
    {
        $me = $request->user()->id;

        $threads = Message::selectRaw('IF(sender_id = ?, receiver_id, sender_id) as peer_id, MAX(id) as last_id', [$me])
            ->where(function ($q) use ($me) {
                $q->where('sender_id', $me)
                    ->orWhere('receiver_id', $me);
            })
            ->groupBy('peer_id')
            ->orderByDesc('last_id')
            ->get();

        $lastIds = $threads->pluck('last_id');

        $messages = Message::with([
                'sender:id,name,email',
                'receiver:id,name,email',
                'attachments'
            ])
            ->whereIn('id', $lastIds)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Inbox fetched successfully',
            'data' => $messages,
        ]);
    }

    public function messages(Request $request, User $user)
    {
        $authUser = $request->user();

        if (!$this->canChatWith($authUser, $user)) {
            return response()->json([
                'status' => false,
                'message' => 'You are not allowed to chat with this user.',
            ], 403);
        }

        $me = $authUser->id;

        $messages = Message::with([
                'sender:id,name,email',
                'receiver:id,name,email',
                'attachments'
            ])
            ->where(function ($q) use ($me, $user) {
                $q->where('sender_id', $me)
                    ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)
                    ->where('receiver_id', $me);
            })
            ->orderBy('id', 'asc')
            ->paginate(50);

        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'status' => true,
            'message' => 'Messages fetched successfully',
            'peer' => $user->only('id', 'name', 'email'),
            'data' => $messages,
        ]);
    }


    public function send(Request $request, User $user)
{
    $authUser = $request->user();

    if (!$this->canChatWith($authUser, $user)) {
        return response()->json([
            'status' => false,
            'message' => 'You are not allowed to send message to this user.',
        ], 403);
    }

    $data = $request->validate([
        'body' => 'nullable|string|max:3000',
        'attachments' => 'nullable|array',
        'attachments.*' => 'nullable|file|max:25600|mimes:jpg,jpeg,png,webp,gif,pdf,mp4,webm,mov',
    ]);

    if (!$request->hasFile('attachments') && blank($data['body'] ?? null)) {
        return response()->json([
            'status' => false,
            'message' => 'Nothing to send',
        ], 422);
    }

    $msg = Message::create([
        'sender_id' => $authUser->id,
        'receiver_id' => $user->id,
        'body' => $data['body'] ?? '',
    ]);

    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            if (strtolower($file->getClientOriginalExtension()) === 'svg') {
                continue;
            }

            $path = $file->store('chat', 'public');
            $mime = $file->getMimeType();

            $width = null;
            $height = null;

            if (str_starts_with($mime, 'image/')) {
                try {
                    [$width, $height] = getimagesize($file->getRealPath());
                } catch (\Throwable $e) {
                    $width = null;
                    $height = null;
                }
            }

            MessageAttachment::create([
                'message_id' => $msg->id,
                'path' => $path,
                'mime' => $mime,
                'size' => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
                'width' => $width,
                'height' => $height,
                'duration' => null,
            ]);
        }
    }

    $msg->load([
        'sender:id,name,email',
        'receiver:id,name,email,fcm_token',
        'attachments'
    ]);

    broadcast(new DirectMessageSent($msg))->toOthers();

    try {
        FcmService::sendToUser(
            $user,
            $authUser->name ?? 'New Message',
            filled($msg->body) ? $msg->body : 'Sent an attachment',
            [
                'type' => 'chat_message',
                'message_id' => $msg->id,
                'sender_id' => $authUser->id,
                'receiver_id' => $user->id,
            ]
        );
    } catch (\Throwable $e) {
        Log::error('FCM chat notification failed', [
            'error' => $e->getMessage(),
            'receiver_id' => $user->id,
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'Message sent successfully',
        'data' => $msg,
    ]);
}


}