<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        Gate::define('chat-with', function (User $user,  $user_id) {
            $target = User::find($user_id);
            return !$user->hasBlocked($target) && !$target->hasBlocked($user);
        });
        
    }
}
