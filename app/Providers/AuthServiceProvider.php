<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthServiceProvider extends ServiceProvider
{
    use HandlesAuthorization;
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        
        Gate::define('check-if-user-can-update-post', function(User $user, $post){
            if($user->id != $post->user_id || $post->status != 'scheduled'){
                return false;
            }

            return true;
        });

        Gate::define('check-if-user-can-publish-post', function(User $user, $post){
            
            if($user->id != $post->user_id || $post->status != 'draft'){
                return false;
            }

            return true;
        });

        Gate::define('check-if-user-reached-max-scheduled-posts-per-day', function(User $user){
            
            $postsCount = $user->posts()->where('status', 'scheduled')
        ->whereDate('created_at', today($user->timezone))
        ->count();
        $maxCount = config('app.max_posts_count');

        if($postsCount < $maxCount) {
            return true;
        }

            return $this->deny('You have reached the maximum scheduled posts per day');
        });
    }
}
