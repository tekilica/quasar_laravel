<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerGates();
    }

    private function registerGates()
    {
        Gate::define('view-users', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'hr']);
        });

        Gate::define('create-user', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'hr']);
        });

        Gate::define('edit-users', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'hr']);
        });

        Gate::define('delete-users', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'hr']);
        });

        Gate::define('view-images', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']);
        });

        Gate::define('upload-images', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'photographer']);
        });

        Gate::define('view-image', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']);
        });

        Gate::define('edit-images', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'photographer']);
        });

        Gate::define('delete-images', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'photographer']);
        });

        Gate::define('view-news', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'journalist']);
        });

        Gate::define('view-videos', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']);
        });

        Gate::define('upload-video', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'photographer', 'operator']);
        });

        Gate::define('view-video', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']);
        });

        Gate::define('edit-videos', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'photographer', 'operator']);
        });

        Gate::define('delete-videos', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'photographer', 'operator']);
        });

        Gate::define('view-soundtracks', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']);
        });

        Gate::define('upload-soundtrack', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'photographer', 'operator']);
        });

        Gate::define('view-soundtrack', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']);
        });

        Gate::define('edit-soundtracks', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'photographer', 'operator']);
        });

        Gate::define('delete-soundtracks', function(User $user) {
            return in_array($user->userRole->role->name, ['admin', 'photographer', 'operator']);
        });
    }
}
