<?php

namespace App\Policies;

use App\Models\Classwork;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SubmissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Submission $submission): bool
    {
        // $isTeacher = DB::select('SELECT * FROM classroom_user
        //     WHERE user_id = ?
        //     AND role = ?
        //     AND EXISTS(
        //         SELECT 1 FROM classworks WHERE classworks.classroom_id = classroom_user.classroom_id
        //         AND EXISTS(
        //         SELECT 1 from submissions where submissions.classwork_id = classworks.id AND id = ?
        //         )
        //     )', [
        //     $user->id,
        //     'teacher',
        //     $submission->id
        // ]);

        // $isOwner = $submission->user_id == $user->id;
        $isTeacher = $user->classrooms()
            ->wherePivot('role', 'teacher')
            ->where('classrooms.id', $submission->classwork->classroom_id)
            ->exists();
        $isOwner = $user->id === $submission->user_id;

        return ($isTeacher || $isOwner);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Model $user, Classwork $classwork): bool
    {
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
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Submission $submission): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Submission $submission): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Model $user, Submission $submission): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Model $user, Submission $submission): bool
    {
        return false;
    }
}
