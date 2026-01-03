<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('classrooms.read')) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }
        $classrooms = Classroom::with('user:id,name', 'topics')
            ->withCount('students')
            ->paginate(2);
        return ClassroomResource::collection($classrooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('classrooms.create')) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }
        $request->validate([
            'name' => 'required'
        ]);
        $classroom = Classroom::create($request->all());
        return Response::json([
            'code' => 100,
            'message' => __('message created'),
            'classroom' => $classroom
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('classrooms.read')) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }
        return new ClassroomResource($classroom->load('user')->loadCount('students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('classrooms.update')) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }
        $request->validate([
            'name' => ['sometimes', 'required', Rule::unique('classrooms', 'name')->ignore($classroom->id)],
            'section' => ['sometimes', 'required']
        ]);
        $classroom->update($request->all());
        return Response::json([
            'code' => 100,
            'message' => __('message updated'),
            'classroom' => $classroom
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('classrooms.delete')) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }
        Classroom::destroy($id);
        Response::json([], 204);
    }
}
