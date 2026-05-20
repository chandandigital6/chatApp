<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FcmService
{
    public static function send($token, $title, $body, $data = [])
    {
        if (!$token) {
            return false;
        }

        $serverKey = config('services.fcm.server_key');

        return Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
            ],
            'data' => $data,
        ]);
    }
}