<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    public static function sendToUser($user, string $title, string $body, array $data = []): bool
    {
        if (!$user || blank($user->fcm_token)) {
            return false;
        }

        $messaging = (new Factory)
            ->withServiceAccount(config('services.firebase.credentials'))
            ->createMessaging();

        $message = CloudMessage::withTarget('token', $user->fcm_token)
            ->withNotification(Notification::create($title, $body))
            ->withData(array_map('strval', $data));

        $messaging->send($message);

        return true;
    }
}