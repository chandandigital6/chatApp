<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FcmService
{
    public static function sendToUser($user, string $title, string $body, array $data = []): bool
    {
        if (!$user || blank($user->fcm_token)) {
            Log::warning('FCM token missing', [
                'user_id' => $user?->id,
            ]);
            return false;
        }

        $credentials = config('services.firebase.credentials');

        if (!$credentials || !file_exists($credentials)) {
            Log::error('Firebase credentials file not found', [
                'path' => $credentials,
            ]);
            return false;
        }

        try {
            $messaging = (new Factory)
                ->withServiceAccount($credentials)
                ->createMessaging();

            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification(Notification::create($title, $body))
                ->withData(array_map('strval', $data));

            $messaging->send($message);

            Log::info('FCM notification sent', [
                'user_id' => $user->id,
            ]);

            return true;

        } catch (\Throwable $e) {
            Log::error('FCM notification failed', [
                'user_id' => $user->id,
                'token' => $user->fcm_token,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}