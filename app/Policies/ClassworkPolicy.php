<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\Classwork;
use App\Models\Scopes\UserClassroomScope;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class ClassworkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Classwork $classwork): bool
    {
        $teacher = $user->classrooms()
            ->wherePivot('classroom_id', $classwork->classroom_id)
            ->wherePivot('role', 'teacher')
            ->exists();
        $assigned = $user->classworks()
            ->wherePivot('classwork_id', $classwork->id)
            ->exists();

        return ($teacher || $assigned);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Model $user, Classroom $classroom): bool
    {
        return $user->classrooms()
            ->withoutGlobalScope(UserClassroomScope::class)
            ->wherePivot('classroom_id', $classroom->id)
            ->wherePivot('role', 'teacher')
            ->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Classwork $classwork): bool
    {
        return $user->classrooms()
            ->wherePivot('classroom_id', '=', $classwork->classroom_id)
            ->wherePivot('role', '=', 'teacher')
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Classwork $classwork): bool
    {
        return $user->classrooms()
            ->wherePivot('classroom_id', '=', $classwork->classroom_id)
            ->wherePivot('role', '=', 'teacher')
            ->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Model $user, Classwork $classwork): bool
    {
        return $user->classrooms()
            ->wherePivot('classroom_id', '=', $classwork->classroom_id)
            ->wherePivot('role', '=', 'teacher')
            ->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Model $user, Classwork $classwork): bool
    {
        return $user->classrooms()
            ->wherePivot('classroom_id', '=', $classwork->classroom_id)
            ->wherePivot('role', '=', 'teacher')
            ->exists();
    }
}
