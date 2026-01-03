<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class ClassroomPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        return $user->classrooms()
            ->exists();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Classroom $classroom): bool
    {
        return $user->classrooms()
            ->wherePivot('classroom_id', $classroom->id)
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Model $user, Classroom $classroom): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Classroom $classroom): bool
    {
        return $user->classrooms()
            ->wherePivot('classroom_id', $classroom->id)
            ->wherePivot('role', 'teacher')
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Classroom $classroom): bool
    {
        return $user->classrooms()
            ->wherePivot('classroom_id', $classroom->id)
            ->wherePivot('role', 'teacher')
            ->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Model $user, Classroom $classroom): bool
    {
        return $user->classrooms()
            ->wherePivot('classroom_id', $classroom->id)
            ->wherePivot('role', 'teacher')
            ->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Classroom $classroom): bool
    {
        return $user->classrooms()
            ->wherePivot('classroom_id', $classroom->id)
            ->wherePivot('role', 'teacher')
            ->exists();
    }
}
