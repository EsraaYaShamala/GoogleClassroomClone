@extends('layouts.master')
@section('title', 'Create Classworks')
@section('content')

    <div class="container">
        <h1>{{ $classroom->name }}(#{{ $classroom->id }})</h1>
        <h3>Classworks</h3>
        <hr>
        <form action="{{ route('classrooms.classworks.store', compact(['classroom'])) }}" method="post">
            @csrf
            @include('classworks._form', [
                'button_label' => 'Create Classwork',
            ])
        </form>

    </div>

@endsection
