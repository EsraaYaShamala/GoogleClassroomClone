@extends('layouts.master')
@section('title', 'show topic ' . $topic->name)
@section('content')
    <div class="div container">
        <h1>{{ $topic->name }} (#{{ $topic->id }})</h1>
        {{-- <h3>{{ $classroom->section }}</h3> --}}
        <div class="row">
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    {{-- اعرضي كل ال posts  --}}
                    {{-- <span class="text-success fs-2">{{ $classroom->code }}</span> --}}
                </div>
            </div>
            <div class="col-md-9"></div>
        </div>
    </div>
@endsection
