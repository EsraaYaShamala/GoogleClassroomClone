@extends('layouts.master')
@section('title', 'GSG_create_classroom')
@section('content')
    <div class="div container">
        <h1>Create Classroom</h1>



        <form action="{{ route('classrooms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('classrooms._form', [
                'button_label' => 'Create Room',
            ])
        </form>
    </div>
@endsection
