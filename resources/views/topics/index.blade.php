@extends('layouts.master')
@section('title', 'Topics')
@section('content')
    <div class="div container">
        <form action="{{ route('classrooms.topics.create', compact(['classroom'])) }}" method="get">
            @csrf
            <button type="submit" class="btn btn-light">Create Topic</button>
        </form>
        <h1>Topics</h1>
        <x-alert name="success" class="alert-success" />
        <x-alert name="error" class="alert-danger" />
        <div class="row">
            @foreach ($topics as $topic)
                <div class="col-md-3">
                    <div class="card">
                        <img src="" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">{{ $topic->name }}</h5>
                            <div class="d-flex justify-content-between">
                                {{-- <a href="{{ route('classrooms.topics.show', compact(['classroom', 'topic'])) }}"
                                    class="btn btn-sm btn-primary">View</a> --}}
                                <a href="{{ route('classrooms.topics.edit', compact(['classroom', 'topic'])) }}"
                                    class="btn btn-sm btn-dark">Edit</a>
                                <form action="{{ route('classrooms.topics.destroy', compact(['classroom', 'topic'])) }}"
                                    method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                <form action="{{ route('assignments.create') }}" method="get">
                                    @csrf
                                    <button type="submit" class="btn btn-light">Create Assignment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
