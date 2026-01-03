@extends('layouts.master')
@section('title', 'Show Post')
@section('content')
    <div class="container">
        <h1>{{ $classroom->name }}(#{{ $classroom->id }})</h1>
        {{-- <h3></h3> --}}
        <hr>
        <h4>Comments for {{ $post->title }}</h4>
        <hr>
        <div class="div">
            <p>{{ $post->content }}</p>
        </div>
        <x-alert name="success" class="alert-success" />
        <form action="{{ route('comments.store') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="type" value="post">
            <div class="d-flex">
                <div class="col-8">
                    <x-form.floating-control name="description" placeholder="Comment">
                        <x-form.input-text-area name="content" placeholder="Comment" />
                    </x-form.floating-control>
                </div>
                <div class="ms-1">
                    <button type="submit" class="btn btn-primary">Create Comment</button>
                </div>
            </div>
        </form>
        <div class="mt-4">
            @foreach ($post->comments as $comment)
                <div class="row">
                    <div class="col-md-2">
                        {{-- <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Avatar" class="rounded-circle"> --}}
                    </div>
                    <div class="col-md-10">
                        <p>{{ $comment->content }}</p>
                        <p>Posted by {{ $comment->user->name }}</p>
                        <p>Posted at {{ $comment->created_at->diffForHumans(null, true, true) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection
