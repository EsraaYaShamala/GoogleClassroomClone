@extends('layouts.master')
@section('title', 'Post')
@section('content')

    <div class="container">
        <h1>{{ $classroom->name }}(#{{ $classroom->id }})</h1>
        <hr>
        @forelse ($posts as $i =>  $groupposts)
            <h3>{{ $groupposts->first()->topic->name }}</h3>
            <div class="accordion accordion-flush" id="accordion{{ $i }}}">
                @foreach ($groupposts as $post)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapse{{ $post->id }}" aria-expanded="false"
                                aria-controls="flush-collapseThree">
                                {{ $post->title }}
                            </button>
                        </h2>
                        <div id="flush-collapse{{ $post->id }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                {{ $post->content }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <p class='text-center fs-4'>No posts found for this classroom.</p>
        @endforelse


    </div>

@endsection
