<?php

namespace App\Http\Controllers;

use App\Events\DirectMessageSent;
use App\Models\MessageAttachment;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BroadcastDMController extends Controller
{
     public function create()
    {
        // simple form
        return view('admin.dm_broadcast');
    }

    public function store(Request $request)
    {
        $request->validate([
            'body' => 'nullable|string|max:3000',
            'attachments.*' => 'file|max:25600|mimetypes:image/jpeg,image/png,image/webp,image/gif,application/pdf,video/mp4,video/webm,video/quicktime',
        ], [
            'attachments.*.max' => 'Each file must be <= 25MB',
        ]);

        if (!$request->hasFile('attachments') && blank($request->input('body'))) {
            return back()->with('error','Nothing to send');
        }

        $admin = $request->user();
        $sent  = 0;

        // ---- Upload each attachment ONCE; reuse file for all receivers
        $commonAttachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file->isValid()) continue;
                if ($file->getClientOriginalExtension() === 'svg') continue; // security

                $path = $file->store('chat', 'public');
                $mime = $file->getMimeType();
                $size = $file->getSize();
                $orig = $file->getClientOriginalName();

                $width = $height = $duration = null;
                if (str_starts_with($mime, 'image/')) {
                    try { [$width,$height] = getimagesize($file->getRealPath()); } catch (\Throwable $e) {}
                }
                // (optional) video duration via getid3 if you use it

                $commonAttachments[] = [
                    'path'          => $path,
                    'mime'          => $mime,
                    'size'          => $size,
                    'original_name' => $orig,
                    'width'         => $width,
                    'height'        => $height,
                    'duration'      => $duration,
                ];
            }
        }

        // ---- Target users (yahan filter adjust kar sakte ho)
        $query = User::query()
            ->whereKeyNot($admin->id)
            ->orderBy('id');

        // Example filter: only active users
        // $query->where('is_active', true);

        // ---- Chunk to avoid memory spike
        $query->chunkById(200, function ($users) use ($admin, $request, $commonAttachments, &$sent) {
            foreach ($users as $u) {
                // per-user message
                $msg = Message::create([
                    'sender_id'   => $admin->id,
                    'receiver_id' => $u->id,
                    'body'        => $request->input('body', ''),
                ]);

                // replicate attachments rows (same files, different message_id)
                foreach ($commonAttachments as $att) {
                    MessageAttachment::create($att + ['message_id' => $msg->id]);
                }

                // broadcast to that user's private channel
                $msg->loadMissing('sender','attachments');
                broadcast(new DirectMessageSent($msg))->toOthers();

                $sent++;
            }
        });

        return back()->with('success', "Broadcast sent to {$sent} users.");
    }
}
