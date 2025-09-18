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
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

 Gate::define('dm-start', function ($user, $peerId) {
            if ((int) $user->id === (int) $peerId) {
                return false;
            }

            // Super Admins can DM anyone
            if (method_exists($user, 'hasRole') && $user->hasRole('Super Admin')) {
                return true;
            }

            // Global permission
            if ($user->can('chat-anyone')) {
                return true;
            }

            // (Optional) allow DM to Super Admin always
            $superAdminId = Role::where('name', 'Super Admin')->first()?->users()->value('id');
            if ($superAdminId && (int) $peerId === (int) $superAdminId) {
                return true;
            }

            // Pair-wise allow list
            return DmAllowed::where('user_id', $user->id)
                ->where('peer_id', $peerId)
                ->exists();
        });

         Gate::define('dm-start', function ($user, $peerId) {
        if ((int)$user->id === (int)$peerId) return false;

        // Global power: chat-anyone ⇒ किसी से भी DM शुरू कर सकते हैं
        if ($user->can('chat-anyone')) return true;

        // Pair-wise allow list
        if (DmAllowed::where('user_id',$user->id)->where('peer_id',$peerId)->exists()) return true;

        // ✅ Reply allowed: अगर सामने वाले (peer) ने पहले कभी आपको msg भेजा है
        if (Message::where('sender_id',$peerId)->where('receiver_id',$user->id)->exists()) return true;

        return false;
    });
        
    }
}
