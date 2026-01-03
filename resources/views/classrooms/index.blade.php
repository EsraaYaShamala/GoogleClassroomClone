@extends('layouts.master')
@section('title', __('Classrooms'))
@section('content')
    <div class="div container">

        <div class="container row mb-3">
            <h1 class="col-md-9">{{ __('Classrooms') }}</h1>
            <form action="{{ route('classrooms.create') }}" method="get" class="col-md-3">
                @csrf
                <button type="submit" class="btn btn-light ">{{ __('Create Classroom') }}</button>
            </form>
        </div>
        <x-alert name="success" class="alert-success" />
        <x-alert name="error" class="alert-danger" />
        {{-- <ul id="classrooms"></ul> --}}
        <div class="row">
            @foreach ($classrooms as $classroom)
                <div class="col-md-3">
                    <div class="card">
                        <img src="{{ $classroom->cover_image_url }}" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">{{ $classroom->name }}</h5>
                            <p class="card-text">{{ $classroom->section }} - {{ $classroom->room }}</p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('classrooms.show', $classroom->id) }}"
                                    class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                @can('update', $classroom)
                                    <a href="{{ route('classrooms.edit', $classroom->id) }}"
                                        class="btn btn-sm btn-dark">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $classroom)
                                    <form action="{{ route('classrooms.destroy', $classroom->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('scripts')
    {{-- <script>
        fetch('api/v1/classrooms')
            .then(res => res.json())
            .then(json => {
                let ul = document.getElementById('classrooms');
                for (let i in json.data) {
                    ul.innerHTML += `<li>${json.data[i].name}</li>`
                }
            })
    </script> --}}
@endpush
