@extends('layouts.master')
@section('title', 'edit topic ' . $topic->name)
@section('content')
    <div class="div container">
        <h1>Edit Topic</h1>

        <form action="{{ route('classrooms.topics.update', compact(['classroom', 'topic'])) }}" method="POST">
            @csrf
            @method('put')
            <div class="form-floating mb-3">
                <input type="text" value="{{ $topic->name }}" @class(['form-control', 'is-invalid' => $errors->has('name')]) name="name" id="name"
                    placeholder="Topic Name">
                <label for="name">Topic Name</label>
                <x-form.input-feedback name="name" />
            </div>
            <button type="submit" class="btn btn-primary">Update Topic</button>
        </form>
    </div>
@endsection
