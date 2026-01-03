@extends('layouts.master')
@section('title', 'Update Classworks')
@section('content')
    <div class="container">
        <h1>{{ $classroom->name }}(#{{ $classroom->id }})</h1>
        <h3>Classworks</h3>
        <hr>
        <form action="{{ route('classrooms.classworks.update', compact(['classroom', 'classwork'])) }}" method="post">
            @csrf
            @method('put')
            @include('classworks._form', [
                'button_label' => 'Update Classwork',
            ])
        </form>

    </div>

@endsection
