<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Topic;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TopicsController extends Controller
{
    public function index(Classroom $classroom): Renderable
    {
        $topics = Topic::all();
        $success = session('success');
        return view('topics.index', compact(['topics', 'classroom', 'success']));
    }

    public function create(Classroom $classroom)
    {
        $topic = new Topic();
        return view('topics.create', compact(['topic', 'classroom']));
    }

    public function store(Request $request, Classroom $classroom): RedirectResponse
    {
        $classroom->topics()->create($request->all());
        return redirect()->route('classrooms.topics.index', compact(['classroom']))->with('success', 'Topic created');
    }

    public function show(Classroom $classroom, Topic $topic)
    {
        // return view('topics.show', compact(['topic']));
    }

    public function edit(Classroom $classroom, Topic $topic)
    {
        return view('topics.edit', compact(['classroom', 'topic']));
    }

    public function update(Request $request, Classroom $classroom, Topic $topic): RedirectResponse
    {
        $topic->update($request->all());
        return Redirect::route('classrooms.topics.index', compact(['classroom']))->with('success', 'Topic updated');;
    }

    public function destroy(Classroom $classroom, Topic $topic): RedirectResponse
    {
        $topic->delete($topic);
        return Redirect::route('classrooms.topics.index', compact(['classroom']))->with('success', 'Topic deleted successfully');
    }

    public function trashed(Classroom $classroom)
    {
        $topics = Topic::onlyTrashed()->latest('deleted_at')->get();
        return view('topics.trashed', compact(['classroom', 'topics']));
    }

    public function restore(Classroom $classroom, $id): RedirectResponse
    {
        $topic = Topic::onlyTrashed()->findOrFail($id);
        $topic->restore();
        return Redirect::route('classrooms.topics.index', compact(['classroom']))->with('success', 'Topic restored successfully');
    }

    public function forceDelete(Classroom $classroom, $id): RedirectResponse
    {
        $topic = Topic::onlyTrashed()->findOrFail($id);
        $topic->forceDelete();
        return Redirect::route('classrooms.topics.index', compact(['classroom']))->with('success', 'Topic deleted permanently');
    }
}
