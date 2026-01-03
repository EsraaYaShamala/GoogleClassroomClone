<?php

namespace App\Observers;

use App\Models\Classwork;
use Illuminate\Support\Facades\Auth;

class ClassworkObserver
{
    /**
     * Handle the Classwork "created" event.
     */
    public function creating(Classwork $classwork): void
    {
        $classwork->user_id = Auth::id();
    }

    /**
     * Handle the Classwork "updated" event.
     */
    public function updated(Classwork $classwork): void
    {
        //
    }

    /**
     * Handle the Classwork "deleted" event.
     */
    public function deleted(Classwork $classwork): void
    {
        //
    }

    /**
     * Handle the Classwork "restored" event.
     */
    public function restored(Classwork $classwork): void
    {
        //
    }

    /**
     * Handle the Classwork "force deleted" event.
     */
    public function forceDeleted(Classwork $classwork): void
    {
        //
    }
}
