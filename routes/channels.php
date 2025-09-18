<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    // Only the logged-in user can listen to their own channel
    return (int) $user->id === (int) $id
        ? ['id' => $user->id, 'name' => $user->name]
        : false;
});