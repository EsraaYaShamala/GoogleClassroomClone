<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassworkRequest;
use App\Http\Requests\UpdateClassworkRequest;
use App\Http\Resources\ClassroomResource;
use App\Http\Resources\ClassworkResource;
use App\Http\Resources\SubmissionResource;
use App\Models\Classroom;
use App\Models\Classwork;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PhpParser\Builder\Class_;

class ClassworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Classroom $classroom)
    {
        if (!$classroom) {
            return Response::json(['error' => 'Classroom not found'], 404);
            if (!Auth::user()) {
                return Response::json(['error' => 'Unauthorized'], 401);
            }
        }

        $classworks = $classroom->classworks()->with('user')->paginate(2);
        return ClassworkResource::collection($classworks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassworkRequest $request, Classroom $classroom)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user->tokenCan('classworks.create')) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->validated();

        $requestedStudentIds = $data['students'];
        $validStudentIds = $classroom->students()
            ->whereIn('users.id', $requestedStudentIds)
            ->pluck('users.id')
            ->all();
        $diff = array_values(array_diff($requestedStudentIds, $validStudentIds));
        if (!empty($diff)) {
            return response()->json([
                'message' => 'Some students are not in this classroom.',
                'errors' => [
                    'students' => ['Invalid student ids for this classroom.'],
                    'invalid_ids' => $diff,
                ]
            ], 422);
        }
        unset($data['students']);

        try {
            $classwork = DB::transaction(function () use ($classroom, $data, $validStudentIds) {
                $classwork = $classroom->classworks()->create($data)
                    ->refresh() // لاعرض قيمة ال status الي اتاخدت من ال db
                ;
                $classwork->users()->attach($validStudentIds);
                return $classwork->load(['user', 'classroom'])->loadCount('users');
            });
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return new ClassworkResource($classwork);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom, Classwork $classwork)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user->tokenCan('classworks.read')) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }
        $classwork->load(['comments.user', 'user', 'classroom', 'submissions'])->loadCount('users');
        return response()->json([
            'classwork' => new ClassworkResource($classwork),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassworkRequest $request, Classroom $classroom, Classwork $classwork)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user->tokenCan('classworks.create')) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }
        $validate = $request->validated();
        if (isset($validate['description'])) {
            strip_tags($validate['description'], ['h1', 'p', 'ol', 'li', 'a']);
        }
        DB::transaction(function () use ($classwork, $validate) {
            $classwork->update($validate);
            $classwork->users()->sync($validate['students']);
        });
        return response()->json([
            'classwork' => new ClassworkResource($classwork),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Classwork::destroy($id);
        return Response::json([], 204);
    }
}
