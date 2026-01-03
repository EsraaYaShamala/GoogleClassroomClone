<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Classwork;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $validate = $request->validated();

        $map = [
            'classwork' => \App\Models\Classwork::class,
            'post' => \App\Models\Post::class,
        ];

        $commentableClass = $map[$validate['type']] ?? null;
        $commentable = $commentableClass::findOrFail($validate['id']);

        // Authorization
        $this->authorize('create', [Comment::class, $commentable]);

        Auth::user()->comments()->create([
            'commentable_id' => $validate['id'],
            'commentable_type' => $validate['type'],
            'content' => $validate['content'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        return back()->with('success', 'Comment add');
    }
}
