<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Classroom;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(Classroom $classroom)
    {
        $posts = $classroom->posts()->with('topic')->orderBy('created_at')->lazy();
        return view('posts.index', ['classroom' => $classroom, 'posts' => $posts->groupBy('topic_id')]);
    }

    public function create(Classroom $classroom)
    {
        $post = new Post();
        return view('posts.create', compact('classroom', 'post'));
    }

    public function store(PostRequest $request, Classroom $classroom): RedirectResponse
    {
        $validate = $request->validated();
        // DB::transaction(function () use ($classroom, $validate, $request) {
        $post = $classroom->posts()->create($validate);
        // });
        return redirect()->route('classrooms.posts.index', compact(['classroom']))
            ->with('success', 'Post created successfully');
    }

    public function show(Classroom $classroom, Post $post)
    {
        $post->load('comments.user');
        return view('posts.show', compact('classroom', 'post'));
    }
}
