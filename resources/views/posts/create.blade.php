@extends('layouts.master')
@section('title', 'Create Post')
@section('content')

    <div class="container">
        <h1>{{ $classroom->name }}(#{{ $classroom->id }})</h1>
        <h3>Posts</h3>
        <hr>
        <form action="{{ route('classrooms.posts.store', compact(['classroom'])) }}" method="post">
            @csrf
            @include('posts._form', [
                'button_label' => 'Create Post',
            ])
        </form>

    </div>

@endsection
