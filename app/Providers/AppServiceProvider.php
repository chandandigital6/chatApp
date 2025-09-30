<?php

namespace App\Providers;

use App\Models\DmAllowed;
use App\Models\Message;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider

{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    // Super Admin = full access (optional but convenient)
    Gate::before(function ($user, $ability) {
        return method_exists($user, 'hasRole') && $user->hasRole('Super Admin') ? true : null;
    });

    Gate::define('dm-start', function ($user, $peerId) {
        $peerId = (int) $peerId;

        // Self-DM block
        if ((int) $user->id === $peerId) {
            return false;
        }

        // 1) If PEER is Super Admin → always allow (so Support Chat sab ke liye open ho)
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminIds = $superAdminRole->users()->pluck('id')->all();
            if (in_array($peerId, $superAdminIds, true)) {
                return true;
            }
        }

        // (Optional) env override — agar aap ek fixed Support Admin ID rakhte ho
        $envAdminId = (int) env('CHAT_SUPER_ADMIN_ID', 0);
        if ($envAdminId && $peerId === $envAdminId) {
            return true;
        }

        // 2) Global power: 'chat-anyone'
        if ($user->can('chat-anyone')) {
            return true;
        }

        // 3) Pair-wise allow list
        if (DmAllowed::where('user_id', $user->id)->where('peer_id', $peerId)->exists()) {
            return true;
        }

        // 4) Reply allowed: agar peer→user ne pehle kabhi msg bheja ho
        if (Message::where('sender_id', $peerId)->where('receiver_id', $user->id)->exists()) {
            return true;
        }

        // ❌ otherwise block
        return false;
    });
        
    }
}
