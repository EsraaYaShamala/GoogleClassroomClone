@extends('layouts.master')
@section('title', 'edit_classroom ' . $classroom->name)
@section('content')
    <div class="container">
        <h1>Edit Classroom</h1>

        <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @method('put')
            @include('classrooms._form', [
                'button_label' => 'Update Room',
            ])
        </form>
    </div>
@endsection
