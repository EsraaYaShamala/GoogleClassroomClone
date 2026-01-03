<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class ClassroomsController extends Controller
{

    public function __construct()
    {
        $this->middleware('subscribed')->only('create', 'store');
        $this->authorizeResource(Classroom::class);
    }
    public function index(): Renderable
    {
        $classrooms = Classroom::active()->recent()->orderby('created_at', 'DESC')->get(); //collection
        $success = session('success');
        // return response : view, redirect, json_data, file, string
        return view('classrooms.index', compact(['classrooms', 'success']));
    }

    public function create()
    {
        $classroom = new Classroom();
        return view('classrooms.create', compact('classroom'));
    }
    public function store(ClassroomRequest $request): RedirectResponse
    {
        $validate = $request->validated();
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image'); // Uploaded File
            $path = Classroom::uploadCoverImage($file);
            $validate['cover_image_path'] = $path;
        }
        // $validate['code'] = Str::random(8);
        // $validate['user_id'] = Auth::id();

        try {
            DB::beginTransaction();

            $classroom = Classroom::create($validate);

            //join process
            $classroom->join(Auth::id(), 'teacher');

            Db::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return Redirect::back()->with('error', $e->getMessage())->withInput();
        }
        return redirect()->route('classrooms.index')->with('success', 'Classroom created');
    }
    public function show(Classroom $classroom)
    {
        $invitation_link = URL::signedRoute('classrooms.join', [
            'classroom' => $classroom->id,
            'code' => $classroom->code,
        ]);
        return view('classrooms.show')
            ->with([
                'classroom' => $classroom,
                'invitation_link' => $invitation_link,
            ]);
    }
    public function edit(Classroom $classroom)
    {
        return view('classrooms.edit', compact(['classroom']));
    }
    public function update(ClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        $validate = $request->validated();
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $path = Classroom::uploadCoverImage($file);
            $validate['cover_image_path'] = $path;
        }
        $old_image = $classroom->cover_image_path;
        $classroom->update($validate);
        if ($old_image && $old_image != $classroom->cover_image_path) {
            Classroom::deleteCoverImage($old_image);
        }
        return Redirect::route('classrooms.index')->with('success', 'Classroom updated');
    }
    public function destroy(Classroom $classroom): RedirectResponse
    {
        $classroom->delete($classroom);
        return redirect(route('classrooms.index'))
            ->with('success', 'Classroom deleted successfully');
    }
    public function trashed()
    {
        $classrooms = Classroom::onlyTrashed()->latest('deleted_at')->get();
        return view('classrooms.trashed', compact(['classrooms']));
    }
    public function restore($id): RedirectResponse
    {
        $classroom = Classroom::onlyTrashed()->findOrFail($id);
        $classroom->restore();
        return redirect()->route('classrooms.index')->with('success', "Classroom ({$classroom->name}) restored");
    }
    public function forceDelete($id): RedirectResponse
    {
        $classroom = Classroom::withTrashed()->findOrFail($id);
        $classroom->forceDelete();
        // Classroom::deleteCoverImage($classroom->cover_image_path);
        return redirect()->route('classrooms.trashed')
            ->with('success', "Classroom ({$classroom->name}) deleted forever !");
    }
}
