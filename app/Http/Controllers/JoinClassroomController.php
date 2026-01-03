<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Scopes\UserClassroomScope;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class JoinClassroomController extends Controller
{
    public function create(string $id)
    {

        $classroom = Classroom::withoutGlobalScope(UserClassroomScope::class)->active()->findOrFail($id);

        try {
            $this->exists($classroom, Auth::id());
        } catch (Exception $e) {
            return redirect()->route('classrooms.show', compact('classroom'));
        }

        return view('classrooms.join', compact('classroom'));
    }

    public function store(Request $request, string $id)
    {
        $request->validate(['role' => 'in:student,teacher']);

        $classroom = Classroom::withoutGlobalScope(UserClassroomScope::class)->active()->findOrFail($id);

        try {
            //join process
            $classroom->join(Auth::id(), $request->input('role', 'student'));
        } catch (Exception $e) {
            return redirect('classrooms.show',  compact('classroom'));
        }

        return redirect()->route('classrooms.show',  compact('classroom'));
    }

    protected function exists(Classroom $classroom, $user_id)
    {
        $exists = $classroom->users()->where('id', '=', $user_id)->exists();
        if ($exists) {
            throw new Exception('You are already a member of this classroom');
        }
    }
}
