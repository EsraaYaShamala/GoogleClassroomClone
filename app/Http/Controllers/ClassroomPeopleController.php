<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomPeopleController extends Controller
{
    public function index(Classroom $classroom)
    {
        return view('classrooms.people', compact(['classroom']));
    }

    public function destroy(Request $request, Classroom $classroom)
    {
        $request->validate([
            'user_id' => ['required'],
        ]);

        $user_id = $request->input('user_id');
        if ($user_id == $classroom->user_id) {
            return redirect()->route('classrooms.people', compact(['classroom']))
                ->with('error', 'Can not remove user!');
        }
        $classroom->users()->detach($user_id);
        return redirect()->route('classrooms.people', compact(['classroom']))
            ->with('success', 'User removed!');
    }
}
