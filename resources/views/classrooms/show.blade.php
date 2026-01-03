@extends('layouts.master')
@section('title', 'show classroom ' . $classroom->name)
@section('content')
    <div class="div container">
        <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <h3>{{ $classroom->section }}</h3>
        <div class="row">
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <span class="text-success fs-2">{{ $classroom->code }}</span>
                </div>
            </div>
            <div class="col-md-9">
                <p>
                    <a class="btn btn-outline-dark" href="{{ route('classrooms.classworks.index', compact(['classroom'])) }}">
                        Classworks
                    </a>
                </p>
                <p>
                    <a class="btn btn-outline-dark" href="{{ route('classrooms.topics.index', compact(['classroom'])) }}">
                        Topics
                    </a>
                </p>
                <p>Invitation Link <a href="{{ $invitation_link }}">{{ $invitation_link }}</a></p>
            </div>
        </div>
    </div>
@endsection
