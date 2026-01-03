@extends('layouts.master')
@section('title', 'create topic')
@section('content')
    <div class="div container">
        <h1>Create Topic</h1>

        <form action="{{ route('classrooms.topics.store', compact(['classroom'])) }}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-floating mb-3">
                <input type="text" value="{{ old('name') }}" @class(['form-control', 'is-invalid' => $errors->has('name')]) name="name" id="name"
                    placeholder="Topic Name">
                <label for="name">Topic Name</label>
                <x-form.input-feedback name="name" />
            </div>
            <button type="submit" class="btn btn-primary">Create Topic</button>
        </form>
    </div>
@endsection
