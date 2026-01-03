<?php

namespace App\Http\Controllers;

use App\Eumns\ClassworkType;
use App\Events\ClassworkCreated;
use App\Http\Requests\ClassworkRequest;
use App\Http\Requests\UpdateClassworkRequest;
use App\Models\Classroom;
use App\Models\Classwork;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Classroom $classroom)
    {
        $this->authorize('view', [Classroom::class, $classroom]);

        $classworks = $classroom->classworks()
            ->with('topic')
            ->withCount([
                'users as assigned_count' => function ($query) {
                    $query->where('classwork_user.status', '=', 'assigned');
                },
                'users as turnedin_count' => function ($query) {
                    $query->where('classwork_user.status', '=', 'submitted');
                },
                'users as graded_count' => function ($query) {
                    $query->whereNotNull('classwork_user.grade');
                },
            ])
            ->filter($request->query())
            ->latest('published_at')
            ->where(function ($query) {
                $query->whereHas('users', function ($query) {
                    $query->where('id', '=', Auth::id());
                })
                    ->orWhereHas('classroom.teachers', function ($query) {
                        $query->where('id', '=', Auth::id());
                    });
            })
            ->paginate();

        return view('classworks.index', ['classroom' => $classroom, 'classworks' => $classworks]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Classroom $classroom)
    {

        $this->authorize('create', [Classwork::class, $classroom]);

        $type = $this->getType()->value;
        $classwork = new Classwork();
        return view('classworks.create', compact('classroom', 'type', 'classwork'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassworkRequest $request, Classroom $classroom): RedirectResponse
    {
        $this->authorize('create', [Classwork::class, $classroom]);

        $validate = $request->validated();

        try {
            $validate['type'] = $request->type;
            DB::transaction(function () use ($classroom, $validate, $request) {
                $classwork = $classroom->classworks()->create($validate);
                $classwork->users()->attach($request->input('students'));
                event(new ClassworkCreated($classwork));
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('classrooms.classworks.index', compact(['classroom']))
            ->with('success', 'Classwork created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('view', [$classwork]);

        $user = Auth::user();
        if ($user->classrooms()
            ->wherePivot('role', 'teacher')
            ->where('classrooms.id', $classwork->classroom_id)
            ->exists()
        ) {
            $submissions = $classwork->submissions()->get();
        } else {
            $submissions = $classwork->submissions()
                ->where('user_id', $user->id)
                ->get();
        }
        $classwork->load('comments.user');
        return view('classworks.show', compact(['classroom', 'classwork', 'submissions']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('update', $classwork);

        $type = $classwork->type;
        $assigned = $classwork->users()->pluck('id')->toArray();
        return view('classworks.edit', compact('classroom', 'type', 'classwork', 'assigned'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassworkRequest $request,  Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('update', $classwork);

        $validate = $request->validated();
        $type = $this->getType();
        $validate['type'] = $type;
        strip_tags($validate['description'], ['h1', 'p', 'ol', 'li', 'a']);
        DB::transaction(function () use ($classroom, $classwork, $validate, $request) {
            $classwork->update($validate);
            $classwork->users()->sync($request->input('students'));
        });
        return redirect()->route('classrooms.classworks.index', compact(['classroom']))
            ->with('success', 'Classwork updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('delete', $classwork);
    }

    protected function getType()
    {
        try {
            return ClassworkType::from(request('type'));
        } catch (\Error $e) {
            return Classwork::TYPE_ASSIGNMENT;
        }
    }
}
