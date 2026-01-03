@extends('layouts.master')
@section('title', 'Trashed Classrooms')
@section('content')
    <div class="div container">
        <h1>Trashed Classrooms</h1>
        <x-alert name="success" class="alert-success" />
        <x-alert name="error" class="alert-danger" />
        <div class="row">
            @foreach ($classrooms as $classroom)
                <div class="col-md-3">
                    <div class="card">
                        <img src="{{ asset('storage/' . $classroom->cover_image_path) }}" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">{{ $classroom->name }}</h5>
                            <p class="card-text">{{ $classroom->section }} - {{ $classroom->room }}</p>
                            <div class="d-flex justify-content-between">
                                <form action="{{ route('classrooms.restore', $classroom->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-sm btn-success">Restore</button>
                                </form>
                                <form action="{{ route('classrooms.force-delete', $classroom->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete Forever</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
