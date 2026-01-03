<?php

namespace App\Providers;

use App\Events\ClassworkCreated;
use App\Models\Admin;
use App\Models\Classroom;
use App\Models\Classwork;
use App\Models\Post;
use App\Models\Scopes\UserClassroomScope;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Relation::enforceMorphMap([
            'classwork' => Classwork::class,
            'post' => Post::class,
            'user' => User::class,
            'admin' => Admin::class
        ]);
        Gate::define('submissions.create', function (User $user, Classwork $classwork) {
            $teacher = $user->classrooms()
                ->wherePivot('classroom_id', $classwork->classroom_id)
                ->wherePivot('role', 'teacher')
                ->exists();
            if ($teacher) {
                return false;
            }
            return $user->classworks()
                ->wherePivot('classwork_id', $classwork->id)
                ->exists();
        });
    }
}
