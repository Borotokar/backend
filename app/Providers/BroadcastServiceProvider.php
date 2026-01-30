<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes();
        Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
            return true; // add your logic to determine if the user has access
        });

        require base_path('routes/channels.php');
    }
}
